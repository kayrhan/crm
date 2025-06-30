<?php

namespace App\Mail;

use App\Helpers\EmailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignmentMail extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->from($this->data["from"], $this->data["from_title"])
            ->to(EmailHelper::debug_mail($this->data["user"]))
            ->subject($this->data["subject"])
            ->view('emails.ticketmails.new-assigned-personnel', [
            'organization_name' => $this->data["organization_name"],
            'ticket' => $this->data["ticket"],
            'update_by'=>$this->data["update_by"]
        ]);
    }
}
