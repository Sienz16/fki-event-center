<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVenueNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $venueId;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $venueId)
    {
        $this->title = $title;
        $this->message = $message;
        $this->venueId = $venueId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'venue_id' => $this->venueId,
            'type' => 'new_venue'
        ];
    }
} 