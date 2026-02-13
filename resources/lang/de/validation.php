<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines (German)
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions.
    |
    */

    'accepted' => 'Das Feld :attribute muss akzeptiert werden.',
    'active_url' => ':attribute ist keine gültige URL.',
    'after' => ':attribute muss ein Datum nach :date sein.',
    'after_or_equal' => ':attribute muss ein Datum nach oder gleich :date sein.',
    'alpha' => ':attribute darf nur Buchstaben enthalten.',
    'alpha_dash' => ':attribute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'alpha_num' => ':attribute darf nur Buchstaben und Zahlen enthalten.',
    'array' => ':attribute muss ein Array sein.',
    'before' => ':attribute muss ein Datum vor :date sein.',
    'before_or_equal' => ':attribute muss ein Datum vor oder gleich :date sein.',
    'between' => [
        'numeric' => ':attribute muss zwischen :min und :max liegen.',
        'file' => ':attribute muss zwischen :min und :max Kilobytes liegen.',
        'string' => ':attribute muss zwischen :min und :max Zeichen liegen.',
        'array' => ':attribute muss zwischen :min und :max Elemente haben.',
    ],
    'boolean' => ':attribute muss wahr oder falsch sein.',
    'confirmed' => ':attribute Bestätigung stimmt nicht überein.',
    'current_password' => 'Das Passwort ist falsch.',
    'date' => ':attribute ist kein gültiges Datum.',
    'date_equals' => ':attribute muss ein Datum gleich :date sein.',
    'date_format' => ':attribute stimmt nicht mit dem Format :format überein.',
    'different' => ':attribute und :other müssen unterschiedlich sein.',
    'digits' => ':attribute muss :digits Ziffern haben.',
    'digits_between' => ':attribute muss zwischen :min und :max Ziffern liegen.',
    'dimensions' => ':attribute hat ungültige Bilddimensionen.',
    'distinct' => ':attribute hat einen doppelten Wert.',
    'email' => ':attribute muss eine gültige E-Mail-Adresse sein.',
    'ends_with' => ':attribute muss mit einem der folgenden Werte enden: :values.',
    'exists' => 'Die ausgewählte :attribute ist ungültig.',
    'file' => ':attribute muss eine Datei sein.',
    'filled' => ':attribute muss einen Wert haben.',
    'gt' => [
        'numeric' => ':attribute muss größer als :value sein.',
        'file' => ':attribute muss größer als :value Kilobytes sein.',
        'string' => ':attribute muss größer als :value Zeichen sein.',
        'array' => ':attribute muss mehr als :value Elemente haben.',
    ],
    'gte' => [
        'numeric' => ':attribute muss größer oder gleich :value sein.',
        'file' => ':attribute muss größer oder gleich :value Kilobytes sein.',
        'string' => ':attribute muss größer oder gleich :value Zeichen sein.',
        'array' => ':attribute muss :value Elemente oder mehr haben.',
    ],
    'image' => ':attribute muss ein Bild sein.',
    'in' => 'Die ausgewählte :attribute ist ungültig.',
    'in_array' => ':attribute existiert nicht in :other.',
    'integer' => ':attribute muss eine Ganzzahl sein.',
    'ip' => ':attribute muss eine gültige IP-Adresse sein.',
    'ipv4' => ':attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6' => ':attribute muss eine gültige IPv6-Adresse sein.',
    'json' => ':attribute muss ein gültiger JSON-String sein.',
    'lt' => [
        'numeric' => ':attribute muss kleiner als :value sein.',
        'file' => ':attribute muss kleiner als :value Kilobytes sein.',
        'string' => ':attribute muss kleiner als :value Zeichen sein.',
        'array' => ':attribute muss weniger als :value Elemente haben.',
    ],
    'lte' => [
        'numeric' => ':attribute muss kleiner oder gleich :value sein.',
        'file' => ':attribute muss kleiner oder gleich :value Kilobytes sein.',
        'string' => ':attribute muss kleiner oder gleich :value Zeichen sein.',
        'array' => ':attribute muss nicht mehr als :value Elemente haben.',
    ],
    'max' => [
        'numeric' => ':attribute darf nicht größer als :max sein.',
        'file' => ':attribute darf nicht größer als :max Kilobytes sein.',
        'string' => ':attribute darf nicht größer als :max Zeichen sein.',
        'array' => ':attribute darf nicht mehr als :max Elemente haben.',
    ],
    'mimes' => ':attribute muss eine Datei vom Typ sein: :values.',
    'mimetypes' => ':attribute muss eine Datei vom Typ sein: :values.',
    'min' => [
        'numeric' => ':attribute muss mindestens :min sein.',
        'file' => ':attribute muss mindestens :min Kilobytes sein.',
        'string' => ':attribute muss mindestens :min Zeichen sein.',
        'array' => ':attribute muss mindestens :min Elemente haben.',
    ],
    'multiple_of' => ':attribute muss ein Vielfaches von :value sein.',
    'not_in' => 'Die ausgewählte :attribute ist ungültig.',
    'not_regex' => ':attribute Format ist ungültig.',
    'numeric' => ':attribute muss eine Zahl sein.',
    'password' => 'Das Passwort ist falsch.',
    'present' => ':attribute muss vorhanden sein.',
    'regex' => ':attribute Format ist ungültig.',
    'required' => ':attribute ist erforderlich.',
    'required_if' => ':attribute ist erforderlich, wenn :other :value ist.',
    'required_unless' => ':attribute ist erforderlich, es sei denn :other ist :values.',
    'required_with' => ':attribute ist erforderlich, wenn :values vorhanden ist.',
    'required_with_all' => ':attribute ist erforderlich, wenn :values vorhanden ist.',
    'required_without' => ':attribute ist erforderlich, wenn :values nicht vorhanden ist.',
    'required_without_all' => ':attribute ist erforderlich, wenn keine :values vorhanden sind.',
    'same' => ':attribute und :other müssen übereinstimmen.',
    'size' => [
        'numeric' => ':attribute muss :size sein.',
        'file' => ':attribute muss :size Kilobytes sein.',
        'string' => ':attribute muss :size Zeichen sein.',
        'array' => ':attribute muss :size Elemente enthalten.',
    ],
    'starts_with' => ':attribute muss mit einem der folgenden Werte beginnen: :values.',
    'string' => ':attribute muss eine Zeichenkette sein.',
    'timezone' => ':attribute muss eine gültige Zeitzone sein.',
    'unique' => ':attribute existiert bereits.',
    'uploaded' => ':attribute konnte nicht hochgeladen werden.',
    'url' => ':attribute Format ist ungültig.',
    'uuid' => ':attribute muss eine gültige UUID sein.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly like E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'organization_name' => 'Organisationname',
        'organization_email' => 'Organisations-E-Mail',
        'street' => 'Straße',
        'city' => 'Stadt',
        'zip_code' => 'Postleitzahl',
        'representative_name' => 'Name des Vertreters',
        'representative_role' => 'Funktion des Vertreters',
    ],

    /*
    |--------------------------------------------------------------------------
    | Organization-Specific Validation Messages
    |--------------------------------------------------------------------------
    |
    | Custom messages for organization creation form validation
    |
    */

    'organization' => [
        'name' => [
            'required' => 'Der Organisationname ist erforderlich.',
            'unique' => 'Ein Organisation mit diesem Namen existiert bereits.',
        ],
        'email' => [
            'required' => 'Die E-Mail-Adresse ist erforderlich.',
            'invalid' => 'Die E-Mail-Adresse ist ungültig.',
            'unique' => 'Eine Organisation mit dieser E-Mail-Adresse existiert bereits.',
        ],
        'zip' => [
            'format' => 'Die Postleitzahl muss 5 Ziffern sein.',
        ],
        'gdpr' => [
            'required' => 'Sie müssen der DSGVO-konformen Verarbeitung Ihrer Daten zustimmen.',
        ],
        'terms' => [
            'required' => 'Sie müssen den Nutzungsbedingungen zustimmen.',
        ],
    ],

    'failed' => 'Validierung fehlgeschlagen.',

];
