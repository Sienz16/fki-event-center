<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Admin extends Model
{
    use HasFactory;
    use Notifiable;

    protected $primaryKey = 'management_id';

    protected $fillable = [
        'user_id',
        'manage_name',
        'manage_phoneNo',
        'manage_email',
        'manage_position',
        'manage_img',
        'manage_detail',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}