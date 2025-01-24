<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $eventName;
    public $studentName;

    public function __construct($eventName, $studentName)
    {
        $this->eventName = $eventName;
        $this->studentName = $studentName;
    }

    public function build()
    {
        return $this->subject('Event Registration Notice')
                    ->from('fkieventcenter@gmail.com', 'FKI Event Center') // Set the "From" name
                    ->view('emails.event_registration')
                    ->with([
                        'eventName' => $this->eventName,
                        'studentName' => $this->studentName
                    ]);
    }
}
