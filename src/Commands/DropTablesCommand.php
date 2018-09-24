<?php

namespace Versatile\Core\Commands;

use DB;
use App;
use Illuminate\Console\Command;

class DropTablesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'versatile:droptables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if (!App::environment('local')) {
            exit('Drop Tables command aborted');
        }

        $colname = 'Tables_in_' . DB::getDatabaseName();

        $tables = DB::select('SHOW TABLES');

        if (empty($tables)) {
            exit(PHP_EOL.'There are no tables to delete'.PHP_EOL);
        }

        $droplist = [];

        foreach($tables as $table) {
            $droplist[] = $table->$colname;
        }

        $droplist = implode(',', $droplist);

        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement("DROP TABLE $droplist");
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        DB::commit();

        $this->comment(PHP_EOL."If no errors showed up, all tables were dropped".PHP_EOL);

    }
}
