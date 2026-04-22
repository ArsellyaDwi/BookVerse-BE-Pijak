<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $table = 'transaction_items';

    protected $fillable = [
        'transaction_id',
        'book_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public $timestamps = false;

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
