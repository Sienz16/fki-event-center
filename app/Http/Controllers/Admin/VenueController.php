<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Organizer;
use App\Notifications\NewVenueNotification;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function store(Request $request)
    {
        // Your existing venue creation logic
        $venue = Venue::create($request->validated());

        // Notify all organizers about the new venue
        $organizers = Organizer::all();
        foreach ($organizers as $organizer) {
            $organizer->user->notify(new NewVenueNotification(
                'New Venue Available',
                "A new venue '{$venue->name}' is now available for booking.",
                $venue->id
            ));
        }

        return redirect()->route('admin.venues.index');
    }
} 