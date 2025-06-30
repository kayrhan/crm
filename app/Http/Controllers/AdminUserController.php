<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Organization;
use App\Role;
use App\User;
use App\Ticket;
use App\UserPosition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            if (!in_array('VIEW_USERS', auth()->user()->Permissions)) {
                return redirect('/tickets');
            }
            return view('users.users');
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function addUserIndex(Request $request, $org_id = null)
    {
        try {
            if(in_array(\auth()->user()->role_id, [4, 6, 7, 8])) {
                return redirect('/tickets');
            }
            $position_types = UserPosition::get();
            $roles = Role::where('id', '>=', auth()->user()->role_id)->get();
            if (auth()->user()->role_id == 1) {
                $organizations = Organization::get();
            }
            if (auth()->user()->role_id == 2) {
                $tickets = Ticket::where('status_id', $request->status)->get();
                $organizations = Organization::get();
            }
            if (auth()->user()->role_id == 3) { //For Personnel Admin
                $users = User::where('personnel', auth()->id())->get();
                $userIds = $users->pluck('id');
                $tickets = Ticket::where('status_id', $request->status)
                    ->whereIn('personnel', $userIds)
                    ->orWhere('personnel', auth()->id())
                    ->get();
                $orgId = $tickets->pluck('org_id');
                $organizations = Organization::whereIn('id', $orgId)->get();
            }
            if (auth()->user()->role_id == 5) { //For Firma Admin
                $organizations = Organization::where('id', auth()->user()->org_id)->get();
                $position_types = null;
            }
            return view('users.add-user', compact('roles', 'organizations', "org_id", "position_types"));
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function userIndex(Request $request, $id)
    {
        try {
            $message = '';
            $user = User::where('id', $id)->first();
            return view('users.user', compact('user', 'message'));
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function updateUserIndex(Request $request, $id, $org_id = null)
    {
        try {
            if(in_array(\auth()->user()->role_id, [4, 6, 7, 8])) {
                return redirect('/tickets');
            }

            $position_types = UserPosition::get();
            $roles = Role::where('id', '>=', auth()->user()->role_id)->get();
            if (auth()->user()->role_id == 1) {
                $organizations = Organization::get();
            }
            if (auth()->user()->role_id == 2) {
                // $tickets = Ticket::where('status_id', $request->status)->get();

                $users = User::where('personnel', auth()->id())->get();
                $userIds = $users->pluck('id');
                $tickets = Ticket::where('status_id', $request->status)
                    ->whereIn('personnel', $userIds)
                    ->orWhere('personnel', auth()->id())
                    ->get();
                $orgId = $tickets->pluck('org_id');
                $organizations = Organization::whereIn('id', $orgId)->get();
            }
            if (auth()->user()->role_id == 3) { //For Personnel Admin
                $users = User::where('personnel', auth()->id())->get();
                $userIds = $users->pluck('id');
                $tickets = Ticket::where('status_id', $request->status)
                    ->whereIn('personnel', $userIds)
                    ->orWhere('personnel', auth()->id())
                    ->get();
                $orgId = $tickets->pluck('org_id');
                $organizations = Organization::whereIn('id', $orgId)->get();
            }
            if (auth()->user()->role_id == 5) { //For Firma Admin
                $organizations = Organization::where('id', auth()->user()->org_id)->get();
                $position_types = null;
            }
            $user = User::where('id', $id)->first();
            $up_usr = User::where("id", $user->update_by)->first();
            $add_usr = User::where("id", $user->add_by)->first();
            $user["add_by_name"] = $add_usr->first_name . " " . $add_usr->surname;
            $user["update_by_name"] = $up_usr->first_name . " " . $up_usr->surname;
            return view('users.update-user', compact('user', 'roles', 'organizations', "org_id", "position_types"));
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function getUser(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)->first();
            return $user;
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function getCcUsers(Request $request)
    {
        try {
            $data=[];
            $counter=0;
            foreach (explode(",",$request->cc_ids) as $id){
                $data[$counter]=  User::find($id);
                $counter++;
            }

            return response()->json(["users"=>$data]);
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }


    public function resetPassword(Request $request, $id)
    {

        try {
            $auth_user_id = auth()->id();
            $role = auth()->user()->role_id;


            if ($role != 1 && $role != 2 && $auth_user_id != $id)
                return redirect("/resetPassword/" . $auth_user_id);
            return view('reset-password', compact('id'));
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    public function getUsers(Request $request)
    {
        try {
            if (auth()->user()->role_id == 1) {
                $users = User::select(['id', 'first_name', 'surname', 'email', 'org_id', 'role_id', 'in_use'])->orderBy('first_name', 'ASC');
            }
            if (auth()->user()->role_id == 2) {
                $users = User::select(['id', 'first_name', 'surname', 'email', 'org_id', 'role_id', 'in_use'])->orderBy('first_name', 'ASC');
            }
            if (auth()->user()->role_id == 3) { //For Personnel Admin
                $users = User::select(['id', 'first_name', 'surname', 'email', 'org_id', 'role_id', 'in_use'])->orderBy('first_name', 'ASC');
            }
            if (auth()->user()->role_id == 4) { //For Personnel
                $users = User::where('id', auth()->id())->select(['id', 'first_name', 'surname', 'email', 'org_id', 'role_id', 'in_use'])->orderBy('first_name', 'ASC');
            }
            if (auth()->user()->role_id == 5) { //For Firma Admin
                $users = User::where('org_id', auth()->user()->org_id)->select(['id', 'first_name', 'surname', 'email', 'org_id', 'role_id', 'in_use'])->orderBy('first_name', 'ASC');
            }
            if (auth()->user()->role_id == 6) { //For Firma User
                $users = User::where('id', auth()->id())->select(['id', 'first_name', 'surname', 'email', 'org_id', 'role_id', 'in_use'])->orderBy('first_name', 'ASC');
            }
            return DataTables::of($users)
                ->addColumn('actions', function ($users) {

                    if (auth()->user()->role_id != 3) {

                        if ($users->in_use == 1) {
                            $title = 'Active';
                            $icon = 'fa fa-user-times';
                            $status = 0;
                        } else {
                            $title = 'Inactive';
                            $icon = 'fa fa-user-plus';
                            $status = 1;
                        }
                        if(in_array("UPDATE_USER", auth()->user()->Permissions) && in_array("DELETE_USER", auth()->user()->Permissions)) {
                            return '<a href="#" data-id="' . $users->id . '" class="deleteUser"><i class="fa fa-trash btn btn-dark mr-1 tippy-tooltip" data-tippy-content="Delete the User"></i></a><a href="#" data-id="' . $users->id . '" data-status="' . $status . '" class="userStatus"><i class="' . $icon . ' btn btn-info mr-1 tippy-tooltip" data-tippy-content="Change User\'s Activeness Status"></i></a><a href="/update-user/' . $users->id . '" target="_blank"><i class="fa fa-pencil btn btn-warning tippy-tooltip" data-tippy-content="Edit the User"></i></a>';
                        }
                        if(!in_array("UPDATE_USER", auth()->user()->Permissions) && in_array("DELETE_USER", auth()->user()->Permissions)) {
                            return '<a href="#" data-id="' . $users->id . '" class="deleteUser"><i class="fa fa-trash btn btn-dark"></i></a>';
                        }
                        if(in_array("UPDATE_USER", auth()->user()->Permissions) && !in_array("DELETE_USER", auth()->user()->Permissions)) {
                            return '<a href="' . url('/update-user' . '/' . $users->id) . '" data-bs-toggle="tooltip" data-bs-placement="bottom" title="edit user"><i class="fa fa-pencil btn btn-theme"></i></a> <a href="#" data-id="' . $users->id . '" data-status="'. $status .'" class="userStatus" data-bs-toggle="tooltip" data-bs-placement="bottom" title="change status"><i class="' . $icon . ' btn btn-info" data-toggle="tooltip" data-original-title="' . $title . '"></i></a><span style="display:none;">' . $title . '</span>';
                        }
                        if(!in_array("UPDATE_USER", auth()->user()->Permissions) && !in_array("DELETE_USER", auth()->user()->Permissions)) {
                            return '-';
                        }
                    }
                })->rawColumns(['actions'])
                ->filterColumn("org_id", function($q, $k) {
                    $organization = Organization::where('org_name', 'like', '%' . $k . '%')->select('id')->pluck('id');
                    return $q->whereIn('org_id', $organization);
                })
                ->make(true);
        }
        catch(Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function getOrganizationUsers(Request $request, $organizationId)
    {
        try {
            $users = User::where('org_id', $organizationId)->get();
            return DataTables::of($users)
                ->editColumn("role", function ($row) use ($request) {
                    $role = Role::where("id", $row->role_id)->first();
                    if ($role)
                        return $role->name;
                    else
                        return null;
                })
                ->addColumn('actions', function ($user) {
                    return $user;
                })->rawColumns(['actions'])
                ->make(true);
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function getOrganizationUsersRawData(Request $request, $organization_id) {
        try {
            if(in_array($organization_id, [3, 8])){
                $users = User::query()->select(["id", DB::raw("CONCAT(first_name,' ',surname)as text")])->where(function($query) {
                    $query->orWhere("org_id", 3);
                    $query->orWhere("org_id", 8);
                })->where("first_name", "LIKE", "%" . $request->q . "%")->where("in_use", 1)->orderBy("text")->get();
            }
            else {
                $users = User::query()->select(["id", DB::raw("CONCAT(first_name,' ',surname)as text")])->where("org_id", $organization_id)->where("first_name", "LIKE", "%" . $request->q . "%")->where("in_use", 1)->orderBy("text")->get();
            }

            return $users;
        }
        catch (Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to retrieve organization users!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }
    public function getPersonnelRawData(Request $request) {
        try {
            $q = User::select(['id', DB::raw("CONCAT(first_name,' ',surname)as text"),'email'])->where(function($query) use ($request){
                $query->where('first_name', 'like', '%' . $request->q . '%')
                ->orWhere("email","like","%" . $request->q ."%")
                ->orwhere('surname', 'like', '%' . $request->q . '%');
            })->whereIn('role_id', [4, 1, 2, 3, 7])->where("in_use", 1);
            if($request->except) {
                $q->where("id","!=",$request->except);
                }
            $users = $q->orderBy('text')->get();
            return $users;
        }
        catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function createUser(Request $request)
    {
        try {
            $rules = array(
                'role' => 'required',
                'organization' => 'required',
                'first_name' => 'required',
                'email' => 'required',
                'password' => 'required',
            );
            $validator = Validator::make($request->all(), $rules);
            // Validations
            $redirectUrl = "add-user/" . $request->org_id ?? "";
            if ($validator->fails()) {
                return redirect($redirectUrl)->withInput()->withErrors($validator->errors()->all());
            }
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return redirect($redirectUrl)->withInput()->withErrors('User already exists with this email address');
            }
            if ($request->password != $request->confirm_password) {
                return redirect($redirectUrl)->withInput()->withErrors('Password does not match');
            }

            $user = new User();
            $user->role_id = $request->role;
            $user->org_id = $request->organization;
            $user->first_name = $request->first_name;
            $user->surname = $request->last_name;
            $user->phone_no = $request->phone;
            $user->gsm = $request->gsm;
            $user->description = $request->description;
            $user->email = $request->email;
            if($request->position_type){
                $effort_type = UserPosition::where('id',$request->position_type)->first();
                $user->effort_type = $effort_type->effort_type;
                $user->position_type = $request->position_type;
            }
            $user->get_email = 1;
            $user->in_use = 1;
            $user->last_login = Carbon::now();
            $user->add_by = auth()->id();
            $user->add_ip = request()->ip();
            $user->ip = request()->ip();
            $user->update_by = auth()->id();
            $user->update_ip = request()->ip();
            $user->password = Hash::make($request->password);
            $user->save();

            if ($request->org_id) {
                return redirect("/organizations/".$request->org_id . "/edit");
            } else {
                return redirect('users');
            }
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function editUser(Request $request, $id)
    {

        try {
            $user = User::where('id', $id)->first();

            $rules = array(
                'role' => 'required',
                'organization' => 'required',
                'first_name' => 'required',
                'email' => 'required',
            );
            // Validations
            $redirectUrl = "update-user/" . $id . "/" . $request->org_id ?? "";
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect($redirectUrl)->withInput()->withErrors($validator->errors()->all());
            }
            $checkUser = User::where('email', $request->email)->where('id', '!=', $id)->first();
            if ($checkUser) {
                return redirect($redirectUrl)->withInput()->withErrors('User already exists with this email address');
            }

            $user->role_id = $request->role;
            $user->org_id = $request->organization;
            $user->first_name = $request->first_name;
            $user->surname = $request->last_name;
            $user->phone_no = $request->phone;
            $user->gsm = $request->gsm;
            $user->description = $request->description;
            $user->email = $request->email;
            if($request->position_type){
                $effort_type = UserPosition::where('id',$request->position_type)->first();
                $user->effort_type = $effort_type->effort_type;
                $user->position_type = $request->position_type;
            }
            $user->update_by = auth()->id();
            $user->update_ip = request()->ip();
            $user->save();
            $message = 'User updated successfully';

            if($request->org_id){
                return redirect('organizations/'.$request->org_id . "/edit")->with($message);
            }else{
                return redirect('users')->with($message);
            }
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function deleteUser(Request $request, $id)
    {
            $user = User::where('id', $id)->first();
            $user->delete();
            $message = 'User deleted successfully';
            return redirect('/users')->with($message);
    }
    public function updateUserStatus(Request $request, $userId)
    {
        try {
            $isActive = ($request->status) ? 1 : 0;
            $message = ($request->status) ? 'active' : 'in-active';
            $user = User::where('id', $userId)->first();
            $user->in_use = $isActive;
            $user->save();
            return ['success' => 'User marked ' . $message];
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function updateEmailStatus(Request $request, $userId)
    {
        try {
            $isEmail = ($request->status) ? 1 : 0;
            $message = ($request->status) ? 'active' : 'in-active';
            $user = User::where('id', $userId)->first();
            $user->get_email = $isEmail;
            $user->save();
            return ['success' => 'User emails marked ' . $message];
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function resetUserPassword(Request $request, $userId)
    {
        try {
            $user = User::where('id', $userId)->first();
            $user->password =  Hash::make($request->password);
            $user->save();
            return ['success' => 'Password reset successfully'];
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function loginFromUser(Request $request, $userId)
    {
        try {
            if (auth()->user()->role_id == 1) {
                $user = User::where('id', $userId)->first();
                Auth::login($user);
                return redirect('/tickets');
            }
            else {
                return redirect('/tickets');
            }
        }
        catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    public function getRoles(Request $request)
    {

        $org_id = $request->org_id;

        if ($org_id == 7 || $org_id == 8 || $org_id==3) {
            $roles = Role::where("id", "<=", 4)->orWhere("id", 7)->get();
            return response()->json($roles);
        } else {
            if ($org_id != null) {
                $roles = Role::whereIn("id", [5, 6, 8])->get();
                return response()->json($roles);
            }
        }
    }

    public function getFreelancerUsersRawData(Request $request){

        try {
            $users = User::selectRaw('id,CONCAT(first_name," ",surname) as text')
                ->where('role_id',7)
                ->whereRaw('CONCAT(first_name," ",surname) like ?', '%' . $request->q . '%')
                ->orderByRaw('CONCAT(first_name," ",surname)')->get();
            return $users;
        } catch (Exception $e) {
            return ['error' => $e];
        }
    }
}
