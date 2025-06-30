<?php

namespace App\Listeners;

use App\Discussion;
use App\Events\PackageCreated;
use App\Helpers\Helper;
use App\Ticket;
use Exception;

class CommentPackageCreation {
    public function __construct() {
        //
    }

    public function handle(PackageCreated $event): void {
        try {
            $ticket = Ticket::query()->find($event->package->ticket_id);

            if($ticket) {
                $comment = new Discussion();
                $comment->message = "A package of this ticket has just created. You can check it via: " .  route("package-tracking.update", [$event->package->id]);
                $comment->user_id = $event->package->updated_user_id;
                $comment->is_private = 1; // Müşteri görmesin diye gizli yapıyoruz.
                $comment->ticket_id = $ticket->id;
                $comment->org_id = $ticket->org_id;
                $comment->save();
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to comment a package's update!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }
}