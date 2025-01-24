<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Volunteer;

class CommitteeRequestStatusNotification extends Notification
{
    use Queueable;

    protected $volunteer;
    protected $status;

    public function __construct(Volunteer $volunteer, string $status)
    {
        $this->volunteer = $volunteer;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $statusMessage = $this->status === 'accepted' 
            ? "Your Volunteer request has been accepted" 
            : "Your Volunteer request has been declined";

        return [
            'title' => 'Volunteer Request Update',
            'message' => "{$statusMessage} for event '{$this->volunteer->event->event_name}'",
            'event_id' => $this->volunteer->event_id,
            'type' => 'committee_request'
        ];
    }
} 