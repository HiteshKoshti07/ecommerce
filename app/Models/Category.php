<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    //

    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'attachment',
        'parent_id',
        'description',
        'status'
    ];

    // Disable auto-increment ID
    public $incrementing = false;

    // Set ID type to string
    protected $keyType = 'string';

    // Automatically assign UUID during creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
