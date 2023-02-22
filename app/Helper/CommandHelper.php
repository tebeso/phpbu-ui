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
}