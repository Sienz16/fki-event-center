<?php

namespace App\Http\Controllers;

use App\Models\News; 
use App\Models\Event; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // Get the authenticated student
        $user = Auth::user();
        $student = $user->student;

        // Fetch the latest news with the admin relationship
        $news = News::with('admin')->latest()->take(3)->get();

        // Fetch the registered upcoming events for the student
        $events = Event::whereHas('participants', function ($query) use ($student) {
            $query->where('attendance.stud_id', $student->stud_id);  // Filter by registered student
        })
        ->where('event_date', '>', now())  // Only upcoming events
        ->orderBy('event_date', 'asc')  // Order by the nearest date
        ->take(3)  // Limit to 3 events
        ->get();

        // Pass both news and events data to the student dashboard view
        return view('student.dashboard', compact('news', 'events'));
    }
}
