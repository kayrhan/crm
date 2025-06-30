<?php

namespace App;

use App\Helpers\EmailBankHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];


    protected static function boot()
    {
        parent::boot();
        static::created(function ($organization){
            EmailBankHelper::save_to_bank($organization->email);
            EmailBankHelper::save_to_bank($organization->accounting_to);
            EmailBankHelper::save_to_bank($organization->accounting_cc);
            EmailBankHelper::save_to_bank($organization->accounting_bcc);
        });

        static::updated(function ($organization){
            if(isset($organization->changes["email"])){
                EmailBankHelper::save_to_bank($organization->changes["email"]);
            }

            if(isset($organization->changes["accounting_to"])){
                EmailBankHelper::save_to_bank($organization->changes["accounting_to"]);
            }

            if(isset($organization->changes["accounting_cc"])){
                EmailBankHelper::save_to_bank($organization->changes["accounting_cc"]);
            }

            if(isset($organization->changes["accounting_bcc"])){
                EmailBankHelper::save_to_bank($organization->changes["accounting_bcc"]);
            }

        });
    }

    public function personnel(){
        return $this->belongsTo(User::class, "personnel_id");
    }

    public function ratingType(){
        return $this->belongsTo(RatingType::class, "rating", "rating");
    }

    function getParsedStartDateAttribute()
    {
        $parsedDate = '';
        if ($this->contract_start_date)
            $parsedDate = date("Y-m-d", strtotime($this->contract_start_date));
        return $parsedDate;
    }
    function getParsedEndDateAttribute()
    {
        $parsedDate = '';
        if ($this->contract_end_date)
            $parsedDate = date("Y-m-d", strtotime($this->contract_end_date));
        return $parsedDate;
    }

    public function getOwnerName()
    {
        $owner_name = $this->owner_firstname . " " . $this->owner_lastname;
        if($owner_name == " "){
            $owner_name = "-";
        }
        return $owner_name;
    }


    protected $appends = ['ParsedStartDate', 'ParsedEndDate'];
}
