<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\ResolutionDownloadRequest;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class ResolutionDownloadRequestSubmitted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public string $message;
    public string $type = 'ResolutionDownloadRequestSubmitted';

    protected array $adminIds = [];

    /**
     * Create a new event instance.
     */
    public function __construct(ResolutionDownloadRequest $downloadRequest)
    {
        // Load related models
        $downloadRequest->load('resolution', 'user');

        $resolutionTitle = $downloadRequest->resolution->title_resolutions ?? 'a resolution';
        $requestor = $downloadRequest->user->name ?? 'A user';

        $this->message = "📢 <strong>{$requestor}</strong> has submitted a request to download <em>{$resolutionTitle}</em>. Please review and approve or reject it.";

        // ✅ Get all admins & super admins
        $admins = User::whereIn('usertype', ['admin', 'super_admin'])->get();

        foreach ($admins as $admin) {
            // Save notification for each admin
            Notification::create([
                'user_id' => $admin->id,
                'message' => $this->message,
                'type' => $this->type,
                'is_read' => false,
            ]);

            // Collect admin IDs for broadcasting
            $this->adminIds[] = $admin->id;
        }
    }

    /**
     * Broadcast to all admin private channels
     */
    public function broadcastOn(): array
    {
        return array_map(
            fn ($id) => new Channel('user.' . $id),
            $this->adminIds
        );
    }

    /**
     * Event name used by Vue (Pusher)
     */
    public function broadcastAs(): string
    {
        return 'resolution.download.request.submitted';
    }

    /**
     * Payload sent to frontend
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
        ];
    }
}
