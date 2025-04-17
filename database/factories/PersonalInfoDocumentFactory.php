<?php

namespace Database\Factories;

use App\Models\PersonalInfo;
use App\Models\PersonalInfoDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalInfoDocumentFactory extends Factory
{
    protected $model = PersonalInfoDocument::class;

    public function definition()
    {
        return [
            'personal_info_id' => PersonalInfo::factory(),
            'type' => $this->faker->randomElement(['photo', 'national_card', 'id_card', 'education_doc']),
            'path' => 'sample/' . $this->faker->uuid . '.jpg',
        ];
    }

    public function photo()
    {
        return $this->state(['type' => 'photo']);
    }

    public function nationalCard()
    {
        return $this->state(['type' => 'national_card']);
    }

    public function idCard()
    {
        return $this->state(['type' => 'id_card']);
    }

    public function educationDoc()
    {
        return $this->state(['type' => 'education_doc']);
    }
}
