<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index()
    {
        // Get the authenticated organizer
        $organizer = Auth::user()->organizer;
    
        // Get the events created by this organizer with feedbacks and calculate average ratings, paginated
        $eventsWithFeedback = Event::with(['feedback'])
            ->whereHas('feedback')
            ->withCount(['feedback as average_rating' => function ($query) {
                $query->select(DB::raw('AVG(rating)')); // Use DB facade for raw SQL queries
            }])
            ->paginate(10);

        // Pass the events with feedback to the view
        return view('organizer.feedback.index', compact('eventsWithFeedback'));
    } 

    // Store or update feedback for a specific event
    public function store(Request $request, $eventId)
    {
        // Validate the input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string',
        ]);

        // Get the authenticated student
        $student = Auth::user()->student;

        // Check if the student has already submitted feedback
        $existingFeedback = Feedback::where('event_id', $eventId)
            ->where('stud_id', $student->stud_id)
            ->first();

        if ($existingFeedback) {
            // Update existing feedback
            $existingFeedback->update([
                'rating' => $request->rating,
                'feedback' => $request->feedback,
            ]);

            return redirect()->route('student.events.show', $eventId)
                ->with('success', 'Your feedback has been updated successfully.');
        }

        // Store new feedback if it doesn't exist
        Feedback::create([
            'event_id' => $eventId,
            'stud_id' => $student->stud_id,
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return redirect()->route('student.events.show', $eventId)
            ->with('success', 'Your feedback has been submitted successfully.');
    }

    public function show($eventId)
    {
        // Get the event and load its feedback and related student
        $event = Event::with('feedback.student')->findOrFail($eventId);

        // Pass the event and its feedback to the view
        return view('organizer.feedback.show', compact('event'));
    }
}