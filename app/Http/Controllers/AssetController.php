<?php

namespace App\Http\Controllers;

use App\Asset;
use App\AssetType;
use App\Company;
use App\Organization;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AssetController extends Controller
{
    // Resource Functions
    public function index($company_name)
    {
        $company = Company::where("route_name", $company_name)->first();
        if($company){
            return view("asset-management.assets", compact("company"));
        }else{
            return redirect()->back();
        }
    }

    public function create($company_name)
    {
        $company = Company::where("route_name", $company_name)->first();
        if($company){

            $organizations = Organization::all();
            $asset_types = AssetType::all();
            $companies = $company->sub_companies;
            return view(
                "asset-management.asset-add",
                compact("company", "organizations", "asset_types", "companies")
            );
        }else{
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $data = $request->except("save_close", "order_by_us", "partner_pdf");
        $asset = new Asset();
        $asset->fill($data);

        $asset->order_by_us = $request->order_by_us == "on";
        if ($request->hasFile("partner_pdf")) {
            $asset->partner_pdf_path = $this->uploadFile($request->file("partner_pdf"));
            $asset->partner_pdf_name = $request->file("partner_pdf")->getClientOriginalName();;
        }

        $asset->save();

        $company = Company::find($request->owner_company_id);
        if ($request->save_close == 1) {
            return redirect("/assets/" . $company->route_name);
        }
        return redirect()->route("assets.edit", [
            "asset" => $asset->id,
            "company_name" => $company->route_name
        ]);
    }

    public function edit(Asset $asset, $company_name)
    {
        $company = Company::where("route_name", $company_name)->first();
        if($company){

            $organizations = Organization::all();
            $asset_types = AssetType::all();
            $companies = $company->sub_companies;
            return view(
                "asset-management.asset-edit",
                compact("asset", "company", "organizations", "asset_types", "companies")
            );
        }else{
            return redirect()->back();
        }
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $request->except("save_close", "order_by_us", "partner_pdf");
        $asset->fill($data);

        $asset->order_by_us = $request->order_by_us == "on";
        if (!$asset->order_by_us) {
            $asset->sale_date = null;
            $asset->invoice_no = null;
            $asset->pos_no = null;
            $asset->company_id = null;
            $asset->warranty = null;
        }
        if ($request->hasFile("partner_pdf")) {
            $asset->partner_pdf_path = $this->uploadFile($request->file("partner_pdf"));
            $asset->partner_pdf_name = $request->file("partner_pdf")->getClientOriginalName();;
        }

        $asset->update();
        $company = Company::find($request->owner_company_id);
        if ($request->save_close == 1) {
            return redirect("/assets/" . $company->route_name);
        }
        return redirect()->route("assets.edit", [
            "asset" => $asset->id,
            "company_name" => $company->route_name
        ]);
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return response()->json("success");
    }
    // Resource Function END


    // Uploads a single file and returns file path
    public function uploadFile($file)
    {
        $fileOriginalName = $file->getClientOriginalName();
        $fileOriginalName = (str_replace(['Ğ', 'ğ', 'Ş', 'ş', 'Ü', 'ü', 'Ö', 'ö', ' ', '?', 'ä', 'å', '%', '/', '!', '.'], ['g', 'g', 's', 's', 'u', 'u', 'o', 'o', '-', '-', 'a', 'a', '-', '-', '-', '-'], $fileOriginalName));
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize();
        $fileNameToStore = $fileOriginalName . '_' . time() . '.' . $extension;
        $filename = $file->storeAs('uploads/', $fileNameToStore);
        $file_path = "/uploads/" . $fileNameToStore;
        return $file_path;
    }

    // List for Datatable
    public function list($company_name)
    {
        try {
            $company = Company::where("route_name", $company_name)->first();
            $assets = Asset::where("owner_company_id", $company->id)->orderBy("id", "desc")->get();
            return DataTables::of($assets)
                ->editColumn("organization", function ($row) {
                    return $row->organization->org_name;
                })
                ->editColumn("asset", function ($row) {
                    return $row->asset_type->name;
                })
                ->editColumn("order_by_us", function($row){
                    return $row->order_by_us == 1 ? "yes": "no";
                })
                ->editColumn("partner_pdf", function ($row) {
                    if ($row->partner_pdf_name) {
                        $path = asset($row->partner_pdf_path);
                        $name = $row->partner_pdf_name;
                        return ["name" => $name, "path" => $path];
                    }
                    return null;
                })
                ->editColumn("created_at", function ($row) {
                    $add_date = Carbon::parse($row->created_at)->format("d.m.Y");
                    return $add_date;
                })
                ->addColumn("action", function ($row) {
                    return $row->id;
                })
                ->rawColumns(["action"])
                ->make(true);
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    // Delete Pdf Files
    public function deleteFile(Asset $asset, $type)
    {
        $asset->partner_pdf_name = null;
        $asset->partner_pdf_path = null;
        $asset->update();
        return response()->json("success");
    }
}
