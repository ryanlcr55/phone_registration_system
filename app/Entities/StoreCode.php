<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;


class StoreCode extends Model
{
    const REDIS_KEY = 'store_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_name',
        'store_code',
        'lan',
        'lot',
    ];

    public function registrationRecords()
    {
        return $this->hasMany(PhoneRegistrationRecord::class, 'store_code', 'store_code');
    }

    static function redisDataForm(StoreCode $storeCode) :string
    {
        return $storeCode->toJson();
    }
}
