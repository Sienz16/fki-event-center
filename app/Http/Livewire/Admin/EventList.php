<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use Livewire\WithPagination;

class EventList extends Component
{
    use WithPagination;

    public $tab = 'active';
    public $search = '';
    public $date_filter = '';
    public $venue_type_filter = '';

    // Reset pagination when filters change
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
        $events = Event::when($this->tab, function ($query) {
                // Map 'requested' tab to 'pending' status
                $status = $this->tab === 'requested' ? 'pending' : $this->tab;
                return $query->where('event_status', $status);
            })
            ->when($this->search, function ($query) {
                $query->where('event_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->date_filter, function ($query) {
                $this->applyDateFilter($query);
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

        return view('livewire.admin.event-list', [
            'events' => $events
        ]);
    }

    public function prefetch($tabName)
    {
        // This method will be called when hovering over tabs
        // Livewire will automatically prefetch the data
    }

    public function switchTab($tabName)
    {
        $this->tab = $tabName;
        $this->resetPage(); // This resets the page to 1 when switching tabs
    }
}
