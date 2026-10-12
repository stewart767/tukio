<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the events for the user.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the invoices for the user.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the events assigned to this staff member (single assignment - legacy).
     */
    public function assignedEvents()
    {
        return $this->hasMany(Event::class, 'staff_id');
    }

    /**
     * Get all events assigned to this staff member (many-to-many).
     */
    public function assignedEventsMany()
    {
        return $this->belongsToMany(Event::class, 'event_staff', 'user_id', 'event_id')
                    ->withTimestamps();
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is event admin.
     */
    public function isEventAdmin()
    {
        return $this->role === 'event_admin';
    }

    /**
     * Check if user is staff.
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }
}
