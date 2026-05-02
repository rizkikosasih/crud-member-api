<?php

return [
    'defaults' => [
        'password' => env('USER_DEFAULT_PASSWORD'),
        'role' => env('USER_DEFAULT_ROLE', 'staff'),
    ],
];
