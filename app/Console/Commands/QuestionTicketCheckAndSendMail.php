<?php

namespace App\Console\Commands;


use App\Discussion;
use App\Helpers\EmailHelper;
use App\Helpers\Helper;
use App\Mail\SendQuestionReminderMail;
use App\Organization;
use App\Ticket;
use App\TicketPersonnel;
use App\TicketStatus;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Mail\Mailable;

class QuestionTicketCheckAndSendMail extends Command {
    protected $signature = "QuestionTicketCheckAndSendMail";
    protected $description = "For tickets with question status, if no response is received within 7 days, it sends an e-mail to the customer and the users to whom the ticket is assigned.";

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
          $tickets= Ticket::query()->where("status_id", 5)->whereYear("created_at", ">", "2022")->whereNotNull("comment_due_date")->where("comment_due_date" ,"<", Carbon::now()->subDays(7))->get();

              foreach($tickets as $ticket) {
                  $ticketStatus=TicketStatus::query()->where("ticket_id", $ticket->id)->orderBy("created_at", "DESC")->first();
                  if($ticketStatus && $ticketStatus->status == 5){
                      $lastComment=Discussion::where("ticket_id",$ticket->id)->where("is_private",0)->orderBy('created_at', 'desc')->first();
                      if($lastComment){
                          $user=User::find($lastComment->user_id);
                          if( $user && in_array($user->role_id,[1,2,3,4])){
                              $ticketOrganization = Organization::where("id", $ticket->org_id)->first();
                              $organizationName   = $ticketOrganization->org_name;
                              $seniorOrg = $ticketOrganization->personnel_org;

                              try {
                                  if ($seniorOrg == 8) {
                                      $from = env("MAIL_GETUCON_SUPPORT_TURKEY_FROM");
                                      $fromTitle = "Support | getucon Management & Technology Consultancy";
                                      $template = "emails.commands-mail.question-reminder-getucon-tr";
                                      $subject = '#' . $ticket->id . ' | Status: Question | ' . Str::of($ticket->name)->limit(31, "...") . " | " . $organizationName;
                                      $mailer = env("MAIL_GETUCON_MAILER");
                                  }
                                  elseif ($seniorOrg == 3) {
                                      $from = env("MAIL_GETUCON_SUPPORT_GERMANY_FROM");
                                      $fromTitle = "Support | getucon GmbH";
                                      $template = "emails.commands-mail.question-reminder-getucon-de";
                                      $subject = '#' . $ticket->id . ' | Status: Question | ' . Str::of($ticket->name)->limit(31, "...") . ' | ' . $organizationName;
                                      $mailer = env("MAIL_GETUCON_MAILER");
                                  }
                                  else {
                                      Helper::create_debug_log(
                                          __CLASS__,
                                          __FUNCTION__,
                                          "Senior organization missed on question ticket mail! Ticket-ID=" . $ticket->id ?? "-",
                                          9,
                                          "Not Exception!"
                                      );

                                      return null; // critical error! wrong organization selected
                                  }
                                  $companyUser=User::find($ticket->user);
                                  $masterUser=User::find($ticket->personnel);
                                  $secondUsers=TicketPersonnel::where("ticket_id",$ticket->id)->get();

                                  if(in_array($companyUser->role_id, [5, 6, 8]) && $ticket->is_private) { // Eğer kullanıcı müşteri tarafından ise ve ticket gizli ise atlıyoruz.
                                      break;
                                  }

                                  $bccEmail=[];
                                  if ($secondUsers->count()>0){
                                      foreach ($secondUsers as $assigSecondUser){
                                          $personnel=  User::find($assigSecondUser->personnel);
                                          $bccEmail[]=$personnel->email;
                                      }
                                  }
                                  if($masterUser){
                                      $bccEmail[]=$masterUser->email;
                                  }
                                  $allComments=Discussion::where("ticket_id",$ticket->id)->where("is_private",0)->count();
                                  $getLastComments=Discussion::latest()->where("ticket_id",$ticket->id)->where("is_private",0)->take(3)->get();
                                  $commentMoreThanThree=$allComments > 3 ? 1 : 0;
                                  $comments=[];
                                  $count=0;

                                  foreach ($getLastComments as $comment){
                                      $commentDate=Carbon::parse($comment->updated_at)->format("d.m.Y [H:i:s]");
                                      $commentUser=User::find($comment->user_id);

                                      $comments[$count]["message"]=$comment->message;
                                      $comments[$count]["name"]=$commentUser->first_name." ".$commentUser->surname;
                                      $comments[$count]["date"]=$commentDate;
                                      $count++;
                                  }
                                  $data["comment_more_than_three"]=$commentMoreThanThree;
                                  $data["comments"]=$comments;
                                  $data["last_question_comment"]=$lastComment;
                                  $data["last_comment_by"]=$user;
                                  $data["ticket"]=$ticket;
                                  $data["to"]=$companyUser->email;
                                  $data["bcc"]= $bccEmail;
                                  $data["subj"]=$subject;
                                  $data["template"]=$template;
                                  $data["from"]=$from;
                                  $data["from_title"]=$fromTitle;

                                  if($companyUser){
                                      Mail::mailer($mailer)->send(new SendQuestionReminderMail($data));
                                  }
                              }
                              catch (Exception $e) {

                                  Helper::create_debug_log(
                                      __CLASS__,
                                      __FUNCTION__,
                                      "When read messages an error thrown!",
                                      9, $e->getMessage() . "Line: " . $e->getLine());
                              }

                          }
                      }
                  }

              }


        return 0;
    }
}
