<?php

namespace App\Mail;

use App\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable implements ShouldQueue {
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

        $getucon_logo = Company::where("id",2)->first()->logo;

        if($company == "getucon-de") {
            $this->from(env("MAIL_GETUCON_BUCHHALTUNG_FROM"), "CRM Buchhaltung | getucon GmbH");
            $this->data["logo"] = $getucon_logo;//burası şuanlık sadece getucon logosu almalı
            $email = $this->view('emails.invoices.getucon-de.create-invoice-getucon')->with($this->data);
        }

        $this->subject($this->data["subject"]);

        foreach($attachments as $attachment) {
            $attachment_name = $this->data["accounting"]->filename == $attachment ? substr($attachment, 0, 11) . ".pdf" : $attachment;
            $email->attach(storage_path("app/uploads/") . $attachment, [
                "as" => $attachment_name
            ]);
        }

        return $email;
    }
}