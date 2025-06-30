<?php

namespace App\Mail;

use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SupportTicketMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public  $data;
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
        $email = null;
        $senior_org = $this->data["senior_org"];
        $this->to(env("TEST_MAIL",$this->data["to"]));

        if($this->data["type"] == "ticket"){
            $ticket = $this->data["ticket"];
            $org = Organization::where("id", $ticket->org_id)->first();

            if($senior_org == 3){
                $this->from(env("MAIL_GETUCON_SUPPORT_GERMANY_FROM"),"Support | getucon GmbH");
                $this->subject('#' . $ticket->id . ' | Neues Ticket | ' . Str::of($ticket->name)->limit(31, "...") . ' | ' . $org->org_name);
                $email = $this->view("emails.ticketmails.support-mail.support-mail-getucon-de",["ticket"=>$ticket]);
            }elseif ($senior_org == 8){
                $this->from(env("MAIL_GETUCON_SUPPORT_TURKEY_FROM"),"Support | getucon Management & Technology Consultancy");
                $this->subject('#' . $ticket->id . ' | New Ticket | ' . Str::of($ticket->name)->limit(31, "...") . ' | ' . $org->org_name);
                $email = $this->view("emails.ticketmails.support-mail.support-mail-getucon-tr",["ticket"=>$ticket]);
            }

        }else{
            dd("burda");
            //TODO: Discussion maili için istişare lazım

            return null;
        }


        return $email;
    }
}
