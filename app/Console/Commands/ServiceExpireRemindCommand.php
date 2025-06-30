<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Mail\ServiceRemindMail;
use App\Service;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ServiceExpireRemindCommand extends Command {
    protected $signature = "ServiceExpireRemindCommand";
    protected $description = "This command is used to notify about expiring services!";

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        try {
            $dayname = Carbon::now()->dayName;

            if(!($dayname == "Sunday")) {
                $first_services = Service::query()->where("is_reminded", 0)->whereDate("expiring_date", "<", Carbon::now()->addDays(14))->get(); // Hiç hatırlatıcı çıkmamış servisleri aldık.
                $final_services = Service::query()->where("is_reminded", 1)->whereDate("expiring_date", "<", Carbon::now()->addDays(3))->get(); // Bir hatırlatıcı çıkmış servisleri aldık.
                $mail_recipients = env("TEST_MAIL") ?: ["ka@getucon.de", "md@getucon.de", "cg@getucon.de"];

                foreach($first_services as $service) {
                    $service->is_reminded = 1;
                    $service->save();
                    Mail::to($mail_recipients)->send(new ServiceRemindMail($service));
                }

                foreach($final_services as $service) {
                    $service->is_reminded = 2;
                    $service->save();
                    Mail::to($mail_recipients)->send(new ServiceRemindMail($service));
                }
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to send a service reminder!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return false;
        }
    }
}