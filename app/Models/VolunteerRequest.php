<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerRequest extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'volunteer_requests';

    // Specify the primary key for the model
    protected $primaryKey = 'request_id';

    // The attributes that are mass assignable
    protected $fillable = [
        'volunteer_id', // Reference to the associated volunteer
        'stud_id',      // Reference to the student who applied
        'status',       // Status of the request (e.g., pending, accepted, rejected)
        'created_at',   // Timestamp for when the request was created
        'updated_at',   // Timestamp for when the request was updated
    ];

    // Define the relationship with the Volunteer model
    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }

    // Define the relationship with the Student model
    public function student()
    {
        return $this->belongsTo(Student::class, 'stud_id');
    }

    // Define the relationship with the Event model through Volunteer
    public function event()
    {
        return $this->hasOneThrough(
            Event::class,
            Volunteer::class,
            'volunteer_id', // Foreign key on volunteer_requests table
            'event_id',     // Foreign key on events table
            'volunteer_id', // Local key on volunteer_requests table
            'event_id'      // Local key on volunteers table
        );
    }
}
