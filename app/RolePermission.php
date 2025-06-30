<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    //
    protected $table = 'role_has_permissions';
    public function getpermissionSlugAttribute()
    {
        try {
            $permission = Permission::where('id', $this->permission_id)->first();
            return $permission->slug;
        } catch (\Exception $e) {
            return '';
        }
    }
    public function getpermissionAttribute()
    {
        try {
            $permission = Permission::where('id', $this->permission_id)->first();
            return $permission->name;
        } catch (\Exception $e) {
            return '';
        }
    }

    protected $appends = ['permissionSlug', 'permission'];
}
