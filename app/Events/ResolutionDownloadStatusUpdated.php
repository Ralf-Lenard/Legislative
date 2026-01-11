<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\ResolutionDownloadRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class ResolutionDownloadStatusUpdated implements ShouldBroadcast
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
    public function __construct(ResolutionDownloadRequest $downloadRequest)
    {
        // Eager load resolution relationship
        $this->downloadRequest = $downloadRequest->load('resolution');

        $this->userId = $this->downloadRequest->user_id;
        $this->type = 'ResolutionDownloadStatusUpdated';

        $resolutionTitle = optional($this->downloadRequest->resolution)->title ?? 'a resolution';
        $status = $this->downloadRequest->status;

        // Message based on status
        $this->message = match ($status) {
            'approved' => "Your request to download {$resolutionTitle} has been approved.",
            'rejected' => "Your request to download {$resolutionTitle} was rejected. Reason: {$this->downloadRequest->rejection_reason}",
            default => "Your request to download {$resolutionTitle} is now pending.",
        };

        // Optional resolution image
        $imagePath = optional($this->downloadRequest->resolution)->image;

        // Save notification
        $this->notification = Notification::create([
            'user_id'    => $this->userId,
            'message'    => $this->message,
            'image_path' => $imagePath,
            'type'       => $this->type,
        ]);
    }

    /**
     * Broadcast channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('user.' . $this->userId);
    }

    /**
     * Event name
     */
    public function broadcastAs(): string
    {
        return 'resolution.download.status.updated';
    }

    /**
     * Data sent to frontend
     */
    public function broadcastWith(): array
    {
        return [
            'id'               => $this->notification->id,
            'message'          => $this->message,
            'userId'           => $this->userId,
            'resolution_title' => optional($this->downloadRequest->resolution)->title,
            'status'           => $this->downloadRequest->status,
            'type'             => $this->type,
        ];
    }
}
