<?php

namespace App\Helper;

use App\Models\Backup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use phpseclib\Net\SFTP;

class FileHelper
{
    public const NET_SFTP_TYPE_FILE = 1;


    /**
     * @param string $filename
     *
     * @return Model|Builder
     */
    public static function getBackupInfo(string $filename): Model | Builder
    {
        $backup = Backup::query()->where('filename', $filename)->firstOrFail();

        $backup->setAttribute('type', substr($filename, -strlen($filename), strlen($filename) - 18));
        $backup->setAttribute('extension', explode('.', $filename)[1]);
        $backup->setAttribute('filenameWithoutExtension', explode('.', $filename)[0]);

        return $backup;
    }

    /**
     * @param int $backupId
     *
     * @return Builder|Model
     */
    public static function getBackupInfoById(int $backupId): Model | Builder
    {
        return self::getBackupInfo(Backup::query()->findOrFail($backupId)?->getAttribute('filename'));
    }


    /**
     * @param string $configName
     * @param array  $config
     *
     * @return array|false
     *
     * @link https://github.com/phpseclib/phpseclib
     */
    public static function getFileList(string $configName, array $config): bool | array
    {
        $sftp = self::sftpLogin($config);

        $files = $sftp->rawlist();

        $filesOnlyCallback = static function ($a) {
            return ($a["type"] === self::NET_SFTP_TYPE_FILE);
        };

        $files = array_filter($files, $filesOnlyCallback);

        foreach ($files as $index => $file) {
            $files[$index]['server'] = $configName;
        }

        return $files;
    }


    /**
     * @param array $config
     *
     * @return SFTP
     */
    public static function sftpLogin(array $config): SFTP
    {
        $sftp = new SFTP($config['host']);
        $sftp->login($config['user'], $config['password']) or die("Cannot login");

        return $sftp;
    }
}