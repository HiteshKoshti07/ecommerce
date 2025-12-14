<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;



class Product extends Model
{
    use SoftDeletes;

    public $incrementing = false; // Disable auto-increment
    protected $keyType = 'string'; // UUID is string

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'barcode',
        'description',
        'base_price',
        'discount_price',
        'stock_quantity',
        'category_id',
        'collection',
        'status',
        'product_image',
        'variants',
        'options',
        'inventory',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'product_images',
        'product_video',
        'product_fabric',
        'product_work',
        'product_length',
        'product_care',
        'upsell_products'
    ];

    protected $casts = [
        'variants' => 'array',
        'options' => 'array',
        'inventory' => 'array',
        'tags' => 'array',
        'product_images' => 'array',

    ];


    // Auto-generate UUID when creating
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }
}
