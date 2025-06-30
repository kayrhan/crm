<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssignNewPersonnel {
    use Dispatchable, SerializesModels;

    public $user, $ticket, $authenticated_user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $ticket, $authenticated_user) {
        $this->user = $user;
        $this->ticket = $ticket;
        $this->authenticated_user = $authenticated_user;
    }
}
