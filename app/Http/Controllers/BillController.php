<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Ticket;
use App\Organization;
use App\Exports\BillsExport;
use App\User;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;


class BillController extends Controller {

    public function index() {
        $organizations = Organization::all();
        return view('bills.bills', compact('organizations'));
    }

    public function getBills(Request $request) {
        try {
            if(auth()->user()->role_id == 1) {
                $tickets = Ticket::where("org_id", $request->org_id)->where("status_id", $request->status);
                if($request->status == 6) { // Eğer "Done" ise sadece "Proof" olanları getir.
                    $tickets = $tickets->where("proofed", true);
                }
                $tickets = $tickets->orderBy("close_date","desc");

                return DataTables::of($tickets)
                    ->addColumn("org_name", function($row) {
                        $organization = Organization::query()->find($row->org_id);
                        return $organization ? $organization->org_name : null;
                    })
                    ->editColumn("personnel", function ($row) {
                        $user = User::query()->find($row->personnel);
                        return $user ? $user->first_name . " " . $user->surname : null;
                    })
                    ->editColumn("subject", function ($row) {
                        return $row->name;
                    })->editColumn("transport", function ($row) {
                        return $row->transport_price;
                    })
                    ->editColumn("spent_time", function ($row) {
                        $calculated = $this->getTotalSpentTime($row->id);
                        return sprintf("%02d:%02d", $calculated["hours"], $calculated["minutes"]);
                    })
                    ->editColumn("done", function ($row) {
                        return $row->close_date ?: null;
                    })
                    ->filterColumn("personnel", function($query, $input) {
                        $user = User::query()->whereRaw("CONCAT(first_name, ' ', surname) LIKE '%" . $input . "%'")->pluck("id");
                        return $query->whereIn("personnel", $user);
                    })
                    ->filterColumn("subject", function($query, $input) {
                        return $query->where("name", "LIKE", "%" . $input . "%");
                    })
                    ->filterColumn("category", function($query, $input) {
                        $category = Category::query()->where("name", "LIKE", "%" . $input . "%")->select("id")->pluck("id");
                        return $query->whereIn("category", $category);
                    })
                    ->rawColumns(["it_support","tick_all"])
                    ->make(true);
            }
        }
        catch(Exception $e) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong on Bill Controller! Check 'DataTables' section.",
                9,
                $e->getMessage()
            );
        }
    }

    function getTotalSpentTime($ticket_id) { // Calculate total effort of one ticket.
        $total_minutes = Helper::getTotalEffortAsMinute($ticket_id);
        $hours = intval($total_minutes / 60);
        $minutes = $total_minutes % 60;
        return ["minutes" => $minutes, "hours" => $hours];
    }

    public function exportAll(Request $request) {
        try {
            $ticket_ids = $request->data;
            return Excel::download(new BillsExport($ticket_ids), "bills.xlsx");
        }
        catch(Exception $e) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong on exporting Bill!",
                9,
                $e->getMessage() . ". Line: " . $e->getLine()
            );
        }
    }

    public function updateInvoiced(Request $request) {
        try {
            $ticket_ids = $request->data;

            if($ticket_ids) {
                foreach($ticket_ids as $ticket_id) {
                    Helper::update_ticket_status($ticket_id,7); // set invoiced
                }
                return response()->json(["success"=>1]);
            }
            else {
                return response()->json(["success"=>0]);
            }
        }
        catch(\Exception $e) {
            return response()->json(["success"=>0]);
        }
    }

    public function updateClosed(Request $request){

        try {
            $ticket_ids = $request->data;

            if($ticket_ids) {
                foreach($ticket_ids as $ticket_id) {
                    Helper::update_ticket_status($ticket_id,9); // set closed
                }
                return response()->json(["success"=>1]);
            }
            else {
                return response()->json(["success"=>0]);
            }
        }
        catch(\Exception $e){
            return response()->json(["success"=>0]);
        }
    }
}
