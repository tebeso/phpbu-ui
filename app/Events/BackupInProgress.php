<?php

namespace App\Events;

use App\Helper\CommandHelper;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;

class BackupInProgress extends Event implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    protected string $uuid;

    /**
     * @var int
     */
    protected int $backupId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $uuid, int $backupId)
    {
        $this->uuid     = $uuid;
        $this->backupId = $backupId;
    }


    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'BackupInProgress';
    }


    /**
     * @return int[]
     */
    public function broadcastWith(): array
    {
        $status = CommandHelper::status($this->uuid);

        return [
            'status'   => $status,
            'uuid'     => $this->uuid,
            'backupId' => $this->backupId,
        ];
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel
     */
    public function broadcastOn(): Channel | PrivateChannel
    {
        return new Channel('phpbu.'.$this->backupId);
    }
}
