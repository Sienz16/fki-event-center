<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'matric_no',
        'email',
        'password',
    ];

    /**
     * Define the many-to-many relationship with roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Define the one-to-many relationship with events as an organizer.
     */
    public function organizedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer_id', 'id');
    }

    /**
     * Define the one-to-many relationship with managed venues.
     */
    public function managedVenues(): HasMany
    {
        return $this->hasMany(Venue::class, 'management_id', 'id');
    }

    /**
     * Define the one-to-one relationship with the organizer.
     */
    public function organizer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Organizer::class, 'user_id', 'id');
    }

    /**
     * Define the one-to-one relationship with the student.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Define the one-to-one relationship with the admin.
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id', 'id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}


