<?php

namespace App\Helper;

use JsonException;

class JsonHelper
{
    /**
     * @throws JsonException
     */
    public static function decode($data, $array = false)
    {
        return json_decode($data, $array, 512, JSON_THROW_ON_ERROR);
    }


    /**
     * @throws JsonException
     */
    public static function encode(mixed $data): bool | string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }
}
