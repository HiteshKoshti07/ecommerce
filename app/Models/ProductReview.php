<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductReview extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'product_id',
        'customer_name',
        'rating',
        'review_title',
        'review_text',
        'images',
        'customer_id',
        'verified_purchase',
        'helpful_count',
        'ip_address',
        'status'
    ];

    protected $casts = [
        'images' => 'array',
        'verified_purchase' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    // Relation with product (optional)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
