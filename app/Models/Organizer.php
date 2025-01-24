<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;

    protected $table = 'event_organizers';

    // Set the primary key for the model
    protected $primaryKey = 'organizer_id';

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'user_id',
        'org_name',
        'org_age',
        'org_course',
        'org_position',
        'org_phoneNo',
        'org_detail',
        'org_img',
    ];

    /**
     * Define the relationship between Organizer and User.
     * Each organizer belongs to one user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Define the relationship between Organizer and Event.
     * An organizer can have multiple events.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id', 'organizer_id');
    }

    public function volunteers()
    {
        return $this->hasMany(Volunteer::class, 'organizer_id');
    }
    public function communityForums()
    {
        return $this->hasMany(CommunityForum::class, 'organizer_id');
    }
}

