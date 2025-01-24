<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $primaryKey = 'venue_id';

    protected $fillable = [
        'management_id',
        'venue_name',
        'venue_location',
        'venue_status',
        'venue_details',
        'venue_image',
        'capacity',
        'equipment',
    ];

    // Relationship with Admin for management
    public function management()
    {
        return $this->belongsTo(Admin::class, 'management_id');
    }

    // Relationship with User for organizer
    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    // Define the relationship to events
    public function events()
    {
        return $this->hasMany(Event::class, 'venue_id', 'venue_id');
    }

    public function venueBooks()
    {
        return $this->hasMany(VenueBook::class, 'venue_id', 'venue_id');
    }
}

