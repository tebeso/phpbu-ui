<?php

/**
 * => backup-path : path on the server to access the backups (backup drive needs to be mounted on the servers)
 * => temp-path : a temporary directory that is being used to unzip the backup
 * => backup : uses Samba, so make sure your backup location supports Samba
 * => server : uses SSH. The private key has to be accessible in public/keys/id_rsa
 */

return [
    'backup-path' => '/mnt/bx11',
    'temp-path'   => '/root/backups',
    'backup'      => [
        'hs1' => [
            'host'     => 'u319629-sub1.your-storagebox.de',
            'user'     => 'u319629-sub1',
            'password' => 'lEuOYYUH2zQj2WB4',
        ],
        'hs2' => [
            'host'     => 'u319629-sub2.your-storagebox.de',
            'user'     => 'u319629-sub2',
            'password' => '7IomZXP41xRS9mOp',
        ],
    ],
    'server'      => [
        'hs1' => [
            'host'    => 'hs1.frantos.com',
            'user'    => 'root',
            'port'    => 22,
            'ssh-key' => 'id_rsa',
        ],
        'hs2' => [
            'host'    => 'hs2.frantos.com',
            'user'    => 'root',
            'port'    => 22,
            'ssh-key' => 'id_rsa',
        ],
    ],
    'commands'    => [
        'terminal'            => [
            'name'        => 'Terminal Files',
            'server'      => 'hs2',
            'commands'    => [
                [
                    'command'     => 'cd phpbu && phpbu --limit=terminal',
                    'description' => 'Create backup of current files',
                ],
                [
                    'command'     => 'cp -r $backupPath$/$server$/$filename$ $temp$/$filename$',
                    'description' => 'Copy backup from backup drive to temp folder',
                ],
                [
                    'command'     => 'cd $temp$ && tar -xf $filename$',
                    'description' => 'Extract backup',
                ],
                [
                    'command'     => 'docker stop terminal-v2-php-8.2.0',
                    'description' => 'Stop docker container',
                ],
                [
                    'command'     => 'mv /home/terminal/terminal /home/terminal/terminal.old.$datetime$',
                    'description' => 'Move live folder to another location',
                ],
                [
                    'command'     => 'cp -r $temp$/terminal /home/terminal/terminal',
                    'description' => 'Copy backup to live location',
                ],
                [
                    'command'     => 'cd /home/terminal/terminal && npm install',
                    'description' => 'Install node modules',
                ],
                [
                    'command'     => 'chown -R terminal:terminal /home/terminal/terminal && chown -R terminal:terminal /home/terminal/terminal/.*',
                    'description' => 'Set ownership of live location',
                ],
                [
                    'command'     => 'docker restart terminal-v2-php-8.2.0',
                    'description' => 'Start docker container',
                ],
                [
                    'command'     => 'docker exec -i terminal-v2-php-8.2.0 php -dxdebug.mode=off /usr/local/bin/composer install',
                    'description' => 'Install composer packages',
                ],
                [
                    'command'     => 'rm -rf $temp$/$filename$ && rm -rf $temp$/terminal',
                    'description' => 'Remove temporary files',
                ],
            ],
            'description' => '',
        ],
        'terminal-mysql'      => [
            'name'        => 'Terminal MySQL',
            'server'      => 'hs2',
            'commands'    => [
                [
                    'command'     => 'cd phpbu && phpbu --limit=terminal',
                    'description' => 'Create backup of current files',
                ],
                [
                    'command'     => 'cp -r $backupPath$/$server$/$filename$ $temp$/$filename$',
                    'description' => 'Copy backup from backup drive to temp folder',
                ],
                [
                    'command'     => 'cd $temp$ && tar -xf $filename$',
                    'description' => 'Extract backup',
                ],
            ],
            'description' => '',
        ],
        'matrixservice'       => [
            'name'        => 'MatrixService Files',
            'server'      => 'hs2',
            'commands'    => [
                [
                    'command'     => 'cd phpbu && phpbu --limit=terminal',
                    'description' => 'Create backup of current files',
                ],
                [
                    'command'     => 'cp -r $backupPath$/$server$/$filename$ $temp$/$filename$',
                    'description' => 'Copy backup from backup drive to temp folder',
                ],
                [
                    'command'     => 'cd $temp$ && tar -xf $filename$',
                    'description' => 'Extract backup',
                ],
            ],
            'description' => '',
        ],
        'matrixservice-mysql' => [
            'name'        => 'MatrixService MySQL',
            'server'      => 'hs2',
            'commands'    => [
                [
                    'command'     => 'cd phpbu && phpbu --limit=terminal',
                    'description' => 'Create backup of current files',
                ],
                [
                    'command'     => 'cp -r $backupPath$/$server$/$filename$ $temp$/$filename$',
                    'description' => 'Copy backup from backup drive to temp folder',
                ],
                [
                    'command'     => 'cd $temp$ && tar -xf $filename$',
                    'description' => 'Extract backup',
                ],
            ],
            'description' => '',
        ],
        'b2bgate-mysql'       => [
            'name'        => 'B2BGate MySQL',
            'server'      => 'hs1',
            'commands'    => [
                [
                    'command'     => 'cd phpbu && phpbu --limit=terminal',
                    'description' => 'Create backup of current files',
                ],
                [
                    'command'     => 'cp -r $backupPath$/$server$/$filename$ $temp$/$filename$',
                    'description' => 'Copy backup from backup drive to temp folder',
                ],
                [
                    'command'     => 'cd $temp$ && tar -xf $filename$',
                    'description' => 'Extract backup',
                ],
            ],
            'description' => '',
        ],
        'pimcore'             => [
            'name'        => 'Pimcore Files',
            'server'      => 'hs1',
            'commands'    => [
                [
                    'command'     => 'cd phpbu && phpbu --limit=terminal',
                    'description' => 'Create backup of current files',
                ],
                [
                    'command'     => 'cp -r $backupPath$/$server$/$filename$ $temp$/$filename$',
                    'description' => 'Copy backup from backup drive to temp folder',
                ],
                [
                    'command'     => 'cd $temp$ && tar -xf $filename$',
                    'description' => 'Extract backup',
                ],
            ],
            'description' => '',
        ],
        'pimcore-mysql'       => [
            'name'        => 'Pimcore MySQL',
            'server'      => 'hs1',
            'commands'    => [
                [
                    'command'     => 'cd phpbu && phpbu --limit=terminal',
                    'description' => 'Create backup of current files',
                ],
                [
                    'command'     => 'cp -r $backupPath$/$server$/$filename$ $temp$/$filename$',
                    'description' => 'Copy backup from backup drive to temp folder',
                ],
                [
                    'command'     => 'cd $temp$ && tar -xf $filename$',
                    'description' => 'Extract backup',
                ],
            ],
            'description' => '',
        ],
    ],
];