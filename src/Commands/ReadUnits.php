<?php

namespace Ro749\ListingUtils\Commands;

use Illuminate\Console\Command;
use Ro749\SharedUtils\Readers\DbRead;
use Ro749\SharedUtils\Readers\DbUpdate;
class ReadUnits extends Command
{
    protected $signature = 'read:units {file} {--update : Update instead of create}';

    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');
        if ($this->option('update')) {
            $reader = new DbUpdate(
                model_class: config('overrides.models.Unit'),
                add_new_columns: false,
                public_id: 'unit'
            );
        }
        else{
            $reader = new DbRead(
                model_class: config('overrides.models.Unit'),
                required_columns: ['unit','price','status'],
                add_new_columns: true
            );
        }
        
        $reader->read_csv($file);
        $this->call('migrate', [
            '--force' => true
        ]);
    }
}