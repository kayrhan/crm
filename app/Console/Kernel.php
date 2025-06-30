<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

class Kernel extends ConsoleKernel {
    protected function schedule(Schedule $schedule) {
        if(App::environment("production")) {
            $schedule->command("DueDateTicketMailCommand")->dailyAt("03:00");
            $schedule->command("InvoiceReminder")->dailyAt("05:00");
            $schedule->command("InvoiceReminderTrCommand")->dailyAt("05:00");
            $schedule->command("InvoiceRepeatReminder")->dailyAt("11:00");
            $schedule->command("ServiceExpireRemindCommand")->dailyAt("17:00");
            $schedule->command("QuestionTicketCheckAndSendMail")->days([1])->at("09:00");
            $schedule->command("SupportMailIdleCommand")->everyThreeMinutes();
            $schedule->command("CleanTemporaryFiles")->dailyAt("03:00");
            $schedule->command("QueueListener")->everyMinute();
        }
    }

    protected function commands() {
        $this->load(__DIR__ . "/Commands");
        require base_path("routes/console.php");
    }
}