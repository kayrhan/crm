<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Mail\DueDateReminderMail;
use App\Mail\DueDateSeniorInfoMail;
use App\Organization;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DueDateTicketMailCommand extends Command {
    protected $signature = "DueDateTicketMailCommand";
    protected $description = "Sends reminders to personnel about due date tickets.";

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $dayname = Carbon::now()->dayName;
        if(!($dayname == "Sunday")) { // Pazar günü hatırlatma için mail çıkartma.
            $today = Carbon::now()->subDay()->format("Y-m-d"); // Dünün ve geçmişin tarihini burada alıyoruz.
            $past = Carbon::createFromDate(1970, 1, 1)->format("Y-m-d");
            $tickets_due_date = Ticket::query()->whereNotIn("status_id", [6, 7, 9, 10, 11])->whereNotNull("due_date")->where("due_date", "!=", "")->whereBetween("due_date", [$past, $today]); // Tarihi geçmiş Ticket'ları burada alıyoruz.
            $backup_query = clone $tickets_due_date;
            $tickets = $tickets_due_date->get();
            $personnels = [];

            foreach($tickets as $ticket) {
                $personnel = User::query()->where("id", $ticket->personnel)->get();
                try {
                    if($personnel) {
                        foreach($personnel as $assigned_personnels) {
                            if($assigned_personnels->in_use == 1) {
                                $personnels["id"][] = $assigned_personnels->id;

                                if(($assigned_personnels->org_id == 8 || $assigned_personnels->org_id == 3) && in_array($assigned_personnels->role_id, [1, 2, 3, 4, 7])) {
                                    if($assigned_personnels->email) {
                                        $data["assigned_user"] = $assigned_personnels;
                                        $data["ticket"] = $ticket;
                                        $data["subject"] = "Reminder of Ticket with Ticket ID #" . $ticket->id;
                                        $data["to"] = env("TEST_MAIL", $assigned_personnels->email);

                                        Mail::mailer(env("MAIL_GETUCON_MAILER"))->send(new DueDateReminderMail($data));
                                    }
                                }
                            }
                        }
                    }
                }
                catch(\Exception $e) {
                    Helper::create_debug_log(
                        __CLASS__,
                        __FUNCTION__,
                        "Something went wrong on Due Date Ticket's Mail Command!",
                        2,
                        $e->getMessage()." Line:".$e->getLine()
                    );
                    if(str_contains($e->getMessage(),"Connection could not be established"))
                        return 0;
                }
            }

            try {
                $personnel_ids = array_unique($personnels["id"]);
                unset($personnels["id"]);

                foreach($personnel_ids as $key => $personnel_id) {
                    $q = clone $backup_query;
                    $q->where(function ($query) use ($personnel_id) {
                        $query->where("personnel", $personnel_id);
                    });
                    $personnel = User::query()->find($personnel_id);
                    $personnels[$key]["name_surname"] = ($personnel->first_name ?? "undefined") . " " . ($personnel->surname ?? "undefined");
                    $personnels[$key]["total"] = $q->count();
                    $personnels[$key]["ids"] = $personnel_id;
                }

                usort($personnels, fn($a, $b) => $b["total"] <=> $a["total"]);
                Mail::mailer(env("MAIL_GETUCON_MAILER"))->send(new DueDateSeniorInfoMail($personnels));
            }
            catch(\Exception $e) {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Something went wrong on Senior Section of Due Date Ticket's Mail Command!",
                    2,
                    $e->getMessage()." Line:".$e->getLine()
                );
                if(str_contains($e->getMessage(),"Connection could not be established"))
                    return 0;
            }
        }
        return 0;
    }
}
