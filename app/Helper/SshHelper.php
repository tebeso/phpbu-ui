<?php

namespace App\Helper;

use DivineOmega\SSHConnection\SSHConnection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SshHelper
{
    /**
     * @var SSHConnection|null
     */
    protected ?SSHConnection $connection = null;

    /**
     * @param array $sshConfig
     * @param int   $timeout
     */
    public function __construct(array $sshConfig, int $timeout = 60)
    {
        $connection = (new SSHConnection())
            ->to($sshConfig['host'])
            ->onPort($sshConfig['port'] ?? 22)
            ->as($sshConfig['user'])
            ->timeout($timeout);

        if (self::isKeyExisting($sshConfig)) {
            $connection->withPrivateKey(Storage::disk('keys')->path($sshConfig['ssh-key']));
        } else {
            $connection->withPassword($sshConfig['password']);
        }

        $this->setConnection($connection->connect());
    }


    /**
     * @param string $localPath
     * @param string $remotePath
     *
     * @return Collection
     */
    public function upload(string $localPath, string $remotePath): Collection
    {
        $error = collect();

        try {
            $connection = $this->getConnection();
            $connection->upload($localPath, $remotePath);
        } catch (Throwable $e) {
            $error->add($e->getMessage());
        }

        return $error;
    }


    /**
     * @param string $remotePath
     * @param string $localPath
     *
     * @return Collection
     */
    public function download(string $remotePath, string $localPath): Collection
    {
        $error = collect();

        try {
            $this->getConnection()->download($remotePath, $localPath);
        } catch (Throwable $e) {
            $error->add($e->getMessage());
        }

        return $error;
    }


    /**
     * @return SSHConnection
     */
    public function getConnection(): SSHConnection
    {
        return $this->connection;
    }


    /**
     * @param SSHConnection $connection
     *
     * @return SshHelper
     */
    public function setConnection(SSHConnection $connection): SshHelper
    {
        $this->connection = $connection;
        return $this;
    }


    /**
     * @param array $sshConfig
     *
     * @return bool
     */
    public static function usesSshKey(array $sshConfig): bool
    {
        if (isset($sshConfig['ssh-key'])) {
            return true;
        }

        return false;
    }


    /**
     * @param array $sshConfig
     *
     * @return bool
     */
    public static function isKeyExisting(array $sshConfig): bool
    {
        return self::usesSshKey($sshConfig) && Storage::disk('keys')->exists($sshConfig['ssh-key']);
    }


    /**
     * @param array $sshConfig
     *
     * @return bool
     */
    public static function hasPassword(array $sshConfig): bool
    {
        return isset($sshConfig['password']) === true;
    }


    /**
     * @param array $sshConfig
     *
     * @return bool
     */
    public static function hasCredentials(array $sshConfig): bool
    {
        return self::hasPassword($sshConfig) || self::isKeyExisting($sshConfig);
    }
}