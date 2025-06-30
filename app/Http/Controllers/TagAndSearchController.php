<?php

namespace App\Http\Controllers;

use App\Organization;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagAndSearchController extends Controller
{
    public function getOptions(Request $req)
    {
        $data  = DB::table("email_bank")->select("email")->where('email', 'NOT LIKE', '%getucon.com')->pluck("email");
        foreach ($data as $d){
        $user = User::query()->where("email","like","".$d."%")->first();
        if($user){
            $name = $user->first_name." ".$user->surname;
        }else{
            $organization = Organization::query()->where("email","like","".$d."%")->first();
            if($organization){
                if($organization->owner_firstname !=null || $organization->owner_lastname !=null){
                    $name = $organization->owner_firstname." ".$organization->owner_lastname;
                }else{
                    $name = $organization->org_name;
                }
            }else{
                $name = "External";
            }
        }

        $options[] =[
            "email"=> $d,
            "name"=> $name
        ];
        }

        return response()->json($options);

    }

}
