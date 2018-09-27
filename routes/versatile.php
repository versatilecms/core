<?php

use Versatile\Core\Events\Routing;
use Versatile\Core\Events\RoutingAdmin;
use Versatile\Core\Events\RoutingAdminAfter;
use Versatile\Core\Events\RoutingAfter;
use Versatile\Core\Models\DataType;

/*
|--------------------------------------------------------------------------
| Versatile Routes
|--------------------------------------------------------------------------
|
| This file is where you may override any of the routes that are included
| with Versatile.
|
*/

Route::group([
    'as' => 'versatile.'
], function () {
    event(new Routing());

    $namespacePrefix = '\\' . config('versatile.controllers.namespace') . '\\';

    Route::get('login', [
        'uses' => $namespacePrefix . 'Auth\AuthController@login',
        'as' => 'login'
    ]);

    Route::post('login', [
        'uses' => $namespacePrefix . 'Auth\AuthController@postLogin',
        'as' => 'postlogin'
    ]);

    // Password Reset Routes...
    Route::get('password/reset', ['uses' => $namespacePrefix.'Auth\ForgotPasswordController@showLinkRequestForm', 'as' => 'password.request']);
    Route::post('password/email', ['uses' => $namespacePrefix.'Auth\ForgotPasswordController@sendResetLinkEmail', 'as' => 'password.email']);
    Route::get('password/reset/{token}', ['uses' => $namespacePrefix.'Auth\ResetPasswordController@showResetForm', 'as' => 'password.reset']);
    Route::post('password/reset', ['uses' => $namespacePrefix.'Auth\ResetPasswordController@reset', 'as' => 'password.reset.submit']);

    Route::post('users/impersonate/{originalId}', [
        'uses' => "{$namespacePrefix}UsersController@revertImpersonate",
        'as' => 'users.impersonate'
    ]);

    Route::group([
        'middleware' => 'admin.user'
    ], function () use ($namespacePrefix) {

        event(new RoutingAdmin());

        // Main Admin and Logout Route

        Route::get('/', [
            'uses' => $namespacePrefix . 'VersatileController@index',
            'as' => 'dashboard'
        ]);

        Route::post('logout', [
            'uses' => $namespacePrefix . 'VersatileController@logout',
            'as' => 'logout'
        ]);

        Route::post('upload', [
            'uses' => $namespacePrefix . 'VersatileController@upload',
            'as' => 'upload'
        ]);

        Route::get('profile', [
            'uses' => $namespacePrefix . 'VersatileController@profile',
            'as' => 'profile'
        ]);

        try {

            foreach (DataType::all() as $dataType) {

                $breadController = $namespacePrefix . 'BaseController';
                if (!empty($dataType->controller)) {
                    $breadController = $dataType->controller;
                }

                Route::get($dataType->slug . '/order', $breadController . '@order')->name($dataType->slug . '.order');
                Route::post($dataType->slug . '/order', $breadController . '@updateOrder')->name($dataType->slug . '.order');
                Route::resource($dataType->slug, $breadController);
            }

        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("Custom routes hasn't been configured because: " . $e->getMessage(), 1);
        } catch (\Exception $e) {
            // do nothing, might just be because table not yet migrated.
        }


        // Tests Scaffold
        Route::resource('scaffold', $namespacePrefix . 'UsersScaffoldController');

        // Role Routes
        Route::resource('roles', $namespacePrefix . 'RoleController');


        Route::get('users/impersonate/{userId}', [
            'uses' => "{$namespacePrefix}UsersController@impersonate",
            'as' => 'users.impersonate'
        ]);

        // Menu Routes
        Route::group([
            'as' => 'menus.',
            'prefix' => 'menus/{menu}',
        ], function () use ($namespacePrefix) {
            Route::get('builder', [
                'uses' => $namespacePrefix . 'MenuController@builder',
                'as' => 'builder'
            ]);

            Route::post('order', [
                'uses' => $namespacePrefix . 'MenuController@order_item',
                'as' => 'order'
            ]);

            Route::group([
                'as' => 'item.',
                'prefix' => 'item',
            ], function () use ($namespacePrefix) {
                Route::delete('{id}', [
                    'uses' => $namespacePrefix . 'MenuController@delete_menu',
                    'as' => 'destroy'
                ]);

                Route::post('/', [
                    'uses' => $namespacePrefix . 'MenuController@add_item',
                    'as' => 'add'
                ]);

                Route::put('/', [
                    'uses' => $namespacePrefix .'MenuController@update_item',
                    'as' => 'update'
                ]);
            });
        });

        // Settings
        Route::group([
            'as' => 'settings.',
            'prefix' => 'settings',
        ], function () use ($namespacePrefix) {
            Route::get('/', [
                'uses' => $namespacePrefix . 'SettingsController@index',
                'as' => 'index'
            ]);

            Route::post('/', [
                'uses' => $namespacePrefix . 'SettingsController@store',
                'as' => 'store'
            ]);

            Route::put('/', [
                'uses' => $namespacePrefix . 'SettingsController@update',
                'as' => 'update'
            ]);

            Route::delete('{id}', [
                'uses' => $namespacePrefix . 'SettingsController@delete',
                'as' => 'delete'
            ]);

            Route::get('{id}/move_up', [
                'uses' => $namespacePrefix . 'SettingsController@move_up',
                'as' => 'move_up'
            ]);

            Route::get('{id}/move_down', [
                'uses' => $namespacePrefix . 'SettingsController@move_down',
                'as' => 'move_down'
            ]);

            Route::get('{id}/delete_value', [
                'uses' => $namespacePrefix . 'SettingsController@delete_value',
                'as' => 'delete_value'
            ]);
        });

        // Admin Media
        Route::group([
            'as' => 'media.',
            'prefix' => 'media',
        ], function () use ($namespacePrefix) {
            Route::get('/', [
                'uses' => $namespacePrefix . 'MediaController@index',
                'as' => 'index'
            ]);

            Route::post('files', [
                'uses' => $namespacePrefix . 'MediaController@files',
                'as' => 'files'
            ]);

            Route::post('new_folder', [
                'uses' => $namespacePrefix . 'MediaController@new_folder',
                'as' => 'new_folder'
            ]);

            Route::post('delete_file_folder', [
                'uses' => $namespacePrefix . 'MediaController@delete_file_folder',
                'as' => 'delete_file_folder'
            ]);

            Route::post('directories', [
                'uses' => $namespacePrefix . 'MediaController@get_all_dirs',
                'as' => 'get_all_dirs'
            ]);

            Route::post('move_file', [
                'uses' => $namespacePrefix . 'MediaController@move_file',
                'as' => 'move_file'
            ]);

            Route::post('rename_file', [
                'uses' => $namespacePrefix . 'MediaController@rename_file',
                'as' => 'rename_file'
            ]);

            Route::post('upload', [
                'uses' => $namespacePrefix . 'MediaController@upload',
                'as' => 'upload'
            ]);

            Route::post('remove', [
                'uses' => $namespacePrefix . 'MediaController@remove',
                'as' => 'remove'
            ]);

            Route::post('crop', [
                'uses' => $namespacePrefix . 'MediaController@crop',
                'as' => 'crop'
            ]);
        });

        // BREAD Routes
        Route::group([
            'as' => 'bread.',
            'prefix' => 'bread',
        ], function () use ($namespacePrefix) {
            Route::get('/', [
                'uses' => $namespacePrefix . 'BreadController@index',
                'as' => 'index'
            ]);

            Route::get('{table}/create', [
                'uses' => $namespacePrefix . 'BreadController@create',
                'as' => 'create'
            ]);

            Route::post('/', [
                'uses' => $namespacePrefix . 'BreadController@store',
                'as' => 'store'
            ]);

            Route::get('{table}/edit', [
                'uses' => $namespacePrefix . 'BreadController@edit',
                'as' => 'edit'
            ]);

            Route::put('{id}', [
                'uses' => $namespacePrefix . 'BreadController@update',
                'as' => 'update'
            ]);

            Route::delete('{id}', [
                'uses' => $namespacePrefix . 'BreadController@destroy',
                'as' => 'delete'
            ]);

            Route::post('relationship', [
                'uses' => $namespacePrefix . 'BreadController@addRelationship',
                'as' => 'relationship'
            ]);

            Route::get('delete_relationship/{id}', [
                'uses' => $namespacePrefix . 'BreadController@deleteRelationship',
                'as' => 'delete_relationship'
            ]);
        });

        // Database Routes
        Route::resource('database', $namespacePrefix . 'DatabaseController');

        // Compass Routes
        Route::group([
            'as' => 'compass.',
            'prefix' => 'compass',
        ], function () use ($namespacePrefix) {
            Route::get('/', [
                'uses' => $namespacePrefix . 'CompassController@index',
                'as' => 'index'
            ]);

            Route::post('/', [
                'uses' => $namespacePrefix . 'CompassController@index',
                'as' => 'post'
            ]);
        });

        event(new RoutingAdminAfter());
    });

    event(new RoutingAfter());
});
