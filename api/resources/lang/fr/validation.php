<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => 'Le champ <b>:attribute</b> doit être accepté.',
    'active_url'           => "Le champ <b>:attribute</b> n'est pas une URL valide.",
    'after'                => 'Le champ <b>:attribute</b> doit être une date postérieure au :date.',
    'alpha'                => 'Le champ <b>:attribute</b> doit seulement contenir des lettres.',
    'alpha_dash'           => 'Le champ <b>:attribute</b> doit seulement contenir des lettres, des chiffres et des tirets.',
    'alpha_num'            => 'Le champ <b>:attribute</b> doit seulement contenir des chiffres et des lettres.',
    'array'                => 'Le champ <b>:attribute</b> doit être un tableau.',
    'before'               => 'Le champ <b>:attribute</b> doit être une date antérieure au :date.',
    'between'              => [
        'numeric' => 'La valeur de <b>:attribute</b> doit être comprise entre :min et :max.',
        'file'    => 'La taille du fichier de <b>:attribute</b> doit être comprise entre :min et :max kilo-octets.',
        'string'  => 'Le texte <b>:attribute</b> doit contenir entre :min et :max caractères.',
        'array'   => 'Le tableau <b>:attribute</b> doit contenir entre :min et :max éléments.',
    ],
    'boolean'              => 'Le champ <b>:attribute</b> doit être vrai ou faux.',
    'confirmed'            => 'Le champ de confirmation <b>:attribute</b> ne correspond pas.',
    'date'                 => "Le champ <b>:attribute</b> n'est pas une date valide.",
    'date_format'          => 'Le champ <b>:attribute</b> ne correspond pas au format :format.',
    'different'            => 'Les champs <b>:attribute</b> et :other doivent être différents.',
    'digits'               => 'Le champ <b>:attribute</b> doit contenir :digits chiffres.',
    'digits_between'       => 'Le champ <b>:attribute</b> doit contenir entre :min et :max chiffres.',
    'dimensions'           => "La taille de l'image <b>:attribute</b> n'est pas conforme.",
    'distinct'             => 'Le champ <b>:attribute</b> a une valeur dupliquée.',
    'email'                => 'Le champ <b>:attribute</b> doit être une adresse e-mail valide.',
    'exists'               => 'Le champ <b>:attribute</b> sélectionné est invalide.',
    'filled'               => 'Le champ <b>:attribute</b> est obligatoire.',
    'image'                => 'Le champ <b>:attribute</b> doit être une image.',
    'in'                   => 'Le champ <b>:attribute</b> est invalide.',
    'in_array'             => 'Le champ <b>:attribute</b> n\'existe pas dans :other.',
    'integer'              => 'Le champ <b>:attribute</b> doit être un entier.',
    'ip'                   => 'Le champ <b>:attribute</b> doit être une adresse IP valide.',
    'json'                 => 'Le champ <b>:attribute</b> doit être un document JSON valide.',
    'max'                  => [
        'numeric' => 'La valeur de <b>:attribute</b> ne peut être supérieure à :max.',
        'file'    => 'La taille du fichier de <b>:attribute</b> ne peut pas dépasser :max kilo-octets.',
        'string'  => 'Le texte de <b>:attribute</b> ne peut contenir plus de :max caractères.',
        'array'   => 'Le tableau <b>:attribute</b> ne peut contenir plus de :max éléments.',
    ],
    'mimes'                => 'Le champ <b>:attribute</b> doit être un fichier de type : :values.',
    'min'                  => [
        'numeric' => 'La valeur de <b>:attribute</b> doit être supérieure à :min.',
        'file'    => 'La taille du fichier de <b>:attribute</b> doit être supérieure à :min kilo-octets.',
        'string'  => 'Le texte <b>:attribute</b> doit contenir au moins :min caractères.',
        'array'   => 'Le tableau <b>:attribute</b> doit contenir au moins :min éléments.',
    ],
    'not_in'               => "Le champ <b>:attribute</b> sélectionné n'est pas valide.",
    'numeric'              => 'Le champ <b>:attribute</b> doit contenir un nombre.',
    'present'              => 'Le champ <b>:attribute</b> doit être présent.',
    'regex'                => 'Le format du champ <b>:attribute</b> est invalide.',
    'required'             => 'Le champ <b>:attribute</b> est obligatoire.',
    'required_if'          => 'Le champ <b>:attribute</b> est obligatoire quand la valeur de :other est :value.',
    'required_unless'      => 'Le champ <b>:attribute</b> est obligatoire sauf si :other est :values.',
    'required_with'        => 'Le champ <b>:attribute</b> est obligatoire quand :values est présent.',
    'required_with_all'    => 'Le champ <b>:attribute</b> est obligatoire quand :values est présent.',
    'required_without'     => "Le champ <b>:attribute</b> est obligatoire quand :values n'est pas présent.",
    'required_without_all' => "Le champ <b>:attribute</b> est requis quand aucun de :values n'est présent.",
    'same'                 => 'Les champs <b>:attribute</b> et <b>:other</b> doivent être identiques.',
    'size'                 => [
        'numeric' => 'La valeur de <b>:attribute</b> doit être :size.',
        'file'    => 'La taille du fichier de <b>:attribute</b> doit être de :size kilo-octets.',
        'string'  => 'Le texte de <b>:attribute</b> doit contenir :size caractères.',
        'array'   => 'Le tableau <b>:attribute</b> doit contenir :size éléments.',
    ],
    'string'               => 'Le champ <b>:attribute</b> doit être une chaîne de caractères.',
    'timezone'             => 'Le champ <b>:attribute</b> doit être un fuseau horaire valide.',
    'unique'               => 'La valeur du champ <b>:attribute</b> est déjà utilisée.',
    'url'                  => "Le format de l'URL de <b>:attribute</b> n'est pas valide.",

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

    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes'           => [
        'name'                  => 'Nom',
        'username'              => 'Pseudo',
        'email'                 => 'Adresse E-mail',
        'firstname'             => 'Prénom',
        'lastname'              => 'Nom',
        'password'              => 'Mot de passe',
        'passwordConfirmation'  => 'Confirmation du mot de passe',
        'city'                  => 'Ville',
        'country'               => 'Pays',
        'address'               => 'Adresse',
        'phone'                 => 'Téléphone',
        'mobile'                => 'Portable',
        'age'                   => 'Age',
        'sex'                   => 'Sexe',
        'gender'                => 'Genre',
        'day'                   => 'Jour',
        'month'                 => 'Mois',
        'year'                  => 'Année',
        'hour'                  => 'Heure',
        'minute'                => 'Minute',
        'second'                => 'Seconde',
        'title'                 => 'Titre',
        'content'               => 'Contenu',
        'description'           => 'Description',
        'excerpt'               => 'Extrait',
        'date'                  => 'Date',
        'time'                  => 'Heure',
        'available'             => 'Disponible',
        'size'                  => 'Taille',
        'cgu'                   => 'Conditions d\'utilisation',
        'g-recaptcha-response'  => 'Captcha',
    ],

    'recaptcha' => 'Le captcha n\'est pas correcte.',

];
