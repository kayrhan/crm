<?php

namespace App\Http\Controllers;

use App\ExternalPartner;
use App\ExternalPartnerAttachments;
use App\ExternalPartnerUser;
use App\TicketExternalPartner;
use App\User;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ExternalPartnerController extends Controller
{
    public function index(){

        return view("external-partner.external-partner");
    }

    public function get_users(){

    }

    public function add_partner(){
        $data["update"] = 0;
        return view("external-partner.external-partner-edit")->with($data);
    }

    public function add_partner_post(Request $request){

        try{
            $partner = new ExternalPartner();
            $partner->organization_name = $request->organization_name;
            $partner->email         = $request->email;
            $partner->phone         = $request->phone;
            $partner->address       = $request->address;
            $partner->city          = $request->city;
            $partner->zip_code      = $request->zip_code;
            $partner->comment       = $request->comment;
            $partner->rating        = $request->rating;
            $partner->add_by        = auth()->id();
            $partner->add_ip        = $request->ip();
            $partner->save();

            $attachments = json_decode($request->external_partner_attachments);
            if($attachments){
                foreach ($attachments as $attachment){

                    $size = str_replace(["external_partner_attachments[","]",],"",$attachment->name); // extract size because ajax request not return array from form data
                    $filename = $attachment->value;
                    $partner_attachment = new ExternalPartnerAttachments();
                    $partner_attachment->external_id = $partner->id;
                    $partner_attachment->name = $filename;
                    $partner_attachment->size  = $size;
                    $partner_attachment->add_by = auth()->id();
                    $partner_attachment->add_ip = $request->ip();
                    $partner_attachment->save();
                }
            }

            return redirect("/external-partners");
        }catch (\Exception $e){
            dd($e);
        }

    }

    public function update_partner($id){
        $data["update"] = 1;
        $data["partner"] = ExternalPartner::where("id",$id)->first();
        $data["attachments"] = ExternalPartnerAttachments::where("external_id",$id)->get();
        return view("external-partner.external-partner-edit")->with($data);
    }
    public function update_partner_post(Request $request){

        try{
            $partner                    =  ExternalPartner::find($request->id);
            $partner->organization_name = $request->organization_name;
            $partner->email             = $request->email;
            $partner->phone             = $request->phone;
            $partner->address           = $request->address;
            $partner->city              = $request->city;
            $partner->zip_code          = $request->zip_code;
            $partner->comment           = $request->comment;
            $partner->rating            = $request->rating;
            $partner->save();

            $attachments = json_decode($request->external_partner_attachments);

            if($attachments){

                foreach ($attachments as $attachment ){

                    $size = str_replace(["external_partner_attachments[","]",],"",$attachment->name);
                    $filename = $attachment->value;
                    $partner_attachment = new ExternalPartnerAttachments();
                    $partner_attachment->external_id = $partner->id;
                    $partner_attachment->name = $filename;
                    $partner_attachment->size  = $size;
                    $partner_attachment->add_by = auth()->id();
                    $partner_attachment->add_ip = $request->ip();
                    $partner_attachment->save();
                }
            }

            return response()->json(["save_close"=>$request->save_close,"id"=>$partner->id]);
        }catch (\Exception $e){

        }

    }


    public function get_partners(Request $request){
        $partners = ExternalPartner::all();
        return DataTables::of($partners)
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('d.m.Y');
                })
                ->addColumn("actions",function ($row){
                    return $row;
                })
                ->rawColumns(['actions']) //
                ->make(true);
    }

    public function add_partner_contact(Request $request){

        $partner_user = new ExternalPartnerUser();
        $partner_user->partner_id   = $request->partner_id;
        $partner_user->name         = $request->contact_name;
        $partner_user->surname      = $request->contact_surname;
        $partner_user->email        = $request->contact_email;
        $partner_user->gsm          = $request->contact_gsm;
        $partner_user->fax_no       = $request->contact_fax_no;
        $partner_user->position     = $request->contact_position;
        $partner_user->add_by       = auth()->id();
        $partner_user->save();

        return redirect("/external-partners/update/".$request->partner_id);

    }

    public function update_partner_contact(Request $request,$user_id){
        $partner_user = ExternalPartnerUser::where("id",$user_id)->first();
        $partner_user->name         = $request->contact_name;
        $partner_user->surname      = $request->contact_surname;
        $partner_user->email        = $request->contact_email;
        $partner_user->gsm          = $request->contact_gsm;
        $partner_user->fax_no       = $request->contact_fax_no;
        $partner_user->position     = $request->contact_position;
        $partner_user->updated_by   = auth()->id();
        $partner_user->save();

        return redirect("/external-partners/update/".$request->partner_id);
    }

    public function get_partner_contact_info($id){
        $partner_user = ExternalPartnerUser::where("id",$id)->first();
        return $partner_user;
    }

    public function get_partner_contacts($partner_id){
        $partner_users = ExternalPartnerUser::where("partner_id",$partner_id);

         return DataTables::of($partner_users)
                ->addColumn('actions',function ($row){

                    return $row->id;
                })
                ->rawColumns(['actions'])
                ->make(true);


    }

    public function delete_contact(Request $request){
        try {
             $contact = ExternalPartnerUser::where("id",$request->contact_id)->first();
             $contact->delete();
             return response()->json(["success"=>1]);
        }catch (\Exception $e){
            return  response()->json(["success"=>0]);
        }

    }

    public function get_raw_data(Request $request){

        $partners = ExternalPartner::select(["id","organization_name"])->where('organization_name', 'like', '%' . $request->q . '%')->get();

        return response()->json($partners);
    }

    public function get_partner_users_raw(Request $request){
        $partner_users = ExternalPartnerUser::select(["id","name","surname"])
            ->where("partner_id",$request->partner_id)->
                where(function ($query) use($request){
                    $query->where("name","like","%".$request->q."%")->orWhere("surname","like","%".$request->q."%");
            })->get();

        return response()->json($partner_users);
    }

    public function delete_partner($id){

        try {
            //soft delete external partner
            $partner = ExternalPartner::find($id);
            $partner->delete();

            //soft delete belongs to ticket partner
            $ticket_partner = TicketExternalPartner::where("partner_id",$id);
            $ticket_partner->delete();

            return response()->json(["success"=>1]);

        }catch (\Exception $e){
            return response()->json(["success"=>0]);
        }

    }

    public function delete_attachment($id){
         try {
            //soft delete attachment partner
            $attachment = ExternalPartnerAttachments::find($id);
            $attachment->delete();
            return response()->json(["success"=>1]);

        }catch (\Exception $e){
            return response()->json(["success"=>0]);
        }


    }


}
