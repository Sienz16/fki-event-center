<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $primaryKey = 'feedback_id';

    protected $fillable = ['event_id', 'stud_id', 'feedback', 'rating'];

    // Define relationship to Event
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Define relationship to Student
    public function student()
    {
        return $this->belongsTo(Student::class, 'stud_id');
    }
}
