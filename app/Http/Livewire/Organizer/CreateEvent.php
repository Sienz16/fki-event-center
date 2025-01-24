<?php

namespace App\Http\Livewire\Organizer;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Support\Facades\Auth;

class CreateEvent extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $isMultipleDay = false;
    public $eventType = 'physical';
    public $venues = [];

    #[Rule('required|string|max:255')]
    public $eventName = '';

    #[Rule('required|string')]
    public $eventDesc = '';

    #[Rule('required|date')]
    public $eventDate;

    #[Rule('required_if:isMultipleDay,true|date')]
    public $eventStartDate;

    #[Rule('required_if:isMultipleDay,true|date|after_or_equal:eventStartDate')]
    public $eventEndDate;

    #[Rule('required|date_format:H:i')]
    public $eventStartTime;

    #[Rule('required|date_format:H:i|after:eventStartTime')]
    public $eventEndTime;

    #[Rule('required_if:eventType,physical')]
    public $venueId;

    #[Rule('required_if:eventType,online|string')]
    public $onlinePlatform;

    #[Rule('nullable|integer|min:0')]
    public $volunteerCapacity = 0;

    #[Rule('image|max:2048')]
    public $eventImage;

    #[Rule('nullable|image|max:2048')]
    public $certTemplate;

    #[Rule('nullable|in:portrait,landscape')]
    public $certOrientation = 'portrait';

    public function mount()
    {
        $this->loadVenues();
    }

    public function loadVenues()
    {
        $this->venues = Venue::orderByRaw("FIELD(venue_status, 'Available', 'Under Maintenance')")
                            ->get()
                            ->toArray();
    }

    public function updatedEventType()
    {
        if ($this->eventType === 'physical') {
            $this->loadVenues();
        } else {
            $this->venueId = null;
        }
    }

    public function updatedIsMultipleDay($value)
    {
        if ($value) {
            $this->eventDate = null;
        } else {
            $this->eventStartDate = null;
            $this->eventEndDate = null;
        }
    }

    public function nextStep()
    {
        $this->validate($this->getValidationRules());
        
        if ($this->currentStep < 4) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function getValidationRules()
    {
        $rules = [];
        
        if ($this->currentStep === 1) {
            $rules = [
                'eventName' => 'required|string|max:255',
                'eventDesc' => 'required|string',
                'eventStartTime' => 'required|date_format:H:i',
                'eventEndTime' => 'required|date_format:H:i|after:eventStartTime',
            ];

            if ($this->isMultipleDay) {
                $rules['eventStartDate'] = 'required|date';
                $rules['eventEndDate'] = 'required|date|after_or_equal:eventStartDate';
            } else {
                $rules['eventDate'] = 'required|date';
            }
        }
        
        if ($this->currentStep === 2) {
            if ($this->eventType === 'physical') {
                $rules['venueId'] = 'required|exists:venues,id';
            } else {
                $rules['onlinePlatform'] = 'required|string';
            }
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        $eventData = [
            'event_name' => $this->eventName,
            'event_desc' => $this->eventDesc,
            'event_type' => $this->eventType,
            'event_start_time' => $this->eventStartTime,
            'event_end_time' => $this->eventEndTime,
            'volunteer_capacity' => $this->volunteerCapacity,
            'org_id' => Auth::user()->org_id,
            'cert_orientation' => $this->certOrientation,
        ];

        if ($this->isMultipleDay) {
            $eventData['event_start_date'] = $this->eventStartDate;
            $eventData['event_end_date'] = $this->eventEndDate;
        } else {
            $eventData['event_date'] = $this->eventDate;
        }

        if ($this->eventType === 'physical') {
            $eventData['venue_id'] = $this->venueId;
        } else {
            $eventData['online_platform'] = $this->onlinePlatform;
        }

        $event = Event::create($eventData);

        if ($this->eventImage) {
            $event->event_img = $this->eventImage->store('events', 'public');
        }

        if ($this->certTemplate) {
            $event->cert_template = $this->certTemplate->store('certificates', 'public');
        }

        $event->save();

        session()->flash('success', 'Event created successfully!');
        return redirect()->route('organizer.events.show', $event->event_id);
    }

    public function render()
    {
        return view('livewire.organizer.create-event');
    }
}
