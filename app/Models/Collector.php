<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Collector extends Model
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

  public function fruit_commoditys(){
    return $this->hasMany(Collector::class, "collector_id", 'id');
  }

  public function user(){
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

}
