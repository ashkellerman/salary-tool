<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CollectListingsFromSeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seek:collect {location?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect job listings from seek.co.nz';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Collecting job listings from seek.co.nz...");

        if ($location = $this->argument('location')) {
            $this->info("Location: {$location}");
        }

        $seek = new \App\Services\SeekService();
        $seek->collectListings($location);

        $this->info("Done!");
    }
}
