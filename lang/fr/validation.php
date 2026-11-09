<?php

return [
    'accepted' => 'Vous devez accepter :attribute.',
    'after' => 'Le champ :attribute doit être une date postérieure au :date.',
    'after_or_equal' => 'Le champ :attribute doit être une date postérieure ou égale au :date.',
    'array' => 'Le champ :attribute doit être un tableau.',
    'before' => 'Le champ :attribute doit être une date antérieure au :date.',
    'boolean' => 'Le champ :attribute doit être vrai ou faux.',
    'date' => 'Le champ :attribute n\'est pas une date valide.',
    'date_format' => 'Le champ :attribute ne correspond pas au format :format.',
    'email' => 'Le champ :attribute doit être une adresse e-mail valide.',
    'in' => 'La valeur du champ :attribute est invalide.',
    'integer' => 'Le champ :attribute doit être un nombre entier.',
    'max' => [
        'array' => 'Le champ :attribute ne doit pas contenir plus de :max éléments.',
        'numeric' => 'Le champ :attribute ne doit pas dépasser :max.',
        'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
    ],
    'min' => [
        'array' => 'Le champ :attribute doit contenir au moins :min éléments.',
        'numeric' => 'Le champ :attribute doit être supérieur ou égal à :min.',
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'regex' => 'Le format du champ :attribute est invalide.',
    'required' => 'Le champ :attribute est obligatoire.',
    'required_with' => 'Le champ :attribute est obligatoire quand :values est renseigné.',
    'string' => 'Le champ :attribute doit être une chaîne de caractères.',

    'custom' => [
        'consent' => [
            'accepted' => 'Merci d\'accepter le traitement de vos données pour que nous puissions vous répondre.',
        ],
    ],

    'attributes' => [
        'name' => 'nom',
        'email' => 'adresse e-mail',
        'phone' => 'téléphone',
        'company' => 'entreprise',
        'activity' => 'activité',
        'subject' => 'objet',
        'message' => 'message',
        'mode' => 'type de rendez-vous',
        'starts_at' => 'créneau',
        'password' => 'mot de passe',
        'slot_minutes' => 'durée d\'un rendez-vous',
        'buffer_minutes' => 'pause entre deux rendez-vous',
        'min_notice_hours' => 'délai de prévenance',
        'max_days_ahead' => 'horizon de réservation',
        'starts_on' => 'date de début',
        'ends_on' => 'date de fin',
        'reason' => 'motif',
    ],
];
