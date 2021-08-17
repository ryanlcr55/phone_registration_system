<?php

namespace Database\Factories;

use App\Entities\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'store_name' => $this->faker->name,
            'store_code' => sprintf("%015d", random_int(1,999999999999999)),
            'lat' => $this->faker->randomFloat(7,-99,99),
            'lon' => $this->faker->randomFloat(7, -200, 200),
        ];
    }
}
