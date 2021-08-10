<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PhoneRegistrationRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone_num',
        'store_code',
        'registration_datetime',
    ];

    public function store()
    {
        return $this->belongsTo(
            StoreCode::class,
            'store_code',
            'store_code',
        );
    }
}
