<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Event;
use App\Models\Venue;

class EventUpdatedNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $changes;

    public function __construct(Event $event, array $changes)
    {
        $this->event = $event;
        $this->changes = $changes;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $dateChange = null;
        $timeChange = null;
        $venueChange = null;
        
        foreach ($this->changes as $field => $value) {
            switch ($field) {
                case 'event_date':
                    $dateChange = \Carbon\Carbon::parse($value)->format('d M Y');
                    break;
                case 'event_time':
                    $timeChange = \Carbon\Carbon::parse($value)->format('h:i A');
                    break;
                case 'venue_id':
                    $venue = Venue::find($value);
                    $venueChange = $venue ? $venue->venue_name : 'a new venue';
                    break;
            }
        }

        $message = "The event '{$this->event->event_name}' has been updated: ";
        
        if ($dateChange && $venueChange) {
            $message .= "The date has been changed to {$dateChange} and will now be held at {$venueChange}";
        } elseif ($dateChange && $timeChange) {
            $message .= "The schedule has been changed to {$dateChange} at {$timeChange}";
        } elseif ($dateChange) {
            $message .= "The date has been changed to {$dateChange}";
        } elseif ($timeChange) {
            $message .= "The time has been changed to {$timeChange}";
        } elseif ($venueChange) {
            $message .= "The venue has been changed to {$venueChange}";
        }

        return [
            'title' => 'Event Update',
            'message' => $message,
            'event_id' => $this->event->event_id,
            'type' => 'event_update'
        ];
    }
} 