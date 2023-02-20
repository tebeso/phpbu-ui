<?php

namespace App\Helper;

use Illuminate\Support\Collection;

class ConfigHelper
{
    /**
     * @param string|false $backupType
     *
     * @return Collection
     */
    public static function getConfigs(string | false $backupType = false): Collection
    {
        $phpbuConfig = config('phpbu.commands');

        if (is_string($backupType) === true) {
            return collect($phpbuConfig[$backupType] + ['type' => $backupType]);
        }

        return collect($phpbuConfig);
    }
}