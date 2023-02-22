<?php

namespace App\Helper;

class EnvHelper
{
    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public static function setEnv($key, $value): void
    {
        file_put_contents(
            app()->environmentFilePath(),
            str_replace(
                $key . '=' . env($key),
                $key . '=' . $value,
                file_get_contents(app()->environmentFilePath())
            ));
    }
}