<?php

namespace Database\Factories;

use App\Entities\StoreCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoreCode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'store_name' => $this->faker->name,
            'store_code' => StoreCode::all()->random()->store_code,
            'lan' => $this->faker->randomFloat(),
            'lon' => $this->faker->randomFloat(),
        ];
    }
}
