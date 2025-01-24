<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'students';

    // The primary key associated with the table.
    protected $primaryKey = 'stud_id';

    // The attributes that are mass assignable.
    protected $fillable = [
        'stud_name',
        'stud_age',
        'stud_course',
        'stud_phoneNo',
        'stud_detail',
        'stud_img', 
    ];

    /**
     * Define the relationship with the User model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the Attendance model.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'stud_id');
    }

    /**
     * Define the many-to-many relationship with the Event model through Attendance.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'attendance', 'stud_id', 'event_id')
                    ->withPivot('status', 'register_datetime', 'attendance_datetime')
                    ->withTimestamps();
    }

    public function ecertificates()
    {
        return $this->hasMany(Ecertificate::class, 'stud_id');
    }

    public function volunteerRequests()
    {
        return $this->hasMany(VolunteerRequest::class, 'stud_id');
    }    
}
