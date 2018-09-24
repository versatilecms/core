<?php

namespace Versatile\Core\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeModelCommand extends ModelMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'versatile:make:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Versatile model class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../stubs/model.stub';
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $table = $this->argument('table');

        return $this->addSoftDelete($stub)
            ->replaceTableName($stub, $table)
            ->replaceNamespace($stub, $name)
            ->replaceClass($stub, $name);
    }

    /**
     * Add SoftDelete to the given stub.
     *
     * @param string $stub
     *
     * @return $this
     */
    protected function addSoftDelete(&$stub)
    {
        $traitIncl = $trait = '';

        if ($this->option('softdelete')) {
            $traitIncl = 'use Illuminate\Database\Eloquent\SoftDeletes;';
            $trait = 'use SoftDeletes;';
        }

        $stub = str_replace('//DummySDTraitInclude', $traitIncl, $stub);
        $stub = str_replace('//DummySDTrait', $trait, $stub);

        return $this;
    }

    /**
     * Replace table name
     *
     * @param string $stub
     * @param string $name
     *
     * @return $this
     */
    protected function replaceTableName(&$stub, $table)
    {
        $stub = str_replace('dummy_table_name', $table, $stub);

        return $this;   
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        $arguments = [
            ['table', InputArgument::REQUIRED, 'The table name'],
        ];

        return array_merge($arguments, parent::getArguments());
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = [
            ['softdelete', 'd', InputOption::VALUE_NONE, 'Add soft-delete field to Model'],
        ];

        return array_merge($options, parent::getOptions());
    }
}
