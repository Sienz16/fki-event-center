<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumAction extends Model
{
    use HasFactory;

    // Define the table name if it's different from the default
    protected $table = 'forum_action';

    // Specify the correct primary key
    protected $primaryKey = 'act_id';

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'stud_id',
        'com_id',
        'action_type',
    ];

    /**
     * Define the relationship between ForumAction and the CommunityForum.
     * Each action belongs to a specific community post.
     */
    public function communityForum()
    {
        return $this->belongsTo(CommunityForum::class, 'com_id');
    }

    /**
     * Define the relationship between ForumAction and the User/Student.
     * Each action is performed by a specific student.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'stud_id');
    }
}

