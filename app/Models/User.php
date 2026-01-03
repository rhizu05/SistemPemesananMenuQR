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
        'address',
        'phone_verified_at',
        'otp_code',
        'otp_expires_at',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
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
            'phone_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi ke Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Check if user has admin role
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has kitchen role
     */
    public function isKitchen()
    {
        return $this->role === 'kitchen';
    }

    /**
     * Check if user has customer role
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    /**
     * Check if user has cashier role
     */
    public function isCashier()
    {
        return $this->role === 'cashier';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        // Admin has access to everything except if specifically checked
        if ($this->role === 'admin') {
            return true;
        }
        return $this->role === $role;
    }
}
