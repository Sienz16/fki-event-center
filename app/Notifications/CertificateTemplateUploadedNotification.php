<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CertificateTemplateUploadedNotification extends Notification
{
    use Queueable;

    protected $eventName;
    protected $eventId;
    protected $organizerName;

    public function __construct($eventName, $eventId, $organizerName)
    {
        $this->eventName = $eventName;
        $this->eventId = $eventId;
        $this->organizerName = $organizerName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Certificate Template Uploaded',
            'message' => "A new certificate template has been uploaded for event '{$this->eventName}' by {$this->organizerName}.",
            'event_id' => $this->eventId,
            'type' => 'template_uploaded'
        ];
    }
} 