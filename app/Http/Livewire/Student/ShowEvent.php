<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class ShowEvent extends Component
{
    public $event;
    public $existingFeedback;
    public $rating;
    public $eventCode = '';
    public $feedback = '';
    public $isRegistered = false;
    public $hasAttended = false;

    protected $rules = [
        'eventCode' => 'required|string|max:10',
        'feedback' => 'nullable|string|max:1000',
        'rating' => 'nullable|integer|min:1|max:5',
    ];

    public function mount(Event $event)
    {
        $this->event = $event;
        $student = Auth::user()->student;

        if ($student) {
            $attendance = Attendance::where('stud_id', $student->stud_id)->where('event_id', $this->event->event_id)->first();
            $this->isRegistered = $attendance !== null;
            $this->hasAttended = $attendance && $attendance->status === 'attended';

            $this->existingFeedback = Feedback::where('event_id', $this->event->event_id)
                ->where('stud_id', $student->stud_id)
                ->first();

            if ($this->existingFeedback) {
                $this->feedback = $this->existingFeedback->feedback;
                $this->rating = $this->existingFeedback->rating;
            }
        }
    }

    public function register()
    {
        $student = Auth::user()->student;

        if (!$student) {
            session()->flash('error', 'No student record found.');
            return;
        }

        if ($this->isRegistered) {
            session()->flash('error', 'You are already registered for this event.');
            return;
        }

        Attendance::create([
            'stud_id' => $student->stud_id,
            'event_id' => $this->event->event_id,
            'status' => 'registered',
            'register_datetime' => now(),
        ]);

        $this->isRegistered = true;
        session()->flash('success', 'Successfully registered for the event!');
    }

    public function unregister()
    {
        $student = Auth::user()->student;

        if (!$student) {
            session()->flash('error', 'No student record found.');
            return;
        }

        $attendance = Attendance::where('stud_id', $student->stud_id)
            ->where('event_id', $this->event->event_id)
            ->first();

        if (!$attendance) {
            session()->flash('error', 'You are not registered for this event.');
            return;
        }

        $attendance->delete();
        $this->isRegistered = false;

        session()->flash('success', 'Successfully unregistered from the event.');
    }

    public function confirmAttendance()
    {
        $this->validateOnly('eventCode');

        $event = $this->event;

        if (trim($event->event_code) !== trim($this->eventCode)) {
            session()->flash('error', 'Invalid event code.');
            return;
        }

        $student = Auth::user()->student;

        $attendance = Attendance::where('stud_id', $student->stud_id)
            ->where('event_id', $event->event_id)
            ->first();

        if (!$attendance) {
            session()->flash('error', 'You are not registered for this event.');
            return;
        }

        $attendance->status = 'attended';
        $attendance->attendance_datetime = now();
        $attendance->save();

        $this->hasAttended = true;

        session()->flash('success', 'Attendance confirmed successfully.');
    }

    public function submitFeedback()
    {
        $this->validate();

        $student = Auth::user()->student;

        if (!$student) {
            session()->flash('error', 'No student record found.');
            return;
        }

        $feedbackData = [
            'event_id' => $this->event->event_id,
            'stud_id' => $student->stud_id,
            'feedback' => $this->feedback,
            'rating' => $this->rating,
        ];

        if ($this->existingFeedback) {
            $this->existingFeedback->update($feedbackData);
        } else {
            Feedback::create($feedbackData);
        }

        session()->flash('success', 'Thank you for your feedback!');
    }

    public function render()
    {
        return view('livewire.student.show-event');
    }
}
