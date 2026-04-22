<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $table = 'store_settings';

    protected $fillable = [
        'store_name',
        'store_contact',
        'store_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Get the first store setting (singleton pattern)
    public static function getSettings()
    {
        return self::first() ?? new self();
    }
}
