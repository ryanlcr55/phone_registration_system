<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Store extends Model
{
    use HasFactory;

    const REDIS_KEY = 'stores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_name',
        'store_code',
        'lat',
        'lon',
    ];

    public function registrationRecords()
    {
        return $this->hasMany(PhoneRegistrationRecord::class, 'store_code', 'store_code');
    }

    static function redisDataForm(Store $store): string
    {
        return $store->toJson();
    }
}
