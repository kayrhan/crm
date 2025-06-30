<?php

namespace App\Mail;

use App\AccountingTr;
use App\Helpers\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceReminderMailTurkey extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;
    public $data;
    public $reminder_number;

    public function __construct($data,$reminder_number) {
        $this->data = $data;
        $this->reminder_number = $reminder_number;
    }

    public function build() {
         $invoice        = $this->data["invoice"];
         $attachments    = $this->data["attachments"];
         $customer       = $this->data["customer"];

        if(!empty($this->data["cc"]) && !env("TEST_MAIL")) {
            $this->cc($this->data["cc"]) ;
        }

        if(!empty($this->data["bcc"]) && !env("TEST_MAIL")) {
            $this->bcc($this->data["bcc"]);
        }

            /** Companies tablosunda route_name sütunu veriler kayıt edilirken accounting_tr ve invoice_reminder_tr tablosuna owner_company altında kayıt edilir.
                Bu hangi şirketten fatura kesildiğinin bilgisini verir.
             *  Email templatelerindeki klasör isimleri bunlarla aynı olması gerekiyor
             */

            if($invoice->owner_company == "getucon-tr") {
                $email_directory = "getucon-tr";
                $this->from(env("MAIL_GETUCON_ACCOUNTING_FROM"), "Accounting | getucon Management Technology Consultancy");
            }
            else if($invoice->owner_company == "guler-consulting") {
                $email_directory = "guler-consulting";
                $this->from(env("MAIL_GULER_ACCOUNTING_USERNAME"), "Accounting | Guler Consulting");
            }
            else if($invoice->owner_company == "media-kit") {
                $email_directory = "media-kit";
                $this->from(env("MAIL_MEDIA_KIT_USERNAME"), "Accounting | MediaKit Production");
            }
            else {
                Helper::create_debug_log(__CLASS__, __FUNCTION__, "Gelen şirketle ilgili email templati bulunamadı", 9, "Eksik veri");
                return null;
            }

            if($this->reminder_number==1) {
                $this->subject('1. Reminder for Invoice '.$invoice->invoice_no." | ".$customer->org_name);
                $email = $this->view('emails.invoices.'.$email_directory.'.first-reminder')->with($this->data);
            }
            else if($this->reminder_number==2) {
                $this->subject('2. Reminder for Invoice '.$invoice->invoice_no." | ".$customer->org_name);
                $email = $this->view('emails.invoices.'.$email_directory.'.second-reminder')->with($this->data);
            }
            else if($this->reminder_number==3) {
                $this->subject('LAST Reminder (Blacklisting) for Invoice '.$invoice->invoice_no." | ".$customer->org_name);
                $email = $this->view('emails.invoices.'.$email_directory.'.third-reminder')->with($this->data);
            }
            else if($this->reminder_number==4) {
                $this->subject('BLACKLISTING: '.$customer->org_name.' | Invoice: '.$invoice->invoice_no);
                $email = $this->view('emails.invoices.'.$email_directory.'.blacklist-reminder')->with($this->data);
            }

            $accounting_file_name = AccountingTr::query()->where("no", $this->data["invoice"]->invoice_no)->value("filename");

        foreach($attachments as $attachment) {
            $attachment_name = $accounting_file_name == $attachment->attachment ? substr($attachment->attachment, 0, 9) . ".pdf" : $attachment->attachment;
            $email->attach(storage_path("app/uploads/") . $attachment->attachment, [
                "as" => $attachment_name
            ]);
        }

        return $email;
    }
}