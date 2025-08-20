<?php

namespace Ro749\ListingUtils\Commands;

use Illuminate\Console\Command;

class ListingUtilsCommand extends Command
{
    public $signature = 'listing-utils';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
