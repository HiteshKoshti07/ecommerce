<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Use UUIDs instead of auto-increment IDs
     */
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Fillable columns
     */
    protected $fillable = [
        'id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'subtotal_amount',
        'tax_amount',
        'grand_total',
        'status',
        'payment_status',
        'payment_method',
        'notes',
        'total_amount',
        'sku',
        'variants'
    ];

    /**
     * Auto-generate UUID on create
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }

            if (empty($model->order_number)) {
                $model->order_number = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }


    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
