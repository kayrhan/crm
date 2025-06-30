<?php

namespace App\Mail;

use App\Company;
use App\Helpers\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountingMailTr extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
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
        else{
            Helper::create_debug_log(__CLASS__,
                __FUNCTION__,
                "getucon Ltd. subject missed!",
                9,
                "Not Exception!");

        }
        if($accounting->owner_company == "getucon-tr") {
            $email_directory = "getucon-tr";
            $this->from(env("MAIL_GETUCON_ACCOUNTING_FROM"),"CRM Accounting | getucon Management & Technology Ltd.");
        }
        else if($accounting->owner_company  == "guler-consulting") {
            $email_directory = "guler-consulting";
            $this->from(env("MAIL_GULER_ACCOUNTING_USERNAME"),"CRM Accounting | Guler Consulting");
        }
        elseif($accounting->owner_company  == "media-kit") {
            $email_directory = "media-kit";
            $this->from(env("MAIL_MEDIA_KIT_USERNAME"), "CRM Accounting | MediaKit Production");
        }
        else {
            Helper::create_debug_log(__CLASS__, __FUNCTION__, "Gelen şirketle ilgili email templati bulunamadı", 9, "Eksik veri");
            return null;
        }

        $company = Company::query()->find($accounting->company_id);
        $logo = $company->logo;
        $website = $company->website;
        if($accounting->type == "offer") {//send offer template
            $email = $this->view("emails.accounting." . $email_directory . ".create-offer")->with([
                "logo" => $logo,
                "website" => $website,
                "additional_text" => $additional_text
            ]);
        }
        else {
            $email = $this->view("emails.accounting." . $email_directory . ".storno")->with([
                "additional_text" => $additional_text
            ]);
        }
        foreach ($attachments as $filename) {

            $email->attach(storage_path('app/uploads/').$filename);

        }


        return $email;
        //return $this->view('view.name');
    }
}
