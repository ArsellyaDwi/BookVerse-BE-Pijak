<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';
    
    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get user by email
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}