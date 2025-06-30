<?php

namespace App\Mail;

use App\Helpers\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMailTurkey extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public $data;
    public function __construct($data) {
        $this->data = $data;
    }

    public function build() {
        $company        = $this->data["company"];
        $attachments    = $this->data["attachments"];

        if(!empty($this->data["cc"])) {
            $this->cc($this->data["cc"]) ;
        }

        if(!empty($this->data["bcc"])) {
            $this->bcc($this->data["bcc"]);
        }

        if($company == "getucon-tr") {
            $email_directory = "getucon-tr";
            $this->from(env("MAIL_GETUCON_ACCOUNTING_FROM"), 'Accounting | getucon Management Technology Consultancy ');
        }
        elseif($company == "guler-consulting") {
            $email_directory = "guler-consulting";
            $this->from(env("MAIL_GULER_ACCOUNTING_USERNAME"), 'Accounting | Guler Consulting ');
        }
        elseif($company == "media-kit") {
            $email_directory = "media-kit";
            $this->from(env("MAIL_MEDIA_KIT_USERNAME"), "Accounting | MediaKit Production");
        }
        else {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Not been able to find email template for incoming request!",
                9,
                "Unknown 'Owner Company' on Turkey's Invoice Mail Section! Check the mailable."
            );

            return null;
        }

        $email = $this->view('emails.invoices.' . $email_directory . '.create-invoice')->with($this->data);
        $this->subject($this->data["subject"]);

        foreach($attachments as $attachment) {
            $attachment_name = $this->data["accounting"]->filename == $attachment ? substr($attachment, 0, 9) . ".pdf" : $attachment;
            $email->attach(storage_path("app/uploads/") . $attachment, [
                "as" => $attachment_name
            ]);
        }

        return $email;
    }
}