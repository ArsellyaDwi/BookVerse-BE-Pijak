<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

#[Fillable([
    'name',
    'email',
    'password',
    'phone',
    'gender',
    'birth_date',
    'address',
    'city',
    'province',
    'postal_code',
    'extroversion',
    'neuroticism',
    'agreeableness',
    'conscientiousness',
    'openness'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_CUSTOMER = 'customer';

    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_CUSTOMER => 'Customer',
        ];
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCustomer()
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function recommendations()
    {
        return $this->hasMany(AiRecommendationLog::class);
    }
    /**
     * Get all quotes posted by this user
     */
    public function quotes()
    {
        return $this->hasMany(BookQuote::class, 'user_id');
    }

    /**
     * Get quotes liked by this user
     */
    public function likedQuotes()
    {
        return $this->belongsToMany(BookQuote::class, 'quote_likes', 'user_id', 'quote_id');
    }

    /**
     * Get quotes saved by this user
     */
    public function savedQuotes()
    {
        return $this->belongsToMany(BookQuote::class, 'user_saved_quotes', 'user_id', 'quote_id');
    }
}
