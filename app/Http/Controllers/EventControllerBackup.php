<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User; // Ensure this line is present
use App\Models\Organizer; // Import Organizer model
use App\Models\Venue;
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

class EventControllerBackup extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'event_name' => 'required|string|max:255',
                'event_duration' => 'required|in:single,multiple', // Validate event duration
                'event_type' => 'required|in:physical,online', // Validate event type (physical or online)
                'event_date' => [
                    'nullable',
                    'date',
                    'required_if:event_duration,single',  // Validate date for single-day events only
                ],
                'event_start_date' => 'nullable|date|required_if:event_duration,multiple', // Validate start date for multiple-day events
                'event_end_date' => 'nullable|date|after_or_equal:event_start_date|required_if:event_duration,multiple', // Validate end date for multiple-day events
                'event_start_time' => 'required|date_format:H:i',  // Validate start time format
                'event_end_time' => 'nullable|date_format:H:i|after:event_start_time', // Validate end time if provided
                'venue_id' => 'required_if:event_type,physical|exists:venues,venue_id',  // Check venue exists if event type is physical
                'online_platform' => 'nullable|string|max:255', // Make online platform nullable and a string
                'event_img' => 'nullable|image|max:2048',
                'event_desc' => 'required|string',
                'cert_template' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
                'cert_orientation' => 'required|in:portrait,landscape',
            ]);         
    
            // Venue availability check for physical events
            if ($request->event_type === 'physical') {
                $venueId = $request->venue_id;
                $startDate = $request->event_duration === 'single' ? $request->event_date : $request->event_start_date;
                $endDate = $request->event_duration === 'single' ? $request->event_date : $request->event_end_date;
                $startTime = $request->event_start_time;
                $endTime = $request->event_end_time;

                // Check if any event conflicts with the specified venue, date, and time
                $existingBooking = VenueBook::where('venue_id', $venueId)
                    ->where(function ($query) use ($startDate, $endDate, $startTime, $endTime) {
                        $query->whereBetween('booking_start_date', [$startDate, $endDate])
                            ->orWhereBetween('booking_end_date', [$startDate, $endDate])
                            ->orWhere(function ($query) use ($startDate, $endDate) {
                                $query->where('booking_start_date', '<=', $startDate)
                                        ->where('booking_end_date', '>=', $endDate);
                            });
                    })
                    ->where(function ($query) use ($startTime, $endTime) {
                        $query->whereBetween('booking_start_time', [$startTime, $endTime])
                            ->orWhereBetween('booking_end_time', [$startTime, $endTime])
                            ->orWhere(function ($query) use ($startTime, $endTime) {
                                $query->where('booking_start_time', '<=', $startTime)
                                        ->where('booking_end_time', '>=', $endTime);
                            });
                    })
                    ->exists();

                // If there's a conflict, return an error
                if ($existingBooking) {
                    return redirect()->back()->withErrors([
                        'venue_id' => 'The selected venue is already booked for the specified date and time range.'
                    ])->withInput();
                }
            }

            // Fetch the organizer based on the logged-in user
            $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();
    
            // Create a new event instance
            $event = new Event();
            $event->organizer_id = $organizer->organizer_id;
            $event->event_name = $request->event_name;
            $event->event_type = $request->event_type;
    
            // Check if the event is a single day or multiple days
            if ($request->event_duration === 'single') {
                $event->event_date = $request->event_date; // Store single day date
                $event->event_start_date = null;  // Clear the start date for single day
                $event->event_end_date = null;    // Clear the end date for single day
            } else {
                $event->event_start_date = $request->event_start_date; // Store start date
                $event->event_end_date = $request->event_end_date;     // Store end date
                $event->event_date = null;  // Clear the date for multiple days
            }
    
            $event->event_start_time = $request->event_start_time;
            $event->event_end_time = $request->event_end_time;
    
            if ($request->event_type === 'physical') {
                $event->venue_id = $request->venue_id; // Store venue_id for physical events
            } else {
                $event->venue_id = null; // Clear venue_id for online events
                $event->online_platform = $request->online_platform; // Store online platform for online events
            }
    
            $event->event_desc = $request->event_desc;
    
            // Generate a unique event code
            $event->event_code = Str::random(8);
    
            // Handle image upload
            if ($request->hasFile('event_img')) {
                $path = $request->file('event_img')->store('event_images', 'public');
                $event->event_img = $path;
            }
    
            // Handle certificate template upload
            if ($request->hasFile('cert_template')) {
                $certPath = $request->file('cert_template')->store('cert_templates', 'public');
                $event->cert_template = $certPath;
            }
    
            // Save the certificate orientation
            $event->cert_orientation = $request->cert_orientation;
    
            // Save the event
            $event->save();
    
            // Save booking details in the venue_book table if the event is physical
            if ($request->event_type === 'physical') {
                // Determine the booking start and end dates based on event duration
                $bookingStartDate = $request->event_duration === 'single' ? $request->event_date : $request->event_start_date;
                $bookingEndDate = $request->event_duration === 'single' ? $request->event_date : $request->event_end_date;
            
                // Create the venue booking
                VenueBook::create([
                    'event_id' => $event->event_id,
                    'venue_id' => $request->venue_id,
                    'booking_start_date' => $bookingStartDate,
                    'booking_end_date' => $bookingEndDate,
                    'booking_start_time' => $request->event_start_time,
                    'booking_end_time' => $request->event_end_time,
                ]);
            }
    
            return redirect()->route('organizer.events.index', ['tab' => 'active', 'page' => 1])->with('success', 'Success! Your event is added to the system!');
        } catch (\Exception $e) {
            // Log for debugging
            dd($e->getMessage(), $e->getTrace());
            return redirect()->route('organizer.events.index', ['tab' => 'active', 'page' => 1])->with('error', 'Error occurred! Please try again.');
        }
    }

    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $user = User::with('roles')->find(Auth::id());
        $selectedRole = session('selected_role'); // Fetch the selected role from the session

        // Filtering logic
        $search = $request->input('search');
        $dateFilter = $request->input('date_filter');
        $venueTypeFilter = $request->input('venue_type_filter'); // New venue type filter
        //$tab = $request->input('tab', 'active');
        $tab = $request->input('tab', 'all');  // Default to 'all'

        // Initialize variables with empty collections
        $activeEvents = collect();
        $suspendedEvents = collect();
        $requestedEvents = collect();
        $events = collect(); // This will hold all events for filtering

        if ($selectedRole === 'admin') {
            // Logic specific to admin
            $activeEvents = Event::where('event_status', 'active')
        ->when($search, function ($query) use ($search) {
            return $query->where('event_name', 'like', '%' . $search . '%');
        })
        ->when($dateFilter, function ($query) use ($dateFilter) {
            if ($dateFilter === 'upcoming') {
                return $query->where(function ($query) {
                    // Fetch single-day events
                    $query->whereNotNull('event_date')
                          ->where('event_date', '>', now())
                    // Fetch multi-day events
                          ->orWhere(function ($query) {
                              $query->whereNotNull('event_start_date')
                                    ->where('event_start_date', '>', now());
                          });
                });
            } elseif ($dateFilter === 'past') {
                return $query->where(function ($query) {
                    // Fetch single-day events
                    $query->whereNotNull('event_date')
                          ->where('event_date', '<', now())
                    // Fetch multi-day events
                          ->orWhere(function ($query) {
                              $query->whereNotNull('event_end_date')
                                    ->where('event_end_date', '<', now());
                          });
                });
            }
        })
        ->when($venueTypeFilter, function ($query) use ($venueTypeFilter) {
            return $query->where('event_type', $venueTypeFilter); // Filter by event type
        })
        ->paginate(6, ['*'], 'activePage');

        $suspendedEvents = Event::where('event_status', 'suspended')
            ->when($search, function ($query) use ($search) {
                return $query->where('event_name', 'like', '%' . $search . '%');
            })
            ->when($dateFilter, function ($query) use ($dateFilter) {
                if ($dateFilter === 'upcoming') {
                    return $query->where(function ($query) {
                        // Fetch single-day events
                        $query->whereNotNull('event_date')
                              ->where('event_date', '>', now())
                        // Fetch multi-day events
                              ->orWhere(function ($query) {
                                  $query->whereNotNull('event_start_date')
                                        ->where('event_start_date', '>', now());
                              });
                    });
                } elseif ($dateFilter === 'past') {
                    return $query->where(function ($query) {
                        // Fetch single-day events
                        $query->whereNotNull('event_date')
                              ->where('event_date', '<', now())
                        // Fetch multi-day events
                              ->orWhere(function ($query) {
                                  $query->whereNotNull('event_end_date')
                                        ->where('event_end_date', '<', now());
                              });
                    });
                }
            })
            ->when($venueTypeFilter, function ($query) use ($venueTypeFilter) {
                return $query->where('event_type', $venueTypeFilter); // Filter by event type
            })
            ->paginate(6, ['*'], 'suspendedPage');

        $requestedEvents = Event::where('event_status', 'pending')
            ->when($search, function ($query) use ($search) {
                return $query->where('event_name', 'like', '%' . $search . '%');
            })
            ->when($dateFilter, function ($query) use ($dateFilter) {
                if ($dateFilter === 'upcoming') {
                    return $query->where(function ($query) {
                        // Fetch single-day events
                        $query->whereNotNull('event_date')
                              ->where('event_date', '>', now())
                        // Fetch multi-day events
                              ->orWhere(function ($query) {
                                  $query->whereNotNull('event_start_date')
                                        ->where('event_start_date', '>', now());
                              });
                    });
                } elseif ($dateFilter === 'past') {
                    return $query->where(function ($query) {
                        // Fetch single-day events
                        $query->whereNotNull('event_date')
                              ->where('event_date', '<', now())
                        // Fetch multi-day events
                              ->orWhere(function ($query) {
                                  $query->whereNotNull('event_end_date')
                                        ->where('event_end_date', '<', now());
                              });
                    });
                }
            })
            ->when($venueTypeFilter, function ($query) use ($venueTypeFilter) {
                return $query->where('event_type', $venueTypeFilter); // Filter by event type
            })
            ->paginate(6, ['*'], 'requestedPage');

        return view('admin.events.index', compact('activeEvents', 'suspendedEvents', 'requestedEvents', 'tab'));
        } elseif ($selectedRole === 'event_organizer') {
            // Logic specific to event organizers
            $organizer = Organizer::where('user_id', $user->id)->firstOrFail();
            $organizerId = $organizer->organizer_id;
        
            // Active Events Query
            $activeEvents = Event::where('event_status', 'active')
                ->where('organizer_id', $organizerId)
                ->when($search, function ($query) use ($search) {
                    return $query->where('event_name', 'like', '%' . $search . '%');
                })
                ->when($dateFilter, function ($query) use ($dateFilter) {
                    if ($dateFilter === 'upcoming') {
                        return $query->where(function ($query) {
                            // Fetch single-day events
                            $query->whereNotNull('event_date')
                                  ->where('event_date', '>', now())
                            // Fetch multi-day events
                                  ->orWhere(function ($query) {
                                      $query->whereNotNull('event_start_date')
                                            ->where('event_start_date', '>', now());
                                  });
                        });
                    } elseif ($dateFilter === 'past') {
                        return $query->where(function ($query) {
                            // Fetch single-day events
                            $query->whereNotNull('event_date')
                                  ->where('event_date', '<', now())
                            // Fetch multi-day events
                                  ->orWhere(function ($query) {
                                      $query->whereNotNull('event_end_date')
                                            ->where('event_end_date', '<', now());
                                  });
                        });
                    }
                })
                ->when($venueTypeFilter, function ($query) use ($venueTypeFilter) {
                    return $query->where('event_type', $venueTypeFilter); // Filter by event type
                })
                ->paginate(6, ['*'], 'activePage');  // Note: Pagination page is set to 6 per page
        
            // Suspended Events Query
            $suspendedEvents = Event::where('event_status', 'suspended')
                ->where('organizer_id', $organizerId)
                ->when($search, function ($query) use ($search) {
                    return $query->where('event_name', 'like', '%' . $search . '%');
                })
                ->when($dateFilter, function ($query) use ($dateFilter) {
                    if ($dateFilter === 'upcoming') {
                        return $query->where(function ($query) {
                            // Fetch single-day events
                            $query->whereNotNull('event_date')
                                  ->where('event_date', '>', now())
                            // Fetch multi-day events
                                  ->orWhere(function ($query) {
                                      $query->whereNotNull('event_start_date')
                                            ->where('event_start_date', '>', now());
                                  });
                        });
                    } elseif ($dateFilter === 'past') {
                        return $query->where(function ($query) {
                            // Fetch single-day events
                            $query->whereNotNull('event_date')
                                  ->where('event_date', '<', now())
                            // Fetch multi-day events
                                  ->orWhere(function ($query) {
                                      $query->whereNotNull('event_end_date')
                                            ->where('event_end_date', '<', now());
                                  });
                        });
                    }
                })
                ->when($venueTypeFilter, function ($query) use ($venueTypeFilter) {
                    return $query->where('event_type', $venueTypeFilter); // Filter by event type
                })
                ->paginate(6, ['*'], 'suspendedPage');  // Paginate for suspended events
        
            // Requested Events Query (Pending Approval)
            $requestedEvents = Event::where('event_status', 'pending')
                ->where('organizer_id', $organizerId)
                ->when($search, function ($query) use ($search) {
                    return $query->where('event_name', 'like', '%' . $search . '%');
                })
                ->when($dateFilter, function ($query) use ($dateFilter) {
                    if ($dateFilter === 'upcoming') {
                        return $query->where(function ($query) {
                            // Fetch single-day events
                            $query->whereNotNull('event_date')
                                  ->where('event_date', '>', now())
                            // Fetch multi-day events
                                  ->orWhere(function ($query) {
                                      $query->whereNotNull('event_start_date')
                                            ->where('event_start_date', '>', now());
                                  });
                        });
                    } elseif ($dateFilter === 'past') {
                        return $query->where(function ($query) {
                            // Fetch single-day events
                            $query->whereNotNull('event_date')
                                  ->where('event_date', '<', now())
                            // Fetch multi-day events
                                  ->orWhere(function ($query) {
                                      $query->whereNotNull('event_end_date')
                                            ->where('event_end_date', '<', now());
                                  });
                        });
                    }
                })
                ->when($venueTypeFilter, function ($query) use ($venueTypeFilter) {
                    return $query->where('event_type', $venueTypeFilter); // Filter by event type
                })
                ->paginate(6, ['*'], 'requestedPage');  // Paginate for requested events
        
            // Returning the view with paginated results
            return view('organizer.events.index', compact('activeEvents', 'suspendedEvents', 'requestedEvents'));
        } elseif ($selectedRole === 'student') {
            // Student-specific logic to filter events based on selected tab
            if ($tab == 'registered') {
                // Show only registered events (exclude attended events)
                $events = Event::whereHas('participants', function ($query) use ($user) {
                    $query->where('attendance.stud_id', $user->student->stud_id)
                          ->where('attendance.status', 'registered');  // Only show events where status is "registered"
                })
                ->when($request->input('search'), function ($query, $search) {
                    return $query->where('event_name', 'like', '%' . $search . '%');
                })
                ->when($request->input('venue_type_filter'), function ($query, $venueTypeFilter) {
                    return $query->where('event_type', $venueTypeFilter); // Filter by event type
                })
                ->paginate(6);
            } elseif ($tab == 'past') {
                // Show only past events that the student attended
                $events = Event::whereHas('attendances', function ($query) use ($user) {
                        $query->where('stud_id', $user->student->stud_id)
                              ->where('status', 'attended'); // Only fetch attended events
                    })
                    ->where(function ($query) {
                        $query->whereNotNull('event_date') // For single-day events
                              ->where('event_date', '<', now())
                              ->orWhere(function ($query) {
                                  $query->whereNotNull('event_end_date') // For multi-day events
                                        ->where('event_end_date', '<', now());
                              });
                    })
                    ->when($request->input('search'), function ($query, $search) {
                        return $query->where('event_name', 'like', '%' . $search . '%');
                    })
                    ->when($request->input('venue_type_filter'), function ($query, $venueTypeFilter) {
                        return $query->where('event_type', $venueTypeFilter); // Filter by event type
                    })
                    ->paginate(6);
            } else {
                // Fetch all active events, categorizing based on attendance
                $events = Event::where('event_status', 'active')
                ->leftJoin('attendance', function ($join) use ($user) {
                    $join->on('events.event_id', '=', 'attendance.event_id')
                        ->where('attendance.stud_id', '=', $user->student->stud_id);
                })
                ->select('events.*')
                ->selectRaw("
                    CASE 
                        WHEN attendance.status = 'attended' THEN 3
                        WHEN attendance.status = 'registered' THEN 2
                        ELSE 1
                    END AS custom_order
                ")
                ->orderBy('custom_order', 'asc')  // Sort by custom order: 1 (not registered), 2 (registered), 3 (attended)
                ->when($request->input('search'), function ($query, $search) {
                    return $query->where('event_name', 'like', '%' . $search . '%');
                })
                ->when($request->input('date_filter'), function ($query, $dateFilter) {
                    if ($dateFilter === 'upcoming') {
                        return $query->where(function ($query) {
                            $query->whereNotNull('event_date')
                                  ->where('event_date', '>', now())
                                  ->orWhere(function ($query) {
                                      $query->whereNotNull('event_start_date')
                                            ->where('event_start_date', '>', now());
                                  });
                        });
                    } elseif ($dateFilter === 'past') {
                        return $query->where(function ($query) {
                            // Fetch single-day events
                            $query->whereNotNull('event_date')
                                  ->where('event_date', '<', now())
                            // Fetch multi-day events
                                  ->orWhere(function ($query) {
                                      $query->whereNotNull('event_end_date')
                                            ->where('event_end_date', '<', now());
                                  });
                        });
                    }
                })
                ->when($request->input('venue_type_filter'), function ($query, $venueTypeFilter) {
                    return $query->where('event_type', $venueTypeFilter); // Filter by event type
                })
                ->paginate(6);
            }
            return view('student.events.index', compact('events', 'tab'));
        } else {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }

    public function show($id)
    {
        $event = Event::with('organizer')->where('event_id', $id)->firstOrFail();
        $selectedRole = session('selected_role');

        if ($selectedRole === 'admin') {
            return view('admin.events.show', compact('event'));
        } elseif ($selectedRole === 'event_organizer') {
             // Get the authenticated student
            $student = Auth::user()->student;

            // Fetch existing feedback for this student
            $existingFeedback = Feedback::where('event_id', $event->event_id)
                ->where('stud_id', $student->stud_id)
                ->first();
            return view('organizer.events.show', compact('event'));
        } elseif ($selectedRole === 'student') {
             // Get the authenticated student
            $student = Auth::user()->student;

            // Fetch existing feedback for this student
            $existingFeedback = Feedback::where('event_id', $event->event_id)
                ->where('stud_id', $student->stud_id)
                ->first();
            return view('student.events.show', compact('event', 'existingFeedback')); // Pass existingFeedback
        } else {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }

    public function create()
    {
        // Fetch all available venues (You can filter if needed, for example, only venues that are available)
        $venues = Venue::where('venue_status', 'Available')->get();
    
        // Pass the venues to the view
        return view('organizer.events.create', compact('venues'));
    }    

    public function edit($id)
    {
        // Fetch the event being edited
        $event = Event::findOrFail($id);
    
        // Prepare date range for querying
        $startDate = $event->event_start_date ?? $event->event_date; // Use start date or event date for single-day events
        $endDate = $event->event_end_date ?? $event->event_date; // Use end date or event date for single-day events
    
        // Get all available venues, including the venue already booked by this event
        $availableVenues = Venue::whereDoesntHave('venueBooks', function ($query) use ($startDate, $endDate, $event) {
            $query->where('venue_id', '!=', $event->venue_id) // Exclude current venue from conflicts
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('booking_start_date', [$startDate, $endDate])
                            ->orWhereBetween('booking_end_date', [$startDate, $endDate])
                            ->orWhere(function ($query) use ($startDate, $endDate) {
                                $query->where('booking_start_date', '<=', $startDate)
                                    ->where('booking_end_date', '>=', $endDate);
                            });
                });
        })->orWhere('venue_id', $event->venue_id) // Ensure the event’s own venue is included
        ->get();
    
        // Pass the event and available venues to the view
        return view('organizer.events.edit', compact('event', 'availableVenues'));
    }

    public function update(Request $request, $id)
    {
        try {
            $event = Event::findOrFail($id);
    
            // Handle event suspension, reactivation, and code regeneration
            if ($request->has('suspend')) {
                $event->event_status = 'suspended';
                $event->save();
                return redirect()->route('organizer.events.index')->with('success', 'Event suspended successfully.');
            }
    
            if ($request->has('reactivate')) {
                $event->event_status = 'active';
                $event->save();
                return redirect()->route('organizer.events.index')->with('success', 'Event reactivated successfully.');
            }
    
            if ($request->has('requestActivation')) {
                $event->event_status = 'pending';
                $event->save();
                return redirect()->route('organizer.events.index')->with('success', 'Event activation requested successfully.');
            }
    
            if ($request->has('regenerate_code')) {
                $event->event_code = Str::random(8);
                $event->save();
                return redirect()->route('organizer.events.show', $event->event_id)->with('success', 'Event code regenerated successfully!');
            }
    
            // Validate the input
            $request->validate([
                'event_name' => 'required|string|max:255',
                'event_duration' => 'required|in:single,multiple',
                'event_type' => 'required|in:physical,online',
                'event_date' => 'required_if:event_duration,single|nullable|date',
                'event_start_date' => 'required_if:event_duration,multiple|nullable|date',
                'event_end_date' => 'required_if:event_duration,multiple|nullable|date|after_or_equal:event_start_date',
                'event_start_time' => ['required', 'date_format:H:i'], 
                'event_end_time' => ['nullable', 'date_format:H:i', 'after:event_start_time'],
                'venue_id' => 'required_if:event_type,physical|nullable|exists:venues,venue_id',  
                'online_platform' => 'nullable|string|max:255', // Allow null or string for online_platform
                'event_img' => 'nullable|image|max:2048',
                'event_desc' => 'required|string',
                'cert_template' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
                'cert_orientation' => 'required|in:portrait,landscape',
            ]);
    
            // Update event details based on single or multiple days
            if ($request->input('event_duration') === 'single') {
                $event->event_date = $request->input('event_date');
                $event->event_start_date = null;
                $event->event_end_date = null;
            } else {
                $event->event_start_date = $request->input('event_start_date');
                $event->event_end_date = $request->input('event_end_date');
                $event->event_date = null;
            }
    
            // Update other event details
            $event->event_name = $request->input('event_name');
            $event->event_start_time = $request->input('event_start_time');
            $event->event_end_time = $request->input('event_end_time');
            $event->event_desc = $request->input('event_desc');
            $event->event_type = $request->input('event_type');
    
            // For physical events, set venue_id; for online events, set online_platform
            if ($request->input('event_type') === 'physical') {
                $event->venue_id = $request->input('venue_id');
                $event->online_platform = null;  // Clear online platform
            } else {
                $event->online_platform = $request->input('online_platform');
                $event->venue_id = null;  // Clear venue_id
            }
    
            // Handle image upload
            if ($request->hasFile('event_img')) {
                if ($event->event_img && Storage::disk('public')->exists($event->event_img)) {
                    Storage::disk('public')->delete($event->event_img);
                }
                $path = $request->file('event_img')->store('event_images', 'public');
                $event->event_img = $path;
            }
    
            // Handle certificate template upload
            if ($request->hasFile('cert_template')) {
                if ($event->cert_template && Storage::disk('public')->exists($event->cert_template)) {
                    Storage::disk('public')->delete($event->cert_template);
                }
                $certPath = $request->file('cert_template')->store('cert_templates', 'public');
                $event->cert_template = $certPath;
            }
    
            // Update certificate orientation
            $event->cert_orientation = $request->input('cert_orientation');
    
            // Save the event
            $event->save();
    
            // Update venue_book table for physical events
            if ($request->input('event_type') === 'physical') {
                $bookingStartDate = $request->input('event_duration') === 'single' ? $request->input('event_date') : $request->input('event_start_date');
                $bookingEndDate = $request->input('event_duration') === 'single' ? $request->input('event_date') : $request->input('event_end_date');
                
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
            } else {
                // If the event type is online, remove any associated venue bookings
                VenueBook::where('event_id', $event->event_id)->delete();
            }
    
            return redirect()->route('organizer.events.show', $event->event_id)->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            // Display the error message
            dd($e->getMessage());
        }
    }    

    public function destroy($id)
    {
        $event = Event::where('event_id', $id)->firstOrFail();

        if ($event->event_img && Storage::disk('public')->exists($event->event_img)) {
            Storage::disk('public')->delete($event->event_img);
        }

        $event->delete();

        return redirect()->route('organizer.events.index')->with('success', 'Event deleted successfully');
    }

    public function suspend($id)
    {
        $event = Event::findOrFail($id);
        $event->event_status = 'suspended'; // Assuming you have an event_status column in your events table
        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Event suspended successfully.');
    }
    
    public function register($id)
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Get the associated student record for this user
        $student = $user->student; // Assuming User has a relationship with Student model
    
        if (!$student) {
            return redirect()->route('student.events.show', $id)->with('error', 'No student record found for this user.');
        }
    
        // Check if the student has already registered for the event
        $existingAttendance = Attendance::where('stud_id', $student->stud_id)
            ->where('event_id', $id)
            ->first();
    
        if ($existingAttendance) {
            return redirect()->route('student.events.show', $id)->with('error', 'You have already registered for this event.');
        }
    
        // Create a new attendance record
        Attendance::create([
            'stud_id' => $student->stud_id,
            'event_id' => $id,
            'status' => 'registered',
            'register_datetime' => now(),
        ]);
    
        // Fetch the user's email
        $userEmail = $user->email; // Get the email from the authenticated user
        $eventName = Event::find($id)->event_name; // Get the event name based on the event ID
    
        // Send the confirmation email
        Mail::to($userEmail)->send(new EventRegistrationMail($eventName)); // Use the user's email
    
        return redirect()->route('student.events.show', $id)->with('success', 'You have successfully registered for the event! A confirmation email has been sent to you.');
    }    
    
    public function showParticipants($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        $participants = Attendance::where('event_id', $eventId)
            ->with(['student.user'])
            ->get()
            ->map(function($attendance) {
                return (object) [
                    'stud_name' => $attendance->student->stud_name,
                    'matric_no' => $attendance->student->user->matric_no,
                    'email' => $attendance->student->user->email,
                    'stud_phoneNo' => $attendance->student->stud_phoneNo,
                    'stud_course' => $attendance->student->stud_course,
                    'register_datetime' => $attendance->register_datetime,
                    'status' => $attendance->status,  // Add the status field
                    'attendance_datetime' => $attendance->attendance_datetime // Add the attendance_datetime field
                ];
            });
        
        return view('organizer.events.participants', compact('event', 'participants'));
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

    public function confirmAttendance(Request $request, $eventId)
    {
        // Validate input
        $request->validate([
            'event_code' => 'required|string',
        ]);

        // Find the event
        $event = Event::findOrFail($eventId);

        // Check if the provided event code matches
        if ($event->event_code !== $request->event_code) {
            return redirect()->route('student.events.show', $eventId)->with('error', 'Invalid event code. Please try again.');
        }

        // Get the authenticated user and their student record
        $user = Auth::user();
        $student = $user->student;

        // Check if the student is registered for the event
        $attendance = Attendance::where('stud_id', $student->stud_id)
            ->where('event_id', $eventId)
            ->first();

        if (!$attendance) {
            return redirect()->route('student.events.show', $eventId)->with('error', 'You are not registered for this event.');
        }

        // Update the attendance status and record the date and time
        $attendance->status = 'attended';
        $attendance->attendance_datetime = now();
        $attendance->save();

        return redirect()->route('student.events.show', $eventId)->with('success', 'Attendance confirmed successfully.');
    }

    // public function checkVenueAvailability(Request $request)
    // {
    //     // Validate the incoming request data
    //     $request->validate([
    //         'venue_id' => 'required|integer',
    //         'event_date' => 'required|date',
    //         'event_start_time' => 'required|date_format:H:i',
    //         'event_end_time' => 'required|date_format:H:i',
    //     ]);

    //     $venueId = $request->input('venue_id');
    //     $eventDate = $request->input('event_date');
    //     $eventStartTime = $request->input('event_start_time');
    //     $eventEndTime = $request->input('event_end_time');

    //     // Check for existing events in the database
    //     $events = Event::where('venue_id', $venueId)
    //         ->whereDate('event_date', $eventDate)
    //         ->where(function($query) use ($eventStartTime, $eventEndTime) {
    //             $query->whereBetween('event_start_time', [$eventStartTime, $eventEndTime])
    //                   ->orWhereBetween('event_end_time', [$eventStartTime, $eventEndTime]);
    //         })
    //         ->exists();

    //     // Return the availability status as JSON
    //     return response()->json(['available' => !$events]);
    // } 

    // public function checkVenueAvailability(Request $request)
    // {
    //     $request->validate([
    //         'event_type' => 'required|in:physical,online',
    //         'event_duration' => 'required|in:single,multiple',
    //         'event_date' => 'nullable|date',
    //         'event_start_date' => 'nullable|date',
    //         'event_end_date' => 'nullable|date|after_or_equal:event_start_date',
    //         'event_start_time' => 'required|date_format:H:i',
    //         'event_end_time' => 'required|date_format:H:i|after:event_start_time',
    //     ]);

    //     if ($request->event_type !== 'physical') {
    //         return response()->json(['availableVenues' => []]); // No venues needed for online events
    //     }

    //     // Determine the date range based on event duration
    //     $startDate = $request->event_duration === 'single' ? $request->event_date : $request->event_start_date;
    //     $endDate = $request->event_duration === 'single' ? $request->event_date : $request->event_end_date;
    //     $startTime = $request->event_start_time;
    //     $endTime = $request->event_end_time;

    //     // Query to find available venues
    //     $availableVenues = Venue::whereDoesntHave('venueBooks', function ($query) use ($startDate, $endDate, $startTime, $endTime) {
    //         $query->where(function ($dateQuery) use ($startDate, $endDate) {
    //             $dateQuery->whereBetween('booking_start_date', [$startDate, $endDate])
    //                     ->orWhereBetween('booking_end_date', [$startDate, $endDate])
    //                     ->orWhere(function ($query) use ($startDate, $endDate) {
    //                         $query->where('booking_start_date', '<=', $startDate)
    //                                 ->where('booking_end_date', '>=', $endDate);
    //                     });
    //         })->where(function ($timeQuery) use ($startTime, $endTime) {
    //             $timeQuery->whereBetween('booking_start_time', [$startTime, $endTime])
    //                     ->orWhereBetween('booking_end_time', [$startTime, $endTime])
    //                     ->orWhere(function ($query) use ($startTime, $endTime) {
    //                         $query->where('booking_start_time', '<=', $startTime)
    //                                 ->where('booking_end_time', '>=', $endTime);
    //                     });
    //         });
    //     })->get(['venue_id', 'venue_name']);

    //     return response()->json(['availableVenues' => $availableVenues]);
    // }

    public function checkVenueAvailability(Request $request)
    {
        $request->validate([
            'event_type' => 'required|in:physical,online',
            'event_duration' => 'required|in:single,multiple',
            'event_date' => 'nullable|date',
            'event_start_date' => 'nullable|date',
            'event_end_date' => 'nullable|date|after_or_equal:event_start_date',
            'event_start_time' => 'required|date_format:H:i',
            'event_end_time' => 'required|date_format:H:i|after:event_start_time',
            'event_id' => 'nullable|integer', // for edit scenarios
        ]);
    
        // If the event is online, no physical venue required
        if ($request->event_type !== 'physical') {
            return response()->json(['availableVenues' => []]);
        }
    
        // Determine date range based on event duration
        $startDate = $request->event_duration === 'single' ? $request->event_date : $request->event_start_date;
        $endDate = $request->event_duration === 'single' ? $request->event_date : $request->event_end_date;
        $startTime = $request->event_start_time;
        $endTime = $request->event_end_time;
        $eventId = $request->event_id;
    
        // Query for available venues while excluding conflicts
        $availableVenues = Venue::whereDoesntHave('venueBooks', function ($query) use ($startDate, $endDate, $startTime, $endTime, $eventId) {
            $query->where(function ($dateQuery) use ($startDate, $endDate) {
                $dateQuery->whereBetween('booking_start_date', [$startDate, $endDate])
                    ->orWhereBetween('booking_end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('booking_start_date', '<=', $startDate)
                              ->where('booking_end_date', '>=', $endDate);
                    });
            })
            ->where(function ($timeQuery) use ($startTime, $endTime) {
                $timeQuery->whereBetween('booking_start_time', [$startTime, $endTime])
                    ->orWhereBetween('booking_end_time', [$startTime, $endTime])
                    ->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('booking_start_time', '<=', $startTime)
                              ->where('booking_end_time', '>=', $endTime);
                    });
            })
            ->when($eventId, function ($query) use ($eventId) {
                // Exclude current event ID from the conflict check
                $query->where('event_id', '!=', $eventId);
            });
        })->get(['venue_id', 'venue_name']);
    
        // Return the available venues list [for json derulo] (only venues without conflict will be listed)
        return response()->json(['availableVenues' => $availableVenues]);
    }
}