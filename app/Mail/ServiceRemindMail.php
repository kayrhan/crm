<?php

namespace App\Mail;

use App\ServiceTypes;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServiceRemindMail extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;
    public $service;

    public function __construct($service) {
        $this->service = $service;
    }

    public function build() {
        $user = User::query()->find($this->service->added_by);
        $user = $user->first_name . " " . $user->surname;
        $service_type = ServiceTypes::query()->find($this->service->service_type)->name;
        return $this->from(env("MAIL_USERNAME", "crm@getucon.com"))->subject("Service Reminder | ". $this->service->provider . " | ". $service_type)->view("emails.services.expire", [
            "service" => $this->service,
            "user" => $user,
            "service_type" => $service_type
        ]);
    }
}