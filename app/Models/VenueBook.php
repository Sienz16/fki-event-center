<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueBook extends Model
{
    use HasFactory;

    // Define the table name (optional if the table name matches the model name in lowercase)
    protected $table = 'venue_book';

    protected $primaryKey = 'venue_book_id';

    // Fillable fields for mass assignment
    protected $fillable = [
        'event_id',
        'venue_id',
        'booking_start_date',
        'booking_end_date',
        'booking_start_time',
        'booking_end_time',
    ];

    // Relationship with the Event model
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    // Relationship with the Venue model
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'venue_id');
    }

    public static function checkAvailability($data)
    {
        return self::where('venue_id', $data['venue_id'])
            ->where(function ($query) use ($data) {
                $query->applyDateTimeConditions($data['event_start_date'], $data['event_end_date'], $data['event_start_time'], $data['event_end_time']);
            })
            ->exists();
    }

    public function scopeApplyDateTimeConditions($query, $startDate, $endDate, $startTime, $endTime, $excludeEventId = null)
    {
        return $query->whereBetween('booking_start_date', [$startDate, $endDate])
            ->orWhereBetween('booking_end_date', [$startDate, $endDate])
            ->orWhere(function ($subQuery) use ($startDate, $endDate) {
                $subQuery->where('booking_start_date', '<=', $startDate)
                    ->where('booking_end_date', '>=', $endDate);
            })
            ->whereBetween('booking_start_time', [$startTime, $endTime])
            ->orWhereBetween('booking_end_time', [$startTime, $endTime])
            ->when($excludeEventId, fn($q) => $q->where('event_id', '!=', $excludeEventId));
    }
}
