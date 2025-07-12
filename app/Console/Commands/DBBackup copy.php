<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class DBBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dbbackup';

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
        $path = Storage::path('/backup/'.now()->format('Y-m-d_H-i-s').'.gz');

        $command = 'mysqldump --user='.env('DB_USERNAME').' --password='.env('DB_PASSWORD').' --host='.env('DB_HOST').' '.env('DB_DATABASE').' | gzip > '.$path;

        $process = Process::run($command);

        if ($process->successful()) {
            $this->info('Backup created successfully at: '.basename($path));
        }
    }
}
