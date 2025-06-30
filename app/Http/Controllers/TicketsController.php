<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Category;
use App\Discussion;
use App\EffortType;
use App\Events\AssignNewPersonnel;
use App\Helpers\ArrayHelper;
use App\Helpers\EmailHelper;
use App\Helpers\Helper;
use App\ImportantDecision;
use App\Mail\CreateTicketMail;
use App\Organization;
use App\Priority;
use App\Status;
use App\SubCategory;
use App\Ticket;
use App\TicketAttachment;
use App\TicketEffortLog;
use App\TicketEffortTotal;
use App\TicketExternalPartner;
use App\TicketMailLog;
use App\TicketPersonnel;
use App\TicketReference;
use App\TicketRobotFromMail;
use App\TicketStatus;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TicketsController extends Controller {

    public function index(Request $request) {
        try {
            if(!in_array('VIEW_TICKETS', auth()->user()->Permissions)) {
                return redirect('/dashboard');
            }

            if(session()->has("update-ticket")) {
                $path = session()->pull("update-ticket");
                return redirect($path);
            }

            if(session()->has("tickets")) {
                $path = session()->pull("tickets");
                return redirect($path);
            }

            if(!in_array(\auth()->user()->role_id, [5, 6, 8])) {
                $top20 = Organization::where('is_vip', 1)->get();
                $employees = User::where('in_use', 1)->where(function($query) {
                    $query->orWhere('org_id', 8)->orWhere('org_id', 3);
                })->orderBy('org_id', 'DESC')->orderBy('id', 'ASC')->get();

                return view('tickets.tickets', compact("top20", "employees"));
            }
            else {
                return view('tickets.firma-tickets');
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to index the tickets!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function addTicketIndex($reference = null,$copy = null) {
        try {
            $role = auth()->user()->role_id;

            if($role === 4) {
                $data["status"] = Status::query()->where("main", 1)->whereIn("id", [1, 2, 3, 4, 5, 6, 8])->get();
            }
            else {
                $data["status"] = Status::query()->where("main", 1)->get();
            }

            $data["category"] = Category::query()->orderBy("sort")->get();
            $data["sub_category"] = SubCategory::query()->get();
            $data["effortTypes"] = EffortType::query()->get();
            $parent_ticket = Ticket::query()->find($reference);

            if(in_array($role, [1, 2, 3, 4]) && $copy == 'copy') {
                $data["userEffortType"] = \auth()->user()->effort_type;
                $partners = TicketExternalPartner::where("ticket_id", $reference)->get();
                $data["partners"] = $partners;
                $data["partner_count"] = count($partners);
                $copy = 1;
                $ticket_personnel = $parent_ticket->assigned_users();
                return view('tickets.add-ticket', compact("data", "parent_ticket","copy","ticket_personnel"));
            }

            if(in_array($role, [1, 2, 3, 4])) {
                $data["userEffortType"] = \auth()->user()->effort_type;
                return view('tickets.add-ticket', compact("data", "parent_ticket"));
            }

            if(in_array($role, [5, 6, 8])) { //For Firma
                return view('tickets.add-ticket-firma', compact('data'));
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to index 'Add Ticket' page.",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function updatedTicketIndex(Request $request, $ticketId) {
        try {
            $auth_user = auth()->id();
            $role_id = auth()->user()->role_id;
            $ticket = Ticket::query()->findOrFail($ticketId);
            $ticketRobotFromMail = TicketRobotFromMail::query()->where("ticket_id", $ticketId)->first();

            if($ticketRobotFromMail) {
                $ticket["ticketRobotFromMail"]=$ticketRobotFromMail->email;
            }

            $ticket_personnel = $ticket->assigned_users();
            $calendar = Calendar::query()->where("ticket_id", $ticketId)->when(in_array($role_id, [4, 7]), function($query) {
                return $query->where("user_id", auth()->id());
            })->orderBy("start", "DESC")->get();
            $calendar_statuses = DB::table("calendar_statuses")->select("*")->get();

            if ($role_id != 1 && $role_id != 2 && $role_id != 3) {

                if (in_array($role_id, [5, 6, 8])) {
                    $org_users = User::where("org_id", auth()->user()->org_id)->select("id")->pluck("id");


                    if (!in_array($ticket->user, $org_users->toArray()) || $ticket->is_private) {
                        return redirect("/tickets");
                    }
                }
                else {
                    if ($ticket->personnel != $auth_user && $ticket->user != $auth_user && (!in_array($auth_user, array_column($ticket_personnel, 'id')))) {
                        return redirect("/tickets");
                    }
                }
            }

            if(in_array($ticket->status_id, [6, 9])) { // Eğer ticket "Done", "Closed" veya "Terminated" olarak ayarlanmışsa mantığı burada kuruyoruz.
                $data["status"] = Status::query()->whereIn("id", [6, 7, 9, 11])->get();
            }
            else {
                if(in_array($ticket->status_id, [7, 10, 11])) { // Eğer ticket faturalandırılmışsa, "Done" yapılamaz.
                    $data["status"] = Status::query()->whereIn("id", [7, 9, 10, 11])->orderBy("order", "ASC")->get();
                }
                else {
                    if(in_array($role_id, [4, 7])) {
                        $data["status"] = Status::query()->where("main", 1)->whereIn("id", [1, 2, 3, 4, 5, 6, 8])->get();
                    }
                    else {
                        $data["status"] = Status::query()->where("main", 1)->get();
                    }
                }
            }

            $data["category"] = Category::query()->orderBy("sort")->get();
            $data["sub_category"] = SubCategory::query()->get();
            $ticket["lastEdit"] = TicketStatus::query()->where('ticket_id', $ticketId)->orderBy('created_at', 'desc')->first();
            $ticket["updated_from"] = User::query()->find($ticket->update_by);
            $ticket["created_from"] = User::query()->find($ticket->add_by);
            $role = auth()->user()->role_id;

            if(in_array($role, [1, 2, 3, 4, 7])) {
                $data["effortTypes"] = EffortType::query()->get();
                $mail_log = TicketMailLog::where("ticket_id", $ticketId)->first();
                $effort_logs = TicketEffortLog::query()->where("ticket_id", $ticketId)->get();
                $effort_with_types = $effort_logs->isEmpty() ? null : $this->calculateEffortByType($ticketId);
                $data["ticket_status_log"] = TicketStatus::where("ticket_id", $ticketId)->orderBy("created_at", "desc")->get();
                $ticket['discussion'] = Discussion::where('ticket_id', $ticket->id)->orderBy('created_at', 'desc')->get();
                $ticket['discussionNotPrivate'] = Discussion::where('ticket_id', $ticket->id)->where("is_private", 0)->count();

                foreach ($ticket['discussion'] as $discussion) {
                    $commentRobotFromMail=TicketRobotFromMail::where("comment_id",$discussion->id)->first();
                    if($commentRobotFromMail){
                        $discussion["commentRobotFromMail"]=$commentRobotFromMail->email;
                    }
                    $discussion->total_logs = $this->calculateCommentEffort($discussion->id);
                    $discussion->effort_logs = TicketEffortLog::query()->where("ticket_id", $ticketId)->where("discussion_id", $discussion->id)->get();
                }

                $ticket["attachment"] = TicketAttachment::where('ticket_id', $ticket->id)->orderBy('id', 'desc')->get();
                $importantDecisions = ImportantDecision::where("ticket_id", $ticket->id)->orderBy("id", "desc")->get();
                $main_ticket = TicketReference::where('child_ticket', $ticket->id)->value('parent_ticket');
                $sub_tickets = TicketReference::where('parent_ticket', $ticket->id)->pluck('child_ticket')->toArray();
                $ticket["parent_ticket"] = Ticket::where('id', $main_ticket)->first();
                $ticket["child_ticket"] = Ticket::whereIn('id', $sub_tickets)->get();

                if(Auth::user()->role_id === 4 && $ticket["parent_ticket"]) { // Eğer personel bir üst ticket'ı göremiyorsa, listeden kaldırıyoruz.
                    $parent_ticket_users = $ticket["parent_ticket"]->secondary_users();
                    array_push($parent_ticket_users, $ticket["parent_ticket"]->personnel, $ticket["parent_ticket"]->user);

                    if(!(in_array(Auth::id(), $parent_ticket_users))) {
                        unset($ticket["parent_ticket"]);
                    }
                }

                if(Auth::user()->role_id === 4 && $ticket["child_ticket"]) { // Eğer personel bir alt ticket'ı göremiyorsa, array'den siliyoruz.
                    foreach($ticket["child_ticket"] as $key => $item) {
                        $child_ticket_users = $item->secondary_users();
                        array_push($child_ticket_users, $item->personnel, $item->user);

                        if(!(in_array(Auth::id(), $child_ticket_users))) {
                            unset($ticket["child_ticket"][$key]);
                        }
                    }
                }

                $partners = TicketExternalPartner::where("ticket_id", $ticketId)->get();
                $ticket["partners"] = $partners;
                $ticket["partner_count"] = count($partners);
                $data["invoiced_and_correction_log"] = TicketStatus::where("ticket_id",$ticket->id)->whereIn("status",[7,10])->orderBy("created_at","asc")->get();
                $data["userEffortType"] = \auth()->user()->effort_type;

                return view('tickets.update-ticket', compact("ticket", "data", "mail_log", "effort_logs", "importantDecisions", "ticket_personnel", "calendar", "calendar_statuses", "effort_with_types"));
            }
            if(in_array($role, [5, 6, 8])) { //For Firma
                $ticket['discussion'] = Discussion::where('ticket_id', $ticket->id)->where("is_private", 0)->orderBy('created_at', 'desc')->get();

                $attachment_ids  = DB::table("ticket_attachments")
                    ->leftJoin("discussions","ticket_attachments.discussion_id","=","discussions.id")
                    ->where("discussions.is_private",0)->where("ticket_attachments.ticket_id",$ticket->id)
                    ->select("ticket_attachments.id")->pluck("ticket_attachments.id");
                $ticket_attachments = TicketAttachment::where("ticket_id",$ticket->id)->whereNull("discussion_id")->where("private",0)->get();
                $discussion_attachments = TicketAttachment::whereIn('id', $attachment_ids)->orderBy('id', 'desc')->get();
                $ticket["ticket_attachments"] = $ticket_attachments;
                $ticket["discussion_attachments"] = $discussion_attachments;
                $ticket["total_time"] = Helper::convert_minute_to_clock(Helper::getDiscountedEffortsAsMinute($ticket->id));

                return view('tickets.update-ticket-firma', compact('ticket', 'data', 'calendar', 'calendar_statuses'));
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to index a ticket!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            abort(404);
        }
    }

    public function getTickets(Request $request) {
        if($request->order && in_array($request->order[0]["column"], [1, 9, 10, 11])) {
            $tickets = Ticket::query()->orderBy($request->columns[$request->order[0]["column"]]["data"], $request->order[0]["dir"]);
        }
        else {
            $tickets = Ticket::query()->orderByDesc("id");
        }

        $role_id = auth()->user()->role_id;

        if(in_array($role_id, [1, 2])) { // For Super Admin and Admin
            if ($request->status != 'all') {
                $tickets = $tickets->where('status_id', $request->status);
            }
            if ($request->personnel != 0) {
                $tickets = $tickets->where('personnel', $request->personnel)->orWhereRaw('id IN (SELECT ticket_id FROM ticket_personnels WHERE personnel = ' . $request->personnel . ')');
            }
            if ($request->org_id != "null") {
                $tickets = $tickets->where('org_id', $request->org_id)->where("status_id", "!=", 9)->where("status_id", "!=", 7);
            }
            if ($request->proofed != "null") {
                $tickets = $tickets->where("proofed", $request->proofed);
            }
        }
        if ($role_id == 3) { //For Personnel Admin
            if ($request->status != 'all') {
                $tickets = $tickets->where('status_id', $request->status);
            }
            if ($request->personnel) {
                $tickets = $tickets->where('personnel', $request->personnel)->orWhereRaw('id IN (SELECT ticket_id FROM ticket_personnels WHERE personnel = ' . $request->personnel . ')');
            }
            if ($request->org_id != "null") {
                $tickets = $tickets->where('org_id', $request->org_id)->where("status_id", "!=", 9)->where("status_id", "!=", 7);
            }
            if ($request->proofed) {
                $tickets = $tickets->where("proofed", $request->proofed);
            }
        }
        if ($role_id == 4) { // For Personnel
            if ($request->status == 'all') {
                $tickets = $tickets->where(function ($query) {
                    $query->orWhere('user', auth()->id())->orWhere('personnel', auth()->id())->orWhereRaw('id IN (SELECT ticket_id FROM ticket_personnels WHERE personnel = ' . auth()->id() . ' AND deleted_at IS NULL)');
                });
            }
            else {
                $tickets = $tickets->where('status_id', $request->status)->where(function ($query) {
                    $query->orWhere('personnel', auth()->id())->orWhere("user", auth()->id())->orWhereRaw('id IN (SELECT ticket_id FROM ticket_personnels WHERE personnel = ' . auth()->id() . ' AND deleted_at IS NULL)');
                });
            }
        }
        if ($role_id == 5) { // For Firma Admin
            if ($request->status == "all") {
                $tickets = $tickets->where("org_id", auth()->user()->org_id)->where("is_private", 0)->orderByRaw('FIELD(status_id,1,5,4,3,6,7,8,9,2)');
            }
            else {
                if ($request->status == 3)
                    $tickets = $tickets->whereIn('status_id', [2, 3, 4])->where('org_id', auth()->user()->org_id)->where("is_private", 0);
                else
                    $tickets = $tickets->where('status_id', $request->status)->where('org_id', auth()->user()->org_id)->where("is_private", 0);
            }
        }
        if ($role_id == 6) { // For Firma User
            if ($request->status == 'all') {
                $tickets = $tickets->where('user', auth()->id())->where("is_private", 0)->orderByRaw('FIELD(status_id,1,5,4,3,6,7,8,9,2)');
            }
            else {
                if ($request->status == 3) {
                    $tickets = $tickets->whereIn('status_id', [2, 3, 4])->where('user', auth()->id())->where("is_private", 0);
                }
                else {

                    $tickets = $tickets->where('status_id', $request->status)->where('user', auth()->id())->where("is_private", 0);
                }
            }
        }
        if ($role_id == 7) { // For Freelancer
            if ($request->status == 'all') {
                $tickets = $tickets->where(function ($query) {
                    $query->orWhere('user', auth()->id())->orWhere('personnel', auth()->id())->orWhereRaw('id IN (SELECT ticket_id FROM ticket_personnels WHERE personnel = ' . auth()->id() . ' AND deleted_at IS NULL)');
                });
            }
            else {
                $tickets = $tickets->where('status_id', $request->status)->where(function ($query) {
                    $query->orWhere('personnel', auth()->id())->orWhere("user", auth()->id())->orWhereRaw('id IN (SELECT ticket_id FROM ticket_personnels WHERE personnel = ' . auth()->id() . ' AND deleted_at IS NULL)');
                });
            }
        }
        if($role_id == 8) {
            $users = User::where("org_id", auth()->user()->org_id)->where("role_id", 6)->where("id", "!=", 4)->select("id")->pluck("id")->toArray(); // Organizasyonun "Firma User" olan kullanıcılarını ayıklıyoruz.
            $users[] = auth()->id();
            $tickets_query = $tickets->whereIn("user", $users)->where("is_private", 0);

            if($request->status == 'all') {
                $tickets = $tickets_query->orderByRaw('FIELD(status_id,1,5,4,3,6,7,8,9,2)');
            }
            else {
                if($request->status == 3) {
                    $tickets = $tickets_query->whereIn('status_id', [2, 3, 4]);
                }
                else {
                    $tickets = $tickets_query->where('status_id', $request->status);
                }
            }
        }


        $dataTable = DataTables::of($tickets)

            ->filter(function ($query) use ($request, $tickets) {

                $value = $request["search"]["value"];
                if ($value && auth()->user()->role_id == 1) {

                    $user           = User::whereRaw("concat(first_name,' ',surname) like '%".$value."%'")->select('id')->pluck('id');
                    $personnel      = User::whereRaw("concat(first_name,' ',surname) like '%".$value."%'")->select('id')->pluck('id');
                    $organization   = Organization::where('org_name', 'like', '%' . $value . '%')->select('id')->pluck('id');
                    $ticket         = Ticket::where("name", "like", "%" . $value . "%")->select("id")->pluck("id");
                    $ticket_ids     = Ticket::where("id", "like", "%" . $value . "%")->select("id")->pluck("id");
                    $query->orWhereIn("personnel", $personnel)
                        ->orWhereIn("user", $user)
                        ->orWhereIn("org_id", $organization)
                        ->orWhereIn("id", $ticket)
                        ->orWhereIn("id",$ticket_ids);


                    return $query;
                }
                elseif($value && (in_array(auth()->user()->role_id, [5, 6, 8]))) {
                    $user       = User::whereRaw("concat(first_name,' ',surname) like '%".$value."%'")->select('id')->pluck('id');
                    $ticket          = Ticket::where("name", "like", "%" . $value . "%")->select("id")->pluck("id");
                    $ticket_id         = Ticket::where("id", "like", "%" . $value . "%")->select("id")->pluck("id");
                    $query->where(function($q) use($user,$ticket,$ticket_id){
                        $q->orWhereIn("user", $user);
                        $q->orWhereIn("id", $ticket);
                        $q->orWhereIn("id",$ticket_id);
                    })->where("org_id",\auth()->user()->org_id);
                }
                else {
                    return $query;
                }
            })
            ->editColumn("personnel",function ($row){
                $users = [$row->getTicketAssignedUserName()];
                foreach ($row->assigned_users() as $sec){
                    $users[] = $sec["name"];
                }
                return $users;
            })
            ->editColumn("due_date", function($row) {
                if($row->comment_due_date && $row->status_id == 5 && ($row->comment_due_date != $row->due_date)) {
                    $text = '<span>' . Carbon::parse($row->due_date)->format("d.m.Y") . '</span><br>' . '<span style="color: #705ec8" class="font-italic font-weight-light">' . Carbon::parse($row->comment_due_date)->format("d.m.Y") . '</span>';
                }
                else {
                    $text = '<span>' . Carbon::parse($row->due_date)->format("d.m.Y") . '</span>';
                }

                return $text;
            })
            ->editColumn("counter_since_last_opened", function($row) {
                if($row->status_id == 1) {
                    $status = TicketStatus::query()->where("ticket_id", $row->id)->where("status", $row->status_id)->latest()->first();
                    $now = Carbon::now();
                    if($status) {
                        return $status->created_at->diffInSeconds($now);
                    }

                    return $row->created_at->diffInSeconds($now);
                }

                return null;
            })
            ->filterColumn("due_date", function($query, $input) {
                return $query->where("due_date", $input)->orWhere("comment_due_date", $input); //TODO: Bug
            })
            ->filterColumn("personnel", function ($q, $k) {
                $user = User::whereRaw("concat(first_name,' ',surname) like '%".$k."%'")->select('id')->pluck('id');
                $pers = TicketPersonnel::whereIn("personnel",$user)->select("ticket_id")->pluck("ticket_id");
                return $q->whereIn('personnel', $user)->orWhereIn("id",$pers);
            })
            ->filterColumn("status_id", function ($q, $k) {

                $k=explode(',',$k);
                $proofedStatus=false;
                $counter=0;
                $proofed=false;
                $done=false;
                if(in_array("6", $k)){
                    $done=true;
                    $proofedStatus=0;
                }
                foreach ($k as $status){
                    if($status=="proofed"){
                        $proofed=true;
                        $k[$counter]=6;
                        $proofedStatus = 1;
                    }
                    $counter++;
                }

                $statues = Status::whereIn('id', $k)->select('id')->get();
                $statusIds=[];

                foreach ($statues as $status){
                    if($status->id==6 && in_array(auth()->user()->role_id, [5, 6, 8])){
                        $proofedStatus = 1;
                        }

                    $statusIds[]=$status->id;
                }
                if($proofed && $done ){
                    $proofedStatus=null;
                }

                if (count($statusIds)>0) {

                    if($proofedStatus === 0) {
                        $key = array_search(6, $statusIds);
                        if ($key !== false) {
                            unset($statusIds[$key]);
                        }
                        return $q->where(function ($query) {
                            $query->where("proofed", 0)->where("status_id", 6);
                        })->orWhereIn("status_id", $statusIds);
                    }

                    if($proofedStatus === 1) {
                        $key = array_search(6, $statusIds);
                        if ($key !== false) {
                            unset($statusIds[$key]);
                        }
                        return $q->where(function ($query) {
                            $query->where("proofed", 1)->where("status_id", 6);
                        })->orWhereIn("status_id", $statusIds);
                    }

                    return $q->whereIn('status_id', $statusIds);
                }



                return $q->where('status_id', 1);
            })
            ->filterColumn("user", function ($q, $k) {
                $user = User::whereRaw("concat(first_name,' ',surname) like '%".$k."%'")->select('id')->pluck('id');
                return $q->whereIn('user', $user);
            })
            ->filterColumn("org_id", function ($q, $k) {
                    $organization = Organization::where('org_name', 'like', '%' . $k . '%')->select('id')->pluck('id');
                    return $q->whereIn('org_id', $organization);
                })
            ->filterColumn("category", function ($q, $k) {
                    $category = Category::where('name', 'like', '%' . $k . '%')->select('id')->pluck('id');
                    return $q->whereIn('category', $category);
                })
            ->filterColumn("priority", function ($q, $k) {
                $priority = Priority::where('id', $k)->select('id')->first();
                $k = trim($k, "^$");
                return $q->where('priority', $k);
            })
            ->rawColumns(["due_date"])
            ->make(true);
        return $dataTable;
    }

    public function getWithDueDateTickets(Request $request) {
        $today = Carbon::now()->subDay()->format("Y-m-d");
        $past  = Carbon::createFromDate(1970, 1, 1)->format("Y-m-d");
        $tickets = Ticket::query()->whereNotIn("status_id", [6, 7, 9, 10, 11])->whereNotNull("due_date")->where("due_date", "!=", "")->whereBetween("due_date", [$past, $today]);

        if(!in_array(auth()->user()->role_id,[1, 2, 3])) {
            $tickets->where(function ($query) {
                    $query->orWhere('user', auth()->id())->orWhere('personnel', auth()->id())->orWhereRaw('id IN (SELECT ticket_id FROM ticket_personnels WHERE personnel = ' . auth()->id() . ' AND deleted_at IS NULL)');
           });
        }

        if($request->due_date_personnel) {
            $tickets->where(function ($q) use($request) {
                $q->where("personnel",$request->due_date_personnel);
                $q->orWhereRaw('id IN (SELECT ticket_id FROM ticket_personnels WHERE personnel = ' . $request->due_date_personnel . ' AND deleted_at IS NULL)');
            });
        }

        if($request->order && in_array($request->order[0]["column"], [1, 9, 10])) {
            $tickets->orderBy($request->columns[$request->order[0]["column"]]["data"], $request->order[0]["dir"]);
        }
        else {
            $tickets->orderBy("due_date");
        }

        return DataTables::of($tickets)
            ->editColumn("personnel",function ($row){
                $users = [$row->getTicketAssignedUserName()];
                foreach ($row->assigned_users() as $sec){
                    $users[] = $sec["name"];
                }
                return $users;
            })
            ->editColumn("due_date", function($row) {
                if($row->comment_due_date && $row->status_id == 5 && ($row->comment_due_date != $row->due_date)) {
                    $text = '<span>' . Carbon::parse($row->due_date)->format("d.m.Y") . '</span><br>' . '<span style="color: #705ec8" class="font-italic font-weight-light">' . Carbon::parse($row->comment_due_date)->format("d.m.Y") . '</span>';
                }
                else {
                    $text = '<span>' . Carbon::parse($row->due_date)->format("d.m.Y") . '</span>';
                }

                return $text;
            })
            ->editColumn("counter_since_last_opened", function($row) {
                if($row->status_id == 1) {
                    $status = TicketStatus::query()->where("ticket_id", $row->id)->where("status", $row->status_id)->latest()->first();
                    $now = Carbon::now();
                    if($status) {
                        return $status->created_at->diffInSeconds($now);
                    }

                    return $row->created_at->diffInSeconds($now);
                }

                return null;
            })
            ->filterColumn("due_date", function($query, $input) {
                return $query->where("due_date", $input)->orWhere("comment_due_date", $input);
            })
            ->filterColumn("personnel", function ($q, $k) {
                $user = User::where('first_name', 'like', '%' . $k . '%')->select('id')->pluck('id');
                $pers = TicketPersonnel::whereIn("personnel",$user)->select("ticket_id")->pluck("ticket_id");
                return $q->whereIn('personnel', $user)->orWhereIn("id",$pers);
            })
            ->filterColumn('status_id', function($q, $k) {
                $status = Status::where('id', $k)->select('id')->first();

                if($status) {
                    return $q->where('status_id', $status->id);
                }

                return $q->where('status_id', 1);
            })
            ->filterColumn("user", function ($q, $k) {
                $user = User::where('first_name', 'LIKE', '%' . $k . '%')->select('id')->pluck('id');
                return $q->whereIn('user', $user);
            })
            ->filterColumn("org_id", function ($q, $k) {
                    $organization = Organization::where('org_name', 'LIKE', '%' . $k . '%')->select('id')->pluck('id');
                    return $q->whereIn('org_id', $organization);
                })
            ->filterColumn("category", function ($q, $k) {
                    $category = Category::where('name', 'like', '%' . $k . '%')->select('id')->pluck('id');
                    return $q->whereIn('category', $category);
                })
            ->filterColumn("priority", function ($q, $k) {
                $priority = Priority::where('id', $k)->select('id')->first();
                $k = trim($k, "^$");
                return $q->where('priority', $k);
            })
            ->rawColumns(["due_date"])
            ->make(true);
    }

    public function createTicket(Request $request) {

        try {
            $organization = Organization::where('id', auth()->user()->org_id)->first();
            $auth_user    = User::where("id",\auth()->id())->first();
            $personnel    = $organization->personnel_id ?? 5; // Eğer organizasyona kullanıcı atanmamışsa, müşteri ticket açarken Cem Güler assigned user olarak atanıyor.

            $ticket                  = new Ticket();
            $ticket->name            = $request->name;
            $ticket->description     = $request->description;
            $ticket->org_id          = $request->organization ?? auth()->user()->org_id;
            $ticket->user            = $request->user ?? auth()->id();
            $ticket->personnel       = $request->personnel ?? $personnel; // Eğer değer varsa, direkt olarak ticket'a master user olarak atıyoruz. Eğer yok ise önce organizasyonun personnel değerini kontrol ediyoruz, o da  yoksa Cem Güler'i assigned user olarak atıyoruz. (Sadece bir adet Master User olabilir.)
            $ticket->status_id       = $request->status ?? 1;
            $ticket->due_date        = $request->due_date;
            $ticket->priority        = $request->priority ?? 1;
            $ticket->category        = $request->category == null ? 7 : $request->category;
            $ticket->sub_category_id = $request->sub_category;
            $ticket->transport_price = $request->transport_price ?? 0;
            $ticket->add_by          = $auth_user->id;
            $ticket->add_ip          = request()->ip();
            $ticket->update_by       = $auth_user->id;
            $ticket->update_ip       = request()->ip();
            $ticket->save();

            if($request->parent_ticket) { // Eğer ticket, başka bir ticket'ın referansı olarak ekleniyorsa mantığı burada kuruyoruz.
                try {
                    TicketReference::create([
                        'parent_ticket' => $request->parent_ticket,
                        'child_ticket'=> $ticket->id,
                        'created_by' => Auth::id()
                    ]);
                }
                catch(Exception $exception) {
                    Helper::create_debug_log(
                        __CLASS__,
                        __FUNCTION__,
                        "Something went wrong while trying to create a reference!",
                        9,
                        $exception->getMessage() . " Line:" . $exception->getLine()
                    );
                }
            }

            if($request->assigned_personnel) { // Eğer ikincil kullanıcı varsa, mantığı burada kuruyoruz.
                if (!is_array($request->assigned_personnel)) { // İkincil kullanıcı değeri tek değer ise, mantığı burada kuruyoruz.
                    $assigned_personnel = new TicketPersonnel();
                    $assigned_personnel->ticket_id = $ticket->id;
                    $assigned_personnel->personnel = $request->assigned_personnel;
                    $assigned_personnel->save();
                }
                else {
                    foreach ($request->assigned_personnel as $assigned_personnels) { // Eğer ikincil kullanıcı değeri birden fazla değer ise, mantığı burada kuruyoruz.
                        $assigned_personnel = new TicketPersonnel();
                        $assigned_personnel->ticket_id = $ticket->id;
                        $assigned_personnel->personnel = $assigned_personnels;
                        $assigned_personnel->save();
                    }
                }
            }

            if($request->effort_types) {
                $total_index = count($request->effort_types);
                for($i = 0; $i < $total_index; $i++) {
                    if($request->effort_types[$i] != null) {
                        $effort_log = new TicketEffortLog();
                        $effort_log->ticket_id = $ticket->id;
                        $effort_log->effort_type = $request->effort_types[$i];
                        $effort_log->minutes = $request->mints[$i] ?? 0;
                        $effort_log->hours = $request->hours[$i] ?? 0;
                        $effort_log->user_id = $auth_user->id;
                        $effort_log->updated_by = $auth_user->id;

                        if($effort_log->minutes != 0 || $effort_log->hours != 0) {
                            $effort_log->save();
                        }

                        $ticket_effort_total = TicketEffortTotal::query()->where("ticket_id", $ticket->id)->where("effort_type", $request->effort_types[$i])->first();

                        if($ticket_effort_total) {
                            $total_minutes = $ticket_effort_total->total_minutes;
                            $final_minutes = $ticket_effort_total->final_minutes;
                            $ticket_effort_total->total_minutes = $total_minutes + (($effort_log->hours * 60) + $effort_log->minutes);
                            $ticket_effort_total->final_minutes = $effort_log->effort_type == 5 ? $final_minutes : $final_minutes + (($effort_log->hours * 60) + $effort_log->minutes); // Internal ekliyorsak net'e dokunmuyoruz.
                            $ticket_effort_total->discount = $effort_log->effort_type == 5 ? 100 : 0;
                        }
                        else {
                            $ticket_effort_total = new TicketEffortTotal();
                            $ticket_effort_total->ticket_id = $effort_log->ticket_id;
                            $ticket_effort_total->effort_type = $effort_log->effort_type;
                            $ticket_effort_total->total_minutes = ($effort_log->hours * 60) + $effort_log->minutes;
                            $ticket_effort_total->discount = $effort_log->effort_type == 5 ? 100 : 0;
                            $ticket_effort_total->final_minutes = $effort_log->effort_type == 5 ? 0 : ($effort_log->hours * 60) + $effort_log->minutes;
                        }

                        $ticket_effort_total->save();
                    }
                }
            }


            //EMAIL COMPONENT


            $senior_org = Organization::where("id", $ticket->org_id)->first()->personnel_org; //organizasyondan sorumlu şirket

            if ($senior_org == 8) {
                $mailer = env("MAIL_GETUCON_MAILER");
            }
            elseif ($senior_org == 3) { //
                $mailer = env("MAIL_GETUCON_MAILER");
            }
            else {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Senior organization missed! Ticket-ID=" . $ticket->id ?? "-",
                    9,
                    "Not Exception!"
                );
                return response()->json(["status" => 0]); // critical error! wrong organization selected
            }

            $attachments = $request->ticketAttachments;
            $added_attachments = [];
            if ($attachments) {
                if (in_array(auth()->user()->role_id, [5, 6, 8])) {
                    foreach ($request->ticketAttachments as $key => $file) {
                        $attachment = new TicketAttachment();
                        $attachment->ticket_id = $ticket->id;
                        $attachment->attachment = $file;
                        $attachment->size = $key;
                        $attachment->is_mail=$request->email_to ? 1 : 0;;
                        $attachment->private = false;
                        $attachment->add_by = auth()->id();
                        $attachment->add_ip = request()->ip();
                        $attachment->save();

                    }
                }
                else {
                    foreach ($request->ticketAttachments as $key => $file) {
                        $attachment = new TicketAttachment();
                        $attachment->ticket_id = $ticket->id;
                        $attachment->attachment = $file["link"];
                        $attachment->size = $key;
                        $attachment->is_mail= $request->email_to ? 1 : 0;
                        $attachment->private = $file["isPrivate"] == "on";
                        $attachment->add_by = auth()->id();
                        $attachment->add_ip = request()->ip();
                        $attachment->save();
                        $added_attachments[]=$attachment->id;
                    }
                }


            } //ticket attachmet saving

            $attachments = TicketAttachment::query()->whereIn("id", $added_attachments)->where("private", 0)->get();
            $totalSize=0;

            foreach($attachments as $attachment){
                $totalSize += $attachment->size;
            }

            if($totalSize > 10485760) { // 10 MB'tan büyük ise maille attachmentslar gönderilmiyor.
                foreach($attachments as $attachment) {
                    $attachment->is_mail = 0;
                    $attachment->save();
                }

                $attachments=[];
            }

            if($senior_org != 7) { // medasol se mail çıkarma
                if($request->email_to) { // mail send to assigned user
                    $email_to = EmailHelper::explode($request->email_to);
                    $email_cc = $request->email_cc ? EmailHelper::explode($request->email_cc)  : "";
                    $email_bcc = $request->email_bcc ? EmailHelper::explode($request->email_bcc) : "";

                    if ($request->assigned_user == "true") {
                        $data["personnel"]  = 1;
                        $log_data["who"]    = 1; //mail log (personnel)
                    }
                    else {
                        $data["personnel"]  = 0;
                        $log_data["who"]    = 4; //mail log (other person)
                    }
                    try {
                        $data["to"]             = $email_to;
                        $data["cc"]             = $email_cc;
                        $data["bcc"]            = $email_bcc;
                        $data["attachments"]    = $attachments;
                        $data["ticket"]         = $ticket;
                        $data["sent_by"]        = $auth_user;

                        Mail::mailer($mailer)->send(new CreateTicketMail($data));

                        $log_data["mail_to"]    = $data["to"];
                        $log_data["mail_cc"]    = $data["cc"];
                        $log_data["mail_bcc"]   = $data["bcc"];
                        $log_data["ticket"]     = $ticket;

                        if(!$request->email_holder_to)
                            EmailHelper::create_ticket_mail_log($log_data);

                    }
                    catch (Exception $e){

                        Helper::create_debug_log(
                            __CLASS__,
                            __FUNCTION__,
                            "Create ticket mail failed!",
                            9,
                            $e->getMessage()
                        );
                    }

                }
                /**
                    SEND TO CUSTOMER
                 */
                if ($request->email_holder_to) { // mail send to customer ...

                    if ($request->email_to) {
                        if ($request->assigned_user == "true") {
                            $log_data["who"] = 3; //mail log (customer and personnel)
                        } else {
                            $log_data["who"] = 5; //mail log (customer and other person)
                        }
                    } else {
                        $log_data["who"] = 2; //mail log (customer)
                    }

                    $data["personnel"] = 0;
                    $email_holder_to   = EmailHelper::explode($request->email_holder_to);
                    $email_holder_cc   = $request->email_holder_cc  ? EmailHelper::explode($request->email_holder_cc)  : "";
                    $email_holder_bcc  = $request->email_holder_bcc ? EmailHelper::explode($request->email_holder_bcc) : "";

                    $data["to"]             = $email_holder_to;
                    $data["cc"]             = $email_holder_cc;
                    $data["bcc"]            = $email_holder_bcc;
                    $data["attachments"]    = $attachments;
                    $data["ticket"]         = $ticket;
                    $data["sent_by"]        = $auth_user;

                    try {
                        Mail::mailer($mailer)->send(new CreateTicketMail($data));
                        // mail_to,mail_cc ve mail_bcc bilgileri yukarıdan geliyor
                        $log_data["mail_holder_to"]     = $email_holder_to;
                        $log_data["mail_holder_cc"]     = $email_holder_cc;
                        $log_data["mail_holder_bcc"]    = $email_holder_bcc;
                        $log_data["ticket"]             = $ticket;

                        EmailHelper::create_ticket_mail_log($log_data);

                    }
                    catch (Exception $e) {
                        Helper::create_debug_log(
                            __CLASS__,
                            __FUNCTION__,
                            "Create ticket mail failed! Send to customer",
                            9,
                            $e->getMessage()
                        );
                    }


                } //super admin,admin,personnel admin,personnel

                /**
                    if customer create ticket send mail assigned user and customer
                 */
                if (in_array(auth()->user()->role_id, [5, 6, 8])) {

                    $assigned_user          = User::where("id", $personnel)->first();
                    $customer               = User::where("id", auth()->id())->first();
                    $assigned_user_email    = $assigned_user != null ? $assigned_user->email : null;
                    $customer_email         = $customer != null ? $customer->email : null;

                    if ($assigned_user_email && $customer_email) {

                        if ($senior_org == 8)
                            $data["bcc"] = explode(";", env("TEST_MAIL", "cg@getucon.de;md@getucon.de;si@getucon.de")); //WARNING!
                        if ($senior_org == 3)
                            $data["bcc"] = explode(";", env("TEST_MAIL", "cg@getucon.de;md@getucon.de;si@getucon.de")); //WARNING!


                        try {
                            $data["bcc"]        = ArrayHelper::array_differ($assigned_user_email,$data["bcc"]);
                            $data["to"]         = $assigned_user_email;
                            $data["bcc"]        = array_unique($data["bcc"]);
                            $data["personnel"]  = 1;
                            $data["ticket"]     = $ticket;
                            $data["sent_by"]    = $auth_user;
                            $data["attachments"] = $attachments;

                            Mail::mailer($mailer)->send(new CreateTicketMail($data));

                            $data["personnel"] = 0; //because send personnel above and send customer
                            $data["to"]        = $customer_email;
                            $data["bcc"]       = null;

                            Mail::mailer($mailer)->send(new CreateTicketMail($data));

                            $log_data["mail_to"]        = $assigned_user_email;
                            $log_data["mail_bcc"]       = $data["bcc"];
                            $log_data["mail_holder_to"] = $customer_email;
                            $log_data["ticket"]         = $ticket;
                            $log_data["who"]            = 6; // mail log (automatic mail if customer create a ticket)
                            EmailHelper::create_ticket_mail_log($log_data);

                        }
                        catch (Exception $e){

                            Helper::create_debug_log(
                                __CLASS__,
                                __FUNCTION__,
                                "Customer create ticket auto mail failed!",
                                9,
                                $e->getMessage()
                            );
                        }

                    }
                }

                if(in_array(auth()->user()->org_id, [3, 8])) {
                    if(!$request->email_to && !$request->email_holder_to) {
                        $emails = [];
                        $master = User::where('id', $request->personnel)->first()->email;
                        $emails[] = $master;

                        if($request->assigned_personnel) {
                            $secondary = User::whereIn('id', $request->assigned_personnel)->select('email')->pluck('email')->toArray();
                            $emails = array_merge($emails, $secondary);
                        }

                        if($master) {
                            if($senior_org == 8) {
                                $data["bcc"] = explode(";", env("TEST_MAIL", "cg@getucon.de;md@getucon.de;si@getucon.de"));
                            }
                            if($senior_org == 3) {
                                $data["bcc"] = explode(";", env("TEST_MAIL", "cg@getucon.de;md@getucon.de;si@getucon.de"));
                            }

                            $data["bcc"] = ArrayHelper::array_differ($emails, $data["bcc"]);
                            $data["to"] = $emails;
                            $data["personnel"] = 1; // Detaylı Email İçin
                            $data["ticket"] = $ticket;
                            $data["sent_by"] = $auth_user;
                            $data["attachments"] = $attachments;
                            $data["who"] = 7; // mail log vector (mail log personnel (automatic))

                            try {
                                Mail::mailer($mailer)->send(new CreateTicketMail($data));

                                $log_data["mail_to"] = $data["to"];
                                $log_data["mail_bcc"] = $data["bcc"];
                                $log_data["who"] = 7; // mail log vector (mail log personnel (automatic))
                                $log_data["ticket"] = $ticket;

                                EmailHelper::create_ticket_mail_log($log_data);
                            }
                            catch(Exception $exception) {
                                Helper::create_debug_log(
                                    __CLASS__,
                                    __FUNCTION__,
                                    "Something went wrong while trying to send ticket creation email! (Personnel)",
                                    9,
                                    $exception->getMessage() . " Line:" . $exception->getLine()
                                );
                            }
                        }
                    }
                }
            }

            //add external partner if exist in request
            if (isset($request->external_partners[0])) {

                if ($request->external_partners[0] != null) {
                    $lenght = count($request->external_partners);
                    for ($i = 0; $i < $lenght; $i++) {
                        $ticket_ext_partner = new TicketExternalPartner();
                        $ticket_ext_partner->ticket_id = $ticket->id;
                        $ticket_ext_partner->partner_id = $request->external_partners[$i];
                        $ticket_ext_partner->contact_id = $request->external_partner_contacts[$i]??null; // buraya bak ikisini eşdeğer alman lazım
                        $ticket_ext_partner->save();
                    }
                }
            }
            //create status log
            Helper::update_ticket_status($ticket->id,$ticket->status_id);

            return response()->json(["success" => 1]);
        }
        catch (Exception $e) {

            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Ticket Create Error! Ticket ID=" . $ticket->id ?? "-",
                9,
                $e->getMessage().$e->getLine()
            );


        }
    }

    public function editTicket(Request $request, $ticketId) {
        try {
            if(in_array(auth()->user()->role_id, [5, 6, 8])) { // müşteri sadece attachmentda değişiklik yapabilir
                if($request->ticketAttachments) {
                    foreach($request->ticketAttachments as $key => $attachment) {
                        $ticketAttachment = new TicketAttachment();
                        $ticketAttachment->ticket_id = $ticketId;
                        $ticketAttachment->attachment = $attachment;
                        $ticketAttachment->size = $key;
                        $ticketAttachment->add_by = auth()->id();
                        $ticketAttachment->add_ip = request()->ip();
                        $ticketAttachment->private = 0;
                        $ticketAttachment->save();
                    }
                }
            }
            else {
                $rules = array('name' => 'required');

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return redirect('update-ticket/' . $ticketId)->withInput()->withErrors($validator->errors()->all());
                }


                $ticket = Ticket::where('id', $ticketId)->firstOrFail();
                $all_assigned_users = [];
                $all_assigned_users[] = $ticket->personnel;
                $ticket->name = ($request->name) ? $request->name : $ticket->name;
                $ticket->description = ($request->description) ? $request->description : $ticket->description;
                $ticket->org_id = ($request->organization) ? $request->organization : $ticket->org_id;
                $ticket->user = ($request->user) ? $request->user : $ticket->user;
                $ticket->personnel = ($request->personnel) ? $request->personnel : $ticket->personnel;
                $ticket->comment_due_date = $request->ticket_comment_due_date ?? $ticket->comment_due_date;

                if ($request->status) {
                    if ($request->status != $ticket->status_id) {

                        Helper::update_ticket_status($ticket->id,$request->status);

                    }
                }


                $isCond1 = in_array($ticket->status_id, [6, 7, 9]); // Ticket Statusu Done, Invoiced ya da Closed mu?
                $isCond2 = in_array($request->status, [6, 7, 9]); // Yeni Status Done, Invoiced ya da Closed mu?
                if ($isCond1) {
                    if ($isCond2) {
                        $ticket->status_id = ($request->status) ? $request->status : $ticket->status_id;
                    }
                }
                else {
                    $ticket->status_id = ($request->status) ? $request->status : $ticket->status_id;
                }

                if($request->status !=5 ){
                    $ticket->comment_due_date = null;
                }

                $ticket->due_date = $request->due_date??$ticket->due_date;
                $ticket->priority = ($request->priority) ? $request->priority : $ticket->priority;
                $ticket->category = ($request->category) ? $request->category : $ticket->category;
                $ticket->sub_category_id = $request->sub_category;

                $ticket->transport_price = ($request->transport_price) ? $request->transport_price : $ticket->transport_price;
                $ticket->update_by = auth()->id();
                $ticket->update_ip = request()->ip();



                // Ticket Attachments

                if ($request->ticketAttachments) {
                    foreach ($request->ticketAttachments as $key => $attachment) {

                        $ticketAttachment = new TicketAttachment();
                        $ticketAttachment->ticket_id = $ticket->id;
                        $ticketAttachment->attachment = $attachment["link"];
                        $ticketAttachment->size = $key;
                        if (auth()->user()->role_id != 7)
                            $ticketAttachment->private = isset($attachment["isPrivate"]) ? $attachment["isPrivate"] == "on" : "off";
                        else
                            $ticketAttachment->private = true;
                        $ticketAttachment->add_by = auth()->id();
                        $ticketAttachment->add_ip = request()->ip();
                        $ticketAttachment->save();
                    }
                }
                $ticket->save();

                $ticket_personnel = TicketPersonnel::where('ticket_id', $ticketId)->pluck('personnel')->toArray(); // Mevcut personelleri al ve Array'e çevir.

                if($ticket_personnel) {
                    $all_assigned_users = array_merge($all_assigned_users, $ticket_personnel);
                }

                $assigned_users_final = [];
                $assigned_users_final[] = $ticket->personnel;

                if($request->assigned_personnel) { // İkincil kullanıcı varsa mantığını burada kuruyoruz.
                    $assigned_users_final = array_merge($assigned_users_final, $request->assigned_personnel);
                    $difference = array_diff($request->assigned_personnel, $ticket_personnel); // Mevcut ikincil kullanıcılara başka ikincil kullanıcı eklenmiş mi?
                    $to_be_deleted = array_diff($ticket_personnel, $request->assigned_personnel); // Mevcut ikincil kullanıcılardan biri silinmiş mi?

                    if($difference) { // Eğer ikincil kullanıcı eklenmişse mantığı burada kuruyoruz.
                        foreach($difference as $personnel) {
                            $assigned_personnels = new TicketPersonnel();
                            $assigned_personnels->ticket_id = $ticket->id;
                            $assigned_personnels->personnel = $personnel;
                            $assigned_personnels->save();
                        }
                    }

                    if($to_be_deleted) { // Eğer ikincil kullanıcı silinmişse mantığı burada kuruyoruz.
                        foreach($to_be_deleted as $personnel) {
                            TicketPersonnel::query()->where("ticket_id", $ticketId)->where("personnel", $personnel)->delete();
                        }
                    }
                }

                $new_asssigned_personnel = array_diff($assigned_users_final, $all_assigned_users);

                foreach($new_asssigned_personnel as $personnel) {
                    $user = User::query()->find($personnel);
                    AssignNewPersonnel::dispatch($user, $ticket, Auth::id());
                }

                if ($request->assigned_personnel == null) { // Eğer bütün ikincil kullanıcılar silinmişse mantığını burada kuruyoruz.
                    TicketPersonnel::query()->where("ticket_id", $ticketId)->delete();
                }

                if($request->ticket_references) {
                    foreach($request->ticket_references as $ticket_reference) {
                        TicketReference::query()->create([
                            'parent_ticket' => $ticket->id,
                            'child_ticket' => $ticket_reference,
                            'created_by' => Auth::id()
                        ]);
                    }
                }


                //firstly delete all partners and add again
                $partners = TicketExternalPartner::query()->where("ticket_id", $ticketId);
                $partners->delete();
                //add external partner if exist in request

                if (isset($request->external_partners[0])) {

                    if ($request->external_partners[0] != null) {
                        $lenght = count($request->external_partners);
                        for ($i = 0; $i < $lenght; $i++) {
                            if ($request->external_partners[$i] != null) {
                                $ticket_ext_partner = new TicketExternalPartner();
                                $ticket_ext_partner->ticket_id = $ticket->id;
                                $ticket_ext_partner->partner_id = $request->external_partners[$i];
                                $ticket_ext_partner->contact_id = $request->external_partner_contacts[$i]??null; // buraya bak ikisini eşdeğer alman lazım
                                $ticket_ext_partner->save();
                            }
                        }
                    }
                }
            }

            if ($request->save_close == "1") {
                return redirect("/tickets");
            }
            else {
                return redirect("/update-ticket/" . $ticketId);
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to update a ticket!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            abort(500);
        }
    }

    public function getEffort($effort_id) {
        $effort = TicketEffortLog::query()->find($effort_id);
        return response()->json($effort);
    }

    public function updateEffort(Request $request) {
        try {
            $effort = TicketEffortLog::query()->find($request->effort_id);
            $original_effort_type = $effort->effort_type;
            $original_hours = $effort->hours;
            $original_minutes = $effort->minutes;
            $effort->effort_type = $request->effort_type;
            $effort->minutes = $request->effort_minute ?? 0;
            $effort->hours = $request->effort_hour ?? 0;
            $effort->updated_by = auth()->id();
            $effort->save();

            if($effort->wasChanged("effort_type")) {
                $ticket_effort_logs = TicketEffortLog::query()->where("ticket_id", $effort->ticket_id)->where("effort_type", $original_effort_type)->get();
                $new_total_ticket_efforts = TicketEffortTotal::query()->where("ticket_id", $effort->ticket_id)->where("effort_type", $effort->effort_type)->first();
                $total_ticket_efforts = TicketEffortTotal::query()->where("ticket_id", $effort->ticket_id)->where("effort_type", $original_effort_type)->first();

                if($ticket_effort_logs->isNotEmpty()) {
                    $total_ticket_efforts->total_minutes -= ($original_hours * 60) + $original_minutes;
                    $total_ticket_efforts->total_minutes = max($total_ticket_efforts->total_minutes, 0);
                    $total_ticket_efforts->final_minutes = $total_ticket_efforts->effort_type == 5 ? 0 : $total_ticket_efforts->total_minutes;
                    $total_ticket_efforts->discount = $total_ticket_efforts->effort_type == 5 ? 100 : 0;
                    $total_ticket_efforts->save();
                }
                else {
                    $total_ticket_efforts->delete();
                }

                if($new_total_ticket_efforts) {
                    $new_total_ticket_efforts->total_minutes += ($effort->hours * 60) + $effort->minutes;
                }
                else {
                    $new_total_ticket_efforts = new TicketEffortTotal();
                    $new_total_ticket_efforts->ticket_id = $effort->ticket_id;
                    $new_total_ticket_efforts->effort_type = $effort->effort_type;
                    $new_total_ticket_efforts->total_minutes = ($effort->hours * 60) + $effort->minutes;
                }

                $new_total_ticket_efforts->final_minutes = $effort->effort_type == 5 ? 0 : $new_total_ticket_efforts->total_minutes;
                $new_total_ticket_efforts->discount = $effort->effort_type == 5 ? 100 : 0;
                $new_total_ticket_efforts->save();
            }
            else {
                if($effort->wasChanged("minutes") || $effort->wasChanged("hours")) {
                    $total_ticket_efforts = TicketEffortTotal::query()->where("ticket_id", $effort->ticket_id)->where("effort_type", $effort->effort_type)->first();
                    $total_ticket_efforts->total_minutes -= ($original_hours * 60) + $original_minutes;
                    $total_ticket_efforts->total_minutes = max($total_ticket_efforts->total_minutes, 0);
                    $total_ticket_efforts->total_minutes += ($effort->hours * 60) + $effort->minutes;
                    $total_ticket_efforts->final_minutes = $effort->effort_type == 5 ? 0 : $total_ticket_efforts->total_minutes;
                    $total_ticket_efforts->discount = $effort->effort_type == 5 ? 100 : 0;
                    $total_ticket_efforts->save();
                }
            }

            return response()->json([
                "status" => "Successful",
                "message" => "The effort has updated successfully!"
            ]);
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to update an effort!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return response()->json([
                "status" => "Error",
                "message" => "Something went wrong!"
            ]);
        }
    }

    function addEffort(Request $request) {
        try {
            $effort = new TicketEffortLog();
            $effort->ticket_id = $request->ticket_id;
            $effort->effort_type = $request->effort_type;
            $effort->minutes = $request->effort_minute ?? 0;
            $effort->hours = $request->effort_hour ?? 0;

            if($request->is_discussion == 1) {
                $effort->is_discussion = $request->is_discussion;
                $effort->discussion_id = $request->discussion_id;
            }

            $effort->user_id = Auth::id();
            $effort->updated_by = Auth::id();
            $effort->save();
            $ticket_effort_total = TicketEffortTotal::query()->where("ticket_id", $effort->ticket_id)->where("effort_type", $effort->effort_type)->first();

            if($ticket_effort_total) {
                $ticket_effort_total->total_minutes += ($effort->hours * 60) + $effort->minutes;
            }
            else {
                $ticket_effort_total = new TicketEffortTotal();
                $ticket_effort_total->ticket_id = $effort->ticket_id;
                $ticket_effort_total->effort_type = $effort->effort_type;
                $ticket_effort_total->total_minutes = ($effort->hours * 60) + $effort->minutes;
            }

            $ticket_effort_total->final_minutes = $effort->effort_type == 5 ? 0 : $ticket_effort_total->total_minutes;
            $ticket_effort_total->discount = $effort->effort_type == 5 ? 100 : 0;
            $ticket_effort_total->save();
            return response()->json([
                "status" => "Successful",
                "message" => "The effort has added successfully!"
            ]);
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to add an effort!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return response()->json([
                "status" => "Error",
                "message" => "Something went wrong!"
            ]);
        }
    }

    public function deleteEffort($effort_id) {
        try {
            $effort = TicketEffortLog::query()->find($effort_id);
            $ticket_id = $effort->ticket_id;
            $ticket_effort_total = TicketEffortTotal::query()->where("ticket_id", $ticket_id)->where("effort_type", $effort->effort_type)->first();
            $ticket_effort_total->total_minutes -= ($effort->hours * 60) + $effort->minutes;

            if($ticket_effort_total->total_minutes == 0) {
                $ticket_effort_total->delete();
            }
            else {
                $ticket_effort_total->final_minutes = $effort->effort_type == 5 ? 0 : $ticket_effort_total->total_minutes;
                $ticket_effort_total->discount = $effort->effort_type == 5 ? 100 : 0;
                $ticket_effort_total->save();
            }

            $effort->delete();
            return response()->json([
                "status" => "Successful",
                "message" => "The effort has deleted successfully!"
            ]);
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to delete an effort! Effort ID:" . $effort_id,
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return response()->json([
                "status" => "Error",
                "message" => "Something went wrong!"
            ]);
        }
    }

    public function removeAttachment($attachment_id) {
        try {
            $ticket_attachment = TicketAttachment::query()->findOrFail($attachment_id);
            $ticket_attachment->delete();
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to remove a ticket's attachment!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return redirect('/tickets');
        }
    }

    public function deleteTicket($ticket_id) {
        try {
            $ticket = Ticket::query()->findOrFail($ticket_id);
            $ticket->delete();
            return redirect('tickets')->with('success', 'Ticket deleted successfully!');
        }
        catch (Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to delete a ticket!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function updateStatusCounter() {
        $role = auth()->user()->role_id;
        $user = auth()->id();
        $organization = auth()->user()->org_id;

        if(in_array($role, [1, 2, 3])) {
            $tickets = Ticket::all();
        }
        elseif(in_array($role, [4, 7])) {
            $tickets = Ticket::query()->where("personnel", $user)->orWhere("user", $user)->orWhereRaw('id IN (SELECT ticket_id FROM ticket_personnels WHERE personnel = ' . $user . ' AND deleted_at IS NULL)')->get();
        }
        else {
            if($role == 6) {
                $tickets = Ticket::query()->where("user", $user)->where("is_private", 0)->get();
            }
            elseif($role == 8) {
                $users = User::query()->where("org_id", auth()->user()->org_id)->where("role_id", 6)->where("id", "!=", 4)->select("id")->pluck("id")->toArray(); // Organizasyonun "Firma User" olan kullanıcılarını ayıklıyoruz.
                $users[] = auth()->id();
                $tickets = Ticket::query()->whereIn("user", $users)->where("is_private", 0)->get();
            }
            else {
                $tickets = Ticket::query()->where("org_id", $organization)->where("is_private", 0)->get();
            }
        }

        if(in_array($role, [1, 2, 3, 4, 7])) {
            $counter["transferred_tickets"] = $tickets->where('status_id', 2)->count();
            $counter["in_progress_tickets"] = $tickets->where('status_id', 3)->count();
            $counter["answered_tickets"] = $tickets->where('status_id', 4)->count();

        }
        else {
            $counter["in_progress_tickets"] = $tickets->whereIn('status_id', [2, 3, 4])->count();
        }

        $counter["total_tickets"] = $tickets->count();
        $counter["opened_tickets"] = $tickets->where('status_id', 1)->count();
        $counter["question_tickets"] = $tickets->where('status_id', 5)->count();
        $counter["done_tickets"] = $tickets->where('status_id', 6)->count();
        $counter["invoiced_tickets"] = $tickets->where('status_id', 7)->count();
        $counter["on_hold_tickets"] = $tickets->where('status_id', 8)->count();
        $counter["closed_tickets"] = $tickets->where('status_id', 9)->count();
        return response()->json($counter);
    }

    public function updateStatus(Request $request) {
        $ticket = Ticket::find($request->ticket_id);
        $isCond1 = in_array($ticket->status_id, [6, 7, 9]); // Ticket Statusu Done, Invoiced ya da Closed mu?
        $isCond2 = in_array($request->new_status, [6, 7, 9,10]); // Yeni Status Done, Invoiced, Closed veya correction after invoice mi?

        if($isCond1 && !$isCond2) {
            return response()->json(["success" => 0]);
        }

        if ($request->new_status != $ticket->status_id) {

            Helper::update_ticket_status($ticket->id,$request->new_status);

            return response()->json(["success" => 1]);
        }
    }

    public function change_private_status(Request $request) {

        try {

            $attachment = TicketAttachment::find($request->id);
            $attachment->private = $request->is_private;
            $attachment->save();
            return response(["success" => 1, "is_private" => intval($attachment->private)]);
        }
        catch (Exception $e) {

            return \response()->json(["success" => 0]);
        }
    }

    public function getTicket($id) {
        $ticket = Ticket::where('id', $id)->first();
        if ($ticket) {
            $ticket_user = $ticket->user;
            $ticket_personnel = $ticket->personnel;
            $ticket_personnels = TicketPersonnel::whereIn('ticket_id', [$id])->whereNull('deleted_at')->pluck('personnel')->toArray();
            $auth_user = auth()->id();
            $auth_role = auth()->user()->role_id;

            if ($ticket_user == $auth_user || $ticket_personnel == $auth_user || in_array($auth_user, $ticket_personnels) || in_array($auth_role, [1, 2, 3])) {
                $ticket = Ticket::find($id)->only("id", "name", "organizationName", "org_id", "category", "categoryName");
                return response()->json($ticket);
            }
            else {
                return response()->json(null);
            }
        }
        else {
            return response()->json(null);
        }
    }

    public function proofTicket(Request $request)
    {
        $ticket = Ticket::find($request->id);
        $ticket->proofed = true;
        $ticket->proofed_at = Carbon::now()->toDateTimeString();
        $ticket->proof_by = auth()->id();
        $ticket->proof_ip = request()->ip();
        $ticket->update_by = auth()->id();
        $ticket->update_ip = $request->ip();
        $ticket->update();

        return response()->json(
            [
                "proofed_at" => Carbon::parse($ticket->proofed_at)->format("d.m.Y H:i:s"),
                "proofed_by" => $ticket->getProofedName(),
            ]
        );
    }

    public function removeReference(Request $request) {
        $reference = TicketReference::where('parent_ticket', $request->parent)->where('child_ticket', $request->child)->first();

        if($reference) {
            try {
                $reference->delete();
                return response()->json(['status' => 'Success']);
            }
            catch(Exception $exception) {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Something went wrong while trying to delete a ticket's reference! Reference:" . $reference->id,
                    9,
                    $exception->getMessage() . " Line:" . $exception->getLine()
                );

                abort(500);
            }
        }
        else {
            return response()->json(['status' => 'Error']);
        }
    }

    public function getPossibleReferenceTickets($ticket_id, Request $request) { // Bu fonksiyonu, referans ekleme bölümündeki Select2 seçenekleri için kullanacağız.
        try {
            $related_tickets = Helper::getAllRelatedReferences($ticket_id); // Bu ticket ile ilişkili bütün ticket'ları alıyoruz. Bu array'i Select2 seçeneklerinden çıkarmak için kullanıyor olacacağız.
            $child_tickets = TicketReference::pluck('child_ticket')->toArray(); // Halihazırda parent ticket'ı olan ticket'ları da child ticket olarak ekleyemez.

            if(Auth::user()->role_id === 4) {
                $legit_tickets = Ticket::where('personnel', Auth::id())->orderBy('id', 'DESC');
            }
            else {
                $legit_tickets = Ticket::orderBy('id', 'DESC');
            }

            $tickets = $legit_tickets->select(['id', 'name as text'])->whereNotIn('id', $related_tickets)->whereNotIn('id', $child_tickets)->where(function($query) use($request) {
                $query->where('id', 'LIKE', '%' . $request->q . '%')->orWhere('name', 'LIKE', '%' . $request->q . '%');
            })->limit(50);

            return $tickets->get();
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to retrieve possible reference tickets!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function changePrivacyStatus(Request $request) { // Bu fonksiyon bir ticket'in statüsünü "Private" veya "Public" yapmak için yazılmıştır.
        try {
            $ticket = Ticket::query()->findOrFail($request->ticket_id);

            if($request->status === "0") { // "Public" hale getir.
                $ticket->is_private = 0;
                $message = "Ticket's privacy status has changed as public successfully.";
            }
            else { // "Private" hale getir.
                $ticket->is_private = 1;
                $message = "Ticket's privacy status has changed as private successfully.";
            }

            $ticket->save();
            return [
                "status" => "Success",
                "message" => $message
            ];
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to change a ticket's privacy status!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return ["status" => "Error"];
        }
    }

    public function calculateEffortByType($ticket_id) {
        try {
            $efforts = TicketEffortTotal::query()->where("ticket_id", $ticket_id)->get();
            $effort_array = [];

            foreach($efforts as $key => $value) {
                $effort_array[$key]["id"] = $value->id;
                $effort_array[$key]["minutes"] = $value->total_minutes;
                $effort_array[$key]["total"] = Helper::convertMinuteToHourWithText($value->total_minutes);
                $effort_array[$key]["final"] = Helper::convertMinuteToHourWithText($value->final_minutes);
                $effort_array[$key]["final_minutes"] = $value->final_minutes % 60;
                $effort_array[$key]["final_hours"] = intval($value->final_minutes / 60);
                $effort_array[$key]["discount"] = number_format($value->discount, 1, ".", "");
                $effort_array[$key]["type"] = EffortType::query()->find($value->effort_type)->type;
            }

            $total = Helper::convertMinuteToHourWithText(Helper::getTotalEffortAsMinute($ticket_id));
            $final = Helper::convertMinuteToHourWithText(Helper::getDiscountedEffortsAsMinute($ticket_id));
            $discount = Helper::getTotalEffortAsMinute($ticket_id) === 0 ?: (100 - round(((Helper::getDiscountedEffortsAsMinute($ticket_id) / Helper::getTotalEffortAsMinute($ticket_id)) * 100), 3));
            $effort_array[] = [
                "total" => $total,
                "final" => $final,
                "discount" => number_format($discount, 1, ".", ""),
                "type" => "TOTAL"
            ];

            return $effort_array;
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to calculate an effort by its type",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function calculateCommentEffort($discussion_id) { // Bir yorumun, eforlarının toplam süresini hesaplamak için bu fonksiyonu kullanıyoruz.
        try {
            $efforts = TicketEffortLog::query()->where("discussion_id", $discussion_id)->get();
            $effort_types = TicketEffortLog::query()->where("discussion_id", $discussion_id)->pluck("effort_type")->unique();
            $type_total_minutes = 0;
            $effort_array = [];

            foreach($effort_types as $effort_type) {
                foreach($efforts->where("effort_type", $effort_type) as $effort_with_type) {
                    $type_total_minutes += ($effort_with_type->hours * 60) + $effort_with_type->minutes;
                }

                $effort_array[$effort_type] = Helper::convertMinuteToHourWithText($type_total_minutes);
                $type_total_minutes = 0;
            }

            return $effort_array;
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to calculate a discussion's effort!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function discountEffort(Request $request) {
        try {
            $effort = TicketEffortTotal::query()->findOrFail($request->effort_total_id);
            $final_minutes = ($request->final_hour * 60) + $request->final_minutes;
            $effort->final_minutes = $final_minutes;
            $effort->discount = (100 - round((($effort->final_minutes / $effort->total_minutes) * 100), 3));
            $effort->save();

            return [
                "status" => "Success",
                "message" => "Discount has applied successfully!"
            ];
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to apply a discount!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return [
                "status" => "Error",
                "message" => "Something went wrong while trying to apply a discount!"
            ];
        }
    }

    public function takeComment(Request $request)
    {
        try {
            if($request->type == "all"){
                $discussions = Discussion::where('ticket_id', $request->ticket_id)->where("is_private", 0)->latest()->get();
            }else{
                $discussions = Discussion::where('ticket_id', $request->ticket_id)->where("is_private", 0)->latest()->limit(1)->get();
            }
            $text = "";
            foreach($discussions as $discussion) {
                $text.="<p>========================</p>
                        <p>".$discussion->UserName." ".Carbon::parse($discussion->created_at)->format('d.m.Y [H:i:s]')."</p>
                        ".$discussion->message." ";
            }
            return response()->json(["status"=>"Success","text"=>$text]);
       }
       catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to take comment!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return [
                "status" => "Error",
            ];
       }
    }
}