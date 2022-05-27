<?php

namespace Hito\Modules\Attendance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Hito\Modules\Attendance\Models\AttendanceType;

class AttendanceTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AttendanceType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->text,
            'color' => $this->faker->hexColor,
            'status' => $this->faker->randomElement(['DRAFT', 'PUBLISHED'])
        ];
    }
}

