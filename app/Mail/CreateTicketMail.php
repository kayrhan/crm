<?php

namespace App\Mail;

use App\Helpers\EmailHelper;
use App\Helpers\Helper;
use App\Organization;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CreateTicketMail extends Mailable implements ShouldQueue {
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
        $ticket_organization = Organization::query()->find($this->data["ticket"]->org_id);
        $senior_org = $ticket_organization->personnel_org;
        $organization_name = $ticket_organization->org_name;
        $assigned_user = User::query()->find($this->data["ticket"]->personnel);

        if($senior_org == 8) {
            $from = env("MAIL_GETUCON_SUPPORT_TURKEY_FROM");
            $from_title = "Support | getucon Management & Technology Consultancy";
            $template = "emails.ticketmails.create-getucon-tr";
            $subject = '#' . $this->data["ticket"]->id . ' | New Ticket | ' . Str::of($this->data["ticket"]->name)->limit(31, "...") . " | " . $organization_name;
        }
        elseif($senior_org == 3) {
            $from = env("MAIL_GETUCON_SUPPORT_GERMANY_FROM");
            $from_title = "Support | getucon GmbH";
            $template = "emails.ticketmails.create-getucon-de";
            $subject = '#' . $this->data["ticket"]->id . ' | Neues Ticket | ' . Str::of($this->data["ticket"]->name)->limit(31, "...") . ' | ' . $organization_name;

        }
        else {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Senior organization missed! Ticket-ID=" . $this->data["ticket"]->id ?? "-",
                9,
                "Not Exception!"
            );

            return null; // critical error! wrong organization selected
        }

        $this->from($from,$from_title)
            ->to(EmailHelper::debug_mail($this->data["to"]))
            ->subject($subject)
            ->view($template,[
                "sent_by"=>$this->data["sent_by"],
                "ticket"=>$this->data["ticket"],
                "personnel"=>$this->data["personnel"],
                "ticket_organization"=>$organization_name,
                "assigned_user"=>$assigned_user

            ]);

        if(isset($this->data["cc"]) && !empty($this->data["cc"])){
            $this->cc(EmailHelper::debug_mail($this->data["cc"]));
        }

        if(isset($this->data["bcc"]) && !empty($this->data["bcc"])){
            $this->bcc(EmailHelper::debug_mail($this->data["bcc"]));
        }

        if(isset($this->data["attachments"]) && !empty($this->data["attachments"])){
            foreach($this->data["attachments"] as $attachment) {
                $this->attach(storage_path('app/uploads/') . $attachment->attachment);
            }
        }

        return $this;

    }
}
