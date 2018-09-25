<?php

namespace Versatile\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageServiceProviderLaravel5;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use Versatile\Core\Providers\VersatileDummyServiceProvider;
use Versatile\Core\Traits\Seedable;
use Versatile\Core\VersatileServiceProvider;

class InstallCommand extends Command
{
    use Seedable;

    protected $seedersPath = __DIR__.'/../../database/seeds/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'versatile:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Versatile Admin package';

    protected function getOptions()
    {
        return [
            ['with-dummy', null, InputOption::VALUE_NONE, 'Install with dummy data', null],
        ];
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }

        return 'composer';
    }

    public function fire(Filesystem $filesystem)
    {
        return $this->handle($filesystem);
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
        $this->info('Publishing the Versatile assets, database, and config files');

        // Publish only relevant resources on install
        $tags = ['versatile_assets', 'dummy_content', 'config'];

        $this->call('vendor:publish', ['--provider' => VersatileServiceProvider::class, '--tag' => $tags]);
        $this->call('vendor:publish', ['--provider' => ImageServiceProviderLaravel5::class]);

        $this->info('Migrating the database tables into your application');
        $this->call('migrate');

        $this->info('Attempting to set Versatile User model as parent to App\User');
        if (file_exists(app_path('User.php'))) {
            $str = file_get_contents(app_path('User.php'));

            if ($str !== false) {
                $str = str_replace('extends Authenticatable', "extends \Versatile\Core\Models\User", $str);

                file_put_contents(app_path('User.php'), $str);
            }
        } else {
            $this->warn('Unable to locate "app/User.php".  Did you move this file?');
            $this->warn('You will need to update this manually.  Change "extends Authenticatable" to "extends \Versatile\Core\Models\User" in your User model');
        }

        $this->info('Adding Versatile routes to routes/web.php');
        $routes_contents = $filesystem->get(base_path('routes/web.php'));
        if (false === strpos($routes_contents, 'Versatile::routes()')) {
            $filesystem->append(
                base_path('routes/web.php'),
                "\n\nRoute::group(['prefix' => 'admin'], function () {\n    Versatile::routes();\n});\n"
            );
        }

        $this->info('Seeding data into the database');
        $this->seed('VersatileDatabaseSeeder');

        $this->info('Adding the storage symlink to your public folder');
        $this->call('storage:link');

        $this->info('Successfully installed Versatile! Enjoy');
    }
}
