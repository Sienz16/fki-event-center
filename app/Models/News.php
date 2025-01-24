<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $primaryKey = 'news_id';
    protected $fillable = [
        'management_id',
        'news_title',
        'news_details',
        'news_tag',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Define the relationship between News and Admin.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'management_id', 'management_id');
    }
}

