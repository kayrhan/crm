<?php

namespace App\Helpers;

use App\EmailBank;

class EmailBankHelper
{

    public static function save_to_bank($email){

        if($email) {
            $instance = EmailBank::where("email", $email)->first();
            if (!$instance) {
                $new_instance = new EmailBank();
                $new_instance->email = $email;
                $new_instance->save();
            }
        }

    }

}
