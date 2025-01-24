<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class EventList extends Component
{
    use WithPagination;

    public $tab = 'all';
    public $search = '';
    public $date_filter = '';
    public $venue_type_filter = '';

    public function switchTab($tabName)
    {
        $this->tab = $tabName;
        $this->resetPage(); // Reset to first page when switching tabs
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination when search changes
    }

    public function updatingDateFilter()
    {
        $this->resetPage(); // Reset pagination when date filter changes
    }

    public function updatingVenueTypeFilter()
    {
        $this->resetPage(); // Reset pagination when venue type filter changes
    }

    public function getEvents()
    {
        $user = Auth::user();
        $query = Event::query()->where('event_status', 'active');

        // Apply filters based on the selected tab
        if ($this->tab == 'registered') {
            $query->whereHas('participants', function ($q) use ($user) {
                $q->where('attendance.stud_id', $user->student->stud_id)
                  ->where('attendance.status', 'registered');
            });
        } elseif ($this->tab == 'past') {
            $query->whereHas('attendances', function ($q) use ($user) {
                $q->where('attendance.stud_id', $user->student->stud_id)
                  ->where('attendance.status', 'attended');
            });
        } elseif ($this->tab == 'all') {
            $query->whereDoesntHave('participants', function ($q) use ($user) {
                $q->where('attendance.stud_id', $user->student->stud_id);
            });
        }

        // Apply search filter if provided
        if ($this->search) {
            $query->where('event_name', 'like', '%' . $this->search . '%');
        }

        // Apply date filter
        $query = $this->applyDateFilter($query);

        // Apply venue type filter
        if ($this->venue_type_filter) {
            $query->where('event_type', $this->venue_type_filter);
        }

        // Order by event date (using either event_start_date or event_date)
        $query->orderBy(function($query) {
            return $query->selectRaw('COALESCE(event_start_date, event_date)')
                ->whereColumn('events.event_id', 'events.event_id')
                ->limit(1);
        }, 'desc');

        return $query->paginate(6);
    }

    public function applyDateFilter($query)
    {
        if ($this->date_filter === 'upcoming') {
            $query->where(function ($q) {
                $q->whereNotNull('event_date')->where('event_date', '>', now())
                  ->orWhere(fn($q) => $q->whereNotNull('event_start_date')->where('event_start_date', '>', now()));
            });
        } elseif ($this->date_filter === 'past') {
            $query->where(function ($q) {
                $q->whereNotNull('event_date')->where('event_date', '<', now())
                  ->orWhere(fn($q) => $q->whereNotNull('event_end_date')->where('event_end_date', '<', now()));
            });
        }
        return $query;
    }

    public function render()
    {
        $events = $this->getEvents();
        return view('livewire.student.event-list', compact('events'));
    }
}
