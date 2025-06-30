<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DueDateSeniorInfoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public  $personnels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($personnels)
    {
        $this->personnels = $personnels;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $this->from(env("MAIL_GETUCON_SUPPORT_TURKEY_FROM"),"CRM getucon")
            ->subject("Tickets Summary of the Personnel with the Due Date")
            ->to(env("TEST_MAIL",["cg@getucon.de","md@getucon.de","si@getucon.de", "ta@getucon.de", "ay@getucon.de"]));


        return $this->view('emails.commands-mail.due-date-reminder-senior-info',["personnels"=>$this->personnels]);
    }
}
