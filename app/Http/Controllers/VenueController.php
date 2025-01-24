<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\VenueBook;
use App\Models\Organizer;
use App\Models\Admin;
use App\Models\User;
use App\Notifications\EventNotification;
use App\Notifications\NewVenueNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VenueController extends Controller
{
    public function index()
    {
        $selectedRole = session('selected_role');
        
        if ($selectedRole === 'admin') {
            $admin = Admin::where('user_id', Auth::id())->firstOrFail();

            $venues = Venue::where('management_id', $admin->management_id)
                          ->with(['venueBooks.event' => function($query) {
                              $query->whereDate('event_date', '>=', now())
                                   ->orWhere(function($q) {
                                       $q->whereDate('event_start_date', '>=', now())
                                         ->orWhereDate('event_end_date', '>=', now());
                                   })
                                   ->orderByRaw('COALESCE(event_date, event_start_date) ASC')
                                   ->orderBy('event_start_time');
                          }])
                          ->paginate(6);
                           
            return view('admin.venue.index', compact('venues'));
        } elseif ($selectedRole === 'event_organizer') {
            $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();

            $venues = Venue::orderByRaw("FIELD(venue_status, 'Available', 'Under Maintenance')")
                          ->paginate(6);

            $bookedVenues = Venue::whereIn('venue_id', function ($query) use ($organizer) {
                $query->select('venue_id')
                      ->from('venue_book')
                      ->whereIn('event_id', function ($subQuery) use ($organizer) {
                          $subQuery->select('event_id')
                                    ->from('events')
                                    ->where('organizer_id', $organizer->organizer_id);
                      });
            })->with('organizer')->get();

            return view('organizer.venue.index', compact('venues', 'bookedVenues'));
        } else {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }   

    public function create()
    {
        return view('admin.venue.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'venue_name' => 'required|string|max:255',
            'venue_location' => 'required|string|max:500',
            'venue_status' => 'required|in:Available,Booked,Under Maintenance',
            'venue_details' => 'nullable|string',
            'venue_image' => 'nullable|image|max:10240',
            'capacity' => 'nullable|integer|min:1',
            'equipment' => 'nullable|string',
        ]);

        // Fetch the admin associated with the authenticated user
        $admin = Admin::where('user_id', Auth::id())->firstOrFail();

        // Create the new venue and assign the management_id from the Admin model
        $venue = new Venue();
        $venue->management_id = $admin->management_id;
        $venue->venue_name = $request->venue_name;
        $venue->venue_location = $request->venue_location;
        $venue->venue_status = $request->venue_status;
        $venue->venue_details = $request->venue_details;
        $venue->capacity = $request->capacity;
        $venue->equipment = $request->equipment;

        // Handle the venue image upload
        if ($request->hasFile('venue_image')) {
            $path = $request->file('venue_image')->store('venue_images', 'public');
            $venue->venue_image = $path;
        }

        $venue->save();

        // Notify all organizers about the new venue
        $organizers = Organizer::all();
        foreach ($organizers as $organizer) {
            $organizer->user->notify(new NewVenueNotification(
                'New Venue Available',
                "A new venue '{$venue->venue_name}' with capacity of {$venue->capacity} is now available for booking.",
                $venue->venue_id
            ));
        }

        return redirect()->route('admin.venue.index')->with('success', 'Venue added successfully!');
    }
    
    public function edit(Venue $venue)
    {
        return view('admin.venue.edit', compact('venue'));
    }
        
    public function update(Request $request, Venue $venue)
    {
        $validatedData = $request->validate([
            'venue_name' => 'required|string|max:255',
            'venue_location' => 'required|string|max:500',
            'venue_status' => 'required|in:Available,Booked,Under Maintenance',
            'venue_details' => 'nullable|string',
            'venue_image' => 'nullable|image|max:10240',
            'capacity' => 'nullable|integer|min:1',
            'equipment' => 'nullable|string',
        ]);

        // Handle the venue image update
        if ($request->hasFile('venue_image')) {
            if ($venue->venue_image) {
                Storage::disk('public')->delete($venue->venue_image);
            }
            $path = $request->file('venue_image')->store('venue_images', 'public');
            $validatedData['venue_image'] = $path;
        }

        $venue->update($validatedData);

        return redirect()->route('admin.venue.index')->with('success', 'Venue updated successfully.');
    }        
     
    public function destroy(Venue $venue)
    {
        $venue->delete();
        return redirect()->route('admin.venue.index')->with('success', 'Venue deleted successfully.');
    }    

    public function show($id)
    {
        $selectedRole = session('selected_role');
        $venue = Venue::with('events')->findOrFail($id);

        if ($selectedRole === 'admin') {
            // Fetch any additional data needed for the admin view, if necessary
            return view('admin.venue.show', compact('venue'));
        } elseif ($selectedRole === 'event_organizer') {
            return view('organizer.venue.show', compact('venue'));
        } else {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }

    public function book(Request $request, $venue_id)
    {
        $venue = Venue::findOrFail($venue_id);

        if ($venue->venue_status !== 'Available') {
            return redirect()->route('organizer.venues.index')->with('error', 'This venue is not available for booking.');
        }

        $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();

        $venue->venue_status = 'Booked';
        $venue->organizer_id = $organizer->organizer_id;
        $venue->save();

        // Notify admins about venue booking
        $admins = User::whereHas('roles', function($query) {
            $query->where('role_name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new EventNotification(
                'New Venue Booking',
                "Venue '{$venue->venue_name}' has been booked by {$organizer->organizer_name}.",
                null,
                'venue_booked'
            ));
        }

        return redirect()->route('organizer.venues.index')->with('success', 'Venue booked successfully!');
    }  

    public function removeBooking($venue_id)
    {
        // Find the venue
        $venue = Venue::findOrFail($venue_id);
    
        // Fetch the organizer associated with the authenticated user
        $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();
    
        // Check if the authenticated organizer booked the venue
        if ($venue->organizer_id === $organizer->organizer_id) {
            $venue->venue_status = 'Available';
            $venue->organizer_id = null;
            $venue->save();
    
            return redirect()->route('organizer.venues.index', ['activeTab' => 2])
                             ->with('success', 'Booking removed successfully!');
        }
    
        return redirect()->route('organizer.profile.index', ['activeTab' => 2])
                         ->with('error', 'Unauthorized action.');
    }
    
    public function getAvailableVenues(Request $request)
    {
        Log::info('Venue availability check request:', $request->all());

        try {
            // Get all available venues
            $venues = Venue::where('venue_status', 'Available')->get();

            // If this is for an existing event, get its current venue
            $currentVenueId = $request->input('current_venue_id');
            $eventId = $request->input('event_id');

            // If no date/time constraints are provided, return all available venues
            if (!$request->filled(['event_start_time', 'event_end_time']) || 
                (!$request->filled('event_date') && !$request->filled(['event_start_date', 'event_end_date']))) {
                return response()->json([
                    'availableVenues' => $venues,
                    'currentVenueAvailable' => true
                ]);
            }

            // Filter venues based on booking conflicts
            $availableVenues = $venues->filter(function($venue) use ($request, $eventId) {
                $startDate = $request->event_duration === 'single' ? 
                    $request->event_date : 
                    $request->event_start_date;
                
                $endDate = $request->event_duration === 'single' ? 
                    $request->event_date : 
                    $request->event_end_date;

                // Don't count the event's own booking when checking availability
                return !$this->checkVenueAvailability(
                    $venue->venue_id,
                    $startDate,
                    $endDate,
                    $request->event_start_time,
                    $request->event_end_time,
                    $eventId
                );
            });

            // Check if current venue is still available
            $currentVenueAvailable = true;
            if ($currentVenueId) {
                $currentVenueAvailable = $availableVenues->contains('venue_id', $currentVenueId);
            }

            return response()->json([
                'availableVenues' => $availableVenues->values(),
                'currentVenueAvailable' => $currentVenueAvailable
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getAvailableVenues:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to fetch available venues',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkVenueAvailability($venueId, $startDate, $endDate, $startTime, $endTime, $excludeEventId = null)
    {
        $query = VenueBook::where('venue_id', $venueId);

        // Exclude the current event's booking when checking availability
        if ($excludeEventId) {
            $query->where('event_id', '!=', $excludeEventId);
        }

        return $query->where(function ($query) use ($startDate, $endDate, $startTime, $endTime) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('booking_start_date', [$startDate, $endDate])
                        ->orWhereBetween('booking_end_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('booking_start_date', '<=', $startDate)
                                ->where('booking_end_date', '>=', $endDate);
                        });
                })
                ->where(function ($q) use ($startTime, $endTime) {
                    $q->whereBetween('booking_start_time', [$startTime, $endTime])
                        ->orWhereBetween('booking_end_time', [$startTime, $endTime])
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            $q->where('booking_start_time', '<=', $startTime)
                                ->where('booking_end_time', '>=', $endTime);
                        });
                });
            })->exists();
    }
}
