<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DueDateReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */

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
        $this->from(env("MAIL_GETUCON_SUPPORT_TURKEY_FROM"),"CRM getucon")
            ->subject($this->data["subject"])
            ->to($this->data["to"]);


        return $this->view('emails.commands-mail.due-date-reminder',$this->data);
    }
}
