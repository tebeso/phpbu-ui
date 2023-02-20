<?php

namespace App\Helper;

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
     * @return int
     */
    public static function status($uuid): int
    {
        $entries   = Progress::query()->where('uuid', $uuid);
        $total     = $entries->count();
        $completed = $entries->where('completed', 1)->count();

        return $completed / $total * 100;
    }
}