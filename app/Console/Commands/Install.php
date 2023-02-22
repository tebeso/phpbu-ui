<?php

namespace App\Console\Commands;

use App\Helper\EnvHelper;
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
    protected $description = 'Installs and configures PHPBU UI';

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

        EnvHelper::setEnv('DOCKER_PHP_PORT_HTTP', $this->ask('Which port should be used for PHP over HTTP?', 80));
        EnvHelper::setEnv('DOCKER_PHP_PORT_HTTPS', $this->ask('Which port should be used for PHP over HTTPS?', 443));
        EnvHelper::setEnv('DOCKER_PHP_PORT_SUPERVISOR', $this->ask('Which port should be used for Supervisor?', 9001));
        EnvHelper::setEnv('DOCKER_MYSQL_PORT', $this->ask('Which port should be used for MySQL?', 3306));
        EnvHelper::setEnv('DOCKER_RABBITMQ_PORT_HTTP', $this->ask('Which port should be used for RabbitMQ Webinterface?', 15672));

        return CommandAlias::SUCCESS;
    }
}
