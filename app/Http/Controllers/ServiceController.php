<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Service;
use App\Helpers\Helper;
use App\ServiceAttachment;
use App\ServiceTypes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller {
    public function index() {
        return view("services.index", ["service_types" => ServiceTypes::all()]);
    }

    public function create() {
        return view("services.create", [
            "service_types" => ServiceTypes::all(),
            "organizations" => Organization::all()
        ]);
    }

    public function store(Request $request) {
        try {
            $service = new Service();
            $service->title = $request->input("title");
            $service->description = $request->input("description");
            $service->organization_id = $request->input("organization");
            $service->provider = $request->input("provider");
            $service->service_type = $request->input("service-type");
            $service->service_amount = Helper::price_format_to_db($request->input("service-amount"));
            $service->currency = $request->input("currency");
            $service->beginning_date = $request->input("beginning-date");
            $service->expiring_date = $request->input("expiring-date");
            $service->access_link = $request->input("access-link");
            $service->added_by = auth()->id();
            $service->updated_by = auth()->id();
            $service->save();

            if($request->input("service-attachments")) {
                foreach($request->input("service-attachments") as $key => $value) {
                    $file = new ServiceAttachment();
                    $file->service_id = $service->id;
                    $file->file_name = $value;
                    $file->size = $key;
                    $file->added_by = Auth::id();
                    $file->save();
                }
            }

            return redirect("/services");
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to create a service!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return response()->json([
                "success" => $exception
            ]);
        }
    }

    public function edit(Service $service) {
        return view("services.edit", [
            "service_types" => ServiceTypes::all(),
            "organizations" => Organization::all(),
            "service" => $service,
            "service_attachments" => ServiceAttachment::query()->where("service_id", $service->id)->get()
        ]);
    }

    public function update(Request $request, Service $service) {
        try {
            $service->title = $request->input("title");
            $service->organization_id = $request->input("organization");
            $service->provider = $request->input("provider");
            $service->service_type = $request->input("service-type");
            $service->service_amount = Helper::price_format_to_db($request->input("service-amount"));
            $service->currency = $request->input("currency");
            $service->beginning_date = $request->input("beginning-date");
            $service->expiring_date = $request->input("expiring-date");
            $service->description = $request->input("description");
            $service->access_link = $request->input("access-link");
            $service->updated_by = auth()->id();
            $service->save();

            return redirect("/services/" . $service->id);
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to update a service!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return response()->json([
                "success" => $exception
            ]);
        }
    }

    public function delete(Service $service) {
        try {
            ServiceAttachment::query()->where("service_id", $service->id)->delete();
            $service->delete();
            return response()->json(["success" => 1]);
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to delete an service!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return response()->json(["success" => 0]);
        }
    }


    public function list() {
        try {
            $services = Service::query()->get();

            return DataTables::of($services)
                ->editColumn("organization", function($row) {
                    return Organization::query()->find($row->organization_id)->org_name;
                })
                ->editColumn("service_type", function($row) {
                    $service_type = ServiceTypes::query()->find($row->service_type);
                    return $service_type->name;
                })
                ->editColumn("service_amount", function($row) {
                    return number_format($row->service_amount, "2", ",", ".");
                })
                ->editColumn("beginning_date", function($row) {
                    return Carbon::parse($row->beginning_date)->format("d.m.Y");
                })
                ->editColumn("expiring_date", function($row) {
                    return Carbon::parse($row->expiring_date)->format("d.m.Y");
                })
                ->addColumn("actions",function($row) {
                    return $row->id;
                })
                ->rawColumns(["actions"])
                ->make(true);
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to list services!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function deleteAttachment($attachment_id) {
        try {
            ServiceAttachment::query()->find($attachment_id)->delete();
            return response()->json(["success" => 1]);
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to delete an service attachment!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return response()->json(["success" => 0]);
        }
    }
}