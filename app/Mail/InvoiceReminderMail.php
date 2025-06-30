<?php

namespace App\Mail;

use App\Accounting;
use App\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceReminderMail extends Mailable implements ShouldQueue {
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

        if(!empty($this->data["cc"])) {
            $this->cc($this->data["cc"]) ;
        }

        if(!empty($this->data["bcc"])) {
            $this->bcc($this->data["bcc"]);
        }

        $company = Company::where("id",$invoice->cid)->first();
        $owner_company = $invoice->owner_company;

        if($owner_company == "getucon-de") {
            $getucon_logo = Company::where("id",2)->first()->logo;
            $this->from(env("MAIL_GETUCON_BUCHHALTUNG_FROM"), "CRM Buchhaltung | getucon GmbH");
            $this->data["logo"] = $getucon_logo; // burası getucona bağlı şirketler için dinamik yapılmıştı fakat sadece getucon logosu olacak.logo ismini tabloda alıyor
            if($this->reminder_number==1) {
                $this->subject('1. Zahlungserinnerung: Rechnung '.$invoice->invoice_no." | ".$customer->org_name);
                $email = $this->view('emails.invoices.getucon-de.first-reminder-getucon')->with($this->data);
            }
            else if($this->reminder_number==2) {
                $this->subject('Mahnung: Rechnung '.$invoice->invoice_no." | ".$customer->org_name);
                $email = $this->view('emails.invoices.getucon-de.second-reminder-getucon')->with($this->data);
            }
            else if($this->reminder_number==3) {
                $this->subject('Letzte Mahnung: Rechnung '.$invoice->invoice_no." | ".$customer->org_name);
                $email = $this->view('emails.invoices.getucon-de.third-reminder-getucon')->with($this->data);
            }
            else if($this->reminder_number==4) {
                $this->subject('Aktion erforderlich: Kunde ist in Blacklist! | Rechnung:'.$invoice->invoice_no." | ".$customer->org_name);
                $email = $this->view('emails.invoices.getucon-de.blacklist-reminder-getucon')->with($this->data);
            }
        }

        $accounting_file_name = Accounting::query()->where("no", substr($this->data["invoice"]->invoice_no, 3, 8))->value("filename");

        foreach($attachments as $attachment) {
            $attachment_name = $accounting_file_name == $attachment->attachment ? substr($attachment->attachment, 0, 11) . ".pdf" : $attachment->attachment;
            $email->attach(storage_path("app/uploads/") . $attachment->attachment, [
                "as" => $attachment_name
            ]);
        }

        return $email;
    }
}