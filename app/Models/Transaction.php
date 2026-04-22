<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'payment_method_id',
        'date',
        'total',
        'status',
        'payment_proof',
        'delivery_address_id',
        'delivery_method_id',
        'shipping_cost',
    ];

    protected $casts = [
        'date' => 'datetime',
        'total' => 'decimal:2',
    ];

    public $timestamps = false;

    // Status constants
    const STATUS_WAITING_PAYMENT = 'waiting_payment';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DONE = 'done';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses()
    {
        return [
            self::STATUS_WAITING_PAYMENT => 'Waiting Payment',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DONE => 'Done',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(DeliveryAddress::class, 'delivery_address_id');
    }

    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class, 'delivery_method_id');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'transaction_items')
            ->withPivot('quantity', 'price', 'subtotal');
    }
}
