<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Authentication
    |--------------------------------------------------------------------------
    |
    | For login to http://yourdomain/admin, be sure to change the password.
    |
     */
    'user'               => 'admin',
    'password'           => 'secret',

    /*
    |--------------------------------------------------------------------------
    | Facebook Settings
    |--------------------------------------------------------------------------
    |
    | To make the application works, you have to create both the Facebook Page
    | and Facebook App by your own. You also need to obtain a Page access token
    | which should never expired.
    |
    | To create Facebook Page see:
    |   https://www.facebook.com/pages/create/
    |
    | To create Facebook App see:
    |   https://developers.facebook.com/
    |
    | To obtain a Page access token, follow the readme guide:
    |   https://github.com/kxgen/kangxi-anonymous-publisher/blob/master/readme.md
    |
     */
    'fb_app_setting'     => [
        'app_id'                => '',
        'app_secret'            => '',
        'default_graph_version' => 'v2.5',
    ],
    'fb_page_token'      => '',

    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA
    |--------------------------------------------------------------------------
    |
    | To avoid abuse and spam, we use Google reCAPTCHA service to verify guest.
    | For more informations see:
    |   https://www.google.com/recaptcha/intro/index.html
    |
     */
    'recaptcha_key'      => '',
    'recaptcha_secret'   => '',

];
