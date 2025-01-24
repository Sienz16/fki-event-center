<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'event_name',
        'event_code',
        'event_type',
        'event_date',
        'event_start_date',
        'event_end_date',
        'event_start_time',
        'event_end_time',
        'venue_type',
        'venue_id',
        'other_venue_name',
        'online_platform',
        'event_img',
        'event_desc',
        'cert_template',
        'cert_orientation',
        'event_status',
        'template_status',
    ];

    // Specify the custom primary key for this model
    protected $primaryKey = 'event_id';

    // If your primary key is not auto-incrementing, you can specify that here.
    public $incrementing = true;

    // If the primary key is not an integer, specify its type
    protected $keyType = 'int';

    protected $dates = [
        'created_at',
        'updated_at',
        'event_date',
        'event_start_date',
        'event_end_date',
        // ... any other date fields
    ];

    protected $casts = [
        'updated_at' => 'datetime',
        // ... other casts
    ];

    /**
     * Get the organizer that manages the event.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class, 'organizer_id', 'organizer_id');
    }

    /**
     * Define the relationship with the Attendance model.
     * This will allow you to access all student registrations and attendance for the event.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'event_id');
    }

    public function participants()
    {
        return $this->belongsToMany(Student::class, 'attendance', 'event_id', 'stud_id')
                    ->withPivot('register_datetime');
    }

    public function ecertificates()
    {
        return $this->hasMany(Ecertificate::class, 'event_id');
    }

    // Define the relationship between Event and Volunteer
    public function volunteers()
    {
        return $this->hasMany(Volunteer::class, 'event_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'event_id', 'event_id');
    }

    // Define the relationship to venues
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'venue_id');
    }

    public function volunteer()
    {
        return $this->hasOne(Volunteer::class, 'event_id', 'event_id');
    }

    public static function filterEvents($filters, $role)
    {
        $query = self::query();
    
        if (!empty($filters['search'])) {
            $query->where('event_name', 'like', '%' . $filters['search'] . '%');
        }
    
        if (!empty($filters['date_filter'])) {
            $query->filterByDate($filters['date_filter']);
        }
    
        if (!empty($filters['venueTypeFilter'])) {
            $query->where('event_type', $filters['venueTypeFilter']);
        }
    
        return $query; // Return the query builder instance
    }

    public static function createEvent($data, $organizer_id)
    {
        return self::create([
            'organizer_id' => $organizer_id,
            'event_name' => $data['event_name'],
            'event_code' => Str::random(8),
            'event_type' => $data['event_type'],
            // Set other fields as needed from $data...
        ]);
    }

    public function updateEventDetails($data)
    {
        $this->update([
            'event_name' => $data['event_name'],
            'event_type' => $data['event_type'],
            'event_desc' => $data['event_desc'],
            'event_start_date' => $data['event_start_date'] ?? null,
            'event_end_date' => $data['event_end_date'] ?? null,
            'venue_id' => $data['event_type'] === 'physical' ? $data['venue_id'] : null,
            'online_platform' => $data['event_type'] === 'online' ? $data['online_platform'] : null,
        ]);
    }

    public function updateStatus($status)
    {
        $this->update(['event_status' => $status]);
    }

    public function regenerateCode()
    {
        $this->update(['event_code' => Str::random(8)]);
    }

    public static function generateUniqueCode()
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (static::where('event_code', $code)->exists());

        return $code;
    }

    /**
     * Get the students registered for this event.
     */
    public function registeredStudents()
    {
        return $this->belongsToMany(Student::class, 'attendance', 'event_id', 'stud_id')
            ->where('status', 'registered')
            ->withPivot('status', 'attendance_datetime')
            ->withTimestamps();
    }
}