<?php

namespace App\Listeners;

use App\EmailLog;
use App\Helpers\EmailBankHelper;
use App\Helpers\Helper;
use Illuminate\Mail\Events\MessageSent;

class MailSentListener {
    public function __construct() {
        //
    }

    public function handle(MessageSent $event) {
        try {
            $parsed_from = $event->message->getFrom();
            $parsed_to = $event->message->getTo();
            $parsed_cc = $event->message->getCc();
            $parsed_bcc = $event->message->getBcc();
            $from = "";
            $to = "";
            $cc = "";
            $bcc = "";

            if(is_array($parsed_from) && !empty($parsed_from)) {
                $from = $parsed_from[0]->getAddress();
            }

            if(is_array($parsed_to) && !empty($parsed_to)) {
                foreach($parsed_to as $value) {
                    $to .= $value->getAddress() . ";";
                }
            }

            if(is_array($parsed_cc) && !empty($parsed_cc)) {
                foreach($parsed_cc as $value) {
                    $cc .= $value->getAddress() . ";";
                }
            }

            if(is_array($parsed_bcc) && !empty($parsed_bcc)) {
                foreach($parsed_bcc as $value) {
                    $bcc .= $value->getAddress() . ";";
                }
            }

            $subject = $event->message->getSubject() ?? "";
            $msg_id = $event->sent->getMessageId();
            $date = $event->message->getDate();
            $attachments = $event->message->getAttachments() ?? [];
            $regular_str = "";

            foreach($attachments as $attachment) {
                $regular_str .= "mime-type={$attachment->getContentType()}\n";
                $regular_str .= "filename={$attachment->getFilename()}\n" ;
            }

            try {
                $auth_user_id = auth()->user()->id ?? 0;
            }
            catch(\Exception $e) {
                $auth_user_id = 0;
            }

            $log = new EmailLog();
            $log->from = $from;
            $log->to = $to;
            $log->cc = $cc;
            $log->bcc = $bcc;
            $log->subject = $subject;
            $log->msg_id = $msg_id;
            $log->date = $date?->format("Y-m-d H:i:s");
            $log->attachs = $regular_str;
            $log->auth_user = $auth_user_id;
            $log->save();


           $email_list = [];
           $to_list = explode(";",$to);
           $cc_list = explode(";",$cc);
           $bcc_list = explode(";",$bcc);

           if(!empty($to)){
               $this->add_to_bank($email_list,$to_list);
           }
           if(!empty($cc)){
               $this->add_to_bank($email_list,$cc_list);
           }
           if(!empty($bcc)){
               $this->add_to_bank($email_list,$bcc_list);
           }

           foreach ($email_list as $key=>$email){
               EmailBankHelper::save_to_bank($email);
           }
        }
        catch(\Exception $e) {
            Helper::create_debug_log(__CLASS__,__FUNCTION__,"Mail log fail",1,$e->getMessage());
        }
    }

    private function add_to_bank(&$email_list,$values) {
        foreach($values as $value) {
            $email_list[] = $value;
        }

        $email_list = array_unique($email_list);
    }
}