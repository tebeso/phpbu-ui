<?php

namespace App\Helper;

use App\Models\JobBatches;
use App\Models\Progress;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CommandHelper
{
    /**
     * @param string $filename
     *
     * @return Collection
     */
    public static function prepareCommands(string $filename): Collection
    {
        $backup       = FileHelper::getBackupInfo($filename);
        $backupConfig = ConfigHelper::getConfigs($backup->getAttribute('type'));

        $search = [
            '$backupPath$',
            '$temp$',
            '$datetime$',
            '$server$',
            '$filename$',
            '$filenameWithoutExtension$',
        ];

        $replace = [
            config('phpbu.backup-path'),
            config('phpbu.temp-path'),
            Carbon::now()->format('YmdHis'),
            $backup->getAttribute('server'),
            $backup->getAttribute('filename'),
            $backup->getAttribute('filenameWithoutExtension'),
        ];

        $commands = [];

        foreach ($backupConfig->get('commands') as $command) {
            $command['command'] = str_replace($search, $replace, $command['command']);
            $commands[]         = $command;
        }

        return collect($commands);
    }


    /**
     * @param $uuid
     *
     * @return array
     */
    public static function status($uuid): array
    {
        $entries   = Progress::query()->where('uuid', $uuid);
        $total     = $entries->count();
        $completed = $entries->where('completed', 1)->count();

        return [
            'progress' => $completed / $total * 100,
            'log'      => $entries->get(),
        ];
    }


    /**
     * Returns backup id if a restore job is running.
     *
     * @return false|string
     */
    public static function checkRestoreRunning(): bool | string
    {
        $uuid = JobBatches::query()->whereNot('pending_jobs', 0)->first()?->getAttribute('name');

        if ($uuid === null) {
            return false;
        }

        return Progress::query()->where('uuid', $uuid)->first()?->getAttribute('backup_id') ?? false;
    }
}