<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FarmerTransaction extends Model
{
    use HasFactory;
    protected static function boot()
  {
    parent::boot();
    static::creating(function ($model) {
      if (!$model->getKey()) {
        $model->{$model->getKeyName()} = (string) Str::uuid();
      }
    });
  }

  public function getIncrementing()
  {
    return false;
  }

  /**
   * Get the auto-incrementing key type.
   *
   * @return string
   */
  public function getKeyType()
  {
    return 'string';
  }

  public function fruit_commodity(){
    return $this->belongsTo(FruitCommodity::class, "fruit_commodity_id", "id");
  }

  public function customer_transaction(){
    return $this->hasMany(CustomerTransaction::class, "farmer_transaction_id", 'id');
  }

}
