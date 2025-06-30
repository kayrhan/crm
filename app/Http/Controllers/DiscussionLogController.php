<?php

namespace App\Http\Controllers;

use App\DiscussionLog;
use App\Helpers\Helper;
use App\TicketAttachment;
use App\User;
use Carbon\Carbon;
use Exception;

class DiscussionLogController extends Controller {
    public function getDiscussionLogs($id,$batch_count) {
        try {
            $last_logs = DiscussionLog::query()->where("discussion_id", $id)->orderBy("created_at", "DESC")->limit($batch_count)->get();
            $sender_user_response = [];
            $receiver_user_response = [];
            $who_response_response   = [];
            $emails_response = [];
            $emails_cc_response = [];
            $emails_bcc_response = [];
            $created_at_response = [];
            $log_ids_response = [];
            $attachments_response = [];

            foreach($last_logs as $last_log) {
                $sender_user = User::query()->find($last_log->sender_user_id);
                $receiver_user = User::query()->find($last_log->receiver_user_id);
                $attachments = TicketAttachment::query()->where("discussion_id", $id)->where("discussion_log_id", $last_log->id)->get();
                $who = $last_log->who;
                $email = $last_log->emails;
                $created_at = Carbon::parse($last_log->created_at)->format("d.m.Y H:i:s");
                $sender_user_response[] = $sender_user;
                $receiver_user_response[] = $receiver_user;
                $who_response_response[] = $who;
                $emails_response[] = $email;
                $created_at_response[] = $created_at;
                $log_ids_response[] = $last_log->id;
                $emails_cc_response[] = $last_log->emails_cc;
                $emails_bcc_response[] = $last_log->emails_bcc;
                $attachments_response[] = $attachments;
            }

            return response()->json([
                "sender_user" => $sender_user_response,
                "receiver_user" => $receiver_user_response,
                "who" => $who_response_response,
                "emails" => $emails_response,
                "emails_cc" => $emails_cc_response,
                "emails_bcc" => $emails_bcc_response,
                "created_at" => $created_at_response,
                "batch_count" => $batch_count,
                "log_id" => $log_ids_response,
                "attachments" => $attachments_response
            ]);
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to retrieve discussion logs!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }
}