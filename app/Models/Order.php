<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
  use HasFactory;
  protected $fillable = [
    'user_id',
    'total_price',
    'status',
    'province_id',
    'regency_id',
    'district_id',
    'village_id',
    'province_name',
    'regency_name',
    'district_name',
    'village_name',
    'address',
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
    $type = 'R';
    $systemId = 'X5';
    $sequenceNumber = str_pad(self::getNextSequenceNumber(), 6, '0', STR_PAD_LEFT);
    $randomNumber = mt_rand(10000, 99999);
    $checksum = self::calculateCheckSum($type . $systemId . $sequenceNumber . $randomNumber);
    return $type . $systemId . "-" . $sequenceNumber . "-" . $randomNumber . "-" . $checksum;
  }

  private static function getNextSequenceNumber()
  {
    $lastOrder = self::orderBy('id', 'desc')->first();
    return $lastOrder ? intval(substr($lastOrder->order_code, 4, 6)) + 1 : 1;
  }

  private static function calculateCheckSum($data)
  {
    $checksum = 0;
    for ($i = 0; $i < strlen($data); $i++) {
      $checksum += ord($data[$i]);
    }
    return $checksum % 100;
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