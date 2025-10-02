<?php

namespace Ro749\ListingUtils\Commands;

use Illuminate\Console\Command;
use Ro749\SharedUtils\Readers\DbReader;
use Ro749\SharedUtils\Readers\DbUpdate;
class ReadUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'read:units {file} {--update : Update instead of create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');
        if ($this->option('update')) {
            $reader = new DbUpdate(
                table: 'units',
                add_new_columns: false,
                public_id: 'unit'
            );
        }
        else{
            $reader = new DbReader(
                table: 'units',
                required_columns: ['unit','price','status'],
                add_new_columns: true
            );
        }
        
        $reader->read_cvs($file);
        $this->call('migrate', [
            '--force' => true
        ]);
    }
}