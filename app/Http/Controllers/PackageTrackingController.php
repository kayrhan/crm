<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Organization;
use App\PackageTracking;
use App\PacketTrackingStatus;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PackageTrackingController extends Controller {
    public function index() {
        $trackings = PackageTracking::all();
        return view("package-tracking.index", ["trackings" => $trackings]);
    }

    public function addPackage() {
        $statusses = PacketTrackingStatus::all();
        return view("package-tracking.add-package", ["statusses" => $statusses]);
    }

    public function addPackagePost(Request $request) {
        $request->validate([
            "shop" => "required|max:255",
            "order_date" => "required",
            "status" => "required",
            "ticket_id" => "nullable|exists:tickets,id"
        ]);

        $package = new PackageTracking;
        $package->shop = $request->shop;
        $package->order_date = $request->order_date;
        $package->expected_delivery_date = $request->expected_delivery_date;
        $package->cargo_company = $request->cargo_company;
        $package->status = $request->status;
        $package->ticket_id = $request->ticket_id;
        $package->user_id = auth()->id();
        $package->updated_user_id = auth()->id();
        $url = $request->tracking_id;

        if(!empty($url)) {
            if(str_starts_with($url, "http://") or str_starts_with($url, "https://")) {
                $package->tracking_id = $url;
            }
            else {
                $package->tracking_id = "https://" . $url;
            }
        }

        $package->express = $request->express;
        $package->description = $request->description;
        $package->save();

        return redirect()->route("package-tracking.index");
    }

    public function updatePackage($id) {
        $statusses = PacketTrackingStatus::all();
        $track = PackageTracking::query()->find($id);
        $user = User::query()->find($track->user_id);
        $updated_user = User::query()->find($track->updated_user_id);
        $ticket_name = "";
        $organization = null;

        if($track->ticket_id) {
            $ticket = Ticket::query()->find($track->ticket_id);
            $organization = Organization::query()->find($ticket->org_id)->org_name;
            $ticket_name = ($ticket) ? $ticket->name : "";
        }

        return view("package-tracking.update-package", [
            "statusses" => $statusses,
            "track" => $track,
            "user" => $user,
            "updated_user" => $updated_user,
            "ticket_name" => $ticket_name,
            "organization" => $organization
        ]);
    }

    public function updatePackagePost(Request $request) {
        $request->validate([
            "shop" => "required",
            "order_date" => "required",
            "status" => "required",
            "ticket_id" => "nullable|exists:tickets,id",
        ]);

        try {
            $package = PackageTracking::query()->find($request->track_id);
            $package->shop = $request->shop;
            $package->order_date = $request->order_date;
            $package->expected_delivery_date = $request->expected_delivery_date;
            $package->cargo_company = $request->cargo_company;
            $package->status = $request->status;
            $package->tracking_id = $request->tracking_id;
            $package->express = $request->express;
            $package->ticket_id = $request->ticket_id;
            $package->description = $request->description;
            $package->updated_user_id = auth()->id();
            $package->save();

            return redirect()->route("package-tracking.index");
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to update a package's tracking!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return false;
        }
    }

    public function deletePackage($id) {
        $tracking = PackageTracking::query()->find($id);
        $tracking->delete();

        return ["status" => 1];
    }

    public function list() {
        try {
            $trackings = PackageTracking::query()->get();

            return DataTables::of($trackings)
                ->editColumn("description", function($row) {
                    return strip_tags($row->description);
                })
                ->editColumn("order_date", function($row) {
                    return Carbon::parse($row->order_date)->format("d.m.Y");
                })
                ->editColumn("expected_delivery_date", function($row) {
                    if($row->expected_delivery_date) {
                        $old = 0;
                        $today = new DateTime(Carbon::now()->format("Y-m-d"));
                        $expected_date = new DateTime($row->expected_delivery_date);

                        if($today > $expected_date && $row->status != 3) {
                            $old = 1; // Beklenen Tarihte Gelmemiş Kargo
                        }

                        return [Carbon::parse($row->expected_delivery_date)->format("d.m.Y"), $old];
                    }

                    return null;
                })
                ->editColumn("cargo_company", function($row) {
                    return $row->cargo_company;
                })
                ->editColumn("tracking_id", function($row) {
                    if($row->tracking_id) {
                        $url = parse_url($row->tracking_id);
                        return [$url["host"], $row->tracking_id];
                    }

                    return null;
                })
                ->editColumn("ticket_id", function ($row) {
                    if($row->ticket_id) {
                        $url = url("/update-ticket/" . $row->ticket_id);
                        return [$url, $row->ticket_id];
                    }

                    return null;
                })
                ->editColumn("user_id", function($row) {
                    $user = User::query()->find($row->user_id);
                    return $user->first_name . " " . $user->surname;
                })
                ->editColumn("express", function($row) {
                    return $row->express == 1 ? "Yes" : "No";
                })
                ->editColumn("organization", function($row) {
                    if($row->ticket_id) {
                        $ticket = Ticket::query()->find($row->ticket_id);

                        if($ticket) {
                            return Organization::query()->find($ticket->org_id)->org_name;
                        }
                    }

                    return null;
                })
                ->addColumn("actions", function($row) {
                    return '<div  style="display:flex;justify-content:center;"><a class="btn btn-sm btn-info small mr-1" href="/update-package/' . $row->id . '"  ><i class="fa fa-edit "></i></a><a class="btn btn-sm btn-danger deletePackage small" data-package-id="' . $row->id . '"><i class="fa fa-trash"></i></a></div>';
                })
                ->rawColumns(["actions", "description"])
                ->make();
        }
        catch (Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to list packages' trackings!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return false;
        }
    }
}
