<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ProcessQueue extends Command
{
    protected $signature = 'app:process-queue';
    protected $description = 'Process queued jobs';

    public function handle()
    {
        $this->info('Processing queued jobs...');
        Artisan::call('queue:work', [
            '--stop-when-empty' => true,
            '--tries' => 3
        ]);
        $this->info('Queue processed successfully.');
    }
}
