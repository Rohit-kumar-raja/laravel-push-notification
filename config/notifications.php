<?php

return [

    'default' => env('MAIL_DRIVER', 'mail'),

    'channels' => [
        'webpush' => [
            'driver' => 'webpush',
        ],
    ],

];
