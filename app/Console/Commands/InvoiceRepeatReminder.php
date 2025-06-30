<?php

namespace App\Console\Commands;

use App\Accounting;
use App\AccountingTr;
use App\Company;
use App\Mail\InvoiceRepeatReminderMail;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Exception;
use App\Helpers\Helper;

class InvoiceRepeatReminder extends Command {
    protected $signature = "InvoiceRepeatReminder";
    protected $description = "Sends reminders about repeating invoices.";

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        try {
            $today = Carbon::today()->format('m-d');

            $accounting = Accounting::whereNotNull('repeat_date')
                ->where(function ($query) use ($today) {
                    $query->whereRaw("DATE_FORMAT(DATE_SUB(repeat_date, INTERVAL repeat_reminder DAY), '%m-%d') = ?", [$today])
                        ->orWhereRaw("DATE_FORMAT(repeat_date, '%m-%d') = ?", [$today]);
                })
                ->where('type', 'invoice')
                ->get();

            $accounting_tr = AccountingTr::whereNotNull('repeat_date')
                ->where(function ($query) use ($today) {
                    $query->whereRaw("DATE_FORMAT(DATE_SUB(repeat_date, INTERVAL repeat_reminder DAY), '%m-%d') = ?", [$today])
                        ->orWhereRaw("DATE_FORMAT(repeat_date, '%m-%d') = ?", [$today]);
                })
                ->where('type', 'invoice')
                ->get();

            if($accounting) {
                foreach($accounting as $value) {
                    $invoice_number = "RG-" . $value->no;
                    $invoice_amount = $value->total_amount;
                    $customer = Organization::query()->find($value->customer_id)->org_name;
                    $organization = Company::query()->find($value->company_id)->name;
                    $link = "getucon/accounting/update/invoice/" . $value->id;
                    $repeat_date = $value->repeat_date;
                    $data = [
                        "organization" => $organization,
                        "customer" => $customer,
                        "link" => $link,
                        "invoice_amount" => $invoice_amount,
                        "invoice_number" => $invoice_number,
                        "repeat_date" => $repeat_date
                    ];

                    Mail::mailer(env('MAIL_GETUCON_MAILER'))->send(new InvoiceRepeatReminderMail($data));
                }
            }

            if($accounting_tr) {
                foreach($accounting_tr as $value) {
                    $invoice_number = $value->no;
                    $invoice_amount = $value->total_amount;
                    $customer = Organization::query()->find($value->customer_id)->org_name;
                    $organization = Company::query()->find($value->company_id)->name;
                    $link = "accounting-tr/update/" . $value->owner_company . "/invoice/" . $value->id;
                    $repeat_date = $value->repeat_date;
                    $data = [
                        "organization" => $organization,
                        "customer" => $customer,
                        "link" => $link,
                        "invoice_amount" => $invoice_amount,
                        "invoice_number" => $invoice_number,
                        "repeat_date" => $repeat_date
                    ];

                    Mail::mailer(env('MAIL_GETUCON_MAILER'))->send(new InvoiceRepeatReminderMail($data));
                }
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to send a repeated invoice!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }
}