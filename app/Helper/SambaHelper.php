<?php

namespace App\Helper;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;

class SambaHelper
{
    /**
     * @param string $server
     *
     * @return Repository|Application|mixed
     */
    public static function getConfig(string $server): mixed
    {
        return config('phpbu.backup.' . $server);
    }
}