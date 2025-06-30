<?php

namespace App\Http\Controllers;

use App\Contracts;
use App\Helpers\Helper;
use App\MailWhitelist;
use App\Organization;
use App\RatingType;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class OrganizationController extends Controller {
    public function index() {
        return view('organizations.organizations');
    }

    public function create() {
        $rating_types = RatingType::all();
        $top_organizations = [
            ["name" => "getucon Management & Technology", "value" => 8],
            ["name" => "getucon GmbH", "value" => "3"]
        ];
        return view('organizations.add-organization', compact("rating_types", "top_organizations"));
    }

    public function store(Request $request) {
        try {
            $rules = array(
                "owner_firstname" => "required",
                "owner_lastname" => "required",
                'name' => 'required',
                'email' => ["required", "email", "unique:App\Organization,email"],
                'user' => 'required',
                'organization' => 'required'
            );
            $validator = Validator::make($request->all(), $rules, $messages = [
                "email.unique" => "There is already an organization registered with this e-mail address",
                "owner_firstname" => "Owner First Name field is required",
                "owner_lastname" => "Owner Last Name field is required"
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors()->all());
            }

            if ($this->validateDomain($request->email)) {
                return redirect()->back()->withInput()->withErrors(
                    ["domain" => "The email domain need to be unique for each organization"]
                );
            }

            $max = Organization::withTrashed()->max("customer_no");


            $organization = new Organization();
            DB::transaction(function () use ($organization, $request, $max) {
                $organization->customer_no      = $max + 1; // new customer created by new cusomer no
                $organization->personnel_org    = $request->organization;
                $organization->owner_firstname  = $request->owner_firstname;
                $organization->owner_lastname   = $request->owner_lastname;
                $organization->personnel_id     = $request->user;
                $organization->org_name         = $request->name;
                $organization->phone_no         = $request->phone;
                $organization->email            = $request->email;
                $organization->gsm              = $request->gsm;
                $organization->description      = $request->description;
                $organization->address          = $request->address;
                $organization->city             = $request->city;
                $organization->zip_code         = $request->zip_code;
                $organization->is_contracted    = $request->contracted ?? 0;
                $organization->rating           = $request->rating;
                $organization->accounting_to = $request->to;
                $organization->accounting_cc = $request->cc;
                $organization->accounting_bcc = $request->bcc;
                $organization->add_by = auth()->id();
                $organization->add_ip = request()->ip();
                $organization->update_by = auth()->id();
                $organization->update_ip = request()->ip();
                $organization->save();
            });

            if($request->save_close == 1) {
                return redirect()->route("organizations.index");
            }
            else {
                return redirect()->route("organizations.edit", ["organization" => $organization->id]);
            }
        }
        catch (Exception $e) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Organization not created!",
                9,
                $e->getMessage()
            );

            abort(500);
        }
    }

    public function edit(Organization $organization) {
        $rating_types = RatingType::all();
        $top_organizations = [
            ["name" => "getucon Management & Technology", "value" => 8],
            ["name" => "getucon GmbH", "value" => "3"]
        ];
        $up_usr = User::query()->findOrFail($organization->update_by);
        $add_usr = User::query()->findOrFail($organization->add_by);
        $organization["add_by_name"] = $add_usr->first_name . " " . $add_usr->surname;
        $organization["update_by_name"] = $up_usr->first_name . " " . $up_usr->surname;
        return view('organizations.edit-organization', compact("rating_types", "top_organizations", "organization"));
    }

    public function update(Request $request, $id) {
        try {
            $rules = array(
                "owner_firstname" => "required",
                "owner_lastname" => "required",
                'name' => 'required'
            );
            $validator = Validator::make($request->all(), $rules, $messages = [
                "owner_firstname" => "Owner First Name field is required",
                "owner_lastname" => "Owner Last Name field is required"
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors()->all());
            }
            $organization = Organization::query()->findOrFail($id);

            if ($this->validateDomain($request->email, $organization->email)) {
                return redirect()->back()->withInput()->withErrors(
                    ["domain" => "The email domain need to be unique for each organization"]
                );
            }

            $organization->owner_firstname  = $request->owner_firstname;
            $organization->owner_lastname   = $request->owner_lastname;
            $organization->personnel_org = $request->organization;
            $organization->personnel_id = $request->user;
            $organization->org_name = $request->name;
            $organization->phone_no = $request->phone;
            $organization->email = $request->email;
            $organization->gsm = $request->gsm;
            $organization->description = $request->description;
            $organization->address = $request->address;
            $organization->city = $request->city;
            $organization->zip_code = $request->zip_code;
            $organization->is_contracted = $request->contracted;
            $organization->rating = $request->rating;
            $organization->accounting_to = $request->to;
            $organization->accounting_cc = $request->cc;
            $organization->accounting_bcc = $request->bcc;
            $organization->update_by = auth()->id();
            $organization->update_ip = request()->ip();
            $organization->save();

            if($request->save_close == 1) {
                return redirect()->route("organizations.index");
            }
            else {
                return redirect()->route("organizations.edit", ["organization" => $organization->id]);
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to update an organization!",
                9,
                $exception->getMessage() . " Line" . $exception->getLine()
            );

            abort(500);
        }
    }

    public function destroy(Organization $organization) {
        try {
            $users = User::query()->where("org_id", $organization->id)->get();

            if($users->count()) {
                foreach($users as $user) {
                    $user->delete();
                }
            }

            $organization->delete();
            return "Organization deleted successfully";
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to delete an organization!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function getOrganizationsRawData(Request $request) {
        try {
            if(in_array(auth()->user()->role_id, [1, 2, 3, 4])) {
                $organizations = Organization::query()->select(["id", "org_name as text"])->where("org_name", "LIKE", "%" . $request->q . "%")->orderBy("org_name")->get();
            }
            if(in_array(auth()->user()->role_id, [5, 6, 8])) {
                $organizations = Organization::query()->select(["id", "org_name as text"])->where("id", auth()->user()->org_id)->orderBy("org_name")->get();
            }
            if(auth()->user()->role_id === 7) {
                $organizations = Organization::query()->select(["id", "org_name as text"])->where("id", 8)->orderBy("org_name")->get();
            }

            return $organizations;
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to index organizations raw data!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function getOrganization($id) {
        try {
            return Organization::query()->findOrFail($id);
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to get an organization's details!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function getOrganizations(Request $request) {
        try {
            $organizations = Organization::select(['personnel_id', 'rating', 'customer_no', 'personnel_org' ,'org_name', 'is_active', 'id', 'email', 'phone_no']);
            return DataTables::of($organizations)
                ->editColumn("personnel_id", function ($row) use ($request) {

                    $personnel_name = User::where("id", $row->personnel_id)->first();
                    if ($personnel_name)
                        return $personnel_name->first_name . " " . $personnel_name->surname;
                    else
                        return null;
                })
                ->editColumn("personnel_org", function ($row) use ($request) {

                    $personnel_org = Organization::where("id", $row->personnel_org)->first();
                    if ($personnel_org)
                        return $personnel_org->org_name;
                    else
                        return null;
                })
                ->filterColumn("personnel_id", function ($q, $k) {

                    $user = User::where('first_name', 'like', '%' . $k . '%')->orWhere("surname", "like", "%" . $k . "%")->orWhereRaw("concat(first_name,' ',surname)  like '%".$k."%'")->select('id')->pluck('id');
                    return $q->whereIn('personnel_id', $user);
                })
                ->filterColumn("personnel_org", function ($q, $k) {
                    $organization = Organization::where("org_name", "like", "%" . $k . "%")->select('id')->pluck('id');
                    return $q->whereIn('personnel_org', $organization);
                })
                ->filterColumn("is_active",function ($q,$k){
                    return $q->where("is_active",$k);
                })
                ->filterColumn("rating_flag",function ($q,$k){
                    return $q->where("rating",$k);
                })
                ->addColumn('actions', function ($organizations) {
                    if ($organizations->is_active == 1) {
                        $title = 'Active';
                        $icon = 'fe fe-user-check';
                        $status = 0;
                    } else {
                        $title = 'Inactive';
                        $icon = 'fe fe-user-x';
                        $status = 1;
                    }
                    if (in_array('UPDATE_ORGANIZATION', auth()->user()->Permissions) && in_array('DELETE_ORGANIZATION', auth()->user()->Permissions))
                        return '<a href="#" data-id="' . $organizations->id . '" class="deleteOrganization"><i class="fa fa-trash btn btn-danger"></i></a> <a href="#" data-id="' . $organizations->id . '" data-status="' . $status . '" class="updateStatus"><i class="' . $icon . ' btn btn-info" data-toggle="tooltip" data-original-title="' . $title . '"></i></a';
                    if (!in_array('UPDATE_ORGANIZATION', auth()->user()->Permissions) && in_array('DELETE_ORGANIZATION', auth()->user()->Permissions))
                        return '<a href="#" data-id="' . $organizations->id . '" class="deleteOrganization"><i class="fa fa-trash btn btn-danger"></i></a>';
                    if (in_array('UPDATE_ORGANIZATION', auth()->user()->Permissions) && !in_array('DELETE_ORGANIZATION', auth()->user()->Permissions))
                        return '<a href="' . url('/update-organization' . '/' . $organizations->id) . '"><i class="fa fa-pencil btn btn-warning"></i></a>';
                    if (!in_array('UPDATE_ORGANIZATION', auth()->user()->Permissions) && !in_array('DELETE_ORGANIZATION', auth()->user()->Permissions))
                        return '';
                })

                ->addColumn('rating_flag', function ($organizations) {
                    $color = 'success';
                    $text = '';
                    if ($organizations->rating == 1 || !$organizations->rating) {
                        $color = 'danger';
                        $text = 'Blacklist Client';
                    }
                    if ($organizations->rating == 2) {
                        $color = 'warning';
                        $text = 'Normal Client';
                    }
                    if ($organizations->rating == 3) {
                        $color = 'success';
                        $text = 'Good Client';
                    }
                    return '<div class="text-center"><i class="fa fa-flag btn btn-' . $color . '" ></i><span style="display:none; ">' . $text . '</span></div>';
                })

                ->rawColumns(['actions', 'rating_flag'])
                ->make(true);
        }
        catch(Exception $exception) {
            return Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to index organizations!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    /*
        Mail Validation: Aynı domain adı altında birden çok organizasyon kaydedilemez
        mail_whitelist tablosundaki mail domainleri bu filtrelemeye dahil değil
        bu filtreleme için ticket_id: 4795
    */
    public function validateDomain($mail, $ownerMail = null)
    {
        if ($ownerMail) {
            $organizationDomains = Organization::all()->whereNotIn("email", ["NULL", null, "", $ownerMail])->pluck("email")->toArray();
        } else {
            $organizationDomains = Organization::all()->whereNotIn("email", ["NULL", null, ""])->pluck("email")->toArray();
        }
        $domainWhitelist = MailWhitelist::all()->pluck("domain")->toArray();
        for ($i = 0; $i < count($organizationDomains); $i++) {
            $domain = explode("@", $organizationDomains[$i])[1];
            if (!in_array($domain, $domainWhitelist)) {
                $organizationDomains[$i] = $domain;
            }
        }

        $mailDomain = explode("@", $mail)[1];

        $domainValidator = Validator::make(["domain" => $mailDomain], [
            "domain" => [Rule::notIn($organizationDomains)]
        ]);

        return $domainValidator->fails();
    }

    public function updateOrganizationStatus(Request $request, $id) {
        try {
            $isActive = ($request->status) ? 1 : 0;
            $message = ($request->status) ? 'active' : 'in-active';
            $organization = Organization::where('id', $id)->first();
            $organization->is_active = $isActive;
            $organization->save();
            return ['success' => 'Organization marked ' . $message];
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying update an organization's status!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function precisionPrice($price) {
        return round($price * 100) / 100;
    }
    /*Statusu continious veya upgraded olan contractların transport price değerlerini dönüyor*/
    public function getOrganizationContractData(Request $request, $organizationId){
        $getContract = Contracts::where('oid',$organizationId)->where('type',2)->whereNull('terminated_date')->where('type','!=',5)->orderBy('id', 'DESC')->first();
        if(!$getContract){
            // eger support-service-maintance kategorisinde bir contrat yoksa ilk eklenen contractı baz alarak gönderiyorum, ve view de gösteriyorum.
            $getContract = Contracts::where('oid',$organizationId)->whereNull('terminated_date')->where('type','!=',5)->orderBy('id', 'DESC')->first();
            if(!$getContract){
                return response(\GuzzleHttp\json_encode(['status' => 0]), 200)->header('Content-Type', 'application/json');
            }
        }

        if($getContract->type==1){
            $transportTypeText = 'DataCenter';
        } else if($getContract->type==2){
            $transportTypeText = 'Support-Service-Maintance';
        } else if($getContract->type==5){
            $transportTypeText = 'Leasing-Firewall';
        } else if($getContract->type==3){
            $transportTypeText = 'Non-Service';
        } else if($getContract->type==4){
            $transportTypeText = 'Web Contract';
        } else {
            $transportTypeText = 'Non-Service';
        }

        $returnData = [
            'transportPrice1' => $this->precisionPrice($getContract->transportPrice1 - ($getContract->transportPrice1 * ($getContract->transportPriceDiscount1 / 100))),
            'transportPrice2' => $this->precisionPrice($getContract->transportPrice2 - ($getContract->transportPrice2 * ($getContract->transportPriceDiscount2 / 100))),
            'transportPrice3' => $this->precisionPrice($getContract->transportPrice3 - ($getContract->transportPrice3 * ($getContract->transportPriceDiscount3 / 100))),
            'transportTypeText' => $transportTypeText
        ];

        return response(\GuzzleHttp\json_encode(['status' => 1,'contract'=>$returnData]), 200)->header('Content-Type', 'application/json');
    }
}