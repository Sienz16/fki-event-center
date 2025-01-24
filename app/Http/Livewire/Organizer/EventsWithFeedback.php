<?php

namespace App\Http\Livewire\Organizer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventsWithFeedback extends Component
{
    use WithPagination;

    public function render()
    {
        // Get the authenticated organizer
        $organizer = Auth::user()->organizer;

        // Fetch events created by this organizer with feedbacks and calculate average ratings, paginated
        $eventsWithFeedback = Event::with(['feedback'])
            ->where('organizer_id', $organizer->organizer_id) // Ensure we only fetch the organizer's events
            ->whereHas('feedback') // Only fetch events that have feedback
            ->withCount(['feedback as average_rating' => function ($query) {
                $query->select(DB::raw('AVG(rating)')); // Calculate average rating
            }])
            ->paginate(3); // Limit to 3 events per page

        return view('livewire.organizer.events-with-feedback', [
            'eventsWithFeedback' => $eventsWithFeedback,
        ]);
    }
}
