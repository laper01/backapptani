<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Customer extends Model
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

  public function customer_transaction(){
    return $this->hasMany(CustomerTransaction::class, 'customer_id', 'id');
  }

}
