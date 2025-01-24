<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\Attendance;
use App\Notifications\EventStatusNotification;
use App\Notifications\EventSuspendedStudentNotification;

class EventActions extends Component
{
    public $event;
    public $showModal = false;
    public $actionType = null;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function openModal($type)
    {
        $this->actionType = $type;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->actionType = null;
    }

    public function approveTemplate()
    {
        $this->event->update([
            'template_status' => 'approved'
        ]);

        // Notify organizer
        $organizer = Organizer::findOrFail($this->event->organizer_id);
        $organizer->user->notify(new EventStatusNotification(
            'Template Approved',
            "Your certificate template for event '{$this->event->event_name}' has been approved.",
            $this->event->event_id,
            'template_approved'
        ));

        session()->flash('success', 'Certificate template has been approved.');
        return redirect()->route('admin.events.show', $this->event->event_id);
    }

    public function rejectTemplate()
    {
        $this->event->update([
            'template_status' => 'rejected'
        ]);

        // Notify organizer
        $organizer = Organizer::findOrFail($this->event->organizer_id);
        $organizer->user->notify(new EventStatusNotification(
            'Template Rejected',
            "Your certificate template for event '{$this->event->event_name}' has been rejected. Default template will be used.",
            $this->event->event_id,
            'template_rejected'
        ));

        session()->flash('success', 'Certificate template has been rejected.');
        return redirect()->route('admin.events.show', $this->event->event_id);
    }

    public function updateEventStatus()
    {
        try {
            if ($this->actionType === 'suspend') {
                $this->event->event_status = 'suspended';
                
                // Notify registered students
                $registeredStudents = Attendance::where('event_id', $this->event->event_id)
                    ->where('status', 'registered')
                    ->with(['student.user'])
                    ->get();
                
                foreach ($registeredStudents as $attendance) {
                    if ($attendance->student && $attendance->student->user) {
                        $attendance->student->user->notify(
                            new EventSuspendedStudentNotification($this->event->event_name)
                        );
                    }
                }

                // Send notification to organizer
                $organizer = Organizer::findOrFail($this->event->organizer_id);
                $organizer->user->notify(new EventStatusNotification(
                    'Event Suspended',
                    "Your event '{$this->event->event_name}' has been suspended.",
                    $this->event->event_id,
                    'suspended'
                ));

            } elseif ($this->actionType === 'reactivate') {
                $this->event->event_status = 'active';
                
                // Send notification for reactivation
                $organizer = Organizer::findOrFail($this->event->organizer_id);
                $organizer->user->notify(new EventStatusNotification(
                    'Event Activated',
                    "Your event '{$this->event->event_name}' has been reactivated.",
                    $this->event->event_id,
                    'active'
                ));
            }
            
            $this->event->save();
            $this->closeModal();
            session()->flash('success', 'Event status updated successfully.');
            
            return redirect()->route('admin.events.show', $this->event->event_id);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update event status.');
        }
    }

    public function render()
    {
        $this->event->refresh();
        return view('livewire.admin.event-actions');
    }
} 