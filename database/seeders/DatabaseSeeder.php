<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        AuthController::create(
            [
                'name'     => 'Tebin Ulrich',
                'email'    => 'tebin.ulrich@frantos.com',
                'password' => 'Tebin1687!',
            ]
        );

        (new BackupController())->ajaxScanForBackups();
    }
}
