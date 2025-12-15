<?php

return [
    'admin1' => [
        'firstname' => env('SEEDER_ADMIN1_FIRSTNAME', 'admin'),
        'lastname' => env('SEEDER_ADMIN1_LASTNAME', 'admin'),
        'username' => env('SEEDER_ADMIN1_USERNAME', 'admin'),
        'password' => env('SEEDER_ADMIN1_PASSWORD', 'password'),
        'email' => env('SEEDER_ADMIN1_EMAIL', 'admin@example.com'),
    ],
    'admin2' => [
        'firstname' => env('SEEDER_ADMIN2_FIRSTNAME', 'admin2'),
        'lastname' => env('SEEDER_ADMIN2_LASTNAME', 'admin2'),
        'username' => env('SEEDER_ADMIN2_USERNAME', 'admin2'),
        'password' => env('SEEDER_ADMIN2_PASSWORD', 'password'),
        'email' => env('SEEDER_ADMIN2_EMAIL', 'admin2@example.com'),
    ],
    'users' => [
        [
            'firstname' => 'test',
            'lastname' => 'test',
            'username' => 'test',
            'password' => env('SEEDER_USER_PASSWORD', 'password'),
            'email' => env('SEEDER_TEST_EMAIL', 'test@example.com'),
        ],
        [
            'firstname' => 'test2',
            'lastname' => 'test2',
            'username' => 'test2',
            'password' => env('SEEDER_USER_PASSWORD', 'password'),
            'email' => env('SEEDER_TEST2_EMAIL', 'test2@example.com'),
        ],
    ],
];
