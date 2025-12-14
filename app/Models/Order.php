<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

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

    /**
     * Scope: Filter orders by date range
     * 
     * @param Builder $query
     * @param string|null $startDate Format: Y-m-d
     * @param string|null $endDate Format: Y-m-d
     * @return Builder
     */
    public function scopeDateRange(Builder $query, ?string $startDate = null, ?string $endDate = null): Builder
    {
        if ($startDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        return $query;
    }

    /**
     * Scope: Filter orders for today
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    /**
     * Scope: Filter orders for yesterday
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeYesterday(Builder $query): Builder
    {
        return $query->whereDate('created_at', Carbon::yesterday());
    }

    /**
     * Scope: Filter orders for last N days
     * 
     * @param Builder $query
     * @param int $days
     * @return Builder
     */
    public function scopeLastDays(Builder $query, int $days): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days)->startOfDay());
    }

    /**
     * Scope: Filter orders for this month
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    }

    /**
     * Scope: Filter orders for last month
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeLastMonth(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ]);
    }
}
