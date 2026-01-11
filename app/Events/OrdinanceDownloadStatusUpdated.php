<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\OrdinanceDownloadRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class OrdinanceDownloadStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $downloadRequest;
    public $message;
    public $userId;
    public $type;
    public $notification;

    /**
     * Create a new event instance.
     */
    public function __construct(OrdinanceDownloadRequest $downloadRequest)
    {
        // Eager load ordinance relationship if exists
        $this->downloadRequest = $downloadRequest->load('ordinance');

        $this->userId = $this->downloadRequest->user_id;
        $this->type = 'OrdinanceDownloadStatusUpdated';

        $ordinanceTitle = optional($this->downloadRequest->ordinance)->title ?? 'an ordinance';
        $status = $this->downloadRequest->status;

        // Message based on status
        $this->message = match ($status) {
            'approved' => "Your request to download {$ordinanceTitle} has been approved.",
            'rejected' => "Your request to download {$ordinanceTitle} was rejected. Reason: {$this->downloadRequest->rejection_reason}",
            default => "Your request to download {$ordinanceTitle} is now pending.",
        };

        // Optional ordinance image if you have one
        $imagePath = optional($this->downloadRequest->ordinance)->image;

        // Save notification to database
        $this->notification = Notification::create([
            'user_id' => $this->userId,
            'message' => $this->message,
            'image_path' => $imagePath,
            'type' => $this->type,
        ]);
    }

    public function broadcastOn(): Channel
    {
        return new Channel('user.' . $this->userId);
    }

    public function broadcastAs(): string
    {
        return 'ordinance.download.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->notification->id,
            'message' => $this->message,
            'userId' => $this->userId,
            'ordinance_title' => optional($this->downloadRequest->ordinance)->title,
            'status' => $this->downloadRequest->status,
            'type' => $this->type,
        ];
    }
}
