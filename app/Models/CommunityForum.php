<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityForum extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the default
    protected $table = 'community_forum';

    // Set the primary key for the model
    protected $primaryKey = 'com_id';

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'organizer_id',
        'img',
        'desc',
    ];

    /**
     * Define the relationship between CommunityForum and Organizer.
     * Each community forum post belongs to one organizer.
     */
    public function Organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    /**
     * Check if the given user has liked this post.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isLikedBy(?User $user): bool
    {
        // Check if user is null, return false if not authenticated
        if (!$user) {
            return false;
        }
    
        return ForumAction::where('com_id', $this->com_id)
                          ->where('stud_id', $user->student->stud_id)  // Check for the student ID
                          ->where('action_type', 'like')
                          ->exists();
    }
    
    /**
     * Relationship with the forum actions (likes, views, etc.)
     */
    public function actions()
    {
        return $this->hasMany(ForumAction::class, 'com_id');
    }
}
