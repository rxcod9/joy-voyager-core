<?php

return [

    /*
     * The config_key for voyager-core package.
     */
    'config_key' => env('VOYAGER_CORE_CONFIG_KEY', 'joy-voyager'),

    /*
     * The route_prefix for voyager-core package.
     */
    'route_prefix' => env('VOYAGER_CORE_ROUTE_PREFIX', 'joy-voyager'),

    /*
    |--------------------------------------------------------------------------
    | Controllers config
    |--------------------------------------------------------------------------
    |
    | Here you can specify voyager controller settings
    |
    */

    'controllers' => [
        'namespace' => 'Joy\\VoyagerCore\\Http\\Controllers',
    ],
];
