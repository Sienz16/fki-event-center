<?php

namespace App\Http\Livewire\Organizer;

use Livewire\Component;
use App\Models\Event;
use App\Models\Organizer;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class EventList extends Component
{
    use WithPagination;

    public $tab = 'active';
    public $search = '';
    public $date_filter = '';
    public $venue_type_filter = '';

    public function switchTab($tabName)
    {
        $this->tab = $tabName;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function updatingVenueTypeFilter()
    {
        $this->resetPage();
    }

    private function applyDateFilter($query)
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
        $organizerId = Organizer::where('user_id', Auth::id())->value('organizer_id');

        $events = Event::where('organizer_id', $organizerId)
            ->when($this->tab, function ($query) {
                $status = $this->tab === 'requested' ? 'pending' : $this->tab;
                return $query->where('event_status', $status);
            })
            ->when($this->search, function ($query) {
                $query->where('event_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->date_filter, function ($query) {
                return $this->applyDateFilter($query);
            })
            ->when($this->venue_type_filter, function ($query) {
                $query->where('event_type', $this->venue_type_filter);
            })
            ->orderBy(function($query) {
                return $query->selectRaw('COALESCE(event_start_date, event_date)')
                    ->whereColumn('events.event_id', 'events.event_id')
                    ->limit(1);
            }, 'desc')
            ->paginate(6);

        return view('livewire.organizer.event-list', [
            'events' => $events
        ]);
    }
}
