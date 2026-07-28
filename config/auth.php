<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'sys_users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'sys_users',
        ],
    ],

    // PENTING: provider diarahkan ke model App\Models\SysUser (bukan App\Models\User bawaan),
    // karena ERMIS memakai tabel sys_users dengan UUID primary key.
    'providers' => [
        'sys_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\SysUser::class,
        ],
    ],

    'passwords' => [
        'sys_users' => [
            'provider' => 'sys_users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
