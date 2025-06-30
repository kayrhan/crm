<?php

namespace App\Http\Controllers;

use App\Organization;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FreelancerController extends Controller
{
    public function index(){
        return view("freelancer.freelancers");
    }

    public function list(){
         try {

            $vip_org = User::where("in_use",1)->where("role_id",7)->get();
            return DataTables::of($vip_org)
                ->editColumn("org_id",function ($row){
                    return Organization::where("id",$row->org_id)->first()->org_name;
                })
                ->addColumn("actions",function ($row){

                    return "<a class='btn btn-sm btn-primary' target='_blank' href='/update-user/".$row->id."'><i class='fa fa-eye text-white'></i><a/>";
                })
                ->rawColumns(["actions"])
                ->make(true);

        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
}
