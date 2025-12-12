<?php

use Laravel\Fortify\Features;

return [
    'guard' => 'web',

    'passwords' => 'users',

    'username' => 'username',

    'email' => 'email',

    'home' => '/decks',

    'prefix' => '',

    'domain' => null,

    'path' => '',

    'middleware' => ['web'],

    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
    ],

    'views' => true,

    'features' => [
        Features::twoFactorAuthentication(['confirm' => true, 'confirmPassword' => true]),
    ],
];
