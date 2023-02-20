<?php

namespace App\Console\Commands;

use App\Http\Controllers\AuthController;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpbu:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a user for the PHPBU UI';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        AuthController::create(
            [
                'name'     => $this->ask('What is your name?'),
                'email'    => $this->ask('What is your email?'),
                'password' => $this->secret('What is your password?'),
            ]
        );

        return CommandAlias::SUCCESS;
    }
}
