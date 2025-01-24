<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\VolunteerRequest;
use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\VolunteerRequestNotification;
use App\Notifications\CommitteeRequestStatusNotification;
use Illuminate\Support\Facades\Log;

class VolunteerController extends Controller
{
    public function index()
    {
        $selectedRole = session('selected_role');
    
        if ($selectedRole === 'event_organizer') {
            $organizer = Auth::user()->organizer;
    
            if (!$organizer) {
                return redirect()->route('login')->with('error', 'Organizer not found.');
            }
    
            // Fetch volunteers along with event and organizer information
            $volunteers = Volunteer::with('event', 'organizer')
            ->where('organizer_id', $organizer->organizer_id)
            ->get();

            // Calculate accepted count for each volunteer
            foreach ($volunteers as $volunteer) {
                $volunteer->remaining_needed = $volunteer->remainingVolunteersNeeded();
            }           

            return view('organizer.volunteers.index', compact('volunteers'));
        } elseif ($selectedRole === 'student') {
            // Retrieve all volunteer opportunities with event information
            $volunteers = Volunteer::with('event')->get();
        
            // Retrieve all volunteer requests submitted by the logged-in student
            $student = Auth::user()->student;
            $submittedRequests = VolunteerRequest::with('volunteer.event')
                                                 ->where('stud_id', $student->stud_id)
                                                 ->get();
    
            // If needed, calculate remaining needed volunteers
            foreach ($volunteers as $volunteer) {
                $volunteer->remaining_needed = $volunteer->remainingVolunteersNeeded();
            }
    
            return view('student.volunteers.index', compact('volunteers', 'submittedRequests'));
        } else {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }

    public function create()
    {
        $organizer = Auth::user()->organizer;
    
        if ($organizer) {
            // Retrieve events that do not have any volunteer requests
            $events = $organizer->events()
                                ->where('event_status', 'active')
                                ->whereDoesntHave('volunteers')
                                ->get();
        } else {
            $events = collect();
        }
    
        return view('organizer.volunteers.create', compact('events'));
    }    

    public function store(Request $request)
    {
        $organizer = Auth::user()->organizer;
    
        if (!$organizer) {
            return redirect()->route('organizer.volunteers.index')->with('error', 'You are not authorized to create a volunteer request.');
        }
    
        $request->validate([
            'event_id' => 'required|exists:events,event_id',
            'volunteer_capacity' => 'required|integer|min:1',
        ]);
    
        // Check if a volunteer request already exists for the selected event
        $existingRequest = Volunteer::where('event_id', $request->event_id)->exists();
    
        if ($existingRequest) {
            return redirect()->back()->with('error', 'A volunteer request already exists for this event.');
        }
    
        $event = $organizer->events()->where('event_id', $request->event_id)->where('event_status', 'active')->firstOrFail();
    
        Volunteer::create([
            'event_id' => $request->event_id,
            'organizer_id' => $organizer->organizer_id,
            'volunteer_capacity' => $request->volunteer_capacity,
            'notes' => $request->notes,
        ]);
    
        return redirect()->route('organizer.volunteers.index')->with('success', 'Volunteer request created successfully.');
    }    

    public function show($id)
    {
        $selectedRole = session('selected_role');
        $volunteer = Volunteer::with('event')->findOrFail($id);

        if ($selectedRole === 'student') {
            $student = Auth::user()->student;
            $volunteerRequest = VolunteerRequest::where('volunteer_id', $id)
                                                ->where('stud_id', $student->stud_id)
                                                ->first();
            $volunteerStatus = $volunteerRequest ? $volunteerRequest->status : null;

            // Pass the event to the view
            $event = $volunteer->event;

            return view('student.volunteers.show', compact('volunteer', 'volunteerStatus', 'event'));
        } elseif ($selectedRole === 'event_organizer') {
            // Get all volunteer requests for the organizer view
            $volunteerRequests = VolunteerRequest::where('volunteer_id', $id)->get();

            return view('organizer.volunteers.show', compact('volunteer', 'volunteerRequests'));
        } else {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }

    public function edit($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $organizer = Auth::user()->organizer;
        if ($organizer) {
            $events = $organizer->events()->where('event_status', 'active')->get();
        } else {
            $events = collect();
        }

        return view('organizer.volunteers.edit', compact('volunteer', 'events'));
    }

    public function update(Request $request, $id)
    {
        $organizer = Auth::user()->organizer;
        
        if (!$organizer) {
            return redirect()->route('organizer.volunteers.index')->with('error', 'You are not authorized to update this volunteer request.');
        }
    
        $request->validate([
            'volunteer_capacity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);
    
        // Find the existing volunteer request
        $volunteer = Volunteer::findOrFail($id);
    
        // Update only the fields that are editable (volunteer_capacity and notes)
        $volunteer->update([
            'volunteer_capacity' => $request->volunteer_capacity,
            'notes' => $request->notes,
        ]);
    
        return redirect()->route('organizer.volunteers.index')->with('success', 'Volunteer request updated successfully.');
    }    

    public function destroy($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->delete();

        return redirect()->route('organizer.volunteers.index')->with('success', 'Volunteer request deleted successfully.');
    }

    public function storeStudentRequest(Request $request)
    {
        try {
            Log::info('Starting volunteer request process', [
                'event_id' => $request->event_id,
                'user_id' => Auth::id()
            ]);

            $request->validate([
                'event_id' => 'required|exists:events,event_id',
            ]);
        
            $student = Auth::user()->student;
            Log::info('Student found', [
                'student_id' => $student->stud_id,
                'student_name' => $student->stud_name
            ]);
        
            $volunteer = Volunteer::with(['event', 'organizer.user'])->where('event_id', $request->event_id)->firstOrFail();
            Log::info('Volunteer position found', [
                'volunteer_id' => $volunteer->volunteer_id,
                'organizer_id' => $volunteer->organizer_id,
                'organizer_user_id' => $volunteer->organizer->user->id ?? 'none'
            ]);
        
            $volunteerRequest = VolunteerRequest::create([
                'volunteer_id' => $volunteer->volunteer_id,
                'stud_id' => $student->stud_id,
                'status' => 'pending',
            ]);

            Log::info('About to send notification', [
                'organizer_id' => $volunteer->organizer->organizer_id,
                'user_id' => $volunteer->organizer->user->id,
                'event_name' => $volunteer->event->event_name
            ]);

            // Send notification to organizer
            $volunteer->organizer->user->notify(new VolunteerRequestNotification(
                'New Volunteer Request',
                "{$student->stud_name} has requested to volunteer for event '{$volunteer->event->event_name}'.",
                $volunteer->event->event_id,
                $student->stud_id
            ));

            Log::info('Notification sent successfully');
        
            return redirect()->route('student.volunteers.index')
                ->with('success', 'Volunteer request submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Error in volunteer request:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'event_id' => $request->event_id ?? 'none'
            ]);
            return redirect()->route('student.volunteers.index')
                ->with('error', 'Failed to submit volunteer request: ' . $e->getMessage());
        }
    }    

    public function updateStatus(Request $request, $id)
    {
        try {
            $volunteerRequest = VolunteerRequest::with(['volunteer.event', 'volunteer.organizer', 'student'])->findOrFail($id);
            $volunteerRequest->status = $request->input('status');
            $volunteerRequest->save();

            // Only notify the student about their request status
            $volunteerRequest->student->user->notify(new CommitteeRequestStatusNotification(
                $volunteerRequest->volunteer,
                $request->input('status')
            ));

            return redirect()->back()->with('success', 'Volunteer request status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating volunteer status:', [
                'error' => $e->getMessage(),
                'request_id' => $id
            ]);
            return redirect()->back()->with('error', 'Failed to update volunteer request status.');
        }
    }

    public function revokeStudentRequest($id)
    {
        try {
            $student = Auth::user()->student;
            
            // Find the volunteer request with relationships loaded
            $volunteerRequest = VolunteerRequest::with(['volunteer.event', 'volunteer.organizer.user', 'student'])
                ->where('volunteer_id', $id)
                ->where('stud_id', $student->stud_id)
                ->firstOrFail();

            // Store event name before deleting request
            $eventName = $volunteerRequest->volunteer->event->event_name;
            $organizer = $volunteerRequest->volunteer->organizer;

            // Delete the request
            $volunteerRequest->delete();

            // Send notification to organizer
            $organizer->user->notify(new VolunteerRequestNotification(
                'Volunteer Withdrawal',
                "{$student->stud_name} has withdrawn their volunteer request for event '{$eventName}'.",
                $volunteerRequest->volunteer->event->event_id,
                $student->stud_id
            ));

            return redirect()->route('student.volunteers.index', ['activeTab' => 2])
                ->with('success', 'Volunteer request revoked successfully.');
        } catch (\Exception $e) {
            Log::error('Error revoking volunteer request:', [
                'error' => $e->getMessage(),
                'volunteer_id' => $id,
                'student_id' => Auth::id()
            ]);
            
            return redirect()->route('student.volunteers.index', ['activeTab' => 2])
                ->with('error', 'Failed to revoke volunteer request.');
        }
    }      
}
