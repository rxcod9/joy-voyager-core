<?php

return [

    /*
     * The config_key for voyager-core package.
     */
    'config_key' => env('VOYAGER_CORE_CONFIG_KEY', 'joy-voyager-core'),

    /*
     * The route_prefix for voyager-core package.
     */
    'route_prefix' => env('VOYAGER_CORE_ROUTE_PREFIX', 'joy-voyager-core'),

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
