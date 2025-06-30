<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Organization;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VipOrganizationsController extends Controller {

    public function index() {
        return view("organizations.VIP.update-vip-org");
    }

    public function setVipOrganization(Request $request) {
        $vip_count = Organization::where('is_vip', 1)->count();
        $org = Organization::where('id', $request->organization_id)->first();
            if($org->is_vip == 1) {
                return response()->json(['status' => 2]);
            }

        if($vip_count < 20) {
            $org->is_vip = 1;
            $org->save();
            return response()->json(['status' => 1]);
        }

        return response()->json(['status' => 0]);
    }

    public function getVipOrganizations() {
        try {
            $vip_org = Organization::where('is_active', 1)->where('is_vip', 1)->get();
            return DataTables::of($vip_org)
                ->addColumn('actions', function($row) {
                    return $row->id;
                })
                ->make(true);
        }
        catch(\Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to retrieve VIP Organization's data!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function removeVipOrganization($id) {
        $org = Organization::where('id', $id)->first();
        $org->is_vip = 0;
        $org->save();

        return redirect()->route("vip.organizations");
    }
}
