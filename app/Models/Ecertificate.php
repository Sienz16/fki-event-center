<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ecertificate extends Model
{
    use HasFactory;

    protected $table = 'ecertificates';

    protected $primaryKey = 'ecert_id';

    protected $fillable = [
        'stud_id',
        'event_id',
        'ecert_file',
        'unique_code',
        'ecert_datetime',
    ];

    // Relationship with the Student model
    public function student()
    {
        return $this->belongsTo(Student::class, 'stud_id');
    }

    // Relationship with the Event model
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
