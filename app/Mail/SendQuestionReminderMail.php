<?php

namespace App\Mail;

use App\Helpers\EmailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendQuestionReminderMail extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public $data;
    public function __construct($data) {
        $this->data = $data;
    }

    public function build() {

        $this->from($this->data["from"],$this->data["from_title"])
            ->subject($this->data["subj"])
            ->view($this->data["template"],[
                "ticket"=>$this->data["ticket"],
                "comments"=>$this->data["comments"],
                "comment_more_than_three"=>$this->data["comment_more_than_three"]
            ]);


//        if(count($this->data["bcc"])>0){
//            $this->bcc(EmailHelper::debug_mail($this->data["bcc"]));
//        }

        $this->to(EmailHelper::debug_mail($this->data["to"]));
        return $this;
    }
}
