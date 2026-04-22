<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    protected $table = 'delivery_methods';

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'books_per_multiplier',
        'is_active',
        'estimated_days_min',
        'estimated_days_max',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'books_per_multiplier' => 'integer',
        'is_active' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function calculateShippingCost($totalBooks)
    {
        if ($totalBooks <= 0) {
            return 0;
        }

        $multiplier = ceil($totalBooks / $this->books_per_multiplier);

        $cost = $this->base_price * $multiplier;
        return $cost;
    }

    /**
     * Get multiplier info for display
     */
    public function getMultiplierInfoAttribute()
    {
        return "Every {$this->books_per_multiplier} books, base price multiplied";
    }

    /**
     * Get estimated delivery days
     */
    public function getEstimatedDaysAttribute()
    {
        if ($this->estimated_days_min && $this->estimated_days_max) {
            return "{$this->estimated_days_min} - {$this->estimated_days_max} days";
        }
        return 'Varies';
    }

    /**
     * Calculate shipping cost with example for display
     */
    public function getExampleCostAttribute()
    {
        $examples = [];
        for ($i = 1; $i <= 12; $i++) {
            $examples[$i] = $this->calculateShippingCost($i);
        }
        return $examples;
    }
}
