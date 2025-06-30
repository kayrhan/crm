<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\RolePermission;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    //
    public function index()
    {
        try {
            if (!in_array('VIEW_ROLES', auth()->user()->Permissions)) {
                return redirect('/dashboard');
            }
            $success = '';
            return view('roles.roles', compact('success'));
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function roleIndex(Request $request, $id)
    {
        try {
            $role = Role::where('id', $id)->firstOrFail();
            $role['permissions'] = RolePermission::where('role_id', $role->id)->get();
            return view('roles.role', compact('role'));
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function addRoleIndex()
    {
        try {
            $permissions = Permission::get();
            return view('roles.add-role', compact('permissions'));
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function updateRoleIndex($id)
    {
        try {
            $permissions = Permission::get();
            $role = Role::where('id', $id)->first();
            $role['permissions'] = RolePermission::where('role_id', $role->id)->get();
            $permissionArray = $role['permissions']->pluck('permission_id')->toArray();
            return view('roles.update-role', compact('role', 'permissions', 'permissionArray'));
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function getRoles(Request $request)
    {
        try {
            $roles = Role::get();
            return DataTables::of($roles)
                ->addColumn('actions', function ($roles) {
                    if (in_array('UPDATE_ROLE', auth()->user()->Permissions) && in_array('DELETE_ROLE', auth()->user()->Permissions))
                        if (!$roles->system)
                            return '<a href="' . url('/update-role' . '/' . $roles->id) . '"><i class="fa fa-pencil btn btn-warning"></i></a> | <a href="#" data-id="' . $roles->id . '" class="deleteRole"><i class="fa fa-trash btn btn-danger"></i></a>';
                        else
                            return '-';
                    if (!in_array('UPDATE_ROLE', auth()->user()->Permissions) && in_array('DELETE_ROLE', auth()->user()->Permissions))
                        if (!$roles->system)
                            return '<a href="#" data-id="' . $users->id . '" class="deleteUser"><i class="fa fa-trash btn btn-danger"></i></a>';
                        else
                            return '-';
                    if (in_array('UPDATE_ROLE', auth()->user()->Permissions) && !in_array('DELETE_ROLE', auth()->user()->Permissions))
                        return '<a href="' . url('/update-user' . '/' . $users->id) . '"><i class="fa fa-pencil btn btn-warning"></i></a>';
                    if (!in_array('UPDATE_ROLE', auth()->user()->Permissions) && !in_array('DELETE_ROLE', auth()->user()->Permissions))
                        return '-';
                })->rawColumns(['actions'])
                ->make(true);
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function getRole(Request $request, $id)
    {
        try {
            $role = Role::where('id', $id)->first();
            return $role;
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function createRole(Request $request)
    {
        try {
            $rules = array(
                'name' => 'required',
                'permissions' => 'required',
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect('add-role')->withInput()->withErrors($validator->errors()->all());
            }
            $role = new Role();
            $role->name = $request->name;
            $role->role_desc = $request->description;
            $role->add_by = auth()->id();
            $role->add_ip = request()->ip();
            $role->update_by = auth()->id();
            $role->update_ip = request()->ip();
            $role->save();

            foreach ($request->permissions as $permission) {
                $rolePermission = new RolePermission();
                $rolePermission->permission_id = $permission;
                $rolePermission->role_id = $role->id;
                $rolePermission->add_by = auth()->id();
                $rolePermission->save();
            }
            return redirect('role/' . $role->id);
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function editRole(Request $request, $id)
    {
        try {
            $rules = array(
                'name' => 'required',
                'permissions' => 'required',
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect('update-role')->withInput()->withErrors($validator->errors()->all());
            }
            $role = Role::where('id', $id)->first();
            $role->name = $request->name;
            $role->role_desc = $request->description;
            $role->save();
            $rolePermission = RolePermission::where('role_id', $role->id)->get();
            foreach ($rolePermission as $permission) {
                $permission->delete();
            }
            foreach ($request->permissions as $permission) {
                $rolePermission = RolePermission::where('role_id', $role->id)->where('permission_id', $permission)->first();
                if (!$rolePermission)
                    $rolePermission = new RolePermission();
                $rolePermission->permission_id = $permission;
                $rolePermission->role_id = $role->id;
                $rolePermission->add_by = auth()->id();
                $rolePermission->save();
            }
            return redirect('role/' . $role->id);
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function deleteRole(Request $request, $id)
    {
        try {
            $role = Role::where('id', $id)->first();
            $rolePermissions = RolePermission::where('role_id', $role->id)->get();
            foreach ($rolePermissions as $permission) {
                $permission->delete();
            }
            $role->delete();
            $success = 'Role deleted successfully';
            return $success;
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
}
