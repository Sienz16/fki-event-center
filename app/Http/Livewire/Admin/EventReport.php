<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class EventReport extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    public $page = 1;
    
    protected $queryString = ['page' => ['except' => 1]];
    
    public function updatingPage()
    {
        $this->dispatch('pageChanged');
    }

    public function render()
    {
        $events = DB::table('events')
            ->leftJoin('feedback', 'events.event_id', '=', 'feedback.event_id')
            ->leftJoin('event_organizers', 'events.organizer_id', '=', 'event_organizers.organizer_id')
            ->select(
                'events.event_id', 
                'events.event_name', 
                'events.event_img', 
                'events.updated_at',
                'event_organizers.org_name',
                'event_organizers.org_img',
                DB::raw('AVG(feedback.rating) as average_rating')
            )
            ->groupBy(
                'events.event_id', 
                'events.event_name', 
                'events.event_img', 
                'events.updated_at',
                'event_organizers.org_name',
                'event_organizers.org_img'
            )
            ->paginate(3);

        return view('livewire.admin.event-report', [
            'events' => $events
        ]);
    }
} 