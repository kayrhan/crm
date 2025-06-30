<?php

namespace App\Providers;

use App\Events\AssignNewPersonnel;
use App\Events\PackageCreated;
use App\Events\PackageUpdated;
use App\Listeners\CommentPackageCreation;
use App\Listeners\CommentPackageUpdate;
use App\Listeners\MailSentListener;
use App\Listeners\SendAssignmentEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;

class EventServiceProvider extends ServiceProvider {
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class
        ],
        MessageSent::class => [
          MailSentListener::class
        ],
        AssignNewPersonnel::class => [
            SendAssignmentEmail::class
        ],
        PackageCreated::class => [
            CommentPackageCreation::class
        ],
        PackageUpdated::class => [
            CommentPackageUpdate::class
        ]
    ];

    public function boot() {
        //
    }
}