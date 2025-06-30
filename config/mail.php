<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send any email
    | messages sent by your application. Alternative mailers may be setup
    | and used as needed; however, this mailer will be used by default.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers to be used while
    | sending an e-mail. You will specify which one you are using for your
    | mailers below. You are free to add additional mailers as required.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses",
    |            "postmark", "log", "array"
    |
    */

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'getucon.com'),
            'port' => env('MAIL_PORT', 465),
            'encryption' => env('MAIL_ENCRYPTION', 'ssl'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'allow_self_signed' => true,
            'verify_peer' => false,
            'verify_peer_name' => false
        ],

        'ses' => [
            'transport' => 'ses',
        ],
        'getucon_mailer' => [
            'transport' => 'smtp',
            'host' => env('MAIL_GETUCON_HOST', 'smtp.strato.de'),
            'port' => env('MAIL_GETUCON_PORT', 465),
            'encryption' => env('MAIL_GETUCON_ENCRYPTION', 'SSL'),
            'username' => env('MAIL_GETUCON_USERNAME'),
            'password' => env('MAIL_GETUCON_PASSWORD'),
            'timeout' => null,
            "allow_self_signed" => true,
            "verify_peer" => false,
            "verify_peer_name" => false
        ],
        'ticket_robot' => [
            'transport' => 'smtp',
            'host' => env('MAIL_TICKET_ROBOT_HOST'),
            'port' => env('MAIL_TICKET_ROBOT_PORT', 587),
            'encryption' => env('MAIL_TICKET_ROBOT_ENCRYPTION', 'tls'),
            'username' => env('MAIL_TICKET_ROBOT_USERNAME'),
            'password' => env('MAIL_TICKET_ROBOT_PASSWORD'),
            'timeout' => null,
            "allow_self_signed" => true,
            "verify_peer" => false,
            "verify_peer_name" => false
        ],
        "guler_accounting"=>[
            'transport' => 'smtp',
            'host' => env('MAIL_GULER_ACCOUNTING_HOST'),
            'port' => env('MAIL_GULER_ACCOUNTING_PORT', 587),
            'encryption' => env('MAIL_GULER_ACCOUNTING_ENCRYPTION', 'tls'),
            'username' => env('MAIL_GULER_ACCOUNTING_USERNAME'),
            'password' => env('MAIL_GULER_ACCOUNTING_PASSWORD'),
            'timeout' => null,
            "allow_self_signed" => true,
            "verify_peer" => false,
            "verify_peer_name" => false
        ],
        "media_kit" => [
            "transport" => "smtp",
            "host" => env('MAIL_MEDIA_KIT_HOST', 'smtp.mailgun.org'),
            "port" => env('MAIL_MEDIA_KIT_PORT', 587),
            "encryption" => env('MAIL_MEDIA_KIT_ENCRYPTION', 'tls'),
            "username" => env('MAIL_MEDIA_KIT_USERNAME'),
            "password" => env('MAIL_MEDIA_KIT_PASSWORD'),
            "timeout" => null,
            "allow_self_signed" => true,
            "verify_peer" => false,
            "verify_peer_name" => false
        ],
        'sendmail' => [
            'transport' => 'sendmail',
            'path' => '/usr/sbin/sendmail -bs',
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
