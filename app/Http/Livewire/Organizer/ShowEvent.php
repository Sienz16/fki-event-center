<?php

namespace App\Http\Livewire\Organizer;

use Livewire\Component;
use App\Models\Event;

class ShowEvent extends Component
{
    public $event;
    public $deleteModalOpen = false;
    public $requestActivationOpen = false;
    public $showEventCodeModal = false;
    public $timeRemaining = 10; // Countdown from 10 seconds

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->resetTimer();
    }

    public function resetTimer()
    {
        $this->timeRemaining = 10;
    }

    public function refreshEventCode()
    {
        $newCode = $this->event->generateUniqueCode();
        $this->event->update([
            'event_code' => $newCode
        ]);
        
        $this->resetTimer();
        
        $this->event->refresh();
    }

    public function deleteEvent()
    {
        $this->event->delete();
        session()->flash('success', 'Event deleted successfully.');
        return redirect()->route('organizer.events.index');
    }

    public function requestActivation()
    {
        $this->event->update(['event_status' => 'pending']);
        session()->flash('success', 'Activation request sent successfully.');
        return redirect()->route('organizer.events.index');
    }

    public function regenerateCode()
    {
        $newCode = $this->event->generateUniqueCode();
        $this->event->update([
            'event_code' => $newCode
        ]);
        
        session()->flash('success', 'Event code regenerated successfully.');
        return redirect()->route('organizer.events.show', $this->event);
    }

    public function render()
    {
        return view('livewire.organizer.show-event');
    }
} 