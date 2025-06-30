<?php

namespace App\Console\Commands;

use App\EmailBank;
use App\Helpers\EmailBankHelper;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TmpSaveBank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TmpSaveBank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $emails = DB::table("users")->select("email")->distinct()->pluck("email")->toArray();

        $this->push_array($emails,DB::table("organizations")->select("email")->distinct()->pluck("email")->toArray());



        $discussion_emails = DB::table("discussion_logs")->select(["emails","emails_cc","emails_bcc"])->get()->toArray();

        foreach ($discussion_emails as $discussion_email){
           $discussion_emails_raw = $discussion_email->emails.";" . $discussion_email->emails_cc.";".$discussion_email->emails_bcc;

           $exploeded = explode(";",$discussion_emails_raw);

           foreach ($exploeded as $key=>$email){
               if(filter_var($email,FILTER_VALIDATE_EMAIL)) {
                    $this->push_array($emails,$email);
               }
           }

        }


        $accounting_mail_logs = DB::table("accounting_mail_logs")->select(["email_to","email_cc","email_bcc"])->get()->toArray();

        foreach ($accounting_mail_logs as $accounting_mail_log){
            $eccounting_emails_raw = $accounting_mail_log->email_to.";" . $accounting_mail_log->email_cc.";".$accounting_mail_log->email_bcc;

            $exploeded = explode(";",$eccounting_emails_raw);

            foreach ($exploeded as $key=>$email){
                if(filter_var($email,FILTER_VALIDATE_EMAIL)) {
                    $this->push_array($emails,$email);
                }
            }

        }


        $ticket_logs = DB::table("ticket_mail_logs")->select(["mail_to","mail_cc","mail_bcc","mail_holder_to","mail_holder_cc","mail_holder_bcc"])->get()->toArray();

        foreach ($ticket_logs as $ticket_log){
            $ticket_emails_raw = $ticket_log->mail_to.";" . $ticket_log->mail_cc.";".$ticket_log->mail_bcc.";".$ticket_log->mail_holder_to.";".$ticket_log->mail_holder_cc.";". $ticket_log->mail_holder_bcc;

            $exploeded = explode(";",$ticket_emails_raw);

            foreach ($exploeded as $key=>$email){
                if(filter_var($email,FILTER_VALIDATE_EMAIL)) {
                    $this->push_array($emails,$email);
                }
            }

        }


        foreach ($emails as $key=>$email){
            EmailBankHelper::save_to_bank($email);
        }


        return 0;
    }

    private function push_array(&$array,$array2){
        if(is_array($array2)) {
            foreach ($array2 as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_EMAIL))
                    $array[] = $value;
            }

        }else{
            $array[] = $array2;
        }



    }


}
