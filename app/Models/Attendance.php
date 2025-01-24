<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $primaryKey = 'attendance_id';

    // Explicitly specify the table name
    protected $table = 'attendance';

    protected $fillable = [
        'stud_id',
        'event_id',
        'status',
        'register_datetime',
        'attendance_datetime',
    ];

    /**
     * Define the relationship with the Student model.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'stud_id');
    }

    /**
     * Define the relationship with the Event model.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}

