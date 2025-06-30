<?php

namespace App\Console\Commands;


use App\AccountingTr;
use App\Helpers\Helper;
use App\InvoicePaymentTr;
use App\InvoiceReminderAttachmentsTr;
use App\Mail\InvoiceReminderMailTurkey;
use App\Organization;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceReminderTrCommand extends Command {
    protected $signature = 'InvoiceReminderTrCommand';
    protected $description = 'Sends invoice reminders. (Turkish Companies)';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {

        $dayname = Carbon::now()->dayName;
        if(!($dayname == "Saturday" || $dayname == "Sunday")) { // Cumartesi veya Pazar hatırlatma maili çıkartma.

            // First Mail
            $invoices = \App\InvoiceReminderTr::whereRaw("DATE(DATE_ADD(deadline, INTERVAL +3 DAY)) < '" . Carbon::now()->format('Y-m-d') . "' ")->whereNull('post_mail_1')->whereNull('post_mail_2')->whereNull('post_mail_3')->where('status', 1)->get();

            foreach($invoices as $invoice) {
                $invoice_amount = AccountingTr::where('no', $invoice->invoice_no)->value('total_amount');
                $total_payments = InvoicePaymentTr::where('invoice_no', $invoice->invoice_no)->sum('payment_amount');
                $customer = Organization::query()->find($invoice->oid);
                $data = [
                    'invoice_amount' => $invoice_amount,
                    'total_payments' => $total_payments,
                    'invoice' => $invoice,
                    'customer' => $customer,
                ];

                if($invoice->cc) {
                    $cc = explode(";", $invoice->cc);
                }
                else {
                    $cc = "";
                }

                $bcc = explode(";", env("TEST_MAIL", "cg@getucon.de;si@getucon.de;ta@getucon.de"));

                if($invoice->bcc) {
                    $db_bcc = explode(";", $invoice->bcc);
                    foreach($db_bcc as $exp_bcc) {
                        $bcc[] = $exp_bcc;
                    }
                }

                $email = env("TEST_MAIL", $customer->accounting_to?:$customer->email);

                if($email) {
                    try {
                        $data["attachments"] = InvoiceReminderAttachmentsTr::where('reminder_id', $invoice->id)->get();
                        $data["cc"] = $cc;
                        $data["bcc"] = $bcc;

                        if($invoice->owner_company == "getucon-tr") {
                            $mailer = env("MAIL_GETUCON_MAILER");
                        }
                        if($invoice->owner_company == "guler-consulting") {
                            $mailer = env("MAIL_GULER_ACCOUNTING_MAILER");
                        }
                        if($invoice->owner_company == "media-kit") {
                            $mailer = env("MAIL_MEDIA_KIT_MAILER");
                        }

                        Mail::mailer($mailer)->to($email)->send(new InvoiceReminderMailTurkey($data, 1));
                        \App\InvoiceReminderTr::where('id', $invoice->id)->update(['post_mail_1' => Carbon::now()]);
                    }
                    catch(Exception $e) {
                        Helper::create_debug_log(
                            __CLASS__,
                            __FUNCTION__,
                            "Failed to send first reminder! Reminder ID:" . $invoice->id,
                            9,
                            $e->getMessage()
                        );
                    }
                }
                else {
                    Helper::create_debug_log(
                        __CLASS__,
                        __FUNCTION__,
                        "First reminder sent but there is no matching customer mail! Reminder ID:" . $invoice->id,
                        9,
                        "Not exception!"
                    );
                }
            }

            // Second Mail
            $invoices2 = \App\InvoiceReminderTr::whereRaw("DATE(DATE_ADD(post_mail_1, INTERVAL +10 DAY)) < '" . Carbon::now()->format('Y-m-d') . "' ")->whereNotNull('post_mail_1')->whereNull('post_mail_2')->whereNull('post_mail_3')->where('status', 1)->get();

            foreach($invoices2 as $invoice) {
                $invoice_amount = AccountingTr::where('no', $invoice->invoice_no)->value('total_amount');
                $total_payments = InvoicePaymentTr::where('invoice_no', $invoice->invoice_no)->sum('payment_amount');
                $customer = Organization::where('id', $invoice->oid)->first();
                $data = [
                    'invoice_amount' => $invoice_amount,
                    'total_payments' => $total_payments,
                    'invoice' => $invoice,
                    'customer' => $customer
                ];

                if($invoice->cc) {
                    $cc = explode(";", $invoice->cc);
                }
                else {
                    $cc = "";
                }

                $bcc = explode(";", env("TEST_MAIL", "cg@getucon.de;si@getucon.de;ta@getucon.de"));

                if($invoice->bcc) {
                    $db_bcc = explode(";", $invoice->bcc);
                    foreach ($db_bcc as $exp_bcc) {
                        $bcc[] = $exp_bcc;
                    }
                }

                $email = env("TEST_MAIL", $customer->accounting_to?:$customer->email);

                if($email) {
                    try {
                        $data["attachments"] = InvoiceReminderAttachmentsTr::where('reminder_id', $invoice->id)->get();
                        $data["cc"] = $cc;
                        $data["bcc"] = $bcc;

                        if($invoice->owner_company == "getucon-tr") {
                            $mailer = env("MAIL_GETUCON_MAILER");
                        }
                        if($invoice->owner_company == "guler-consulting") {
                            $mailer = env("MAIL_GULER_ACCOUNTING_MAILER");
                        }
                        if($invoice->owner_company == "media-kit") {
                            $mailer = env("MAIL_MEDIA_KIT_MAILER");
                        }

                        Mail::mailer($mailer)->to($email)->send(new InvoiceReminderMailTurkey($data, 2));

                        \App\InvoiceReminderTr::where('id', $invoice->id)->update(['post_mail_2' => Carbon::now()]);

                    }
                    catch(Exception $e) {
                        Helper::create_debug_log(
                            __CLASS__,
                            __FUNCTION__,
                            "Failed to send second reminder! Reminder ID:" . $invoice->id,
                            9,
                            $e->getMessage()
                        );
                    }
                }
                else {
                    Helper::create_debug_log(
                        __CLASS__,
                        __FUNCTION__,
                        "Second reminder sent but there is no matching customer mail! Reminder ID:" . $invoice->id,
                        9,
                        "Not exception!"
                    );
                }
            }

            // Third Mail
            $invoices3 = \App\InvoiceReminderTr::whereRaw("DATE(DATE_ADD(post_mail_2, INTERVAL +10 DAY)) < '" . Carbon::now()->format('Y-m-d') . "' ")->whereNotNull('post_mail_1')->whereNotNull('post_mail_2')->whereNull('post_mail_3')->where('status', 1)->get();

            foreach($invoices3 as $invoice) {
                $invoice_amount = AccountingTr::where('no', $invoice->invoice_no)->value('total_amount');
                $total_payments = InvoicePaymentTr::where('invoice_no', $invoice->invoice_no)->sum('payment_amount');
                $customer = Organization::where('id', $invoice->oid)->first();
                $data = [
                    'invoice_amount' => $invoice_amount,
                    'total_payments' => $total_payments,
                    'invoice' => $invoice,
                    'customer' => $customer
                ];

                if ($invoice->cc) {
                    $cc = explode(";", $invoice->cc);
                }
                else {
                    $cc = "";
                }

                $bcc = explode(";", env("TEST_MAIL", "cg@getucon.de;si@getucon.de;ta@getucon.de"));

                if ($invoice->bcc) {
                    $db_bcc = explode(";", $invoice->bcc);
                    foreach ($db_bcc as $exp_bcc) {
                        $bcc[] = $exp_bcc;
                    }
                }

                $email = env("TEST_MAIL", $customer->accounting_to?:$customer->email);

                if($email) {
                    try {
                        $data["attachments"] = InvoiceReminderAttachmentsTr::where('reminder_id', $invoice->id)->get();
                        $data["cc"] = $cc;
                        $data["bcc"] = $bcc;

                        if($invoice->owner_company == "getucon-tr") {
                            $mailer = env("MAIL_GETUCON_MAILER");
                        }
                        if($invoice->owner_company == "guler-consulting") {
                            $mailer = env("MAIL_GULER_ACCOUNTING_MAILER");
                        }
                        if($invoice->owner_company == "media-kit") {
                            $mailer = env("MAIL_MEDIA_KIT_MAILER");
                        }

                        Mail::mailer($mailer)->to($email)->send(new InvoiceReminderMailTurkey($data, 3));
                        \App\InvoiceReminderTr::where('id', $invoice->id)->update(['post_mail_3' => Carbon::now()]);
                    }
                    catch (Exception $e) {
                        Helper::create_debug_log(
                            __CLASS__,
                            __FUNCTION__,
                            "Failed to send third reminder! Reminder ID:" . $invoice->id,
                            9,
                            $e->getMessage()
                        );
                    }
                }
                else {
                    Helper::create_debug_log(
                        __CLASS__,
                        __FUNCTION__,
                        "Third reminder sent but there is no matching customer mail! Reminder ID:" . $invoice->id,
                        9,
                        "Not exception!"
                    );
                }
            }

            // Forth Mail (Blacklisting)
            $invoices4 = \App\InvoiceReminderTr::whereRaw("DATE(DATE_ADD(post_mail_3, INTERVAL +10 DAY)) < '" . Carbon::now()->format('Y-m-d') . "' ")->whereNotNull('post_mail_1')->whereNotNull('post_mail_2')->whereNotNull('post_mail_3')->whereNull('post_mail_4')->where('status', 1)->get();

            foreach ($invoices4 as $invoice) {
                $invoice_amount = AccountingTr::where('no', $invoice->invoice_no)->value('total_amount');
                $total_payments = InvoicePaymentTr::where('invoice_no', $invoice->invoice_no)->sum('payment_amount');
                $customer = Organization::where('id', $invoice->oid)->first();
                $data = [
                    'invoice_amount' => $invoice_amount,
                    'total_payments' => $total_payments,
                    'invoice' => $invoice,
                    'customer' => $customer
                ];

                try {
                    $emails = explode(";", env("TEST_MAIL", "cg@getucon.de;si@getucon.de;ta@getucon.de"));

                    $data["attachments"] = InvoiceReminderAttachmentsTr::where('reminder_id', $invoice->id)->get();

                    $cc_email = env("TEST_MAIL", $customer->accounting_to?:$customer->email);
                    if($cc_email) {
                        $data["cc"] = $cc_email; // Blacklist'e alınan müşteri eklenecek.

                        if($invoice->owner_company == "getucon-tr") {
                            $mailer = env("MAIL_GETUCON_MAILER");
                        }
                        if($invoice->owner_company == "guler-consulting") {
                            $mailer = env("MAIL_GULER_ACCOUNTING_MAILER");
                        }
                        if($invoice->owner_company == "media-kit") {
                            $mailer = env("MAIL_MEDIA_KIT_MAILER");
                        }

                        Mail::mailer($mailer)->to($emails)->send(new InvoiceReminderMailTurkey($data, 4));
                        DB::transaction(function() use($invoice) {
                            \App\InvoiceReminderTr::where('id', $invoice->id)->update(['post_mail_4' => Carbon::now(), 'status' => 3]);
                            Organization::where('id', $invoice->oid)->update(['rating' => 1]);
                        });
                    }
                    else {
                        Helper::create_debug_log(
                            __CLASS__,
                            __FUNCTION__,
                            "Forth reminder sent but there is no matching customer mail! Reminder ID:" . $invoice->id,
                            9,
                            "Not exception!");
                    }
                }
                catch(Exception $e) {
                    Helper::create_debug_log(
                        __CLASS__,
                        __FUNCTION__,
                        "Failed to send forth reminder! Reminder ID:" . $invoice->id,
                        9,
                        $e->getMessage()
                    );
                }
            }
        }
    }
}
