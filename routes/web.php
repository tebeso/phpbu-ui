<?php

use App\Helper\CommandHelper;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware([Authenticate::class])->group(function () {
    Route::get('/', [BackupController::class, 'index']);
    Route::any('/list/{backupType}', [BackupController::class, 'indexList']);
    Route::any('/details/{backupId}', [BackupController::class, 'indexDetails']);

    Route::any('/restore/status/{uuid}', [CommandHelper::class, 'status']);
    Route::any('/restore/check-running/', [CommandHelper::class, 'checkRestoreRunning']);
    Route::any('/restore', [BackupController::class, 'restore']);

    Route::any('/generate-uuid', [BackupController::class, 'generateUuid']);

    Route::any('/scan-backups', [BackupController::class, 'ajaxScanForBackups']);
});

Route::get('login', [AuthController::class, 'index']);
Route::post('custom-login', [AuthController::class, 'customLogin'])->name('login.custom');
Route::get('logout', [AuthController::class, 'logout']);