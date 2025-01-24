<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VolunteerRequestAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $eventName;
    public $eventDate;

    public function __construct($studentName, $eventName, $eventDate)
    {
        $this->studentName = $studentName;
        $this->eventName = $eventName;
        $this->eventDate = $eventDate;
    }

    public function build()
    {
        return $this->subject('Volunteer Request Accepted')
                    ->markdown('emails.volunteers.accepted');
    }
} 