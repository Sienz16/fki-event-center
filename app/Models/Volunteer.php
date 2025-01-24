<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $primaryKey = 'volunteer_id';

    protected $fillable = [
        'event_id',
        'organizer_id',
        'stud_id',
        'status',
        'volunteer_capacity',
        'notes'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'stud_id');
    }

    public function volunteerRequests()
    {
        return $this->hasMany(VolunteerRequest::class, 'volunteer_id');
    }

    public function acceptedVolunteersCount()
    {
        return $this->volunteerRequests()->where('status', 'accepted')->count();
    }

    public function remainingVolunteersNeeded()
    {
        // Calculate the number of accepted volunteers
        $acceptedCount = $this->volunteerRequests()->where('status', 'accepted')->count();
        
        // Subtract from the volunteer capacity to get the remaining needed
        return max($this->volunteer_capacity - $acceptedCount, 0);
    }
}

