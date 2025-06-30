<?php

namespace App\Console\Commands;


use App\Helpers\Helper;
use App\Organization;
use App\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Exceptions\ConnectionFailedException;
use Webklex\PHPIMAP\Exceptions\FolderFetchingException;
use Webklex\PHPIMAP\Exceptions\MaskNotFoundException;
use Webklex\PHPIMAP\Exceptions\RuntimeException;

class SupportMailIdleCommand extends Command {
    protected $signature = "SupportMailIdleCommand";
    protected $description = "This command is used to read Support Email's mails.";

    public function __construct() {
        parent::__construct();
    }


    public function handle() {
        $cm = new ClientManager();

        try {
            $client = $cm->make([
                'host' => env('MAIL_TICKET_ROBOT_HOST'),
                'port' => env('MAIL_TICKET_ROBOT_PORT'),
                'encryption' => env('MAIL_TICKET_ROBOT_ENCRYPTION'),
                'validate_cert' => false,
                'username' => env('MAIL_TICKET_ROBOT_USERNAME'),
                'password' => env('MAIL_TICKET_ROBOT_PASSWORD'),
                'protocol' => 'imap'
            ]);
        }
        catch(MaskNotFoundException $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong on the support mail's credentials!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return false;
        }

        try {
            $client->connect();
        }
        catch(ConnectionFailedException $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong on the support mail's connection!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return false;
        }

        if($client->isConnected()) {
            $msg_uids = [];
            try {
                $inbox = $client->getFolder("INBOX");
            }
            catch(ConnectionFailedException $exception) {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Something went wrong on connection while trying to get a folder on the support mail!",
                    9,
                    $exception->getMessage() . " Line:" . $exception->getLine()
                );

                return false;
            }
            catch(FolderFetchingException $exception) {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Something went wrong while trying to fetch a folder on the support mail!",
                    9,
                    $exception->getMessage() ." Line:" . $exception->getLine()
                );

                return false;
            }
            catch(RuntimeException $exception) {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Runtime Exception has occured while trying to fetch a folder on the support mail!",
                    9,
                    $exception->getMessage() . " Line:" . $exception->getLine()
                );

                return false;
            }
            $query = $inbox->messages()->setFetchOrder("ASC");
            $unseen_mails = $query->where(["UNSEEN"])->get(); // get unseen emails
            try {

                /** GET MESSAGES UID */
                foreach($unseen_mails as $s) {
                    $msg_uids[] = $s->getAttributes()["uid"];
                }

                foreach($msg_uids as $uid) {
                    $message = $query->getMessageByUid($uid); // FETCH MESSAGES BY UID
                    $to_recepient = [];
                    $cc_recepient = [];

                    for($index = $message->getTo()->count(); $index > 0; $index--) {
                        $to_recepient[] = $message->getTo()[$index - 1]->mail;
                    }

                    if($message->getCc()) {
                        for($index = $message->getCc()->count(); $index > 0; $index--) {
                            $cc_recepient[] = $message->getCc()[$index - 1]->mail;
                        }
                    }

                    $data = SupportMailHelper::parseMessage($message);

                    if($data) {
                        $isSpam = SupportMailHelper::controlSpam($data);
                        if($isSpam) {
                            $message->setFlag("Seen");
                            continue;
                        }


                        $user = SupportMailHelper::isUserExist($data->sender);
                        $ticket = SupportMailHelper::parseSubject($data->subject);

                        if($user) { // Eğer kullanıcı CRM'de kayıtlı ise mantığını burada kuruyoruz.
                            $organization = Organization::query()->find($user->org_id);

                            if($ticket && ((!in_array($ticket->status_id, [7, 9, 10, 11])) && ($ticket->proofed == 0))) { // Eğer ticket bulunursa ve statüsü "Invoiced", "Closed", "Terminated", "Done & Proofed" veya "Invoiced After Correction" değilse comment düş.
                                $discussion_data = collect();
                                $discussion_data->ticket = $ticket;
                                $discussion_data->user_id = $user->id;
                                $discussion_data->message = SupportMailHelper::getMessageFormat($message);
                                $discussion_data->attachments = $data->attachments;
                                $discussion_data->sender=$data->sender;
                                $created_discussion = SupportMailHelper::create_discussion($discussion_data, $to_recepient, $cc_recepient);

                                $mail_data["discussion"] = $created_discussion;
                                $mail_data["to"] = $data->sender;
                            }
                            else { // Ticket bulunamaz veya statüsü "Invoiced", "Closed" veya "Invoiced After Correction" ise yeni ticket oluştur.
                                $ticket_data = collect();
                                $ticket_data->ticket_holder = $user->id;
                                $ticket_data->message = SupportMailHelper::getMessageFormat($message);
                                $ticket_data->subject = $data->subject;
                                $ticket_data->assigned_user = $organization->personnel_id;
                                $ticket_data->organization_id = $organization->id;
                                $ticket_data->attachments = $data->attachments;
                                $ticket_data->sender=$data->sender;
                                if($ticket && ((in_array($ticket->status_id, [7, 9, 10, 11])) || $ticket->proofed == 1)) { // Eğer ticket varsa, ama "Invoiced", "Closed", "Done & Proofed" veya "Invoiced After Correction" ise mevcut Ticket ID'sını referans göstererek yeni ticket oluştur.
                                    $ticket_data->reference = $ticket->id;
                                }

                                $created_ticket = SupportMailHelper::create_ticket($ticket_data);



                                $mail_data["ticket"] = $created_ticket;
                                $mail_data["to"] = $data->sender;
                            }
                        }
                        else { // Eğer kullanıcı yoksa mantığını burada kuruyoruz.
                            $organization = SupportMailHelper::isOrganizationExist($data->sender); // Email uzantısından organizasyonu kestirmeye çalış.

                            if($organization) { // Organizasyon bulunursa ve bir tane ise buradan ilerliyoruz.
                                $user = User::where("org_id", $organization->id)->where("role_id", 5)->first(); // Gönderi Firma Admin'i ise ona göre aksiyon alacağız.

                                if($user) {
                                    $user_id = $user->id;
                                    $message_data = SupportMailHelper::getMessageFormat($message);
                                }
                                else {
                                    $user_id = SupportMailHelper::ROBOT;
                                    $message_data = SupportMailHelper::getMessageFormat($message) . " <br>SEND FROM: " . $data->sender;
                                }

                                if($ticket && ((!in_array($ticket->status_id, [7, 9, 10, 11])) && ($ticket->proofed == 0))) { // Eğer Subject'te Ticket ID varsa ve karşılığı varsa, ayrıca "Invoiced", "Closed", "Done & Proofed" veya "Invoice After Correction" değilse yorum düş.
                                    $discussion_data = collect();
                                    $discussion_data->ticket = $ticket;
                                    $discussion_data->user_id = $user_id;
                                    $discussion_data->message = $message_data;
                                    $discussion_data->attachments = $data->attachments;
                                    $discussion_data->sender=$data->sender;
                                    $created_discussion = SupportMailHelper::create_discussion($discussion_data, $to_recepient, $cc_recepient);

                                    $mail_data["discussion"] = $created_discussion;
                                }
                                else { // Ticket bulunamaz veya statüsü "Invoiced", "Closed", "Done & Proofed" veya "Invoiced After Correction" ise yeni ticket oluştur.
                                    $ticket_data = collect();
                                    $ticket_data->ticket_holder = $user_id;
                                    $ticket_data->message = $message_data;
                                    $ticket_data->subject = $data->subject;
                                    $ticket_data->assigned_user = $organization->personnel_id;
                                    $ticket_data->organization_id = $organization->id;
                                    $ticket_data->attachments = $data->attachments;
                                    $ticket_data->sender=$data->sender;

                                    if($ticket && ((in_array($ticket->status_id, [7, 9, 10, 11])) || $ticket->proofed == 1)) { // Eğer ticket varsa, ama "Invoiced", "Closed" veya "Invoiced After Correction" ise mevcut Ticket ID'sını referans göstererek yeni ticket oluştur.
                                        $ticket_data->reference = $ticket->id;
                                    }

                                    $created_ticket = SupportMailHelper::create_ticket($ticket_data);

                                    $mail_data["ticket"] = $created_ticket;
                                }
                                $mail_data["to"] = $data->sender;
                            }
                            else { // Eğer organizasyonu kestiremezsen veya çoklu eşleşme varsa mantığını burada kuruyoruz.
                                if ($ticket && ((!in_array($ticket->status_id, [7, 9, 10, 11])) && $ticket->proofed == 0)) { // Ticket varsa, ayrıca "Invoiced", "Closed" veya "Invoice After Correction" değilse yorum düş.

                                    $discussion_data = collect();
                                    $discussion_data->ticket = $ticket;
                                    $discussion_data->user_id = SupportMailHelper::ROBOT;
                                    $discussion_data->message = SupportMailHelper::getMessageFormat($message) . " <br>SEND FROM: " . $data->sender;
                                    $discussion_data->attachments = $data->attachments;
                                    $discussion_data->sender=$data->sender;
                                    $created_discussion = SupportMailHelper::create_discussion($discussion_data, $to_recepient, $cc_recepient);


                                    $mail_data["discussion"] = $created_discussion;
                                }
                                else { // Ticket yok ise veya statüsü "Invoiced", "Closed" veya "Invoiced After Correction" ise yeni ticket oluştur.
                                    $ticket_data = collect();
                                    $ticket_data->ticket_holder = 235; // Ümit Demirci
                                    $ticket_data->message = SupportMailHelper::getMessageFormat($message) . " <br>SEND FROM: " . $data->sender;
                                    $ticket_data->subject = $data->subject;
                                    $ticket_data->assigned_user = 235; // Ümit Demirci
                                    $ticket_data->organization_id = 8; // getucon Management & Technology
                                    $ticket_data->attachments = $data->attachments;
                                    $ticket_data->sender=$data->sender;

                                    if($ticket && ((in_array($ticket->status_id, [7, 9, 10, 11]) || $ticket->proofed == 1))) { // Eğer ticket varsa, ama "Invoiced", "Closed" veya "Invoiced After Correction" ise mevcut Ticket ID'sını referans göstererek yeni ticket oluştur.
                                        $ticket_data->reference = $ticket->id;
                                    }

                                    $created_ticket = SupportMailHelper::create_ticket($ticket_data);

                                    $mail_data["ticket"] = $created_ticket;
                                }
                                $mail_data["to"] = $data->sender;
                            }
                        }
                        $message->setFlag("Seen");
                    }
                }
                $client->disconnect();
                unset($cm);
            }
            catch(Exception $exception) {
                if(isset($message)) {
                    $message->setFlag("Seen");
                }

                $client->disconnect();
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Something went wrong while trying to process a message on the support mail!",
                    9,
                    $exception->getMessage() . " Line:" . $exception->getLine()
                );

                Log::emergency($exception->getMessage());
                return false;
            }
        }
        else {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong on connection of the support mail!",
                9,
                "Connection Exception!"
            );

            return false;
        }

        return true;
    }
}