<?php

namespace Database\Factories;

use App\Entities\PhoneRegistrationRecord;
use App\Entities\StoreCode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhoneRegistrationRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PhoneRegistrationRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'phone_num' => $this->faker->phoneNumber(),
            'store_code' => StoreCode::all()->random()->store_code,
            'registration_datetime' => $this->faker->dateTimeBetween(Carbon::now()->subWeeks(2), Carbon::now()),
        ];
    }
}
