<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventRegistrationNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $eventId;
    protected $studentId;

    public function __construct($title, $message, $eventId, $studentId)
    {
        $this->title = $title;
        $this->message = $message;
        $this->eventId = $eventId;
        $this->studentId = $studentId;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'event_id' => $this->eventId,
            'student_id' => $this->studentId,
            'type' => 'event_registration',
            'time' => now()
        ];
    }
} 