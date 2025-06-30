<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceRepeatReminderMail extends Mailable implements ShouldQueue {

    use Queueable, SerializesModels;
    public $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function build() {

        $this->from(env("MAIL_GETUCON_BUCHHALTUNG_FROM"))->subject("Upcoming Repeated Invoice | Invoice Number: " . $this->data["invoice_number"])->to(env("TEST_MAIL",["cg@getucon.de", "ce@getucon.de", "si@getucon.de", "ta@getucon.de"]));
        return $this->view('emails.commands-mail.invoice-repeat-reminder')->with($this->data);
    }
}
