<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VolunteerRequestDeclined extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $eventName;

    public function __construct($studentName, $eventName)
    {
        $this->studentName = $studentName;
        $this->eventName = $eventName;
    }

    public function build()
    {
        return $this->subject('Update on Your Volunteer Request')
                    ->markdown('emails.volunteers.declined');
    }
} 