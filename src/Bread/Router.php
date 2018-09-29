<?php

namespace Versatile\Core\Bread;

use Route;

class Router
{
    /**
     * @var array
     */
    protected $extraRoutes = [];

    /**
     * @var string null
     */
    protected $name = null;

    /**
     * @var string null
     */
    protected $controller = null;

    /**
     * @var null|array
     */
    protected $options = null;

    public function __construct($name, $controller, $options)
    {
        $this->name = $name;
        $this->controller = $controller;
        $this->options = $options;

        // BREAD routes for core features

        Route::get($this->name . '/order', [
            'as' => $this->name . '.order',
            'uses' => $this->controller . '@order',
        ]);

        Route::post($this->name . '/order', [
            'as' => $this->name . '.order',
            'uses' => $this->controller . '@updateOrder',
        ]);
    }

    /**
     * The BREAD resource needs to be registered after all the other routes.
     */
    public function __destruct()
    {
        $optionsWithDefaultRouteNames = array_merge([
            'names' => [
                'index'     => $this->name.'.index',
                'create'    => $this->name.'.create',
                'store'     => $this->name.'.store',
                'edit'      => $this->name.'.edit',
                'update'    => $this->name.'.update',
                'show'      => $this->name.'.show',
                'destroy'   => $this->name.'.destroy',
            ],
        ], $this->options);

        Route::resource($this->name, $this->controller, $optionsWithDefaultRouteNames);
    }

    /**
     * Call other methods in this class, that register extra routes.
     *
     * @param $injectables
     * @throws \ReflectionException
     */
    public function with($injectables)
    {
        if (is_string($injectables)) {
            $this->extraRoutes[] = 'with'.ucwords($injectables);
        } elseif (is_array($injectables)) {
            foreach ($injectables as $injectable) {
                $this->extraRoutes[] = 'with'.ucwords($injectable);
            }
        } else {
            $reflection = new \ReflectionFunction($injectables);

            if ($reflection->isClosure()) {
                $this->extraRoutes[] = $injectables;
            }
        }

        return $this->registerExtraRoutes();
    }


    /**
     * Unregister a route
     *
     * @param $injectables
     */
     public function without($injectables) {
     }

    /**
     * Register the routes that were passed using the "with" syntax.
     */
    private function registerExtraRoutes()
    {
        foreach ($this->extraRoutes as $route) {
            if (is_string($route)) {
                $this->{$route}();
            } else {
                $route();
            }
        }
    }

    public function __call($method, $parameters = null)
    {
        if (method_exists($this, $method)) {
            $this->{$method}($parameters);
        }
    }
}