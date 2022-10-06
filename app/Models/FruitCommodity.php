<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FruitCommodity extends Model
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

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'id');
    }

    public function collector()
    {
        return $this->belongsTo(Collector::class, "collector_id", 'id');
    }

    public function fruit()
    {
        return $this->belongsTo(fruit::class, 'fruit_id', 'id');
    }

    public function fruit_commodity()
    {
        return $this->hasMany(FarmerTransaction::class, 'fruit_commodity_id', 'id');
    }

    public function farmer_transaction()
    {
        return $this->belongsTo(CustomerTransaction::class, "farmer_transaction_id", 'id');
    }
}
