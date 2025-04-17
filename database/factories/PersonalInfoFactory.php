<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\PersonalInfo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Morilog\Jalali\Jalalian;

class PersonalInfoFactory extends Factory
{
    protected $model = PersonalInfo::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'father_name' => $this->faker->firstNameMale,
            'national_code' => $this->generateValidNationalCode(),
            'education_level' => $this->faker->randomElement(['diploma', 'associate', 'bachelor', 'master', 'phd']),
            'id_number' => $this->faker->numerify('##########'),
            'id_serial' => $this->faker->randomLetter . '-' . $this->faker->numerify('######'),
            'issue_place_id' => City::inRandomOrder()->first()->id,
            'birth_place_id' => City::inRandomOrder()->first()->id,
            'birth_date' => Jalalian::now()->subYears(rand(18, 60))->toCarbon(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }

    private function generateValidNationalCode()
    {
        $code = strval(rand(100000000, 999999999));
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $code[$i] * (10 - $i);
        }

        $remainder = $sum % 11;
        $controlDigit = $remainder < 2 ? $remainder : 11 - $remainder;

        return $code . strval($controlDigit);
    }
}
