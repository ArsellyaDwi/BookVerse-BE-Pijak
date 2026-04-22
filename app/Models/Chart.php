<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    public function items()
    {
        return $this->hasMany(ChartItem::class);
    }
}
