<?php

namespace Versatile\Core\Support;

use Countable;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

use Versatile\Core\Contracts\ModuleInterface;
use Versatile\Core\Contracts\ModulesRepositoryInterface;
use Versatile\Core\Exceptions\ModuleNotFoundException;

abstract class ModuleRepository implements ModulesRepositoryInterface, Countable
{
    use Macroable;

    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The module path.
     *
     * @var string|null
     */
    protected $path;

    /**
     * The scanned paths.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * @var string
     */
    protected $jsonFile = 'module.json';

    /**
     * @var string
     */
    protected $stubPath;

    /**
     * The constructor.
     *
     * @param Container $app
     * @param string|null $path
     */
    public function __construct(Container $app, $path = null)
    {
        $this->app = $app;
        $this->path = $path;
    }

    abstract protected function createModule($name);

    /**
     * Add other module location.
     *
     * @param string $path
     *
     * @return $this
     */
    public function addLocation($path)
    {
        $this->paths[] = $path;

        return $this;
    }

    /**
     * Get all additional paths.
     *
     * @return array
     */
    public function getPaths() : array
    {
        return $this->paths;
    }

    /**
     * Get scanned modules paths.
     *
     * @return array
     */
    public function getScanPaths() : array
    {
        $paths = $this->paths;

        $paths[] = $this->getPath();

        if ($this->config('scan.enabled')) {
            $paths = array_merge($paths, $this->config('scan.paths'));
        }

        $paths = array_map(function ($path) {
            return ends_with($path, '/*') ? $path : str_finish($path, '/*');
        }, $paths);

        return $paths;
    }

    /**
     * Get & scan all modules.
     *
     * @return Collection
     * @throws \Exception
     */
    public function scan() : Collection
    {
        $paths = $this->getScanPaths();

        $modules = [];

        foreach ($paths as $key => $path) {
            $manifests = $this->app['files']->glob("{$path}/{$this->jsonFile}");

            is_array($manifests) || $manifests = [];

            foreach ($manifests as $manifest) {
                $name = Json::make($manifest)->get('folder');
                $modules[$name] = $this->createModule($name);
            }
        }

        return new Collection($modules);
    }

    /**
     * Get all modules.
     *
     * @return Collection
     * @throws \Exception
     */
    public function all() : Collection
    {
        if (!$this->config('cache.enabled')) {
            return $this->scan();
        }

        return $this->formatCached($this->getCached());
    }

    /**
     * Format the cached data as array of modules.
     *
     * @param array $cached
     * @return Collection
     */
    protected function formatCached($cached) : Collection
    {
        $modules = [];

        foreach ($cached as $name => $module) {
            $path = $module["path"];

            $modules[$name] = $this->createModule($this->app, $name, $path);
        }

        return new Collection($modules);
    }

    /**
     * Get cached modules.
     *
     * @return Collection
     */
    public function getCached()
    {
        return $this->app['cache']->remember($this->config('cache.key'), $this->config('cache.lifetime'), function () {
            return $this->scan();
        });
    }

    /**
     * Get modules by status.
     *
     * @param int $status
     * @return Collection
     * @throws \Exception
     */
    public function getByStatus($status) : Collection
    {
        $modules = [];

        foreach ($this->all() as $name => $module) {
            if ($module->isStatus($status)) {
                $modules[$name] = $module;
            }
        }

        return new Collection($modules);
    }

    /**
     * Determine whether the given module exist.
     *
     * @param $name
     * @return bool
     * @throws \Exception
     */
    public function has($name) : bool
    {
        return array_key_exists($name, $this->all());
    }

    /**
     * Get list of enabled modules.
     *
     * @return Collection
     * @throws \Exception
     */
    public function allEnabled() : Collection
    {
        return $this->getByStatus(1);
    }

    /**
     * Get list of disabled modules.
     *
     * @return Collection
     * @throws \Exception
     */
    public function allDisabled() : Collection
    {
        return $this->getByStatus(0);
    }

    /**
     * Get count from all modules.
     *
     * @return int
     * @throws \Exception
     */
    public function count() : int
    {
        return count($this->all());
    }

    /**
     * Get all ordered modules.
     *
     * @param string $direction
     * @return Collection
     * @throws \Exception
     */
    public function getOrdered($direction = 'asc') : Collection
    {
        $modules = $this->allEnabled();

        uasort($modules, function (Module $a, Module $b) use ($direction) {
            if ($a->order == $b->order) {
                return 0;
            }

            if ($direction == 'desc') {
                return $a->order < $b->order ? 1 : -1;
            }

            return $a->order > $b->order ? 1 : -1;
        });

        return $modules;
    }

    /**
     * Get a module path.
     *
     * @return string
     */
    public function getPath() : string
    {
        return $this->path ?: $this->config('paths.modules', base_path('Modules'));
    }

    /**
     * Find a specific module.
     *
     * @param $folder
     * @return mixed|void
     * @throws \Exception
     */

    /**
     * @param $folder
     * @return ModuleInterface|null
     * @throws \Exception
     */
    public function find($folder)
    {
        foreach ($this->all() as $module) {
            if ($module->folder === strtolower($folder)) {
                return $module;
            }
        }

        return null;
    }

    /**
     * Find all modules that are required by a module. If the module cannot be found, throw an exception.
     *
     * @param $folder
     * @return Collection
     * @throws ModuleNotFoundException
     */
    public function findRequirements($folder) : Collection
    {
        $requirements = [];

        $module = $this->findOrFail($folder);

        foreach ($module->getRequires() as $folder) {
            $requirements[] = $this->find($folder);
        }

        return new Collection($requirements);
    }

    /**
     * Find a specific module, if there return that, otherwise throw exception.
     *
     * @param $folder
     * @return ModuleInterface
     * @throws ModuleNotFoundException
     */
    public function findOrFail($folder)
    {
        $module = $this->find($folder);

        if ($module !== null) {
            return $module;
        }

        throw new ModuleNotFoundException("Module [{$folder}] does not exist!");
    }

    /**
     * Get all modules as laravel collection instance.
     *
     * @param $status
     * @return Collection
     * @throws \Exception
     */
    public function collections($status = 1) : Collection
    {
        return new Collection($this->getByStatus($status));
    }

    /**
     * Get module path for a specific module.
     *
     * @param $module
     * @return string
     */
    public function getModulePath($module)
    {
        try {
            return $this->findOrFail($module)->getPath() . '/';
        } catch (ModuleNotFoundException $e) {
            return $this->getPath() . '/' . Str::studly($module) . '/';
        }
    }

    /**
     * Get a specific config data from a configuration file.
     *
     * @param $key
     *
     * @param null $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->app['config']->get('versatile.modules.' . $key, $default);
    }

    /**
     * Get storage path for module used.
     *
     * @return string
     */
    public function getUsedStoragePath() : string
    {
        $directory = storage_path('app/modules');
        if ($this->app['files']->exists($directory) === false) {
            $this->app['files']->makeDirectory($directory, 0777, true);
        }

        $path = storage_path('app/modules/modules.used');
        if (!$this->app['files']->exists($path)) {
            $this->app['files']->put($path, '');
        }

        return $path;
    }

    /**
     * Set module used for cli session.
     *
     * @param $name
     * @throws ModuleNotFoundException
     */
    public function setUsed($name)
    {
        $module = $this->findOrFail($name);

        $this->app['files']->put($this->getUsedStoragePath(), $module);
    }

    /**
     * Forget the module used for cli session.
     */
    public function forgetUsed()
    {
        if ($this->app['files']->exists($this->getUsedStoragePath())) {
            $this->app['files']->delete($this->getUsedStoragePath());
        }
    }

    /**
     * Get module used for cli session.
     *
     * @return string
     * @throws ModuleNotFoundException
     */
    public function getUsedNow() : string
    {
        return $this->findOrFail($this->app['files']->get($this->getUsedStoragePath()));
    }

    /**
     * Get laravel filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFiles()
    {
        return $this->app['files'];
    }

    /**
     * Determine whether the given module is activated.
     *
     * @param string $name
     * @return bool
     * @throws ModuleNotFoundException
     */
    public function enabled($name) : bool
    {
        return $this->findOrFail($name)->enabled();
    }

    /**
     * Determine whether the given module is not activated.
     * @param string $name
     * @return bool
     * @throws ModuleNotFoundException
     */
    public function disabled($name) : bool
    {
        return !$this->enabled($name);
    }

    /**
     * Enabling a specific module.
     * @param string $name
     * @return void
     * @throws ModuleNotFoundException
     */
    public function enable($name)
    {
        $this->findOrFail($name)->enable();
    }

    /**
     * Disabling a specific module.
     *
     * @param string $name
     * @return void
     * @throws ModuleNotFoundException
     */
    public function disable($name)
    {
        $this->findOrFail($name)->disable();
    }

    /**
     * Delete a specific module.
     *
     * @param string $name
     * @return bool
     * @throws ModuleNotFoundException
     */
    public function delete($name) : bool
    {
        return $this->findOrFail($name)->delete();
    }
}