<?php

namespace App\Http\Livewire\Organizer;

use Livewire\Component;
use App\Models\Volunteer;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class VolunteerList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $organizer = Auth::user()->organizer;
        
        $volunteers = Volunteer::with(['event', 'event.venue', 'volunteerRequests'])
            ->where('organizer_id', $organizer->organizer_id)
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        // Calculate remaining volunteers needed for each volunteer
        foreach ($volunteers as $volunteer) {
            $volunteer->remaining_needed = $volunteer->remainingVolunteersNeeded();
        }

        return view('livewire.organizer.volunteer-list', [
            'volunteers' => $volunteers
        ]);
    }
}
