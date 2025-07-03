<?php
// config/auth.php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'usuarios',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'usuarios',
        ],
        
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'usuarios',
        ],
        
        'profesor' => [
            'driver' => 'session',
            'provider' => 'profesores',
        ],
        
        'estudiante' => [
            'driver' => 'session',
            'provider' => 'estudiantes',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'usuarios' => [
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class,
        ],
        
        'profesores' => [
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class,
            'table' => 'usuario',
        ],
        
        'estudiantes' => [
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class,
            'table' => 'usuario',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'usuarios' => [
            'provider' => 'usuarios',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */
    'password_timeout' => 10800, // 3 horas
];