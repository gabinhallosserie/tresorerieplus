<?php

return [
    'name' => 'Trésorerie Plus',

    'advisor' => [
        'name' => 'Patricia Vialaret',
        'phone' => '06 87 04 88 68',
        'phone_link' => '+33687048868',
        'email' => 'p.vialaret@tresorerie-plus.fr',
        'street' => '144 avenue Jean Jaurès',
        'city' => '12100 Millau',
        'address_line' => '144 avenue Jean Jaurès, 12100 Millau',
    ],

    'notification_email' => env('ADVISOR_NOTIFICATION_EMAIL', 'p.vialaret@tresorerie-plus.fr'),

    'calendar_feed_token' => env('CALENDAR_FEED_TOKEN'),

    'forecast_tool_url' => 'https://www.tresorerie-plus-prog.fr/Tresorerie_Plus_Prog/TRESORERIE_PLUS_PROG_WEB/FR/index.awp',

    'legal' => [
        'company' => 'SARL BCL',
        'capital' => '7 500 €',
        'rcs' => 'RCS Rodez',
        'siret' => '793 199 043 00019',
        'ape' => '6201Z',
        'address' => '144 avenue Jean Jaurès, 12100 Millau',
        'phone' => '05 81 63 05 25',
        'privacy_email' => 'assistance@tresorerie-plus.fr',
        'host' => 'OVH SAS – 2 rue Kellermann, 59100 Roubaix – 1007',
    ],

    // Real client testimonials only: the section stays hidden while this list is empty.
    'testimonials' => [
        // ['quote' => '...', 'author' => 'Prénom N.', 'role' => 'Artisan, Millau'],
    ],
];
