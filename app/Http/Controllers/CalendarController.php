<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Category;
use App\Helpers\Helper;
use App\SummaryOrder;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class CalendarController extends Controller {

    public function index() {

        $isAdmin = auth()->user()->role_id == 1 || auth()->user()->role_id == 2;
        if($isAdmin) { // Freelencer ya da Personnel değil ise
            $users = User::select(['id', DB::raw("CONCAT(first_name,' ',surname)as text")])->whereIn('role_id', [1, 2, 3, 4, 7])->where("in_use", 1)->orderBy('text')->get();
        }
        else{
            $users = User::select(['id', DB::raw("CONCAT(first_name,' ',surname)as text")])->whereIn('id', [auth()->id()])->where("in_use", 1)->orderBy('text')->get();
        }
        $categories = Category::orderBy('sort')->get();

        // Creating new Summary Orders if it doesn't exist
        foreach ($users as $user) {
            $summaryOrder = SummaryOrder::where("user_id", $user->id)->first();
            if(is_null($summaryOrder)){
                $summaryOrder = new SummaryOrder();
                $summaryOrder->user_id = $user->id;
                $order = SummaryOrder::count() + 1;
                $summaryOrder->order = $order;
                $summaryOrder->save();
            }
        }

        $summaryUsers = SummaryOrder::leftJoin("users","summary_orders.user_id","users.id")->where("users.in_use",1)->where("users.id","!=",206)->orderBy("order", "ASC")->get();

        return view("calendar.calendar", [
            "categories" => $categories,
            "users" => $users,
            "summaryUsers" => $summaryUsers,
            "user_id" => null,
            "isAdmin" => $isAdmin,
            "search" => null,
            "calendar" => null
        ]);
    }

    public function indexUser(Request $request, $user_id) {

        $isAdmin = auth()->user()->role_id == 1 || auth()->user()->role_id == 2;
        if(!$isAdmin) { // Eğer freelencer ya da personnel ise normal calendar a yönlendiriyoruz
            return redirect("/calendar");
        }

        $users = User::select(['id', DB::raw("CONCAT(first_name,' ',surname)as text")])->whereIn('role_id', [1, 2, 3, 4, 7])->where("in_use", 1)->orderBy('text')->get();

        $categories = Category::orderBy('sort')->get();

        // dd($summaryUsers->toArray());
        // Creating new Summary Orders if it doesn't exist
        foreach ($users as $user) {
            $summaryOrder = SummaryOrder::where("user_id", $user->id)->first();
            if(is_null($summaryOrder)){
                $summaryOrder = new SummaryOrder();
                $summaryOrder->user_id = $user->id;
                $order = SummaryOrder::count() + 1;
                $summaryOrder->order = $order;
                $summaryOrder->save();
            }
        }

        $summaryUsers = SummaryOrder::orderBy("order", "ASC")->get();

        if($request->ticket == 1) {
            $search = $request->search;
            $calendar = Calendar::query()->where("id", $search)->first();

            if(!$calendar) {
                return redirect()->to("/calendar/" . $user_id);
            }

            return view("calendar.calendar", [
                "categories" => $categories,
                "users" => $users,
                "summaryUsers" => $summaryUsers,
                "user_id" => $user_id,
                "isAdmin" => $isAdmin,
                "search" => $search,
                "calendar" => $calendar
            ]);
        }
        else {
            return view("calendar.calendar", [
                "categories" => $categories,
                "users" => $users,
                "summaryUsers" => $summaryUsers,
                "user_id" => $user_id,
                "isAdmin" => $isAdmin,
                "search" => null,
                "calendar" => null
            ]);
        }
    }

    public function insertNewData(Request $request) {

        $insertData = [
            'subject' => $request->subject,
            'category' => $request->category,
            'organization_id' => $request->organization,
            'message' => $request->message,
            'answer' => $request->answer,
            'start' => $request->start,
            'end' => $request->end,
            'start1' => $request->start1,
            'end1' => $request->end1,
            'guid' => $request->guid,
            'status' => $request->status,
            'user_id' => $request->user_id,
            "ticket_id" => $request->ticket_id
        ];

        $invoice = Calendar::create($insertData);
        return response(\GuzzleHttp\json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
    }

    public function ticketToCalendar(Request $request) {
        $ticket = Ticket::where('id',$request->ticket_to_calender_id)->first();
        if($ticket){

            // eger role 1 ise yani admin ise bladeden gönderdiği user id yi alıyoruz, eğer normal kullanıcıysa başka
            // kullanıcıya eklenmesini engellemek için direk login olan user in id sini alıyoruz.

            if(Auth::user()->role_id==1){
                $userId = $request->calendar_user_id;
            } else {
                $userId = Auth::user()->id;
            }

            $insertData = [
                'subject' => $ticket->name,
                'category' => $ticket->category,
                'organization_id' => $ticket->org_id,
                'message' => $request->message,
                'answer' => $request->answer,
                'start' => $request->start,
                'end' => $request->end,
                'start1' => $request->start1,
                'end1' => $request->end1,
                'guid' => $request->guid,
                'status' => $request->status,
                'user_id' => $userId,
                "ticket_id" => $ticket->id
            ];

            Calendar::create($insertData);

            return response(\GuzzleHttp\json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
        } else {

            return response(\GuzzleHttp\json_encode(['status' => 'error']), 500)->header('Content-Type', 'application/json');
        }


    }

    public function show($id) {
        $data = Calendar::selectRaw('calendar.*,organizations.org_name')->where('calendar.id', $id)->leftJoin('organizations', 'calendar.organization_id', 'organizations.id')->first();
        $show =1;
        $startDate = Carbon::parse($data->start);
        $now = Carbon::now()->startOfDay();

        if($startDate->lessThan($now)){
            $show=0;
        }

        return response(\GuzzleHttp\json_encode(['status' => 'success', 'data' => $data,'show'=>$show]), 200)->header('Content-Type', 'application/json');
    }

    public function updateData(Request $request) {
        try {
            $insertData = [
                "subject" => $request->subject,
                "category" => $request->category,
                "organization_id" => $request->organization,
                "message" => $request->message,
                "answer" => $request->answer,
                "start" => $request->start,
                "end" => $request->end,
                "start1" => $request->start1,
                "end1" => $request->end1,
                "guid" => $request->guid,
                "status" => $request->status,
                "user_id" => $request->user_id,
                "ticket_id" => $request->ticket_id
            ];

            Calendar::query()->where("id", $request->dataid)->update($insertData);
            return response(\GuzzleHttp\json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to update a calendar!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function updateDate(Request $request) {
        $start = explode('.', $request->start);
        $end = explode('.', $request->end);
        $start1 = explode('.', $request->start1);
        $end1 = explode('.', $request->end1);
        $insertData = [
            'start' => $start[0],
            'end' => $end[0],
            'start1' => $start1[0],
            'end1' => $end1[0],
        ];
        Calendar::where('id', $request->id)->update($insertData);
        return response(\GuzzleHttp\json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
    }

    public function delete($id)
    {
        Calendar::where('id', $id)->delete();
        return response(\GuzzleHttp\json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
    }

    public function GUID()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public function kisalt($kelime, $str = 10)
    {
        if (strlen($kelime) > $str) {
            if (function_exists("mb_substr")) $kelime = mb_substr($kelime, 0, $str, "UTF-8") . '...';
            else $kelime = substr($kelime, 0, $str) . '...';
        }
        return $kelime;
    }

    public function getData($id, $start, $end, $org, $status, $timezone) {

        $resultCalendarData = [];
        $getCalendarData = Calendar::selectRaw('calendar.*,categories.color,categories.name as catname,organizations.org_name');
        if ($id > 0) {
            $getCalendarData = $getCalendarData->where('calendar.user_id', $id);
        }
        if ($org > 0) {
            $getCalendarData = $getCalendarData->where('calendar.organization_id', $org);
        }
        if ($status > 0) {
            $getCalendarData = $getCalendarData->where('calendar.status', $status);
        }
        $getCalendarData = $getCalendarData
            ->leftJoin('categories', 'calendar.category', 'categories.id')
            ->leftJoin('organizations', 'calendar.organization_id', 'organizations.id')
            ->whereBetween('calendar.start', [$start, $end])->get();


        foreach ($getCalendarData as $data) {
            // Event colors by statuses
            if ($data->status == 1) {
                $status = 'Open';
                $data->color = "#ff0000";
            } else if ($data->status == 2) {
                $status = 'In Progress';
                $data->color = "#ffbb3b";
            } else if ($data->status == 3) {
                $status = 'Done';
                $data->color = "#03bd9e";
            } else if ($data->status == 4) {
                $status = 'Delay';
                $data->color = "#ff0000";
            } else if ($data->status == 5) {
                $status = 'Absent';
                $data->color = "#9d9d9d";
            }

            if ($timezone == 1) {
                $start = $data->start1;
                $end = $data->end1;
            } else {
                $start = $data->start;
                $end = $data->end;
            }

            $subject = ($data->ticket_id) ? ("#" . $data->ticket_id . " <br> " . $data->subject) : ($data->subject);
            $title = $subject . '<br> <i class="si si-globe"></i> ' . $data->org_name .
                '<br><i class="si si-flag"></i> ' . $status . ' <br> <i class="si si-speech"></i> ' .
                $this->kisalt($data->message, 40);

            $datRow = [
                "id" => $data->guid,
                "body" => $data->id,
                "calendarId" => "" . $data->category . "",
                "title" => $title,
                "isAllday" => false,
                "start" => $start,
                "end" => $end,
                "category" => "time",
                "dueDateClass" => "",
                "color" => "#ffffff",
                "bgColor" => $data->color,
                "dragBgColor" => $data->color,
                "borderColor" => $data->color,
                "customStyle" => "",
                "isFocused" => false,
                "isPending" => false,
                "isVisible" => true,
                "isReadOnly" => false,
                "goingDuration" => 0,
                "comingDuration" => 0,
                "recurrenceRule" => "",
                "isPrivate" => false,
                "ticket_id" => $data->ticket_id
            ];
            array_push($resultCalendarData, $datRow);
        }
        echo json_encode($resultCalendarData);
    }

    public function copy($id, $side)
    {

        $data = Calendar::where('id', $id)->first()->toArray();

        $data['guid'] = $this->GUID();

        $start = new \DateTime($data['start']);
        $end = new \DateTime($data['end']);
        $start1 = new \DateTime($data['start1']);
        $end1 = new \DateTime($data['end1']);

        $taskHour = $end->diff($start)->format("%h");
        $taskMin = $end->diff($start)->format("%i");
        $taskTime = (int) $taskHour * 60 + (int) $taskMin;

        if ($side == 'left') {
            $start->modify('-1 day');
            $end->modify('-1 day');
            $start1->modify('-1 day');
            $end1->modify('-1 day');
        } else if ($side == "right") {
            $start->modify('+1 day');
            $end->modify('+1 day');
            $start1->modify('+1 day');
            $end1->modify('+1 day');
        } else if ($side == "up") {
            $start->modify("-" . $taskTime . " minutes");
            $end->modify("-" . $taskTime . " minutes");
            $start1->modify("-" . $taskTime . " minutes");
            $end1->modify("-" . $taskTime . " minutes");
        } else if ($side == "down") {
            $start->modify("+" . $taskTime . " minutes");
            $end->modify("+" . $taskTime . " minutes");
            $start1->modify("+" . $taskTime . " minutes");
            $end1->modify("+" . $taskTime . " minutes");
        }

        $startResult = $start->format('Y-m-d H:i');
        $startLastResult = explode(' ', $startResult);
        $data['start'] = $startLastResult[0] . 'T' . $startLastResult[1];

        $endResult = $end->format('Y-m-d H:i');
        $endLastResult = explode(' ', $endResult);
        $data['end'] = $endLastResult[0] . 'T' . $endLastResult[1];

        $startResult1 = $start1->format('Y-m-d H:i');
        $startLastResult1 = explode(' ', $startResult1);
        $data['start1'] = $startLastResult1[0] . 'T' . $startLastResult1[1];

        $endResult1 = $end1->format('Y-m-d H:i');
        $endLastResult1 = explode(' ', $endResult1);
        $data['end1'] = $endLastResult1[0] . 'T' . $endLastResult1[1];

        $data['answer'] = null;
        unset($data['id']);
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['deleted_at']);
        $invoice = Calendar::create($data);
        return response(\GuzzleHttp\json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
    }
}
