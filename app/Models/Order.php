<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $fillable = [
    'user_id',
    'total_price',
    'status'
  ];

  /**
   * The "booting" method of the model.
   *
   * @return void
   */
  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->order_code = self::generateOrderCode();
    });
  }

  private static function generateOrderCode()
  {
    $date = now()->format('Ymd');

    $latestOrder = self::whereDate('created_at', now()->toDateString())->latest('id')->first();
    $orderNumber = $latestOrder ? $latestOrder->id + 1 : 1;

    return 'ORD-' . $date . '-' . str_pad($orderNumber, 4, '0', STR_PAD_LEFT);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function orderItems(): HasMany
  {
    return $this->hasMany(OrderItem::class, 'order_id', 'id');
  }
}