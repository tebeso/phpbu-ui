<?php

namespace App\Jobs;

use App\Events\BackupInProgress;
use App\Helper\SshHelper;
use App\Models\Progress;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Command
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     * @var integer
     */
    public int $timeout = 3600;


    protected array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * @return void
     */
    public function handle(): void
    {
        $uuid    = $this->data['uuid'];
        $command = $this->data['command'];
        $backup  = $this->data['backup'];

        BackupInProgress::dispatch($uuid, $backup->getAttribute('id'));

        $ssh = new SshHelper(config('phpbu.server.' . $backup->getAttribute('server')), 1200);

        $progressEntry = Progress::query()
            ->where('uuid', $uuid)
            ->where('command', $command)->get()->first();

        $process = $ssh->getConnection()->run($command);

        $progressEntry->update(
            [
                'completed' => 1,
                'log'       => $process->getError() === '' ? 'OK' : $process->getError(),
            ]
        );

        BackupInProgress::dispatch($uuid, $backup->getAttribute('id'));
    }
}
