<?php

namespace App\Console\Commands;

use App\Http\Controllers\BackupController;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ScanBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpbu:scan-backups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scans for new backups';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        (new BackupController())->ajaxScanForBackups();

        return CommandAlias::SUCCESS;
    }
}
