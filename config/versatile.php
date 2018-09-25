<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User config
    |--------------------------------------------------------------------------
    |
    | Here you can specify versatile user configs
    |
    */

    'user' => [
        'add_default_role_on_register' => true,
        'default_role'                 => 'user',
        'namespace'                    => null,
        'default_avatar'               => 'users/default.png',
        'redirect'                     => '/admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Controllers config
    |--------------------------------------------------------------------------
    |
    | Here you can specify versatile controller settings
    |
    */

    'controllers' => [
        'namespace' => 'Versatile\\Core\\Http\\Controllers',
    ],

    /*
    |--------------------------------------------------------------------------
    | Models config
    |--------------------------------------------------------------------------
    |
    | Here you can specify default model namespace when creating BREAD.
    | Must include trailing backslashes. If not defined the default application
    | namespace will be used.
    |
    */

    'models' => [
        'namespace' => 'App\\',
    ],

    /*
    |--------------------------------------------------------------------------
    | Path to the Versatile Assets
    |--------------------------------------------------------------------------
    |
    | Here you can specify the location of the versatile assets path
    |
    */

    'assets_path' => '/vendor/versatilecms/core/assets',

    /*
    |--------------------------------------------------------------------------
    | Storage Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify attributes related to your application file system
    |
    */

    'storage' => [
        'disk' => 'public',
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Manager
    |--------------------------------------------------------------------------
    |
    | Here you can specify if media manager can show hidden files like(.gitignore)
    |
    */

    'hidden_files' => false,

    /*
    |--------------------------------------------------------------------------
    | Database Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify versatile database settings
    |
    */

    'database' => [
        'tables' => [
            'hidden' => [
                'migrations',
                'data_rows',
                'data_types',
                'menu_items',
                'password_resets',
                'permission_role',
                'settings'
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Multilingual configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify if you want Versatile to ship with support for
    | multilingual and what locales are enabled.
    |
    */

    'multilingual' => [
        /*
         * Set whether or not the multilingual is supported by the BREAD input.
         */
        'enabled' => false,

        /*
         * Set whether or not the admin layout default is RTL.
         */
        'rtl' => false,

        /*
         * Select default language
         */
        'default' => 'pt_br',

        /*
         * Select languages that are supported.
         */
        'locales' => [
            'en',
            'pt_br',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard config
    |--------------------------------------------------------------------------
    |
    | Here you can modify some aspects of your dashboard
    |
    */

    'dashboard' => [
        // Add custom list items to navbar's dropdown
        'navbar_items' => [
            'profile' => [
                'route'      => 'versatile.profile',
                'classes'    => 'class-full-of-rum',
                'icon_class' => 'versatile-person',
            ],
            'home' => [
                'route'        => '/',
                'icon_class'   => 'versatile-home',
                'target_blank' => true,
            ],
            'logout' => [
                'route'      => 'versatile.logout',
                'icon_class' => 'versatile-power',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Automatic Procedures
    |--------------------------------------------------------------------------
    |
    | When a change happens on Versatile, we can automate some routines.
    |
    */

    'bread' => [
        // When a BREAD is added, create the Menu item using the BREAD properties.
        'add_menu_item' => true,

        // which menu add item to
        'default_menu' => 'admin',

        // When a BREAD is added, create the related Permission.
        'add_permission' => true,

        // which role add premissions to
        'default_role' => 'admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Generic Config
    |--------------------------------------------------------------------------
    |
    | Here you change some of the Versatile UI settings.
    |
    */

    'primary_color' => '#22A7F0',

    'show_dev_tips' => true, // Show development tip "How To Use:" in Menu and Settings

    // Here you can specify additional assets you would like to be included in the master.blade
    'additional_css' => [
        //'css/custom.css',
    ],

    'additional_js' => [
        //'js/custom.js',
    ],

    'googlemaps' => [
         'key'    => env('GOOGLE_MAPS_KEY', ''),
         'center' => [
             'lat' => env('GOOGLE_MAPS_DEFAULT_CENTER_LAT', '32.715738'),
             'lng' => env('GOOGLE_MAPS_DEFAULT_CENTER_LNG', '-117.161084'),
         ],
         'zoom' => env('GOOGLE_MAPS_DEFAULT_ZOOM', 11),
     ],

     'media' => [
         'keep_filename' => false,
     ],

     'modules' => [
        /*
        |--------------------------------------------------------------------------
        | Scan Path
        |--------------------------------------------------------------------------
        |
        | Here you define which folder will be scanned. By default will scan vendor
        | directory. This is useful if you host the package in packagist website.
        |
        */
        'scan' => [
            'enabled' => false,
            'paths' => [
                base_path('Themes'),
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Caching
        |--------------------------------------------------------------------------
        |
        | Here is the config for setting up caching feature.
        |
        */
        'cache' => [
            'enabled' => false,
            'key' => 'versatile-modules',
            'lifetime' => 60,
        ]
     ]
];
