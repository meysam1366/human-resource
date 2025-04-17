<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalInfo extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'father_name',
        'national_code',
        'education_level',
        'id_number',
        'id_serial',
        'issue_place_id',
        'birth_place_id',
        'birth_date',
        'status'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(PersonalInfoDocument::class);
    }

    public function issuePlace(): BelongsTo
    {
        return $this->belongsTo(City::class, 'issue_place_id');
    }

    public function birthPlace(): BelongsTo
    {
        return $this->belongsTo(City::class, 'birth_place_id');
    }

    public function getEducationLevelTextAttribute()
    {
        $levels = [
            'diploma' => 'دیپلم',
            'associate' => 'فوق دیپلم',
            'bachelor' => 'کارشناسی',
            'master' => 'کارشناسی ارشد',
            'phd' => 'دکترا'
        ];

        return $levels[$this->education_level] ?? $this->education_level;
    }

    public function getGenderTextAttribute()
    {
        return $this->gender === 'male' ? 'مرد' : 'زن';
    }
}
