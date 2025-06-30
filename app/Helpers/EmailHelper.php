<?php

namespace App\Helpers;

use App\Discussion;
use App\DiscussionLog;
use App\TicketMailLog;

class EmailHelper
{
    public static function debug_mail($default){
        return env("TEST_MAIL",$default);
    }

    /**
        @param $mails | format string;string;string; ... etc
        @return Array | emails array
     */
    public static function explode($mails){
        $mails      = str_replace(" ", "", $mails);

        return explode(";", $mails);
    }

    public static function create_ticket_mail_log($data){

        $mail_log               = new TicketMailLog(); //mail log
        $mail_log->ticket_id    = $data["ticket"]->id; //mail log
        $mail_log->sent_by      = auth()->id(); //mail log
        $mail_log->mail_to      = isset($data["mail_to"]) ? (is_string($data["mail_to"]) ? $data["mail_to"] : implode(";", $data["mail_to"])) : null;
        $mail_log->mail_cc      = isset($data["mail_cc"]) ? (is_string($data["mail_cc"]) ? $data["mail_cc"] : implode(";", $data["mail_cc"])) : null;
        $mail_log->mail_bcc     = isset($data["mail_bcc"]) ?  (is_string($data["mail_bcc"]) ? $data["mail_bcc"] : implode(";", $data["mail_bcc"])) : null;

        $mail_log->mail_holder_to       = isset($data["mail_holder_to"]) ? (is_string($data["mail_holder_to"]) ? $data["mail_holder_to"] : implode(";", $data["mail_holder_to"])) : null;
        $mail_log->mail_holder_cc       = isset($data["mail_holder_cc"]) ? (is_string($data["mail_holder_cc"]) ? $data["mail_holder_cc"] : implode(";", $data["mail_holder_cc"])) : null;
        $mail_log->mail_holder_bcc      = isset($data["mail_holder_bcc"]) ? (is_string($data["mail_holder_bcc"]) ? $data["mail_holder_bcc"] : implode(";", $data["mail_holder_bcc"])) : null;
        $mail_log->who                  = $data["who"]; // mail log personnel (automatic)
        $mail_log->save();
    }

    public static function create_discussion_mail_log($data){
        $log                    = new DiscussionLog();
        $log->discussion_id     = $data["discussion_id"];
        $log->sender_user_id    = $data["sender_user_id"] ?? null;
        $log->receiver_user_id  = $data["receiver_user_id"] ?? null;
        $log->emails            = isset($data["emails"])     ? (is_string($data["emails"])      ? $data["emails"]     : implode(";",$data["emails"]))     : null;
        $log->emails_cc         = isset($data["emails_cc"])  ? (is_string($data["emails_cc"])   ? $data["emails_cc"]  : implode(";",$data["emails_cc"]))  : null;
        $log->emails_bcc        = isset($data["emails_bcc"]) ? (is_string($data["emails_bcc"])  ? $data["emails_bcc"] : implode(";",$data["emails_bcc"])) : null;
        $log->who               = $data["who"];
        $log->is_bcc_possible = "Yes";
        $log->save();

        return $log->id;

    }
}
