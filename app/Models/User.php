<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Add 'role' field here
        'foto', // Add 'foto' field here
        'theme_mode', // Add 'theme_mode' field here
        'timezone', // Add 'timezone' field here
        'language', // Add 'language' field here
        'notification_email', // Add 'notification_email' field here
        'notification_web', // Add 'notification_web' field here
    ];

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
        'password' => 'hashed',
        'notification_email' => 'boolean',
        'notification_web' => 'boolean',
    ];

    public function isSuper()
    {
        return $this->role == 'superadmin'; // Adjust the value if you use a different name for superadmin
    }

    /**
     * Check if the user is an Admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role == 'admin'; // Adjust the value if you use a different name for admin
    }
}
