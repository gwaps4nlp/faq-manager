<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the front-office routes.
    |
    */
    'front-route' => [
        'prefix' => 'faq',
        'middleware' => ['web'],
    ],
    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the back-office routes.
    |
    */
    'back-route' => [
        'prefix' => 'faq',
        'middleware' => ['web','auth','admin'],
    ],

);
