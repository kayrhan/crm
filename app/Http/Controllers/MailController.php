<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Helpers\EmailHelper;
use App\Helpers\Helper;
use App\Mail\SendUpdateTicketMail;
use App\Organization;
use App\Ticket;
use App\TicketAttachment;
use App\User;
use App\Discussion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller {

    public function sendUpdate(Request $request, $ticket_id, $discussion_id) {
        if($request->ajax()) {
            try {
            $email_to = $request->email_to  ? EmailHelper::explode($request->email_to)  : "";
            $email_cc = $request->email_cc  ? EmailHelper::explode($request->email_cc)  : "";
            $email_bcc = $request->email_bcc ? EmailHelper::explode($request->email_bcc) : "";
            $attachments = $request->discussionLogAttachments ?? [];
            $ticket = Ticket::where('id', $ticket_id, $discussion_id)->firstOrFail();
            $organization = Organization::where('id', $ticket->org_id)->firstOrFail();
            $discussion = Discussion::where('id', $discussion_id)->firstOrFail();
            $senior_org = $organization->personnel_org;
            $totalSize=0;
            $isMail=1;

            if($request->discussionLogAttachments) {
                $attachmentSizes = array_keys($request->discussionLogAttachments);
                foreach ($attachmentSizes as $size) {
                    $totalSize = $totalSize + $size;
                }
                if ($totalSize > 10485760) { // 10 MB'tan büyük ise maille attachmentslar gönderilmiyor.
                    $isMail = 0;
                }
            }

            if(in_array($senior_org, [3, 8])) {
                $mailer = env("MAIL_GETUCON_MAILER");
            }
            else {
                return response()->json(["status" => 0]); // Senior Organization Eksikliği
            }

            $who = "";
            $batch = 0;
            if($request->personnel == "true") {
                $who = "to assigned user";
                $data["personnel"] = 1; // for detailed mail
                $log_data["who"] = 1;
            }
            elseif($request->personnel == "false") {
                $who = "to ticket holder";
                $data["personnel"] = 0; // for non-detailed mail
                $log_data["who"] = 2;
            }
            else {
                $batch = 1;
                $data["personnel"] = 0;
            }

            $data["ticket"] = $ticket;
            $data["discussion"] = $discussion;
            $log_data["discussion_id"] = $discussion_id;
            $log_data["sender_user_id"] = auth()->id();

                if($senior_org != 7) {
                    $data["to"] = $email_to;
                    $data["cc"] = $email_cc;
                    $data["bcc"] = $email_bcc;
                    $data["attachments"] = $isMail ? $attachments : "";
                    $log_data["emails"] = $email_to;
                    $log_data["emails_cc"] = $email_cc;
                    $log_data["emails_bcc"] = $email_bcc;

                    Mail::mailer($mailer)->send(new SendUpdateTicketMail($data));

                    if($batch == 0) {
                        $log_id = EmailHelper::create_discussion_mail_log($log_data);

                        foreach($attachments as $size => $filename) {
                            $this->createAttachment($ticket_id, $discussion_id, $filename, $size, $request->ip(), $log_id, $isMail);
                        }
                    }
                    else {
                        $log_data["who"] = 3;
                        $log_id = EmailHelper::create_discussion_mail_log($log_data);

                        foreach($attachments as $size => $filename) {
                            $this->createAttachment($ticket_id, $discussion_id, $filename, $size, $request->ip(), $log_id,$isMail);
                        }

                        return response()->json([
                            "status" => 1,
                            "who" => $who,
                            "batchCount" => count($email_to)
                        ]);
                    }
                }

                return response()->json([
                    "status" => 1,
                    "who" => $who,
                    "batchCount" => 1
                ]);
            }
            catch(\Exception $exception) {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Something went wrong while trying to send comment's mail!",
                    9,
                    $exception->getMessage() . " Line:" . $exception->getLine()
                );

                return response()->json(["status" => 0]);
            }
        }
        else {
            return response()->json(["status" => "not ajax"]);
        }
    }

    public static function sendUpdateToAssignedUsers($ticket_id, $discussion_id) {
        try {
            $ticket = Ticket::query()->findOrFail($ticket_id);
            $organization = Organization::query()->findOrFail($ticket->org_id);
            $senior_org = $organization->personnel_org;

            if($senior_org == 8) { // getucon Management & Technology
                $mailer = env("MAIL_GETUCON_MAILER");
                $data["bcc"] = explode(";", env("TEST_MAIL","cg@getucon.de;md@getucon.de;si@getucon.de"));
            }
            elseif($senior_org == 3) { // getucon GmbH
                $mailer = env("MAIL_GETUCON_MAILER");
                $data["bcc"] = explode(";", env("TEST_MAIL","cg@getucon.de;md@getucon.de;si@getucon.de"));
            }
            else {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Inappropriate senior organization is selected while trying to send an update email about a customer's comment!",
                    9,
                    "Not Exception!"
                );
            }

            $assigned_user_of_organization = User::query()->findOrFail($organization->personnel_id); // Müşterinin Atandığı Personel
            $discussion = Discussion::query()->findOrFail($discussion_id);
            $receivers = []; // Alıcıları bu array'de tanımlayacağız.
            $ticket_users = User::where('id', $ticket->personnel)->orWhereRaw('id IN(SELECT personnel FROM ticket_personnels WHERE ticket_id = ' . $ticket->id . ' AND deleted_at IS NULL)')->select('email')->pluck('email')->toArray();
            $receivers[] = $assigned_user_of_organization->email;
            $receivers = array_unique(array_merge($receivers, $ticket_users));

            if($senior_org != 7) { // "Senior Organization" medasol GmbH değilse mantığı burada kuruyoruz.
                $data["ticket"] = $ticket;
                $data["discussion"] = $discussion;
                $data["to"] = $assigned_user_of_organization->email;
                $data["cc"] = array_diff($ticket_users, array($assigned_user_of_organization->email));
                $data["bcc"] = array_diff($data["bcc"], $receivers);
                $data["personnel"] = 1; // detaylı mail gitmesi için

                Mail::mailer($mailer)->send(new SendUpdateTicketMail($data));

                $log_data["discussion_id"] = $discussion->id;
                $log_data["sender_user_id"] = auth()->id();
                $log_data["receiver_user_id"] = $assigned_user_of_organization->id;
                $log_data["who"] = 0;
                $log_data["emails"] = $data["to"];
                $log_data["emails_cc"] = $data["cc"];
                $log_data["emails_bcc"] = $data["bcc"];

                EmailHelper::create_discussion_mail_log($log_data);
            }

            return true;
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to send ticket's update mail! (Customer, Freelancer)",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function createAttachment($ticket_id, $discussion_id, $file_name, $size, $ip, $log_id, $isMail) {
        $attachment = new TicketAttachment;
        $attachment->ticket_id = $ticket_id;
        $attachment->discussion_id = $discussion_id;
        $attachment->attachment = $file_name;
        $attachment->size = $size;
        $attachment->add_by = auth()->id();
        $attachment->is_mail = $isMail;
        $attachment->private = 0;
        $attachment->add_ip = $ip;
        $attachment->discussion_log_id = $log_id;
        $attachment->save();



    }
}
