<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\Exports\TicketExport;
use App\Exports\TicketsExport;
use App\Helpers\Helper;
use App\Organization;
use App\ReportExportLog;
use App\Status;
use App\Ticket;
use App\TicketAttachment;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller {

    public function index() {
        try {
            $data["statuses"] = Status::query()->where("main", 1)->get();
            $data["logs"] = ReportExportLog::query()->get()->sortByDesc("created_at");
            return view("reports.reports")->with($data);
        }
        catch (Exception $e) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong on indexing 'Reports' Page!",
                9,
                $e->getMessage()
            );
        }
    }

    public function getExcelSummary($request) {

        $type = $request->reportType;
        if($type==1) {
            $requestId = $request->organization;
            $whereColumn = 'org_id';
        }
        else {
            $requestId = $request->freelancer;
            $whereColumn = 'personnel';
        }

        $tickets = Ticket::all();

        if($request->status=="proofed") { // Done and Proofed
            if($request->created_at == "on") {
                if($request->it_category == 0) {
                    $ticket_ids = $tickets->where($whereColumn, $requestId)->where("category", "!=", 14)->where("status_id", 6)->where("proofed", 1)->whereBetween("created_at", [$request->start_date, $request->end_date . " 23:59:59"])->pluck("id")->toArray();
                }
                else {
                    $ticket_ids = $tickets->where($whereColumn, $requestId)->where("category",14)->where("status_id", 6)->where("proofed", 1)->whereBetween("created_at", [$request->start_date, $request->end_date . " 23:59:59"])->pluck("id")->toArray();
                }
            }
            elseif($request->done_date == "on"){
                if($request->it_category == 0) {
                    $ticket_ids = $tickets->where($whereColumn, $requestId)->where("category", "!=", 14)->where("status_id", 6)->where("proofed", 1)->whereBetween("close_date", [$request->start_date, $request->end_date . " 23:59:59"])->pluck("id")->toArray();
                }
                else {
                    $ticket_ids = $tickets->where($whereColumn, $requestId)->where("category", 14)->where("status_id", 6)->where("proofed", 1)->whereBetween("close_date", [$request->start_date, $request->end_date . " 23:59:59"])->pluck("id")->toArray();
                }
            }
        }

        elseif($request->status == 6) { // "Proofed" olup olmadığı fark etmeksizin "Done" Ticket'ların mantığını burada kuruyoruz.

            if($request->created_at == "on") {
                if($request->it_category == 0) {
                    $ticket_ids = $tickets->where($whereColumn, $requestId)->where("category", "!=", 14)->where("status_id", 6)->whereBetween("created_at", [$request->start_date, $request->end_date . " 23:59:59"])->pluck("id")->toArray();
                }
                else {
                    $ticket_ids = $tickets->where($whereColumn, $requestId)->where("category", 14)->where("status_id", 6)->whereBetween("created_at", [$request->start_date, $request->end_date . " 23:59:59"])->pluck("id")->toArray();
                }
            }
            elseif($request->done_date == "on") {
                if($request->it_category == 0) {
                    $ticket_ids = $tickets->where($whereColumn, $requestId)->where("category", "!=", 14)->where("status_id", 6)->whereBetween("close_date", [$request->start_date, $request->end_date . " 23:59:59"])->pluck("id")->toArray();
                }
                else {
                    $ticket_ids = $tickets->where($whereColumn, $requestId)->where("category", 14)->where("status_id", 6)->whereBetween("close_date", [$request->start_date, $request->end_date . " 23:59:59"])->pluck("id")->toArray();
                }
            }
        }
        else {
            if($request->it_category == 0) {
                $ticket_ids = $tickets->where($whereColumn, $requestId)->where("category", "!=", 14)->where("status_id", $request->status)->whereBetween("created_at", [$request->start_date, $request->end_date])->pluck("id")->toArray();
            }
            else {
                $ticket_ids = $tickets->where($whereColumn, $requestId)->where("category", 14)->where("status_id", $request->status)->whereBetween("created_at", [$request->start_date, $request->end_date])->pluck("id")->toArray();
            }
        }

        $tmpFilename = md5(time().rand(0,1000000)).".xlsx";

        (new TicketExport($ticket_ids) )->store( "tempfiles/".$tmpFilename,null,\Maatwebsite\Excel\Excel::XLSX);

        if($type == 1) {
            try {
                Storage::copy("tempfiles/" . $tmpFilename, "uploads/" . $tmpFilename);

                $status = Status::query()->find($request->status);
                if(!$status && $request->status == "proofed"){
                    $status = Status::query()->find(6);
                    $status->name = "Done & Proofed";
                }

                $log = new ReportExportLog();
                $log->organization_id = $request->organization;
                $log->status_name = $status->name;
                $log->is_proofed = $request->status == "proofed" ? 1 : 0;
                $log->user_id = Auth::id();
                $log->starting_date = $request->start_date;
                $log->ending_date = $request->end_date;
                $log->date_type = $request->done_date == "on" ? "Done Date" : "Creation Date";
                $log->file_type = "Excel";
                $log->file_name = $tmpFilename;
                $log->it_category_type = $request->it_category == 0 ? "Without" : "Only";
                $log->save();
            }
            catch(Exception $exception) {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Something went wrong while trying to save a log for tickets' excel report!",
                    9,
                    $exception->getMessage() . " Line:" . $exception->getLine()
                );
            }
        }

        return response()->json(["filename"=>"tickets.xlsx","tempfile"=>$tmpFilename]);
    }

    public function getExcelSummaryAll() {
        return Excel::download(new TicketsExport(), "ticketsAll.xlsx");
    }

    public function getReportSummary(Request $request,$id) {
        try {
            $type = $request->reportType;

            if($id != "all") {
                $request->file_type = "pdf";
            }

            if($request->file_type == "pdf") {
                set_time_limit(600);

                if($id == "all") {
                    if($type == 1) {
                        $dataDetails = Organization::query()->find($request->organization);
                        $whereColumn = "org_id";
                    }
                    else {
                        $dataDetails = User::query()->find($request->freelancer);
                        $whereColumn = "personnel";
                    }

                    if($request->status == "proofed") {
                        if($request->created_at == "on") {
                            if($request->it_category == 0) {
                                $data["tickets"] = Ticket::query()->where("category", '!=', 14)->where($whereColumn, $dataDetails->id)->where("status_id", 6)->where("proofed", 1)->whereBetween("created_at", [$request->start_date, $request->end_date . " 23:59:59"])->get();
                            }
                            else {
                                $data["tickets"] = Ticket::query()->where("category", 14)->where($whereColumn, $dataDetails->id)->where("status_id", 6)->where("proofed", 1)->whereBetween("created_at", [$request->start_date, $request->end_date . " 23:59:59"])->get();
                            }
                        }
                        elseif($request->done_date == "on") {
                            if($request->it_category == 0) {
                                $data["tickets"] = Ticket::query()->where("category", "!=", 14)->where($whereColumn, $dataDetails->id)->where("status_id", 6)->where("proofed", 1)->whereBetween("close_date", [$request->start_date, $request->end_date . " 23:59:59"])->get();
                            }
                            else {
                                $data["tickets"] = Ticket::query()->where("category", 14)->where($whereColumn, $dataDetails->id)->where("status_id", 6)->where("proofed", 1)->whereBetween("close_date", [$request->start_date, $request->end_date . " 23:59:59"])->get();
                            }
                        }
                    }
                    elseif($request->status == 6) {
                        if($request->created_at == "on") {
                            if($request->it_category == 0) {
                                $data["tickets"] = Ticket::query()->where("category", "!=", 14)->where($whereColumn, $dataDetails->id)->where("status_id", 6)->whereBetween("created_at", [$request->start_date, $request->end_date . " 23:59:59"])->get();
                            }
                            else {
                                $data["tickets"] = Ticket::query()->where("category", 14)->where($whereColumn, $dataDetails->id)->where("status_id", 6)->whereBetween("created_at", [$request->start_date, $request->end_date . " 23:59:59"])->get();
                            }
                        }
                        elseif($request->done_date = "on") {
                            if($request->it_category == 0) {
                                $data["tickets"] = Ticket::query()->where("category", "!=", 14)->where($whereColumn, $dataDetails->id)->where("status_id", 6)->whereBetween("close_date", [$request->start_date, $request->end_date . " 23:59:59"])->get();
                            }
                            else {
                                $data["tickets"] = Ticket::query()->where("category", 14)->where($whereColumn, $dataDetails->id)->where("status_id", 6)->whereBetween("close_date", [$request->start_date, $request->end_date . " 23:59:59"])->get();
                            }
                        }
                    }
                    else {
                        if($request->it_category == 0) {
                            $data["tickets"] = Ticket::query()->where("category", "!=", 14)->where($whereColumn, $dataDetails->id)->where("status_id", $request->status)->whereBetween("created_at", [$request->start_date, $request->end_date . " 23:59:59"])->get();
                        }
                        else {
                            $data["tickets"] = Ticket::query()->where("category", 14)->where($whereColumn, $dataDetails->id)->where("status_id", $request->status)->whereBetween("created_at", [$request->start_date, $request->end_date . " 23:59:59"])->get();
                        }
                    }
                }
                else {
                    $data["tickets"] = Ticket::query()->where("id", $id)->get();
                    $organization = Organization::query()->find($data["tickets"][0]->org_id);
                    $request->status = $data["tickets"][0]->status_id;
                }

                $all_tickets_total_spent_minutes = 0;
                $all_tickets_good_will_minutes = 0;

                foreach($data["tickets"] as $ticket) {
                    $discussions = Discussion::query()->where("ticket_id", $ticket->id)->where("is_private", 0)->latest()->get();

                    foreach($discussions as $discussion) {
                        $user = User::query()->find($discussion->user_id);
                        $discussion->user = $user ?? "";
                        $content = preg_replace('/(<)([img])(\w+)([^>]*>)/', '$1', $discussion->message);
                        $discussion->message = strip_tags($content, ["br", "p", "b", "i"]);
                    }

                    $ticket->discussions = $discussions;
                    $attachments = TicketAttachment::query()->where("ticket_id", $ticket->id)->where("private", 0)->where("is_mail", 0)->get();
                    $ticket->attachments = $attachments;
                    $times_goodwill = Helper::getDiscountedEffortsAsMinute($ticket->id);
                    $total_spent_minutes = Helper::getTotalEffortAsMinute($ticket->id);
                    $all_tickets_good_will_minutes += $times_goodwill;
                    $all_tickets_total_spent_minutes += $total_spent_minutes;
                    $discount = $total_spent_minutes != 0 ? (($total_spent_minutes - $times_goodwill) / $total_spent_minutes) * 100 : 0;
                    $ticket->discount = intval(round($discount));
                    $ticket->good_will_time = Helper::convert_minute_to_clock($times_goodwill);
                    $ticket->total_spent_time = Helper::convert_minute_to_clock($total_spent_minutes);
                }

                $status = Status::query()->find($request->status);

                if(!$status && $request->status == "proofed") {
                    $status = Status::query()->find(6);
                    $status->name = "Done & Proofed";
                }

                if($type <= 1) {
                    if($id == "all") {
                        $organization = $dataDetails;
                    }

                    if($organization->personnel_org == 3) {
                        $organization->customer_no_text = "Kunden Nr";
                        $organization->arranger = "Bearbeiter";
                        $organization->date = "Datum";
                        $organization->period = "Zeitraum";

                        if($id == "all") {
                            $organization->title = "Zusammenfassung der " . $status->name . " Tickets";
                        }
                        else {
                            $organization->title = "Zusammenfassung der Ticket ID #" . $data["tickets"][0]->id;
                        }
                    }
                    elseif($organization->personnel_org == 8) {
                        $organization->customer_no_text = "Customer ID";
                        $organization->arranger = "Editor";
                        $organization->date = "Date";
                        $organization->period = "Tickets Reporting";

                        if($id == "all") {
                            $organization->title = "Summary of " . $status->name . " Tickets";
                        }
                        else {
                            $organization->title = "Summary of Ticket with Ticket ID #" . $data["tickets"][0]->id;
                        }
                    }

                    $data["data"] = $organization;
                    $data["title"] = 'Organization';
                    $data["type"] = $type;
                    $data["total_spent_time"] = Helper::convert_minute_to_clock($all_tickets_total_spent_minutes);
                    $data["total_good_will_time"] = Helper::convert_minute_to_clock($all_tickets_good_will_minutes);
                    $data["id"] = $id;
                    $data["time_range"] = ["start_date" => Carbon::parse($request->start_date)->format("d.m.Y"), "end_date" => Carbon::parse($request->end_date)->format("d.m.Y")];
                    $pdf = App::make("dompdf.wrapper");
                    $pdf->loadView("pdftemplates.reports.summary-report", $data);

                    if($id == "all") {
                        $pdfTempFileName = md5(time() . rand(0,1000000)) . ".pdf";
                        $pdf->save(storage_path("app/tempfiles/") . $pdfTempFileName);
                        Storage::copy("tempfiles/" . $pdfTempFileName, "uploads/" . $pdfTempFileName);
                        $returnFileName = $organization->org_name . ".pdf";
                        ob_clean();
                        ob_end_clean();
                        $log = new ReportExportLog();
                        $log->organization_id = $organization->id;
                        $log->status_name = $status->name;
                        $log->is_proofed = $request->status == "proofed" ? 1 : 0;
                        $log->user_id = Auth::id();
                        $log->starting_date = $request->start_date;
                        $log->ending_date = $request->end_date;
                        $log->date_type = $request->done_date == "on" ? "Done Date" : "Creation Date";
                        $log->file_type = "PDF";
                        $log->file_name = $pdfTempFileName;
                        $log->it_category_type = $request->it_category == 0 ? "Without" : "Only";
                        $log->save();

                        return response()->json([
                            "filename" => $returnFileName,
                            "tempfile" => $pdfTempFileName
                        ]);
                    }
                    else {
                        $pdfTempFileName = md5(time() . rand(0,1000000)) . ".pdf";
                        $pdfPath = storage_path("app/tempfiles/" . $pdfTempFileName);
                        $pdf->save($pdfPath);
                        $returnFileName = $id . " Ticket Report.pdf";

                        if($request->ajax()) {
                            ob_clean();
                            ob_end_clean();
                            return response()->json([
                                "filename" => $returnFileName,
                                "tempfile" => $pdfTempFileName
                            ]);
                        }
                        else {
                            return response()->file($pdfPath, [
                                "Content-Type" => "application/pdf",
                                "Content-Disposition" => "attachment;filename=$returnFileName"
                            ]);
                        }
                    }
                }
                else {
                    $user = $dataDetails;
                    $user->customer_no_text = "Freelancer";
                    $user->arranger = "Bearbeiter";
                    $user->date = "Datum";
                    $user->title = "Zusammenfassung der ".$status->name." Tickets";
                    $user->period = "Zeitraum";
                    $user->personnel_org = $user->org_id;
                    $data["data"] = $user;
                    $data["title"] = 'Freelancer';
                    $data["type"] = $type;
                    $data["total_spent_time"] = Helper::convert_minute_to_clock($all_tickets_total_spent_minutes);
                    $data["total_good_will_time"] = Helper::convert_minute_to_clock($all_tickets_good_will_minutes);
                    $data["id"] = $id;
                    $data["time_range"] = [
                        "start_date" => Carbon::parse($request->start_date)->format("d.m.Y"),
                        "end_date" => Carbon::parse($request->end_date)->format("d.m.Y")
                    ];
                    $pdf = App::make("dompdf.wrapper");
                    $pdf->loadView("pdftemplates.reports.summary-report", $data);

                    return $pdf->download($user->first_name.' '.$user->surname. ".pdf");
                }
            }
            elseif($request->file_type == "excel") {
                return $this->getExcelSummary($request);
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to export a report!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function get_tickets_raw(Request $request){
        $tickets = Ticket::select(["id",'name as text'])->where('id', 'like', '%' . $request->q . '%')->get();

        return $tickets;
    }

    public function getFileName($id) {
        $ticket = Ticket::query()->find($id);
        $organization = Organization::query()->find($ticket->org_id);
        $orgName = $organization->org_name;
        $reportDate = now()->format("Y-m-d");
        $returnFileName = "Ticket Report " . $orgName . " " . $reportDate . ".pdf";

        return response()->json([
            "file_name" => $returnFileName
        ]);
    }
}