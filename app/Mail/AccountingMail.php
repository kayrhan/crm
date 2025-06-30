<?php

namespace App\Mail;

use App\Company;
use App\Helpers\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountingMail extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;
    public $data;
    public function __construct($data) {
        $this->data = $data;
    }

    public function build() {
        $attachments = $this->data["attachments"];
        $accounting =   $this->data["accounting"];
        $additional_text  = $this->data["additional_text"];

        if($this->data["cc"]){
            $this->cc($this->data["cc"]);
        }
        if($this->data["bcc"]){
            $this->bcc($this->data["bcc"]);
        }

        if($this->data["subject"]){
            $this->subject($this->data["subject"]);
        }
        else {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "getucon Germany subject missed!",
                9,
                "Not Exception!"
            );
        }

        $company = Company::where("id",$accounting->company_id)->first();
        $logo = $company->logo;
        $website = $company->website;
        if($accounting->type == "offer"){//send offer template
            $email = $this->view("emails.accounting.create-offer-getucon")->with(["logo"=>$logo,"website"=>$website,"additional_text"=>$additional_text]);
            $this->from("vertrieb@getucon.de","CRM Vertrieb | getucon GmbH");
        }
        else if($accounting->type == "proforma"){//send proforma template
            $email = $this->view("emails.accounting.create-proforma-getucon")->with(["logo"=>$logo,"website"=>$website,"additional_text"=>$additional_text]);
            $this->from(env("MAIL_GETUCON_BUCHHALTUNG_FROM"),"CRM Buchhaltung | getucon GmbH");
        }
        else if($accounting->type == "invoice" && $accounting->storno_no){//send storno template
            $company = Company::where("id",2)->first(); // iptal faturada mailde getucon logosu olması lazım
            $logo = $company->logo;
            $website = $company->website;
            $email = $this->view("emails.accounting.create-storno-getucon")->with(["logo"=>$logo,"website"=>$website,"additional_text"=>$additional_text]);
            $this->from(env("MAIL_GETUCON_BUCHHALTUNG_FROM"),"CRM Buchhaltung | getucon GmbH");
        }

        $attachments = array_reverse($attachments);

        foreach($attachments as $filename) {
            $email->attach(storage_path('app/uploads/') . $filename);
        }

        return $email;
    }
}
