<?php

namespace App\Listeners;

use App\Events\AssignNewPersonnel;
use App\Helpers\Helper;
use App\Mail\AssignmentMail;
use App\Organization;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAssignmentEmail implements ShouldQueue {

    public function __construct() {
        //
    }

    public function handle(AssignNewPersonnel $event) {
        $organization = Organization::where('id', $event->ticket->org_id)->first();
        $hosting_organization = $organization->personnel_org;
        $organization_name = $organization->org_name;
        $user = User::where("id", $event->authenticated_user)->first();
        $data = [];
        $data["update_by"] = ($user->first_name ?? "") . " " . ($user->surname ?? "");
        $data["subject"] = '#' . $event->ticket->id . ' | New Assignment | ' . Str::of($event->ticket->name)->limit(31, "...") . " | " . $organization_name;
        $data["user"] = $event->user->email;
        $data["organization_name"] = $organization_name;
        $data["ticket"] = $event->ticket;

        if($hosting_organization == 8) {
            $data["from"] = env("MAIL_GETUCON_SUPPORT_TURKEY_FROM");
            $data["from_title"] = "Support | getucon Management & Technology Consultancy";
            $mailer = env("MAIL_GETUCON_MAILER");
        }
        elseif($hosting_organization == 3) {
            $data["from"] = env("MAIL_GETUCON_SUPPORT_GERMANY_FROM");
            $data["from_title"] = "Support | getucon GmbH";
            $mailer = env("MAIL_GETUCON_MAILER");
        }
        else {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Senior Organization is misselected. Ticket ID:" . $event->ticket->id,
                9,
                "This is not an exception."
            );
        }

        Mail::mailer($mailer)->send(new AssignmentMail($data));
    }
}