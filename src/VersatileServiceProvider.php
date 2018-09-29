<?php

namespace Versatile\Core;

use App;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;

use Intervention\Image\ImageServiceProvider;
use Larapack\DoctrineSupport\DoctrineSupportServiceProvider;
use Arrilot\Widgets\ServiceProvider as WidgetServiceProvider;

use Versatile\Core\Events\FieldsRegistered;
use Versatile\Core\Facades\Versatile as VersatileFacade;
use Versatile\Core\Facades\Actions as ActionsFacade;
use Versatile\Core\Facades\Fields as FieldsFacade;
use Versatile\Core\Facades\Widgets as WidgetsFacade;
use Versatile\Core\Facades\Filters as FiltersFacade;

use Versatile\Core\Components\Actions\Actions;
use Versatile\Core\Components\Fields\Fields;
use Versatile\Core\Components\Widgets\Widgets;
use Versatile\Core\Components\Filters\Filters;

use Versatile\Core\Components\Fields\After\DescriptionHandler;
use Versatile\Core\Http\Middleware\VersatileAdminMiddleware;
use Versatile\Core\Http\Middleware\VersatileGuestMiddleware;

use Versatile\Core\Providers\VersatileEventServiceProvider;
use Versatile\Core\Translator\Collection as TranslatorCollection;

class VersatileServiceProvider extends ServiceProvider
{
    /**
     * Our root directory for this package to make traversal easier
     */
    protected $packagePath = __DIR__ . '/../';

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(VersatileEventServiceProvider::class);
        $this->app->register(WidgetServiceProvider::class);
        $this->app->register(ImageServiceProvider::class);
        $this->app->register(DoctrineSupportServiceProvider::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('Versatile', VersatileFacade::class);
        $loader->alias('Actions', ActionsFacade::class);
        $loader->alias('Fields', FieldsFacade::class);
        $loader->alias('Widgets', WidgetsFacade::class);
        $loader->alias('Filters', FiltersFacade::class);

        $this->app->singleton('versatile', function () {
            return new Versatile();
        });

        $this->app->singleton('actions', function () {
            return new Actions();
        });

        $this->app->singleton('fields', function () {
            return new Fields();
        });

        $this->app->singleton('widgets', function () {
            return new Widgets();
        });

        $this->app->singleton('filters', function () {
            return new Filters();
        });

        $this->loadHelpers();

        $this->registerAlertComponents();
        $this->registerFields();

        $this->registerConfigs();

        if ($this->app->runningInConsole()) {
            $this->registerPublishableResources();
            $this->registerConsoleCommands();
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     * @param Dispatcher $event
     */
    public function boot(Router $router, Dispatcher $event)
    {
        $router->aliasMiddleware('admin.user', VersatileAdminMiddleware::class);

        $router->aliasMiddleware('versatile.guest', VersatileGuestMiddleware::class);

        $this->loadViewsFrom($this->packagePath . 'resources/views', 'versatile');

        $this->loadTranslationsFrom($this->packagePath . 'resources/lang', 'versatile');

        $this->loadMigrationsFrom($this->packagePath . 'database/migrations');

        $this->registerPolicies();

        $this->registerViewComposers();

        $event->listen('versatile.alerts.collecting', function () {
            $this->addStorageSymlinkAlert();
        });

        $this->bootTranslatorCollectionMacros();
    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        $policies = config('versatile.policies');

        foreach ($policies as $class => $policy) {
            Gate::policy($class, $policy);
        }
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Register view composers.
     */
    protected function registerViewComposers()
    {
        // Register alerts
        View::composer('versatile::*', function ($view) {
            $view->with('alerts', VersatileFacade::alerts());
        });
    }

    /**
     * Add storage symlink alert.
     */
    protected function addStorageSymlinkAlert()
    {
        if (app('router')->current() !== null) {
            $currentRouteAction = app('router')->current()->getAction();
        } else {
            $currentRouteAction = null;
        }
        
        $routeName = is_array($currentRouteAction) ? array_get($currentRouteAction, 'as') : null;

        if ($routeName != 'versatile.dashboard') {
            return;
        }

        $storage_disk = (!empty(config('versatile.storage.disk'))) ? config('versatile.storage.disk') : 'public';

        if (request()->has('fix-missing-storage-symlink')) {
            if (file_exists(public_path('storage'))) {
                if (@readlink(public_path('storage')) == public_path('storage')) {
                    rename(public_path('storage'), 'storage_old');
                }
            }

            if (!file_exists(public_path('storage'))) {
                $this->fixMissingStorageSymlink();
            }
        } elseif ($storage_disk == 'public') {
            if (!file_exists(public_path('storage')) || @readlink(public_path('storage')) == public_path('storage')) {
                $alert = (new Alert('missing-storage-symlink', 'warning'))
                    ->title(__('versatile::error.symlink_missing_title'))
                    ->text(__('versatile::error.symlink_missing_text'))
                    ->button(__('versatile::error.symlink_missing_button'), '?fix-missing-storage-symlink=1');
                VersatileFacade::addAlert($alert);
            }
        }
    }

    protected function fixMissingStorageSymlink()
    {
        app('files')->link(storage_path('app/public'), public_path('storage'));

        if (file_exists(public_path('storage'))) {
            $alert = (new Alert('fixed-missing-storage-symlink', 'success'))
                ->title(__('versatile::error.symlink_created_title'))
                ->text(__('versatile::error.symlink_created_text'));
        } else {
            $alert = (new Alert('failed-fixing-missing-storage-symlink', 'danger'))
                ->title(__('versatile::error.symlink_failed_title'))
                ->text(__('versatile::error.symlink_failed_text'));
        }

        VersatileFacade::addAlert($alert);
    }

    /**
     * Register alert components.
     */
    protected function registerAlertComponents()
    {
        $components = ['title', 'text', 'button'];

        foreach ($components as $component) {
            $class = 'Versatile\\Core\\Components\\Alert\\'.ucfirst(camel_case($component)).'Component';
            $this->app->bind("versatile.alert.components.{$component}", $class);
        }
    }

    protected function bootTranslatorCollectionMacros()
    {
        Collection::macro('translate', function () {
            $transtors = [];

            foreach ($this->all() as $item) {
                $transtors[] = call_user_func_array([$item, 'translate'], func_get_args());
            }

            return new TranslatorCollection($transtors);
        });
    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $publishable = [
            'versatile_assets' => [
                $this->packagePath . 'publishable/assets/' => public_path(config('versatile.assets_path')),
            ],
            'dummy_content' => [
                $this->packagePath . 'publishable/dummy_content/' => storage_path('app/public'),
            ],
            'config' => [
                $this->packagePath . 'config/versatile.php' => config_path('versatile.php'),
            ]
        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    public function registerConfigs()
    {
        $this->mergeConfigFrom(
            $this->packagePath . 'config/versatile.php', 'versatile'
        );
    }

    protected function registerFields()
    {
        $fields = [
            "CheckboxHandler",
            "ColorHandler",
            "DateHandler",
            "FileHandler",
            "ImageHandler",
            "MultipleImagesHandler",
            "NumberHandler",
            "PasswordHandler",
            "RadioBtnHandler",
            "RichTextBoxHandler",
            "CodeEditorHandler",
            "MarkdownEditorHandler",
            "SelectDropdownHandler",
            "SelectMultipleHandler",
            "TextHandler",
            "TextAreaHandler",
            "TimeHandler",
            "TimestampHandler",
            "HiddenHandler",
            "CoordinatesHandler"
        ];

        foreach ($fields as $handler) {
            FieldsFacade::addFormField("Versatile\\Core\\Components\\Fields\\Handlers\\{$handler}");
        }

        FieldsFacade::addAfterFormField(DescriptionHandler::class);
        event(new FieldsRegistered($fields));
    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(Commands\InstallCommand::class);
        $this->commands(Commands\ControllersCommand::class);
        $this->commands(Commands\AdminCommand::class);
        $this->commands(Commands\BreadGeneratorCommand::class);
        $this->commands(Commands\PermissionsCommand::class);
        $this->commands(Commands\MakeModelCommand::class);

        if (App::environment('local')) {
            $this->commands(Commands\DropTablesCommand::class);
        }
    }
}
