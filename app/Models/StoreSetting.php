<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'store_slug',
        'email',
        'phone',
        'address_line1',
        'short_description',
        'about_store',
        'is_active',
        'store_logo',
        'cover_banner'
    ];
}
