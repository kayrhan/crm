<?php
namespace App\Console\Commands;
use App\Discussion;
use App\DiscussionLog;
use App\Helpers\EmailHelper;
use App\Helpers\Helper;
use App\Mail\CreateTicketMail;
use App\Mail\SendUpdateTicketMail;
use App\Mail\SupportTicketMail;
use App\Organization;
use App\Ticket;
use App\TicketAttachment;
use App\TicketPersonnel;
use App\TicketReference;
use App\TicketRobotFromMail;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;

class SupportMailHelper {
    const ROBOT = 206;
    private const SUBJECT_FILTER=["Returned email:","Returned Mail:"];

    public static function create_ticket($data) {
        $ticketEmails= self::getTicketInfo($data->message);

        try {
            $new_ticket = new Ticket();
            $new_ticket->name = $data->subject;
            $new_ticket->description = $data->message;
            $new_ticket->org_id = $data->organization_id;
            $new_ticket->user = $data->ticket_holder; // ticket holder
            $new_ticket->personnel = $data->assigned_user; // assigned_user
            $new_ticket->due_date = Carbon::now()->addDays(3)->format("Y-m-d");
            $new_ticket->status_id = 1; // opened ticket
            $new_ticket->category = 4; // other category
            $new_ticket->priority = 1; // Normal
            $new_ticket->add_by = self::ROBOT; //Ticket Robot
            $new_ticket->update_by = self::ROBOT; //Ticket Robot
            $new_ticket->add_ip = "127.0.0.1";
            $new_ticket->update_ip = "127.0.0.1";
            $new_ticket->is_auto = 1; // ticket eğer mailden oluşturulduysa 1 olarak ata
            $new_ticket->save();

            if(isset($data->reference)) {
                TicketReference::query()->create([
                    "parent_ticket" => $data->reference,
                    "child_ticket" => $new_ticket->id,
                    "created_by" => self::ROBOT
                ]);
            }

            foreach ($data->attachments as $key => $attachment) {
                self::create_attachment($attachment, $new_ticket->id,$new_ticket->user, $key);
            }

            $mailSplit=explode("@",$data->sender);

            if($mailSplit[1] == "getucon.de" || $mailSplit[1] == "getucon.com") {
            $fromUser = false;
            $toUser = false;

                if($ticketEmails) {
                    $counter=0;

                    foreach ($ticketEmails as $ticketEmail) {
                        $user= User::query()->where("email",$ticketEmail)->first();

                        if($counter==0 && $user ){
                            $new_ticket->user=$user->id;
                            $fromUser=true;
                        }

                        if($counter==1 && $user ){
                            $new_ticket->personnel=$user->id;
                            $new_ticket->org_id=$user->org_id;
                            $toUser=true;
                        }

                        if($counter>1 && $user && $fromUser && $toUser ){
                            $ticketPersonnel=new TicketPersonnel();
                            $ticketPersonnel->ticket_id=$new_ticket->id;
                            $ticketPersonnel->personnel=$user->id;
                            $ticketPersonnel->save();
                        }

                        $counter++;
                    }

                    if(!($fromUser && $toUser)){
                        $new_ticket->user=$data->ticket_holder;
                        $new_ticket->personnel=$data->assigned_user;
                        $new_ticket->org_id=$data->organization_id;
                    }

                    $new_ticket->save();
                }
            }

            try {
                $attachments = TicketAttachment::query()->where("ticket_id", $new_ticket->id)->get();
                $total_size = 0;

                foreach($attachments as $attachment){
                    $total_size += $attachment->size;
                }

                if($total_size > 10485760) { // 10 MB'tan büyük ise maille attachmentslar gönderilmiyor.
                    foreach($attachments as $attachment) {
                        $attachment->is_mail = 0;
                        $attachment->save();
                    }

                    $attachments = [];
                }

                $data["personnel"] = 1;
                $data["ticket"] = $new_ticket;
                $data["to"] = EmailHelper::debug_mail(User::query()->find($new_ticket->personnel)->email);
                $data["cc"] = "";
                $data["bcc"] = "";
                $data["sent_by"] = User::query()->find(self::ROBOT);
                $data["attachments"] = $attachments;

                Mail::mailer(env("MAIL_GETUCON_MAILER"))->send(new CreateTicketMail($data));
            }
            catch(Exception $exception) {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Something went wrong while trying to send a notification about ticket creation by using Support Mail!",
                    9,
                    $exception->getMessage() . " Line:" . $exception->getLine()
                );
            }

            self::ticketRobotFromMail(self::ROBOT,null,$new_ticket->id,$data->sender);
            return $new_ticket;
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                  __CLASS__,
                  __FUNCTION__,
                  "Something went wrong while trying to create a ticket by using support mail!",
                  9,
                  $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }

        return false;
    }

    public static function create_discussion($data, $to_recepient, $cc_recepient) {
        try {
               $ticket = $data->ticket;
               $current_status = $ticket->status_id;
               $discussion             = new Discussion();
               $discussion->user_id    = $data->user_id;
               $discussion->ticket_id  = $ticket->id;
               $discussion->org_id     = $ticket->org_id;
               $discussion->message    = $data->message;
               $discussion->is_auto    = 1; // eğer mailden oluşturulduysa
               $discussion->save();
               //set ticket status opened if discussion is created

               $ticket->status_id = (in_array($ticket->status_id, [7, 9, 10]) || ($ticket->proofed == 1)) ? $ticket->status_id : 1;
               $ticket->save();

               if($current_status!=$ticket->status_id)
                   Helper::update_ticket_status($ticket->id,$ticket->status_id,true);

               foreach ($data->attachments as $key => $attachment) {
                   self::create_attachment($attachment, $ticket->id,$discussion->user_id, $key, $discussion->id);
               }
               self::ticketRobotFromMail($data->user_id,$discussion->id,null,$data->sender);
            $organization = Organization::query()->findOrFail($ticket->org_id);
            $senior_org = $organization->personnel_org;


            if(in_array($senior_org, [3, 8])) {
                $mailer = env("MAIL_GETUCON_MAILER");
            }

            $data["personnel"] = 1;

            $secondary_users = TicketPersonnel::query()->where("ticket_id", $ticket->id)->get(); // Update Mail'i çıkartmak için ikincil kullanıcıları getiriyoruz.
            $cc_email = []; // İkincil kullanıcıları CC'ye ekleyeceğiz.
            $cc_email_log = ""; // Support Mail'inin Log'u (CC)
            $to_email_log = ""; // Support Mail'inin Log'u (To)

            if($cc_recepient) {
                foreach($cc_recepient as $recepient) {
                    $cc_email_log .= $recepient . ";";
                }
            }

            if($to_recepient) {
                foreach($to_recepient as $recepient) {
                    $to_email_log .= $recepient . ";";
                }
            }

            foreach($secondary_users as $secondary_user) {
                $user = User::query()->find($secondary_user->personnel);
                $cc_email[] = $user->email;
            }

            $user = User::query()->find($ticket->personnel);
            $to_email = $user->email;

            $comment_log = new DiscussionLog();
            $comment_log->discussion_id = $discussion->id;
            $comment_log->sender_user_id = $data->user_id;
            $comment_log->who = 1;
            $comment_log->emails = rtrim($to_email_log, ";");
            $comment_log->emails_cc = rtrim($cc_email_log, ";");
            $comment_log->is_bcc_possible = "No";
            $comment_log->save();

            $data["ticket"] = $ticket;
            $data["discussion"] = $discussion;
            $data["to"] = $to_email;
            $data["cc"] = $cc_email;
            Mail::mailer($mailer)->send(new SendUpdateTicketMail($data));
            return $discussion;
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to create a comment by using support mail!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return false;
        }
    }

    public static function create_attachment($attachment,$ticket_id,$user_id, $key, $discussion_id=null){
         try {
             $attach_attr = $attachment->getAttributes(); // get attachment attributes
             $removeExt = str_replace(".".$attachment->getExtension(),"",$attach_attr["name"]); // remove attachment extension
             $file_name = Helper::generateFileName($removeExt, $attachment->getExtension())."_" . $key . "_" . time().".".$attachment->getExtension();//add timestamp filename with extension
             $file_size = $attach_attr["size"]; // get file size
             $status = file_put_contents(storage_path("app/uploads/").$file_name,$attach_attr["content"]);

             if ($status) {
                 $new_attachment = new TicketAttachment();
                 $new_attachment->ticket_id = $ticket_id;

                 //attachment discussiona kaydedilecekse id sini gir yoksa ticket attachment oluyor
                 if ($discussion_id != null)
                     $new_attachment->discussion_id = $discussion_id;

                 $new_attachment->attachment = $file_name;
                 $new_attachment->size   = $file_size;
                 $new_attachment->add_by = $user_id;
                 $new_attachment->add_ip = "127.0.0.1";
                 $new_attachment->is_auto  = 1; // eğer mailden oluşturulduysa
                 $new_attachment->save();
             }
         }
         catch(Exception $exception) {
             Helper::create_debug_log(
                 __CLASS__,
                 __FUNCTION__,
                 "Something went wrong while trying to create an attachment by using support mail!",
                 9,
                 $exception->getMessage() . " Line:" . $exception->getLine()
             );
         }
    }

    public static function getMessageFormat($message){

        if(!empty(trim($message->getTextBody()))){
            $text = trim($message->getTextBody());
        }else{

            $text = preg_replace(['~<style(.*?)</style>~Usi','~<script(.*?)</script>~Usi'], "", $message->getHTMLBody());

            $text = strip_tags($text,"<br><br/><br />");

        }

        if(str_contains($text,"----Update Ticket----")) {
            $raw_reply = explode("----Update Ticket----", $text);
            $raw_reply = $raw_reply[0];
        }elseif(str_contains($text,"----Create Ticket----")){
            $raw_reply = explode("----Create Ticket----", $text);
            $raw_reply = $raw_reply[0];
        }else{
            $raw_reply = $text;
        }

        $raw_reply = str_replace("\r\n","\n",$raw_reply);
        $raw_reply = str_replace("\r\n\t","\n",$raw_reply);
        $raw_reply = str_replace("\n\n\n","\n\n",$raw_reply);
        $raw_reply = str_replace("\n\n","\n",$raw_reply);
        $raw_reply = str_replace("&nbsp;","",$raw_reply);
        return nl2br(trim($raw_reply));
    }

    public static function parseMessage($message) {

        $data = collect();
        try {
            $data->sender = $message->from[0]->mail;
        }catch (\Exception $e){
            $data->sender = str_replace("<","",str_replace(">","",$message->x_envelope_from[0]));
        }
        if($message->subject !=null) {
            $data->subject = iconv_mime_decode($message->subject[0], ICONV_MIME_DECODE_CONTINUE_ON_ERROR);

        }
        else {
            return null;
        }

        if($message->hasAttachments()){

            $attachments = $message->getAttachments();
            $attach = [];

            $unacceptedTypes = ["exe","rtf","html","js","scf","cmd","tnef","dat"];
            foreach ($attachments as $attachment){
                $signatureImage = self::checkIsSignatureImage($attachment);
                if(!($signatureImage || (in_array($attachment->getExtension(),$unacceptedTypes))))
                    $attach[] = $attachment;
            }

            $data->attachments = $attach;
        }
        else{
            $data->attachments = [];
        }


        return $data;
    }

    public static function parseSubject($subject) {
        foreach(self::SUBJECT_FILTER as $value) {
            if(str_starts_with($subject, $value)) {
                return false;
            }
        }

        preg_match('/#[0-9]{4}/', $subject, $ticket_id);
        if(!empty($ticket_id)) {
            $ticket_id = str_replace("#", "", $ticket_id[0]);
            $ticket_id = str_replace(" |", "", $ticket_id);
            return Ticket::query()->find($ticket_id);
        }
        else {
            return false;
        }
    }

    public static function isUserExist($sender_email) {
        $user = User::query()->where("email", $sender_email)->first();

        if($user) {
            return $user;
        }
        else {
            return false;
        }
    }

    public static function isOrganizationExist($sender_email){
        $email_domain = explode("@",$sender_email)[1];
        $organizations = Organization::query()->whereRaw("email like '%".$email_domain."%'")->get();
        if($organizations->count() == 1){
            return $organizations->first();
        }
        else{
            return null;
        }
    }

    public static function controlSpam($data){
        $sender = $data->sender;

        if (!filter_var($sender,FILTER_VALIDATE_EMAIL)){
            return true;
        }
        else{
            return false;
        }
    }

    public static function getFromHost($message) {
       return  $message->getHeader()->from[0]->host;
    }

    public static function ticketRobotFromMail($userId,$discussionId,$ticketId,$dataSender){
        if($userId==206) {
            $ticketRobotFromMail = new TicketRobotFromMail;
            $ticketRobotFromMail->comment_id = $discussionId;
            $ticketRobotFromMail->ticket_id=$ticketId;
            $ticketRobotFromMail->email = $dataSender;
            $ticketRobotFromMail->created_at = now();
            $ticketRobotFromMail->save();
        }

}
    public static function checkIsSignatureImage($attachment){
        if($attachment->getSize() === 20824) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function getTicketInfo($data) {
        $allData = [];
        if (preg_match('/.*((Subject|Betreff):).*/', $data, $matches)) {
            $subjectMatch = $matches[2];
            if ($subjectMatch) {
                $pattern = "/^(.*\n){7}/";
                if (preg_match($pattern, $data, $matches)) {
                    $result = $matches[0];
                    $pattern = '/([a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/';
                    preg_match_all($pattern, $result, $matches);
                    $email_addresses = $matches[0];
                    $allData=$email_addresses;
                }

                return $allData;
            }
        }
    }
}