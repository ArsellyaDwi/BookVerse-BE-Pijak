<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    protected $table = 'delivery_addresses';

    protected $fillable = [
        'user_id',
        'province',
        'city',
        'district',
        'village',
        'address',
        'lat',
        'long',
        'is_default'
    ];

    protected $casts = [
        'lat' => 'float',
        'long' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'delivery_address_id');
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->village}, {$this->district}, {$this->city}, {$this->province}";
    }
}
