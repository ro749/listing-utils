<?php

namespace Ro749\ListingUtils\Commands;

use Illuminate\Console\Command;
use Ro749\SharedUtils\Readers\DbReader;

class ReadUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'read:cvs {file}';

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
        $reader = new DbReader(
            table: 'units',
            required_columns: ['unit','price','status'],
            add_new_columns: true
        );
        $reader->read_cvs($file);
        $this->call('migrate', [
            '--force' => true
        ]);
    }
}