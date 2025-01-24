<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User; // Ensure this line is present
use App\Models\Organizer; // Import Organizer model
use App\Models\Venue;
use App\Models\Volunteer;
use App\Models\VenueBook;
use App\Models\Attendance;
use App\Models\Feedback;
use App\Mail\EventRegistrationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;     
use App\Http\Controllers\VenueController;
use App\Notifications\EventNotification;
use App\Notifications\EventStatusNotification;
use App\Notifications\EventRegistrationNotification;
use App\Notifications\EventUpdatedNotification;
use App\Notifications\EventSuspendedNotification;
use App\Notifications\EventDeletedNotification;
use App\Notifications\CertificateTemplateUploadedNotification;

class EventController extends Controller
{
    protected $venueController;

    public function __construct(VenueController $venueController)
    {
        $this->venueController = $venueController;
    }

    private function validateEventRequest(Request $request, $isUpdate = false)
    {
        $today = now()->format('Y-m-d');
        
        $rules = [
            'event_name' => 'required|string|max:255',
            'event_duration' => 'required|in:single,multiple',
            'event_type' => 'required|in:physical,online',
            'event_start_time' => 'required|date_format:H:i',
            'event_end_time' => 'required|date_format:H:i|after:event_start_time',
            'venue_type' => 'required_if:event_type,physical|in:faculty,other',
            'venue_id' => 'required_if:venue_type,faculty|nullable|exists:venues,venue_id',
            'other_venue_name' => 'required_if:venue_type,other|nullable|string|max:255',
            'online_platform' => 'required_if:event_type,online|nullable|string|max:255',
            'event_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'event_desc' => 'required|string',
            'cert_template' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'cert_orientation' => 'required|in:portrait,landscape',
            'volunteer_capacity' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ];

        // Add date validation rules based on whether it's an update or create
        if ($request->event_duration === 'single') {
            $rules['event_date'] = [
                'required',
                'date',
                $isUpdate ? 'nullable' : 'after_or_equal:' . $today
            ];
            $rules['event_start_date'] = 'nullable';
            $rules['event_end_date'] = 'nullable';
        } else {
            $rules['event_start_date'] = [
                'required',
                'date',
                $isUpdate ? 'nullable' : 'after_or_equal:' . $today
            ];
            $rules['event_end_date'] = [
                'required',
                'date',
                'after_or_equal:event_start_date'
            ];
            $rules['event_date'] = 'nullable';
        }

        $messages = [
            'event_date.after_or_equal' => 'The event date must be today or a future date.',
            'event_start_date.after_or_equal' => 'The event start date must be today or a future date.',
            'event_end_date.after_or_equal' => 'The event end date must be after or equal to the start date.',
            'event_img.image' => 'The event image must be a valid image file.',
            'event_img.mimes' => 'The event image must be a file of type: jpeg, png, jpg, gif.',
            'event_img.max' => 'The event image must not be larger than 2MB.',
            'venue_id.required_if' => 'Please select a faculty venue when using faculty venues.',
            'other_venue_name.required_if' => 'Please provide the venue name for other venues.',
            'online_platform.required_if' => 'The online platform is required for online events.',
        ];

        return $request->validate($rules, $messages);
    }

    // Add this new method to handle volunteer record creation
    private function createVolunteerRecord($eventId, $organizerId, Request $request)
    {
        return Volunteer::create([
            'event_id' => $eventId,
            'organizer_id' => $organizerId,
            'volunteer_capacity' => $request->volunteer_capacity,
            'notes' => $request->notes,
        ]);
    }

    public function store(Request $request)
    {
        try {
            Log::info('Event creation request:', $request->all());
            
            // Validate the request
            $validated = $this->validateEventRequest($request);
            
            $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();
            $event = Event::create($this->prepareEventData($request, $organizer));

            // Send notification to all admin users
            $admins = User::whereHas('roles', function($query) {
                $query->where('role_name', 'admin');
            })->get();

            foreach ($admins as $admin) {
                $admin->notify(new EventNotification(
                    'New Event Created',
                    "A new event '{$event->event_name}' has been added to the system.",
                    $event->event_id,
                    'event_created'
                ));
            }

            // Only create venue booking if it's a physical event with faculty venue
            if ($request->event_type === 'physical' && $request->venue_type === 'faculty') {
                $this->createVenueBooking($event->event_id, $request);
            }

            if ($request->filled('volunteer_capacity') && $request->volunteer_capacity > 0) {
                $this->createVolunteerRecord($event->event_id, $organizer->organizer_id, $request);
            }

            return redirect()->route('organizer.events.index', ['tab' => 'active', 'page' => 1])
                            ->with('success', 'Success! Your event is added to the system!');
                            
        } catch (\Exception $e) {
            Log::error('Event creation error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'files' => $request->allFiles()
            ]);
            
            return redirect()->back()
                            ->with('error', 'Error occurred: ' . $e->getMessage())
                            ->withInput();
        }
    }

    private function uploadFile(Request $request, $fileKey, $directory)
    {
        // Only process if a new file is being uploaded
        if ($request->hasFile($fileKey)) {
            return $request->file($fileKey)->store($directory, 'public');
        }
        
        // Return null if no file is provided, allowing the existing value to be preserved
        return null;
    }

    private function prepareEventData(Request $request, $organizer)
    {
        $data = $request->only([
            'event_name', 'event_type', 'event_start_time', 'event_end_time', 'event_desc', 'cert_orientation'
        ]);
        
        $data['organizer_id'] = $organizer->organizer_id;
        
        // Only set event_code for new events, not updates
        if (!isset($organizer->event_code)) {
            $data['event_code'] = Str::random(8);
        }
        
        if ($request->event_duration === 'single') {
            $data['event_date'] = $request->event_date;
            // Clear multiple day fields
            $data['event_start_date'] = null;
            $data['event_end_date'] = null;
        } else {
            $data['event_start_date'] = $request->event_start_date;
            $data['event_end_date'] = $request->event_end_date;
            // Clear single day field
            $data['event_date'] = null;
        }

        // Handle venue/platform based on event type
        if ($request->event_type === 'physical') {
            $data['venue_type'] = $request->venue_type; // Add venue type
            
            if ($request->venue_type === 'faculty') {
                $data['venue_id'] = $request->venue_id;
                $data['other_venue_name'] = null;
            } else {
                $data['venue_id'] = null;
                $data['other_venue_name'] = $request->other_venue_name;
            }
            
            $data['online_platform'] = null;
        } else {
            $data['online_platform'] = $request->online_platform;
            $data['venue_id'] = null;
            $data['venue_type'] = null;
            $data['other_venue_name'] = null;
        }

        // Handle file uploads only if new files are provided
        $eventImg = $this->uploadFile($request, 'event_img', 'event_images');
        if ($eventImg) {
            $data['event_img'] = $eventImg;
        }

        $certTemplate = $this->uploadFile($request, 'cert_template', 'cert_templates');
        if ($certTemplate) {
            $data['cert_template'] = $certTemplate;
        }

        // Handle certificate template upload
        if ($request->hasFile('cert_template')) {
            if ($request->file('cert_template')->isValid()) {
                $path = $request->file('cert_template')->store('cert_templates', 'public');
                $data['cert_template'] = $path;
                $data['template_status'] = 'pending';
                
                // Notify all admin users about the new template
                $admins = User::whereHas('roles', function($query) {
                    $query->where('role_name', 'admin');
                })->get();

                foreach ($admins as $admin) {
                    $admin->notify(new CertificateTemplateUploadedNotification(
                        $request->event_name,
                        $request->event_id ?? null,
                        $organizer->org_name
                    ));
                }
            }
        }

        return $data;
    }

    private function createVenueBooking($eventId, $request)
    {
        $bookingStartDate = $request->event_duration === 'single' ? $request->event_date : $request->event_start_date;
        $bookingEndDate = $request->event_duration === 'single' ? $request->event_date : $request->event_end_date;

        VenueBook::create([
            'event_id' => $eventId,
            'venue_id' => $request->venue_id,
            'booking_start_date' => $bookingStartDate,
            'booking_end_date' => $bookingEndDate,
            'booking_start_time' => $request->event_start_time,
            'booking_end_time' => $request->event_end_time,
        ]);
    }
    
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $user = User::with('roles')->find(Auth::id());
        $selectedRole = session('selected_role');
        $tab = $request->input('tab', 'all');
        $search = $request->input('search');
        $dateFilter = $request->input('date_filter');
        $venueTypeFilter = $request->input('venue_type_filter');

        if ($selectedRole === 'admin') {
            // Simply return the view since Livewire handles the data
            return view('admin.events.index');
        } elseif ($selectedRole === 'event_organizer') {
            // Fetch events for organizer
            $organizerId = Organizer::where('user_id', $user->id)->value('organizer_id');

            $activeEvents = Event::where('event_status', 'active')
                ->where('organizer_id', $organizerId)
                ->when($search, fn($q) => $q->where('event_name', 'like', '%' . $search . '%'))
                ->when($dateFilter, fn($q) => $this->applyDateFilter($q, $dateFilter))
                ->when($venueTypeFilter, fn($q) => $q->where('event_type', $venueTypeFilter))
                ->paginate(6, ['*'], 'activePage');

            $suspendedEvents = Event::where('event_status', 'suspended')
                ->where('organizer_id', $organizerId)
                ->when($search, fn($q) => $q->where('event_name', 'like', '%' . $search . '%'))
                ->when($dateFilter, fn($q) => $this->applyDateFilter($q, $dateFilter))
                ->when($venueTypeFilter, fn($q) => $q->where('event_type', $venueTypeFilter))
                ->paginate(6, ['*'], 'suspendedPage');

            $requestedEvents = Event::where('event_status', 'pending')
                ->where('organizer_id', $organizerId)
                ->when($search, fn($q) => $q->where('event_name', 'like', '%' . $search . '%'))
                ->when($dateFilter, fn($q) => $this->applyDateFilter($q, $dateFilter))
                ->when($venueTypeFilter, fn($q) => $q->where('event_type', $venueTypeFilter))
                ->paginate(6, ['*'], 'requestedPage');

            return view('organizer.events.index', compact('activeEvents', 'suspendedEvents', 'requestedEvents', 'tab'));

        } elseif ($selectedRole === 'student') {
            return view('student.events.index', $this->getStudentEvents($user, $search, $dateFilter, $venueTypeFilter, $tab));
        } else {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }
    
    private function getAdminEvents($search, $dateFilter, $venueTypeFilter, $tab)
    {
        $eventStatus = ['active', 'suspended', 'pending'];
        $events = [];
        foreach ($eventStatus as $status) {
            $events[$status] = Event::where('event_status', $status)
                ->when($search, fn($q) => $q->where('event_name', 'like', '%' . $search . '%'))
                ->when($dateFilter, fn($q) => $this->applyDateFilter($q, $dateFilter))
                ->when($venueTypeFilter, fn($q) => $q->where('event_type', $venueTypeFilter))
                ->paginate(6, ['*'], "{$status}Page");
        }
        return array_merge($events, compact('tab'));
    }
    
    private function getOrganizerEvents($user, $search, $dateFilter, $venueTypeFilter)
    {
        $organizerId = Organizer::where('user_id', $user->id)->value('organizer_id');
        $statuses = ['active', 'suspended', 'pending'];
        $events = [];
        foreach ($statuses as $status) {
            $events[$status] = Event::where('event_status', $status)
                ->where('organizer_id', $organizerId)
                ->when($search, fn($q) => $q->where('event_name', 'like', '%' . $search . '%'))
                ->when($dateFilter, fn($q) => $this->applyDateFilter($q, $dateFilter))
                ->when($venueTypeFilter, fn($q) => $q->where('event_type', $venueTypeFilter))
                ->paginate(6, ['*'], "{$status}Page");
        }
        return $events;
    }
    
    private function getStudentEvents($user, $search, $dateFilter, $venueTypeFilter, $tab)
    {
        if ($tab == 'registered') {
            $events = $this->getRegisteredEvents($user, $search, $venueTypeFilter);
        } elseif ($tab == 'past') {
            $events = $this->getPastEvents($user, $search, $venueTypeFilter);
        } else {
            $events = $this->getAllActiveEvents($user, $search, $dateFilter, $venueTypeFilter);
        }
        return compact('events', 'tab');
    }
    
    private function applyDateFilter($query, $dateFilter)
    {
        return $query->where(function ($q) use ($dateFilter) {
            if ($dateFilter === 'upcoming') {
                $q->where(function ($q) {
                    $q->whereNotNull('event_date')->where('event_date', '>', now())
                      ->orWhere(fn($q) => $q->whereNotNull('event_start_date')->where('event_start_date', '>', now()));
                });
            } elseif ($dateFilter === 'past') {
                $q->where(function ($q) {
                    $q->whereNotNull('event_date')->where('event_date', '<', now())
                      ->orWhere(fn($q) => $q->whereNotNull('event_end_date')->where('event_end_date', '<', now()));
                });
            }
        });
    }
    
    private function getRegisteredEvents($user, $search, $venueTypeFilter)
    {
        return Event::whereHas('participants', function ($query) use ($user) {
                $query->where('attendance.stud_id', $user->student->stud_id)->where('attendance.status', 'registered');
            })
            ->when($search, fn($q) => $q->where('event_name', 'like', '%' . $search . '%'))
            ->when($venueTypeFilter, fn($q) => $q->where('event_type', $venueTypeFilter))
            ->paginate(6);
    }
    
    private function getPastEvents($user, $search, $venueTypeFilter)
    {
        return Event::whereHas('attendances', function ($query) use ($user) {
                $query->where('stud_id', $user->student->stud_id)->where('status', 'attended');
            })
            ->where(fn($q) => $this->applyDateFilter($q, 'past'))
            ->when($search, fn($q) => $q->where('event_name', 'like', '%' . $search . '%'))
            ->when($venueTypeFilter, fn($q) => $q->where('event_type', $venueTypeFilter))
            ->paginate(6);
    }
    
    private function getAllActiveEvents($user, $search, $dateFilter, $venueTypeFilter)
    {
        return Event::where('event_status', 'active')
            ->leftJoin('attendance', function ($join) use ($user) {
                $join->on('events.event_id', '=', 'attendance.event_id')
                     ->where('attendance.stud_id', '=', $user->student->stud_id);
            })
            ->select('events.*')
            ->selectRaw("CASE WHEN attendance.status = 'attended' THEN 3 WHEN attendance.status = 'registered' THEN 2 ELSE 1 END AS custom_order")
            ->orderBy('custom_order', 'asc')
            ->when($search, fn($q) => $q->where('event_name', 'like', '%' . $search . '%'))
            ->when($dateFilter, fn($q) => $this->applyDateFilter($q, $dateFilter))
            ->when($venueTypeFilter, fn($q) => $q->where('event_type', $venueTypeFilter))
            ->paginate(6);
    }
    
    public function update(Request $request, $id)
    {
        try {
            $event = Event::findOrFail($id);
            $organizer = Organizer::findOrFail($event->organizer_id);
            
            // Handle request for activation (from organizer)
            if ($request->has('requestActivation')) {
                $event->event_status = 'pending';
                $event->save();
                
                // Notify admins about reactivation request
                $admins = User::whereHas('roles', function($query) {
                    $query->where('role_name', 'admin');
                })->get();

                foreach ($admins as $admin) {
                    $admin->notify(new EventNotification(
                        'Event Reactivation Request',
                        "Event '{$event->event_name}' requires reactivation approval.",
                        $event->event_id,
                        'reactivation_request'
                    ));
                }
                
                return redirect()->back()->with('success', 'Activation request submitted successfully.');
            }

            // Handle event suspension
            if ($request->has('event_status') && $request->event_status === 'suspended') {
                $event->event_status = 'suspended';
                $event->save();
                
                try {
                    Log::info('Fetching registered students for event:', [
                        'event_id' => $event->event_id,
                        'event_name' => $event->event_name
                    ]);
                    
                    // Get all students who are registered through attendance table
                    $registeredStudents = Attendance::where('event_id', $event->event_id)
                        ->where('status', 'registered')
                        ->with(['student.user'])
                        ->get();
                    
                    Log::info('Found registered students:', [
                        'count' => $registeredStudents->count(),
                        'students' => $registeredStudents->pluck('student.stud_id')
                    ]);
                    
                    foreach ($registeredStudents as $attendance) {
                        Log::info('Sending notification to student:', [
                            'student_id' => $attendance->student->stud_id,
                            'has_user' => isset($attendance->student->user),
                        ]);
                        
                        if ($attendance->student && $attendance->student->user) {
                            $attendance->student->user->notify(new EventSuspendedNotification($event));
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error sending suspension notifications:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
                
                // Get the organizer through the event relationship
                $organizer = Organizer::findOrFail($event->organizer_id);
                
                // Send notification to the organizer's user
                $organizer->user->notify(new EventStatusNotification(
                    'Event Suspended',
                    "Your event '{$event->event_name}' has been suspended.",
                    $event->event_id,
                    'suspended'
                ));
                
                return redirect()->back()->with('success', 'Event has been suspended.');
            }

            // Rest of the update logic
            if ($request->input('event_type') === 'physical' && !$request->filled('venue_id')) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['venue_id' => 'Please select a venue for physical events.']);
            }

            // Validate the request
            $validated = $this->validateEventRequest($request, true);
            
            // If a new certificate template is being uploaded
            if ($request->hasFile('cert_template')) {
                // Notify all admin users about the template update
                $admins = User::whereHas('roles', function($query) {
                    $query->where('role_name', 'admin');
                })->get();

                foreach ($admins as $admin) {
                    $admin->notify(new CertificateTemplateUploadedNotification(
                        $event->event_name,
                        $event->event_id,
                        $organizer->org_name
                    ));
                }
            }

            // Update the event
            $this->updateEventData($event, $request);

            return redirect()->route('organizer.events.show', $event->event_id)
                            ->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            Log::error('Event update error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update event. ' . $e->getMessage());
        }
    }

    private function updateEventData(Event $event, Request $request)
    {
        $data = $this->prepareEventData($request, $event->organizer);
        
        // Handle event duration changes and clear irrelevant date fields
        if ($request->event_duration === 'single') {
            $event->event_date = $request->event_date;
            $event->event_start_date = null;
            $event->event_end_date = null;
        } else { // multiple
            $event->event_date = null;
            $event->event_start_date = $request->event_start_date;
            $event->event_end_date = $request->event_end_date;
        }
        
        // Handle event type specific fields
        if ($request->input('event_type') === 'physical') {
            $event->venue_type = $request->input('venue_type');
            if ($request->input('venue_type') === 'faculty') {
                $event->venue_id = $request->input('venue_id');
                $event->other_venue_name = null;
            } else {
                $event->venue_id = null;
                $event->other_venue_name = $request->input('other_venue_name');
            }
            $event->online_platform = null;
        } else {
            $event->venue_id = null;
            $event->venue_type = null;
            $event->other_venue_name = null;
            $event->online_platform = $request->input('online_platform');
        }

        // Update other fields
        foreach ($data as $key => $value) {
            if (!in_array($key, ['venue_id', 'venue_type', 'other_venue_name', 'online_platform', 'event_date', 'event_start_date', 'event_end_date']) && $value !== null) {
                $event->$key = $value;
            }
        }

        // Handle venue booking for physical events with faculty venue
        if ($request->input('event_type') === 'physical' && $request->input('venue_type') === 'faculty') {
            $this->updateVenueBooking($event, $request);
        } else {
            // Remove any existing venue bookings
            VenueBook::where('event_id', $event->event_id)->delete();
        }

        // Handle volunteer information
        if ($request->filled('volunteer_capacity') && $request->volunteer_capacity > 0) {
            // Update or create volunteer record
            Volunteer::updateOrCreate(
                ['event_id' => $event->event_id],
                [
                    'organizer_id' => $event->organizer_id,
                    'volunteer_capacity' => $request->volunteer_capacity,
                    'notes' => $request->notes
                ]
            );
        } else {
            // If volunteer capacity is 0 or null, delete any existing volunteer record
            Volunteer::where('event_id', $event->event_id)->delete();
        }

        $event->save();

        // Get changed fields that require notification
        $changes = array_intersect_key(
            $event->getChanges(),
            array_flip(['event_date', 'event_time', 'venue_id'])
        );
        
        // If relevant fields were changed, notify registered students
        if (!empty($changes)) {
            $registeredStudents = $event->registeredStudents()->get();
            foreach ($registeredStudents as $student) {
                // Only send notification if the user has a student role
                if ($student->user && $student->user->roles()->where('role_name', 'student')->exists()) {
                    $student->user->notify(new EventUpdatedNotification($event, $changes));
                }
            }
        }
    }

    private function updateVenueBooking($event, $request)
    {
        $bookingStartDate = $request->input('event_duration') === 'single' 
            ? $request->input('event_date') 
            : $request->input('event_start_date');
        $bookingEndDate = $request->input('event_duration') === 'single' 
            ? $request->input('event_date') 
            : $request->input('event_end_date');

        VenueBook::updateOrCreate(
            ['event_id' => $event->event_id],
            [
                'venue_id' => $request->input('venue_id'),
                'booking_start_date' => $bookingStartDate,
                'booking_end_date' => $bookingEndDate,
                'booking_start_time' => $request->input('event_start_time'),
                'booking_end_time' => $request->input('event_end_time'),
            ]
        );
    }

    public function show($id)
    {
        $event = Event::with(['organizer'])->where('event_id', $id)->firstOrFail();
        $selectedRole = session('selected_role');

        if ($selectedRole === 'admin') {
            return view('admin.events.show', compact('event'));
        } elseif ($selectedRole === 'event_organizer') {
            return view('organizer.events.show', compact('event'));
        } elseif ($selectedRole === 'student') {
            $student = Auth::user()->student;
            $existingFeedback = Feedback::where('event_id', $event->event_id)
                                        ->where('stud_id', $student->stud_id)
                                        ->first();

            return view('student.events.show', compact('event', 'existingFeedback'));
        } else {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }
    
    public function confirmAttendance(Request $request, $eventId)
    {
        $request->validate(['event_code' => 'required|string']);
        $event = Event::findOrFail($eventId);

        // Add debug logging to check values
        Log::info('Attendance confirmation attempt:', [
            'provided_code' => $request->event_code,
            'actual_code' => $event->event_code,
            'event_id' => $eventId
        ]);

        // Trim both codes to ensure no whitespace issues
        if (trim($event->event_code) !== trim($request->event_code)) {
            return redirect()->route('student.events.show', $eventId)
                ->with('error', 'Invalid event code.');
        }

        $attendance = Attendance::where('stud_id', Auth::user()->student->stud_id)
                                ->where('event_id', $eventId)
                                ->first();
                            
        if (!$attendance) {
            return redirect()->route('student.events.show', $eventId)
                ->with('error', 'You are not registered for this event.');
        }

        $attendance->status = 'attended';
        $attendance->attendance_datetime = now();
        $attendance->save();

        return redirect()->route('student.events.show', $eventId)
            ->with('success', 'Attendance confirmed successfully.');
    }

    public function create()
    {
        $venues = Venue::where('venue_status', 'Available')->get();
        return view('organizer.events.create', compact('venues'));
    }

    public function edit($id)
    {
        $event = Event::with('volunteers')->findOrFail($id);
        $availableVenues = Venue::where('venue_status', 'Available')
            ->when($event->venue_id, function ($query) use ($event) {
                return $query->orWhere('venue_id', $event->venue_id);
            })
            ->get();
        
        return view('organizer.events.edit', compact('event', 'availableVenues'));
    }

    private function getAvailableVenuesForEvent(Event $event)
    {
        $startDate = $event->event_start_date ?? $event->event_date;
        $endDate = $event->event_end_date ?? $event->event_date;

        return Venue::whereDoesntHave('venueBooks', function ($query) use ($startDate, $endDate, $event) {
                $query->where('venue_id', '!=', $event->venue_id)
                    ->whereBetween('booking_start_date', [$startDate, $endDate])
                    ->orWhereBetween('booking_end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('booking_start_date', '<=', $startDate)
                        ->where('booking_end_date', '>=', $endDate);
                    });
            })->orWhere('venue_id', $event->venue_id)
            ->get();
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        
        // Get event details before deletion
        $eventName = $event->event_name;
        
        // Notify registered students before deletion
        $registeredStudents = Attendance::where('event_id', $event->event_id)
            ->where('status', 'registered')
            ->with(['student.user'])
            ->get();
        
        foreach ($registeredStudents as $attendance) {
            if ($attendance->student && $attendance->student->user) {
                $attendance->student->user->notify(new EventDeletedNotification($eventName));
            }
        }
        
        // Delete event image if exists
        if ($event->event_img && Storage::disk('public')->exists($event->event_img)) {
            Storage::disk('public')->delete($event->event_img);
        }
        
        $event->delete();
        return redirect()->route('organizer.events.index')->with('success', 'Event deleted successfully');
    }

    public function showParticipants($eventId)
    {
        $event = Event::findOrFail($eventId);
        $participants = Attendance::where('event_id', $eventId)
            ->with(['student.user'])
            ->get()
            ->map(fn($attendance) => (object) [
                'stud_name' => $attendance->student->stud_name,
                'matric_no' => $attendance->student->user->matric_no,
                'email' => $attendance->student->user->email,
                'stud_phoneNo' => $attendance->student->stud_phoneNo,
                'stud_course' => $attendance->student->stud_course,
                'register_datetime' => $attendance->register_datetime,
                'status' => $attendance->status,
                'attendance_datetime' => $attendance->attendance_datetime
            ]);

        return view('organizer.events.participants', compact('event', 'participants'));
    }
    public function register($id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get the associated student record for this user
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.events.show', $id)
                ->with('error', 'No student record found for this user.');
        }

        // Check if the student has already registered for the event
        $existingAttendance = Attendance::where('stud_id', $student->stud_id)
            ->where('event_id', $id)
            ->first();

        if ($existingAttendance) {
            return redirect()->route('student.events.show', $id)->with('error', 'You have already registered for this event.');
        }

        // Create a new attendance record
        $attendance = Attendance::create([
            'stud_id' => $student->stud_id,
            'event_id' => $id,
            'status' => 'registered',
            'register_datetime' => now(),
        ]);

        // Notify the organizer about the new registration
        $event = Event::findOrFail($id);
        $event->organizer->user->notify(new EventRegistrationNotification(
            'New Event Registration',
            "{$student->stud_name} has registered for your event '{$event->event_name}'.",
            $event->event_id,
            $student->stud_id
        ));

        // Send a confirmation email to the user
        $userEmail = $user->email;
        $eventName = Event::find($id)->event_name;

        Mail::to($userEmail)->send(new EventRegistrationMail($eventName, Auth::user()->student->stud_name));

        return redirect()->route('student.events.show', $id)->with('success', 'You have successfully registered for the event! A confirmation email has been sent to you.');
    }

    public function unregister($id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get the associated student record for this user
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.events.show', $id)->with('error', 'No student record found for this user.');
        }

        // Check if the student is registered for the event
        $attendance = Attendance::where('stud_id', $student->stud_id)
            ->where('event_id', $id)
            ->first();

        if (!$attendance) {
            return redirect()->route('student.events.show', $id)->with('error', 'You are not registered for this event.');
        }

        // Delete the attendance record
        $attendance->delete();

        return redirect()->route('student.events.show', $id)->with('success', 'You have successfully unregistered from the event.');
    }
}