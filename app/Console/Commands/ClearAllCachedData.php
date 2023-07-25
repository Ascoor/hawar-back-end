<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAllCachedData extends Command
{
    protected $signature = 'cache:clear-all';
    protected $description = 'Clear all cached data, including routes and configurations.';

    public function handle()
    {
        // Clear Route Cache
        $this->call('route:clear');

        // Clear Configuration Cache
        $this->call('config:clear');

        // Clear All Cached Data
        $this->call('cache:clear');

        $this->info('All cached data cleared successfully.');
    }
}