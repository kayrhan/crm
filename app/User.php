<?php

namespace App;

use App\Helpers\EmailBankHelper;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user){
            EmailBankHelper::save_to_bank($user->email);
        });
        static::updated(function ($user){

            if(isset($user->changes["email"])){
                EmailBankHelper::save_to_bank($user->changes["email"]);
            }

        });

    }

    public function role()
    {
        return $this->belongsTo(Role::class, "role_id");
    }

    public function getorganizationNameAttribute()
    {
        try {
            $organization = Organization::where('id', $this->org_id)->first();
            if ($organization)
                return $organization->org_name;
        } catch (Exception $e) {
            return '';
        }
    }
    public function getroleNameAttribute()
    {
        try {
            $role = Role::where('id', $this->role_id)->first();
            if ($role)
                return $role->name;
        } catch (Exception $e) {
            return '';
        }
    }
    public function getPermissionsAttribute()
    {
        try {
            $rolePermissions = RolePermission::where('role_id', $this->role_id)->get();
            $rolePermissions = $rolePermissions->pluck('permission')->toArray();
            return $rolePermissions;
        } catch (Exception $e) {
            return '';
        }
    }

    public function getFullName(){
        return $this->first_name . " " . $this->surname;
    }

    protected $appends = ['organizationName', 'roleName', 'Permissions'];
}
