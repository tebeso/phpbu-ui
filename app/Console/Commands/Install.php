<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpbu:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs PHPBU UI';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (Storage::disk('root')->exists('.env') === false) {
            Storage::disk('root')->copy('.env.example', '.env');
        }

        Artisan::call('key:generate');

        return CommandAlias::SUCCESS;
    }
}
