<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'paths' => [
        resource_path('views'),
        app_path('Modules/Brands/Core/Views'),
        app_path('Modules/Aggregators/Core/Views'),
        app_path('Modules/Fops/Core/Views'),
        app_path('Modules/Advs/Core/Views'),
        app_path('Modules/Operators/Core/Views'),
        app_path('Modules/Clients/Core/Views'),
        app_path('Modules/Clients/Payments/Core/Views'),
        app_path('Modules/Postru/Core/Views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

];
