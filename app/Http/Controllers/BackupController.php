<?php

namespace App\Http\Controllers;

use App\Helper\CommandHelper;
use App\Helper\ConfigHelper;
use App\Helper\FileHelper;
use App\Helper\SshHelper;
use App\Models\Backup;
use App\Models\Progress;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BackupController extends Controller
{
    /**
     * @return View|Factory|Application
     */
    public function index(): View | Factory | Application
    {
        return view(
            'welcome',
            [
                'configs' => ConfigHelper::getConfigs(),
            ]
        );
    }


    /**
     * @param string $backupType
     *
     * @return Application|Factory|View
     */
    public function indexList(string $backupType): View | Factory | Application
    {
        return view(
            'list',
            [
                'config'     => ConfigHelper::getConfigs($backupType),
                'backupList' => self::getBackupList($backupType),
            ]
        );
    }


    /**
     * @param string $backupId
     *
     * @return Application|Factory|View
     */
    public function indexDetails(string $backupId): View | Factory | Application
    {
        $backup         = FileHelper::getBackupInfoById($backupId);
        $server         = $backup->getAttribute('server');
        $hasCredentials = SshHelper::hasCredentials(config('phpbu.server.' . $server));

        return view(
            'details',
            [
                'config'         => ConfigHelper::getConfigs($backup->getAttribute('type')),
                'backup'         => $backup,
                'hasCredentials' => $hasCredentials,
            ]
        );
    }


    /**
     * @return void
     */
    public function ajaxScanForBackups(): void
    {
        $configs  = config('phpbu.backup');
        $fileList = [];

        foreach ($configs as $server => $config) {
            $fileList += FileHelper::getFileList($server, $config);
        }

        foreach ($fileList as $file) {
            Backup::query()->firstOrCreate(
                [
                    'server'          => $file['server'],
                    'filename'        => $file['filename'],
                    'size'            => $file['size'],
                    'file_created_at' => $file['atime'],
                ]
            );
        }

        $this->markAsDeletedOrReenable($fileList);
    }


    /**
     * @param array $fileList
     *
     * @return void
     */
    public function markAsDeletedOrReenable(array $fileList): void
    {
        foreach (Backup::query()->get() as $backup) {
            $filename = $backup->getAttribute('filename');

            if (isset($fileList[$filename]) === false) {
                $backup->setAttribute('deleted', 1)->save();
            } elseif (isset($fileList[$filename]) === true && $backup->getAttribute('deleted') === 1) {
                $backup->setAttribute('deleted', 0)->save();
            }
        }
    }


    /**
     * @param Request $request
     *
     * @return void
     */
    public static function restore(Request $request): void
    {
        $backupId = $request->get('backupId');
        $uuid     = $request->get('uuid');

        $backup   = FileHelper::getBackupInfoById($backupId);
        $commands = CommandHelper::prepareCommands($backup->getAttribute('filename'));

        self::createProgressEntries($uuid, $commands);

        $ssh = new SshHelper(config('phpbu.server.' . $backup->getAttribute('server')), 1200);

        foreach ($commands as $command) {

            $progressEntry = Progress::query()
                ->where('uuid', $uuid)
                ->where('command', $command['command'])->get()->first();

            $process = $ssh->getConnection()->run($command['command']);

            $progressEntry->update(
                [
                    'completed' => 1,
                    'log'       => $process->getError() === '' ? 'OK' : $process->getError(),
                ]
            );
        }
    }


    /**
     * @param string     $uuid
     * @param Collection $commands
     *
     * @return string
     */
    protected static function createProgressEntries(string $uuid, Collection $commands): string
    {
        foreach ($commands as $command) {
            Progress::query()->insert(
                [
                    'command'    => $command['command'],
                    'uuid'       => $uuid,
                    'created_by' => Auth::user()?->getAttribute('name'),
                ]
            );
        }

        return $uuid;
    }


    /**
     * @return string|null
     * @throws Exception
     */
    public static function generateUuid(): ?string
    {
        return Str::uuid()->serialize();
    }


    /**
     * @param string $backupType
     * @param bool   $countOnly
     *
     * @return Collection|int
     */
    public static function getBackupList(string $backupType, bool $countOnly = false): Collection | int
    {
        $substrStart = -18 - strlen($backupType);
        $substrLen   = strlen($backupType);

        $sql      = sprintf("SUBSTRING(filename,%s,%s) = '%s'", $substrStart, $substrLen, $backupType);
        $fileList = Backup::query()->whereRaw($sql)->orderBy('file_created_at')->get();

        if ($countOnly === true) {
            return $fileList->count();
        }

        return $fileList;
    }
}