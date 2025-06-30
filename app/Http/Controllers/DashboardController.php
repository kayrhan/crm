<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Organization;
use App\Status;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    //
    public function getDashboard(Request $request)
    {
        try {
            if (in_array(auth()->user()->role_id, [4, 5, 6, 7, 8])) {
                //non authorized roles
                return redirect('/tickets');
            }

            $personnels             = User::
            where(function($query){
                $query->where('org_id',7);
                $query->orWhere('org_id', 3);
                $query->orWhere('org_id', 8);
            })->orderBy("org_id", "desc");

            if($request->user_id>0){
                $personnels = $personnels->where('id',$request->user_id);
            }

            $personnels = $personnels->get();

            $organizations          = Organization::orderBy("is_vip", "desc");

            if($request->org_id>0){
                $organizations = $organizations->where('id',$request->org_id);
            }

            $organizations = $organizations->get();

            $query = DB::table("tickets")->join("organizations", "tickets.org_id", "=", "organizations.id")
                ->selectRaw("tickets.id as ticket_id,
                    tickets.status_id as ticket_status_id,
                    tickets.personnel as ticket_personnel_id,
                    organizations.id as organization_id,
                    organizations.org_name as organization_name,
                    organizations.is_contracted as is_contracted");


            if($request->user_id>0){
                $query = $query->where('tickets.personnel',$request->user_id);
            }
            if($request->org_id>0){
                $query = $query->where('organizations.id',$request->org_id);
            }

            $query = $query->get();

            $query2 = DB::table("tickets")->join("users", "tickets.personnel", "=", "users.id")
                ->selectRaw("users.id as user_id,
                    users.first_name as first_name,
                    users.surname as surname,
                    tickets.id as ticket_id,
                    tickets.status_id as ticket_status_id,
                    tickets.org_id as ticket_org_id,
                    tickets.personnel as ticket_personnel_id");

            if($request->user_id>0){
                $query2 = $query2->where('users.id',$request->user_id);
            }
            if($request->org_id>0){
                $query2 = $query2->where('tickets.org_id',$request->org_id);
            }

            $query2 = $query2->get();


            foreach ($personnels as $personnel) {
                $opened_ticket_org_tmp      = [];
                $transferred_ticket_org_tmp = [];
                $in_progress_ticket_org_tmp = [];
                $answered_ticket_org_tmp    = [];
                $question_ticket_org_tmp    = [];
                $on_hold_ticket_org_tmp     = [];

                $opened_ticket_query        = $query->where("ticket_status_id", 1)->where("ticket_personnel_id", $personnel->id); // opened
                $transferred_ticket_query   = $query->where("ticket_status_id", 2)->where("ticket_personnel_id", $personnel->id); // transferred
                $in_progress_ticket_query   = $query->where("ticket_status_id", 3)->where("ticket_personnel_id", $personnel->id); // In Progress
                $answered_ticket_query      = $query->where("ticket_status_id", 4)->where("ticket_personnel_id", $personnel->id); // answered
                $question_ticket_query      = $query->where("ticket_status_id", 5)->where("ticket_personnel_id", $personnel->id); // question
                $on_hold_ticket_query       = $query->where("ticket_status_id", 8)->where("ticket_personnel_id", $personnel->id); // on hold



                $opened_ticket_total        =  $opened_ticket_query->count();
                $transferred_ticket_total   =  $transferred_ticket_query->count();
                $in_progress_ticket_total   =  $in_progress_ticket_query->count();
                $answered_ticket_total      =  $answered_ticket_query->count();
                $question_ticket_total      =  $question_ticket_query->count();
                $on_hold_ticket_total       =  $on_hold_ticket_query->count();

                $total_ticket               =   $opened_ticket_total + $transferred_ticket_total + $in_progress_ticket_total +
                    $answered_ticket_total + $question_ticket_total + $on_hold_ticket_total;

                $opened_ticket_org          = $opened_ticket_query->groupBy("organization_id");
                $transferred_ticket_org     = $transferred_ticket_query->groupBy("organization_id");
                $in_progress_ticket_org     = $in_progress_ticket_query->groupBy("organization_id");
                $answered_ticket_org        = $answered_ticket_query->groupBy("organization_id");
                $question_ticket_org        = $question_ticket_query->groupBy("organization_id");
                $on_hold_ticket_org         = $on_hold_ticket_query->groupBy("organization_id");


                $this->push_organization($opened_ticket_org, $opened_ticket_org_tmp);

                $this->push_organization($transferred_ticket_org, $transferred_ticket_org_tmp);
                $this->push_organization($in_progress_ticket_org, $in_progress_ticket_org_tmp);
                $this->push_organization($answered_ticket_org, $answered_ticket_org_tmp);
                $this->push_organization($question_ticket_org, $question_ticket_org_tmp);
                $this->push_organization($on_hold_ticket_org, $on_hold_ticket_org_tmp);


                $personnel->opened_ticket_total         = $opened_ticket_total;
                $personnel->transferred_ticket_total    = $transferred_ticket_total;
                $personnel->in_progress_ticket_total    = $in_progress_ticket_total;
                $personnel->answered_ticket_total       = $answered_ticket_total;
                $personnel->question_ticket_total       = $question_ticket_total;
                $personnel->on_hold_ticket_total        = $on_hold_ticket_total;
                $personnel->total_ticket                = $total_ticket;

                $personnel->opened_org_info         = collect($opened_ticket_org_tmp)->sortByDesc("count");
                $personnel->transferred_org_info    = collect($transferred_ticket_org_tmp)->sortByDesc("count");
                $personnel->in_progress_org_info    = collect($in_progress_ticket_org_tmp)->sortByDesc("count");
                $personnel->answered_org_info       = collect($answered_ticket_org_tmp)->sortByDesc("count");
                $personnel->question_org_info       = collect($question_ticket_org_tmp)->sortByDesc("count");
                $personnel->on_hold_org_info        = collect($on_hold_ticket_org_tmp)->sortByDesc("count");
            }

            foreach ($organizations as $organization) {

                $opened_ticket_personnel_tmp        = [];
                $transferred_ticket_personnel_tmp   = [];
                $in_progress_ticket_personnel_tmp   = [];
                $answered_ticket_personnel_tmp      = [];
                $question_ticket_personnel_tmp      = [];
                $on_hold_ticket_personnel_tmp       = [];

                $opened_ticket_query_org        = $query2->where("ticket_status_id", 1)->where("ticket_org_id", $organization->id); // opened
                $transferred_ticket_query_org   = $query2->where("ticket_status_id", 2)->where("ticket_org_id", $organization->id); // transferred
                $in_progress_ticket_query_org   = $query2->where("ticket_status_id", 3)->where("ticket_org_id", $organization->id); // In Progress
                $answered_ticket_query_org      = $query2->where("ticket_status_id", 4)->where("ticket_org_id", $organization->id); // answered
                $question_ticket_query_org      = $query2->where("ticket_status_id", 5)->where("ticket_org_id", $organization->id); // question
                $on_hold_ticket_query_org       = $query2->where("ticket_status_id", 8)->where("ticket_org_id", $organization->id); // on hold


                $opened_ticket_total        =  $opened_ticket_query_org->count();
                $transferred_ticket_total   =  $transferred_ticket_query_org->count();
                $in_progress_ticket_total   =  $in_progress_ticket_query_org->count();
                $answered_ticket_total      =  $answered_ticket_query_org->count();
                $question_ticket_total      =  $question_ticket_query_org->count();
                $on_hold_ticket_total       =  $on_hold_ticket_query_org->count();

                $total_ticket               =   $opened_ticket_total + $transferred_ticket_total + $in_progress_ticket_total +
                    $answered_ticket_total + $question_ticket_total + $on_hold_ticket_total;

                $opened_ticket_personnel            = $opened_ticket_query_org->groupBy("ticket_personnel_id");
                $transferred_ticket_personnel       = $transferred_ticket_query_org->groupBy("ticket_personnel_id");
                $in_progress_ticket_personnel       = $in_progress_ticket_query_org->groupBy("ticket_personnel_id");
                $answered_ticket_personnel          = $answered_ticket_query_org->groupBy("ticket_personnel_id");
                $question_ticket_personnel          = $question_ticket_query_org->groupBy("ticket_personnel_id");
                $on_hold_ticket_personnel           = $on_hold_ticket_query_org->groupBy("ticket_personnel_id");


                $this->push_personnel($opened_ticket_personnel, $opened_ticket_personnel_tmp);
                $this->push_personnel($transferred_ticket_personnel, $transferred_ticket_personnel_tmp);
                $this->push_personnel($in_progress_ticket_personnel, $in_progress_ticket_personnel_tmp);
                $this->push_personnel($answered_ticket_personnel, $answered_ticket_personnel_tmp);
                $this->push_personnel($question_ticket_personnel, $question_ticket_personnel_tmp);
                $this->push_personnel($on_hold_ticket_personnel, $on_hold_ticket_personnel_tmp);

                $organization->opened_ticket_total           = $opened_ticket_total;
                $organization->transferred_ticket_total      = $transferred_ticket_total;
                $organization->in_progress_ticket_total      = $in_progress_ticket_total;
                $organization->answered_ticket_total         = $answered_ticket_total;
                $organization->question_ticket_total         = $question_ticket_total;
                $organization->on_hold_ticket_total          = $on_hold_ticket_total;
                $organization->total_ticket                  = $total_ticket;

                $organization->opened_personnel_info         = collect($opened_ticket_personnel_tmp)->sortByDesc("count");
                $organization->transferred_personnel_info    = collect($transferred_ticket_personnel_tmp)->sortByDesc("count");
                $organization->in_progress_personnel_info    = collect($in_progress_ticket_personnel_tmp)->sortByDesc("count");
                $organization->answered_personnel_info       = collect($answered_ticket_personnel_tmp)->sortByDesc("count");
                $organization->question_personnel_info       = collect($question_ticket_personnel_tmp)->sortByDesc("count");
                $organization->on_hold_personnel_info        = collect($on_hold_ticket_personnel_tmp)->sortByDesc("count");
            }


            $organizations = $organizations->reject(function ($value, $key) {
                return $value->total_ticket == 0;
            });

            $data["statusses"] = Status::where("main",1)->get()->reject(function ($value, $key) { // ana ticket statusleri alınıyor
                return $value->id == 6 || $value->id == 7 || $value->id == 9 || $value->id == 11; // Done, Invoiced, Closed, Terminated
            });

            ///Auth user should be beginning of list
            $personnels     = $personnels->sortByDesc("total_ticket");              // sory by total ticket count
            $auth_user      = $personnels->where("id", auth()->id());          // find auth user
            $personnels->pull($auth_user->keys()->first());                         // delete auth user
            if($auth_user->values()->first()!=null){
                $personnels->prepend($auth_user->values()->first());  // add auth user at the beginning of collection
            }
            $organizations          = $organizations->sortByDesc("total_ticket"); //sort by total ticket

            $data["personnels"]     = $personnels->all();
            $data["organizations"]  = $organizations->all();

            if($request->user_id>0){
                $data['selectedUser'] = User::where('id',$request->user_id)->first();
            }
            if($request->org_id>0){
                $data['selectedOrganization'] = Organization::where('id',$request->org_id)->first();
            }

            if ($request->type == 1) {
                return response()->json($data);
            }
            return view('dashboard.index')->with($data);
        } catch (Exception $e) {

            return ['error' => 'Something went wrong'];
        }
    }

    public function getDashboardProofed(Request $request)
    {
        try {
            if (in_array(auth()->user()->role_id,[4, 5, 6, 7, 8])) {
                //non authorized roles
                return redirect('/tickets');
            }

            $organizations          = Organization::orderBy("is_vip", "desc")->get();

            $query2 = DB::table("tickets")->join("users", "tickets.personnel", "=", "users.id")
                ->selectRaw("users.id as user_id,
                    users.first_name as first_name,
                    users.surname as surname,
                    tickets.id as ticket_id,
                    tickets.status_id as ticket_status_id,
                    tickets.org_id as ticket_org_id,
                    tickets.personnel as ticket_personnel_id,
                    tickets.proofed")
                ->get();

            foreach ($organizations as $organization) {

                $done_proofed_ticket_query_org      = $query2->where("ticket_status_id", 6)->where("proofed", true)->where("ticket_org_id", $organization->id); // done & proofed
                $done_unproofed_ticket_query_org    = $query2->where("ticket_status_id", 6)->where("proofed", false)->where("ticket_org_id", $organization->id); // done & unproofed
                // $invoiced_ticket_query_org          = $query2->where("ticket_status_id", 7)->where("ticket_org_id", $organization->id); // Invoiced
                // $closed_ticket_query_org            = $query2->where("ticket_status_id", 9)->where("ticket_org_id", $organization->id); // Closed

                $done_proofed_ticket_total      =  $done_proofed_ticket_query_org->count();
                $done_unproofed_ticket_total    =  $done_unproofed_ticket_query_org->count();
                // $invoiced_ticket_total          =  $invoiced_ticket_query_org->count();
                // $closed_ticket_total            =  $closed_ticket_query_org->count();

                $total_ticket = $done_proofed_ticket_total + $done_unproofed_ticket_total; //+ $invoiced_ticket_total + $closed_ticket_total;

                $done_proofed_ticket_personnel                  = $done_proofed_ticket_query_org->groupBy("ticket_personnel_id");
                $done_unproofed_ticket_personnel                = $done_unproofed_ticket_query_org->groupBy("ticket_personnel_id");
                // $invoiced_ticket_personnel                      = $invoiced_ticket_query_org->groupBy("ticket_personnel_id");
                // $closed_ticket_personnel                        = $closed_ticket_query_org->groupBy("ticket_personnel_id");

                $done_proofed_ticket_personnel_tmp              = [];
                $done_unproofed_ticket_personnel_tmp            = [];
                // $invoiced_ticket_personnel_tmp                  = [];
                // $closed_ticket_personnel_tmp                    = [];

                $this->push_personnel($done_proofed_ticket_personnel, $done_proofed_ticket_personnel_tmp);
                $this->push_personnel($done_unproofed_ticket_personnel, $done_unproofed_ticket_personnel_tmp);
                // $this->push_personnel($invoiced_ticket_personnel, $invoiced_ticket_personnel_tmp);
                // $this->push_personnel($closed_ticket_personnel, $closed_ticket_personnel_tmp);

                $organization->done_proofed_ticket_total        = $done_proofed_ticket_total;
                $organization->done_unproofed_ticket_total      = $done_unproofed_ticket_total;
                // $organization->invoiced_ticket_total            = $invoiced_ticket_total;
                // $organization->closed_ticket_total              = $closed_ticket_total;
                $organization->total_ticket                     = $total_ticket;

                $organization->done_proofed_personnel_info      = collect($done_proofed_ticket_personnel_tmp)->sortByDesc("count");
                $organization->done_unproofed_personnel_info    = collect($done_unproofed_ticket_personnel_tmp)->sortByDesc("count");

                // $organization->invoiced_personnel_info          = collect($invoiced_ticket_personnel_tmp)->sortByDesc("count");
                // $organization->closed_personnel_info            = collect($closed_ticket_personnel_tmp)->sortByDesc("count");
            }


            $organizations = $organizations->reject(function ($value, $key) {
                return $value->total_ticket == 0;
            });

            ///Auth user should be beginning of list
            $organizations          = $organizations->sortByDesc("total_ticket"); //sort by total ticket
            $data["organizations"]  = $organizations->all();

            if ($request->type == 1) {
                return response()->json($data);
            }
            return view('dashboard.proofedDashboard')->with($data);
        } catch (Exception $e) {

            return ['error' => 'Something went wrong'];
        }
    }

    public function push_organization($data, &$tmp)
    {

        foreach ($data as  $item) {

            array_push($tmp, [
                "count" => $item->count(),
                "organization" => [
                    "org_name" => $item->first()->organization_name,
                    "org_id" => $item->first()->organization_id,
                ],
                "ticket_id" => $item->count() == 1 ? $item->first()->ticket_id : null,
                "is_contracted" => $item->first()->is_contracted,
            ]);
        }
    }

    public function push_personnel($data, &$tmp)
    {

        foreach ($data as  $item) {

            array_push($tmp, [
                "count" => $item->count(),
                "personnel" => [
                    "name_surname" => $item->first()->first_name . " " . $item->first()->surname,
                    "id" => $item->first()->user_id //
                ],
                "ticket_id" => $item->count() == 1 ? $item->first()->ticket_id : null
            ]);
        }
    }

    public function getUsersForDashboard(Request $request){

        try {
            $users = User::selectRaw('id,CONCAT(first_name," ",surname) as text')
                ->where(function($query){
                    $query->where('org_id',7);
                    $query->orWhere('org_id', 3);
                    $query->orWhere('org_id', 8);
                })
                ->whereRaw('CONCAT(first_name," ",surname) like ?', '%' . $request->q . '%')
                ->orderByRaw('CONCAT(first_name," ",surname)')->get();
            return $users;
        } catch (Exception $e) {
            return ['error' => $e];
        }
    }

    public function getOrganizationsForDashboard(Request $request) {
        try {
            return Organization::query()->select(["id", "org_name as text"])->where("org_name", "LIKE", "%" . $request->q . "%")->orderBy("org_name")->get();
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to get organizations detail from dashboard page",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }
}
