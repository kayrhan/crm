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

class SendUpdateTicketMail extends Mailable implements ShouldQueue
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

        $ticket_organization = Organization::where("id", $this->data["ticket"]->org_id)->first();

        $senior_org          = $ticket_organization->personnel_org;
        $organization_name   = $ticket_organization->org_name;
        $ticket_time         = date("d.m.Y [H:i:s]", strtotime($this->data["ticket"]->created_at));
        $discussion_date     = $this->data["discussion"]->created_at;
        $discussion_message  =  $this->data["discussion"]->message;
        $user_discussion     = User::where('id', $this->data["discussion"]->user_id)->firstOrFail();//commentin sahibi

        if ($senior_org == 8) {
            $subject    = '#' . $this->data["ticket"]->id . ' | Update | ' . Str::of($this->data["ticket"]->name)->limit(31, "...") . ' | ' . $organization_name;//
            $template   = "emails.ticketmails.update-getucon-tr";
            $from_title = "Support | getucon Management & Technology Consultancy";
            $from       = env("MAIL_GETUCON_SUPPORT_TURKEY_FROM");
        }
        elseif($senior_org == 3) {
            $subject    = '#' . $this->data["ticket"]->id . ' | Update | ' . Str::of($this->data["ticket"]->name)->limit(31, "...") . ' | ' . $organization_name;//
            $template   = "emails.ticketmails.update-getucon-de";
            $from_title = "Support | getucon GmbH";
            $from       = env("MAIL_GETUCON_SUPPORT_GERMANY_FROM");
        }
        else {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Senior organization missed! Discussion-ID=" . $this->data["discussion"]->id ?? "-",
                9,
                "Not Exception!"
            );
            return null; // critical error! wrong organization selected
        }

        $this   ->from($from,$from_title)
                ->to(EmailHelper::debug_mail($this->data["to"]))
                ->subject($subject)
                ->view($template,[
                "user_discussion"=>$user_discussion,
                "ticket"=>$this->data["ticket"],
                "personnel"=>$this->data["personnel"],
                "organization"=>$ticket_organization,
                "ticket_time"=>$ticket_time,
                "discussion_date"=>$discussion_date,
                "discussion_message"=>$discussion_message,


            ]);

        if(isset($this->data["cc"]) && !empty($this->data["cc"])){
            $this->cc(EmailHelper::debug_mail($this->data["cc"]));
        }
        if(isset($this->data["bcc"]) && !empty($this->data["bcc"])){
            $this->bcc(EmailHelper::debug_mail($this->data["bcc"]));
        }
        if(isset($this->data["attachments"]) && !empty($this->data["attachments"])){
            foreach ($this->data["attachments"] as $size => $filename){
                $this->attach(storage_path('app/uploads/') . $filename);
            }
        }


        return $this;
    }
}
