<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'product_name',
        'sku',
        'variant',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'variant' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
