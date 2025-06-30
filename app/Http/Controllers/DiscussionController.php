<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\DiscussionLog;
use App\DiscussionMessageUpdateLog;
use App\Events\AssignNewPersonnel;
use App\Helpers\Helper;
use App\Ticket;
use App\TicketAttachment;
use App\TicketEffortLog;
use App\TicketEffortTotal;
use App\TicketPersonnel;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller {

    public function createDiscussion(Request $request, $ticketId) {
        try {
            $private = $request->private;
            $ticket = Ticket::where('id', $ticketId)->firstOrFail();
            $all_assigned_users = [];
            $all_assigned_users[] = $ticket->personnel;
            $discussion = new Discussion();
            $discussion->message = $request->discussion;
            $discussion->user_id = auth()->id();
            $discussion->ticket_id = $ticketId;

            if ($private)
                $discussion->org_id = auth()->user()->org_id;
            else
                $discussion->org_id = $ticket->org_id;

            if(auth()->user()->role_id == 7){
                $discussion->is_private = 1;
            }else {
                $discussion->is_private = $private;
            }
            $discussion->save();

            if ($request->discussionAttachments) {
                foreach ($request->discussionAttachments as $key => $attachment) {

                    $attach = new TicketAttachment();
                    $attach->ticket_id          = $ticket->id;
                    $attach->discussion_id      = $discussion->id;
                    $attach->is_mail            = 0;
                    $attach->attachment         = $attachment["link"];
                    $attach->size               = $key;
                    $attach->add_by             = auth()->id();
                    $attach->add_ip             = $request->ip();
                    if(in_array(auth()->user()->role_id,[1,2,3,4,7])) {
                        if (auth()->user()->role_id != 7) { // freelancer attachment eklerken varsayılan olara private ekler

                            if ($attachment["isPrivate"] == "on" || $discussion->is_private)
                                $attach->private = 1;
                            else
                                $attach->private = 0;

                        } else {
                            $attach->private = 1;
                        }
                    }else{
                        $attach->private = 0;
                    }


                    $attach->save();
                }
            }

            // Comment Efforts
            if ($request->commentEfforts && $request->commentEffortToggle == "on") {
                $efforts = $request->commentEfforts;
                $index = count($efforts["effort_types"]);
                for ($i = 0; $i < $index; $i++) {
                    if ($efforts["effort_types"][$i] != null) {
                        $effort_log = new TicketEffortLog();
                        $effort_log->ticket_id = $ticketId;
                        $effort_log->effort_type = $efforts["effort_types"][$i];
                        $effort_log->is_discussion = 1;
                        $effort_log->discussion_id = $discussion->id;
                        $effort_log->minutes = $efforts["mints"][$i] ?? 0;
                        $effort_log->hours =$efforts["hours"][$i] ?? 0;
                        $effort_log->user_id = auth()->id();
                        $effort_log->updated_by = auth()->id();
                        if ($efforts["hours"][$i] != 0 || $efforts["mints"][$i] != 0) { // Both can not be zero
                            $effort_log->save();
                        }

                        $ticket_effort_total = TicketEffortTotal::query()->where("ticket_id", $effort_log->ticket_id)->where("effort_type", $effort_log->effort_type)->first();

                        if($ticket_effort_total) {
                            $total_minutes = $ticket_effort_total->total_minutes;
                            $final_minutes = $ticket_effort_total->final_minutes;
                            $ticket_effort_total->total_minutes = $total_minutes + (($effort_log->hours * 60) + $effort_log->minutes);
                            $ticket_effort_total->final_minutes = $effort_log->effort_type === 5 ? $final_minutes : $final_minutes + (($effort_log->hours * 60) + $effort_log->minutes); // Internal ekliyorsak net'e dokunmuyoruz.
                            $ticket_effort_total->discount = (100 - round((($ticket_effort_total->final_minutes / $ticket_effort_total->total_minutes) * 100), 3));
                        }
                        else {
                            $ticket_effort_total = new TicketEffortTotal();
                            $ticket_effort_total->ticket_id = $effort_log->ticket_id;
                            $ticket_effort_total->effort_type = $effort_log->effort_type;
                            $ticket_effort_total->total_minutes = ($effort_log->hours * 60) + $effort_log->minutes;
                            $ticket_effort_total->discount = 0;
                            $ticket_effort_total->final_minutes = ($effort_log->hours * 60) + $effort_log->minutes;
                        }

                        $ticket_effort_total->save();
                    }
                }
            }

            if(in_array(auth()->user()->role_id, [5, 6, 7, 8])) {
                MailController::sendUpdateToAssignedUsers($ticketId, $discussion->id);

                if(auth()->user()->role_id != 7) {
                    Helper::update_ticket_status($ticket->id, 1); // Müşteri yorum yapmış ise statüyü "Opened" olarak güncelle.
                }

                return redirect('update-ticket/' . $ticket->id);
            }

            if($ticket->status_id != $request->status_comment) {
                Helper::update_ticket_status($ticket->id,$request->status_comment);
            }

            $ticket_personnel = TicketPersonnel::where('ticket_id', $ticketId)->pluck('personnel')->toArray(); // Mevcut personelleri al ve Array'e çevir.

            if($ticket_personnel) {
                $all_assigned_users = array_merge($all_assigned_users, $ticket_personnel);
            }

            $assigned_users_final = [];
            $assigned_users_final[] = $request->personnel_comment ?? $ticket->personnel;

            if ($request->assigned_personnel) { // İkincil kullanıcı varsa mantığını burada kuruyoruz.
                $assigned_users_final = array_merge($assigned_users_final, $request->assigned_personnel);
                $difference = array_diff($request->assigned_personnel, $ticket_personnel); // Mevcut ikincil kullanıcılara başka ikincil kullanıcı eklenmiş mi?
                $to_be_deleted = array_diff($ticket_personnel, $request->assigned_personnel); // Mevcut ikincil kullanıcılardan biri silinmiş mi?
                if ($difference) { // Eğer ikincil kullanıcı eklenmişse mantığı burada kuruyoruz.
                    foreach ($difference as $personnel) {
                        $assigned_personnels = new TicketPersonnel();
                        $assigned_personnels->ticket_id = $ticket->id;
                        $assigned_personnels->personnel = $personnel;
                        $assigned_personnels->save();
                    }
                }
                if ($to_be_deleted) { // Eğer ikincil kullanıcı silinmişse mantığı burada kuruyoruz.
                    foreach ($to_be_deleted as $personnel) {
                        TicketPersonnel::query()->where("ticket_id", $ticketId)->where("personnel", $personnel)->delete();
                    }
                }
            }

            $new_asssigned_personnel = array_diff($assigned_users_final, $all_assigned_users);

            foreach($new_asssigned_personnel as $personnel) {
                $user = User::where('id', $personnel)->first();
                AssignNewPersonnel::dispatch($user, $ticket, Auth::id());
            }
            if($request->status_comment !=5){
                $request->comment_due_date = null;
            }elseif($private){
                $request->comment_due_date = $ticket->comment_due_date;
            }

            if ($request->assigned_personnel == null) { // Eğer bütün ikincil kullanıcılar silinmişse mantığını burada kuruyoruz.
                TicketPersonnel::where('ticket_id', $ticketId)->delete();
            }
            $ticket->comment_due_date = $request->comment_due_date ?? null;
            $ticket->due_date = $request->due_date_comment ?? $ticket->due_date;
            $ticket->personnel = $request->personnel_comment ?? $ticket->personnel;
            $ticket->save();

            if($request->sendmail) {
                return response()->json(["sendmail" => 1, "discussion_id" => $discussion->id]);
            }
            else {
                return redirect('update-ticket/' . $ticket->id);
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to save a comment!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return redirect('/tickets');
        }
    }
    public function changeMessageStatus(Request $request, $messageId)
    {
        try {
            $private = $request->private;
            $discussion = Discussion::where('id', $messageId)->first();
            $discussion->is_private = $private;

            $attachments = TicketAttachment::where("ticket_id",$discussion->ticket_id)->where("discussion_id",$discussion->id)->get();
            foreach ($attachments as $attachment){
                $attachment->private = $private;
                $attachment->save();
            }

            $discussion->save();
            return redirect('update-ticket/' . $discussion->ticket_id);
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    public function get_discussion_data($id)
    {
        $discussion = Discussion::find($id);

        return response()->json(["text" => $discussion->message]);
    }

    public function set_discussion_data(Request $request, $id) {
        try {
            $request->message = urldecode($request->message);
            $discussion = Discussion::find($id);
            $old_discussion = $discussion->message; //temp
            $discussion->message = strip_tags(
                $request->message,
                "<a><p><b><i><u><h1><h2><h3><h4><h5><h6><li><ol><br><img><blockquote><del><em><sup><hr>"
            );
            $discussion->updated_by = auth()->id();
            $discussion->save();
            //if any changes create log
            if (!empty($discussion->getChanges())) {
                $log = new DiscussionMessageUpdateLog();
                $log->discussion_id = $discussion->id;
                $log->old_discussion = $old_discussion;
                $log->user = auth()->id();
                $log->save();
            }
            return response()->json(["success" => 1, "text" => $discussion->message]);
        } catch (\Exception $e) {
            return response()->json(["success" => 0]);
        }
    }

    public function deleteComment($id) {
        try {
            $discussion = Discussion::query()->find($id);
            $discussion->updated_by = auth()->id();
            $discussion->save();
            $to_be_deleted_efforts = TicketEffortLog::query()->where("ticket_id", $discussion->ticket_id)->where("is_discussion", 1)->where("discussion_id", $discussion->id)->get();

            foreach($to_be_deleted_efforts as $to_be_deleted_effort) {
                $to_be_updated_total = TicketEffortTotal::query()->where("ticket_id", $discussion->ticket_id)->where("effort_type", $to_be_deleted_effort->effort_type)->first();
                $to_be_updated_total->total_minutes -= (($to_be_deleted_effort->hours * 60) + $to_be_deleted_effort->minutes);

                if($to_be_updated_total->total_minutes == 0) {
                    $to_be_updated_total->delete();
                }
                else {
                    $to_be_updated_total->final_minutes = $to_be_deleted_effort->effort_type == 5 ? 0 : $to_be_updated_total->total_minutes;
                    $to_be_updated_total->discount = $to_be_deleted_effort->effort_type == 5 ? 100 : 0;
                    $to_be_updated_total->save();
                }
            }

            TicketEffortLog::query()->where("ticket_id", $discussion->ticket_id)->where("is_discussion", 1)->where("discussion_id", $discussion->id)->delete();
            TicketAttachment::query()->where("discussion_id", $discussion->id)->delete();
            $discussion->delete();
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to delete a discussion!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function add_attachment(Request $request, $id) {
        try {
            $discussion = Discussion::find($id);

            if ($request->add_attach_comment) {
                foreach ($request->add_attach_comment as $key => $attachment) {

                    $attach = new TicketAttachment();
                    $attach->ticket_id = $discussion->ticket_id;
                    $attach->discussion_id = $discussion->id;
                    $attach->is_mail = 0;
                    $attach->attachment = $attachment["link"];
                    $attach->size = $key;
                    $attach->add_by = auth()->id();
                    $attach->add_ip = $request->ip();

                    if (auth()->user()->role_id != 7) { // freelancer attachment eklerken varsayılan olara private ekler
                        if ($attachment["isPrivate"] == "on")
                            $attach->private = 1;
                        else
                            $attach->private = 0;
                    } else {
                        $attach->private = 1;
                    }

                    $attach->save();
                }
            }

            return response()->json(["success" => 1]);
        }
        catch (\Exception $e) {
            return response()->json(["success" => 0]);
        }
    }

    public function get_last_receivers(Request $request){

        $ticket = Ticket::where("id",$request->ticket_id)->first();

        $discussion_ids = Discussion::where("ticket_id",$ticket->id)->select("id")->pluck("id");

        $last_discussion_log = DiscussionLog::whereIn("discussion_id",$discussion_ids)->whereNotNull("emails")->where("sender_user_id",auth()->id())->orderBy("created_at","desc")->first();
        if(!$last_discussion_log){
            $last_discussion_log=DiscussionLog::whereIn("discussion_id",$discussion_ids)->whereNotNull("emails")->orderBy("created_at","desc")->first();
        }
        $last_receivers = [];
        if($last_discussion_log){

            $cc_set     = "";
            $bcc_set    = "";
            foreach (explode(";",$last_discussion_log->emails_cc) as $l_cc){
                $cc_set.= "{\"value\":\"".$l_cc."\"},";
            }
            $cc_set = rtrim($cc_set,",");

            $cc = "[".$cc_set."]";

            foreach (explode(";",$last_discussion_log->emails_bcc) as $l_bcc){
                $bcc_set.="{\"value\":\"".$l_bcc."\"},";
            }
            $bcc_set = rtrim($bcc_set,",");
            $bcc = "[".$bcc_set."]";

            $last_receivers["to"]   = $last_discussion_log->emails;
            $last_receivers["cc"]   = $cc_set   !=  "{\"value\":\"\"}"  ?   $cc :   "";
            $last_receivers["bcc"]  = $bcc_set  !=  "{\"value\":\"\"}"  ?   $bcc:   "";
        }

        return response()->json($last_receivers);
    }
}
