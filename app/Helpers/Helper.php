<?php

namespace App\Helpers;

use App\Console\Commands\SupportMailHelper;
use App\DebugLogs;
use App\EffortType;
use App\Ticket;
use App\TicketEffortLog;
use App\TicketEffortTotal;
use App\TicketReference;
use App\TicketStatus;
use App\TransactionCategory;
use App\Transactions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class Helper {
    public static function price_format_to_db($str)
    {// example :  1.515.123,12 -> 1515123.12

        return str_replace(",", ".", str_replace(".", "", $str));

    }

    public static function calculate_total_transactions($company_id){
        $transactions    = Transactions::where("company_id",$company_id)->get();

        $income_tl      = 0;
        $income_euro    = 0;
        $expense_tl     = 0;
        $expense_euro   = 0;
        foreach ($transactions as $transaction){
            if($transaction->transaction_type==1){ // income

                 if($transaction->currency == 1)
                    $income_euro+=$transaction->amount;

                 else if($transaction->currency == 2)
                    $income_tl+=$transaction->amount;

            }else if($transaction->transaction_type==2){// expense

                if($transaction->currency == 1) // euro
                    $expense_euro+=$transaction->amount;

                 else if($transaction->currency == 2) // try
                    $expense_tl+=$transaction->amount;

            }


        }

        return [
            "income_tl"=>$income_tl,
            "income_euro"=>$income_euro,
            "expense_tl"=>$expense_tl,
            "expense_euro"=>$expense_euro,
            ];
    }

    public static function calculate_transactions_timeline($company_id,$start_date=null,$end_date=null){
        $transactions = Transactions::where("company_id",$company_id);
        $categories     = TransactionCategory::all();

        foreach($categories as $category ){
            $transaction = $transactions->where("category_id",$category->id)->get();
            $category->transaciton = $transaction;
        }

        return $categories;
    }

    public static function calculateDiscountedEffortsByType($ticket_id) {
        $discounted_effort_by_types = [];
        $valid_efforts  = EffortType::query()->where("id", "!=", 5)->pluck("id"); // "Internal" olmayan efor tiplerini belirliyoruz.

        foreach($valid_efforts as $valid_effort) {
            $total_efforts_by_type = TicketEffortTotal::query()->where("ticket_id", $ticket_id)->where("effort_type", $valid_effort)->get();

            if($total_efforts_by_type->isEmpty()) {
                $final_hours = 0;
                $final_minutes = 0;
            }
            else {
                $final_hours = ($total_efforts_by_type[0]->final_minutes / 60);
                $final_minutes = ($total_efforts_by_type[0]->final_minutes % 60);
            }

            $discounted_effort_by_types[$valid_effort] = [$final_hours, $final_minutes];
        }

        $discounted_total = [
            intval(self::getDiscountedEffortsAsMinute($ticket_id) / 60), // Final eforlarının toplamının, saat kısmını burada hesaplıyoruz.
            (self::getDiscountedEffortsAsMinute($ticket_id) % 60) // Final eforlarının toplamının, dakika kısmını burada hesaplıyoruz.
        ];

        return [$discounted_effort_by_types, $discounted_total];
    }

        public static function getTotalEffortAsMinute($ticket_id) { // "Discount" göz ardı edilerek, bir ticket için harcanan toplam zamanı hesaplıyoruz.
            $effort_minutes = TicketEffortTotal::query()->where("ticket_id", $ticket_id)->where("effort_type", "!=", 5)->sum("total_minutes");
            return $effort_minutes ?? 0;
        }

        public static function getDiscountedEffortsAsMinute($ticket_id) { // "Discount" yüzde olarak çıkartıldıktan sonra, bir ticket için harcanan toplam net çalışma süresini burada hesaplıyoruz.
            $effort_minutes = TicketEffortTotal::query()->where("ticket_id", $ticket_id)->where("effort_type", "!=", 5)->sum("final_minutes");
            return $effort_minutes ?? 0;
        }

        public static function convert_minute_to_clock($minute) {
            if($minute != 0) {
                $converted_minute = $minute % 60;
                $converted_hour = intval($minute / 60);
            }
            else {
                $converted_minute = 0;
                $converted_hour = 0;
            }
                return sprintf("%02d:%02d",$converted_hour,$converted_minute);
        }

        public static function create_debug_log($controller,$function,$developer_note,$level,$exception_message){

            $debug_log = new DebugLogs();
            $debug_log->controller = $controller;
            $debug_log->function   = $function;
            $debug_log->developer_note = $developer_note;
            $debug_log->level = $level;
            $debug_log->exception_message = $exception_message;
            $debug_log->save();

            if(env("DEBUG_LOG_NOTIFICATION")) {
                Mail::mailer(env("MAIL_GETUCON_MAILER"))->raw("You must control logs!" . " Developer Note:" . $debug_log->developer_note . " Exception Message:" . $debug_log->exception_message, function ($message) {
                    $message->subject("An error on CRM system!");
                    $message->from(env("MAIL_GETUCON_SUPPORT_GERMANY_FROM", "support@getucon.de"), "getucon CRM error");
                    $message->to(["ay@getucon.de"]);
                });
            }

        }

        public static function update_ticket_status($ticket_id,$status,$is_robot=false){
            $ticket = Ticket::find($ticket_id);
            if($status == 6) { // done

                $ticket->close_date = Carbon::now();

            }

            $ticket->status_id = $status;
            $ticket->update_by = $is_robot?SupportMailHelper::ROBOT:auth()->id();
            $ticket->update_ip = $is_robot?"127.0.0.1":request()->ip();
            $ticket->save();

            $ticket_status = new TicketStatus();
            $ticket_status->ticket_id = $ticket->id;
            $ticket_status->status = $status;
            $ticket_status->add_by = $is_robot?SupportMailHelper::ROBOT:auth()->id();
            $ticket_status->add_ip = $is_robot?"127.0.0.1":request()->ip();
            $ticket_status->save();




    }

    public static function accepted_files(){
        return 'image/jpeg,image/png,image/jpg,.pdf,.csv,.ppt,.pptx,.doc,.docx,.mp4,.xlsx,.xlsm,.xltx,.xlsb,.webm,.zip,.rar,.msg,.7z,.tar,.ogg,.mp3,.wav,.snag,.waptt,.ogg,.waptt.opus';
    }

    public static function generateFileName($fileName,$ext)
    {
        // Clear extension
        $extLenght = strlen($ext)+1;
        $fileName = substr($fileName,0,-$extLenght);

        // Remove multiple spaces
        $fileName = preg_replace('/\s+/', ' ', $fileName);

        // Replace spaces with hyphens
        $fileName = preg_replace('/\s/', '-', $fileName);

        // Replace german characters
        $germanReplaceMap = [
            'ä' => 'deutsch_ae_deutschend',
            'Ä' => 'deutsch_Ae_deutschend',
            'ü' => 'deutsch_ue_deutschend',
            'Ü' => 'deutsch_Ue_deutschend',
            'ö' => 'deutsch_oe_deutschend',
            'Ö' => 'deutsch_Oe_deutschend',
            'ß' => 'deutsch_ss_deutschend',
        ];
        $fileName = str_replace(array_keys($germanReplaceMap), $germanReplaceMap, $fileName);

        // Replace special characters
        $findSpecial = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı');
        $replaceSpecial = array('C', 'Ş', 'G', 'U', 'I', 'O', 'c', 's', 'g', 'u', 'o', 'i');
        $fileName = str_replace($findSpecial, $replaceSpecial, $fileName);

        // Remove everything but "normal" characters
        $fileName = preg_replace("([^\w\s\d\-])", '', $fileName);

        // Replace german characters (reverse)
        $germanReplaceMap = [
            'deutsch_ae_deutschend' => 'ä',
            'deutsch_Ae_deutschend' => 'Ä',
            'deutsch_ue_deutschend' => 'ü',
            'deutsch_Ue_deutschend' => 'Ü',
            'deutsch_oe_deutschend' => 'ö',
            'deutsch_Oe_deutschend' => 'Ö' ,
            'deutsch_ss_deutschend' => 'ß',
        ];
        $fileName = str_replace(array_keys($germanReplaceMap), $germanReplaceMap, $fileName);

        // Remove multiple hyphens because of contract and project name connection
        $fileName = preg_replace('/-+/', '-', $fileName);

        // Remove first - or _ character
        $fileName = ltrim($fileName,'-');
        $fileName = ltrim($fileName,'_');

        return $fileName;

    }

    public static function getChildAndItsReferences($ticket_id) { // Bu fonksiyon, ID'si verilen bir ticket'ın bütün derecelerdeki alt ticket'larını bulmak için kullanılır. Üst ticket'ları dahil etmez.
        $tickets = [];
        $tickets[] = $ticket_id;
        $references = TicketReference::whereIn("parent_ticket", $tickets)->pluck("child_ticket")->toArray();

        while($references) {
            $tickets = array_merge($tickets, $references);
            $references = TicketReference::whereIn("parent_ticket", $references)->pluck("child_ticket")->toArray();
        }

        return $tickets;
    }

    public static function getAllRelatedReferences($ticket_id) { // Bu fonksiyon, ID'si verilen bir ticket ile ilişkili bütün referansları bulmak için kullanılır. Bunu en üst ticket'a giderek ve altındaki ilişkilere bakarak yapar.
        $parent_ticket = TicketReference::where("child_ticket", $ticket_id)->value("parent_ticket"); // Mevcut ticket'in ait olduğu ticket'ı kontrol ediyoruz.

        if($parent_ticket) { // Eğer varsa, en üst ticket'ı bulana kadar döngüye gireceğiz.
            while($parent_ticket) {
                $new_parent = TicketReference::where("child_ticket", $parent_ticket)->value("parent_ticket"); // Bir üstü kontrol ediyoruz.

                if($new_parent) { // Bir üst ticket varsa, döngüye o ticket ile girecek.
                    $parent_ticket = $new_parent;
                }
                else { // Değilse eldeki ticket ile döngüden çıkıyoruz.
                    break;
                }
            }

            return self::getChildAndItsReferences($parent_ticket);
        }
        else { // Eğer yoksa, kendisi en üst ticket demektir.
            return self::getChildAndItsReferences($ticket_id);
        }
    }

    public static function convertMinuteToHourWithText($minutes) {
        if($minutes != 0) {
            $minute = $minutes % 60;
            $hour = intval($minutes / 60);
        }
        else {
            $minute = 0;
            $hour = 0;
        }

        $minutes_text = in_array($minute, [0, 1]) ? " Minute" : " Minutes";
        $hours_text = in_array($hour, [0, 1]) ? " Hour" : " Hours";
        return $hour . $hours_text . " and " . $minute . $minutes_text;
    }
}