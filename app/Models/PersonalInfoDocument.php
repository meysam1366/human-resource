<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalInfoDocument extends Model
{
    protected $fillable = [
        'personal_info_id',
        'type',
        'path'
    ];

    public function personalInfo(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class);
    }

    public function getTypeTextAttribute()
    {
        $types = [
            'photo' => 'عکس 3 در 4',
            'national_card' => 'کارت ملی',
            'id_card' => 'صفحه شناسنامه',
            'education_doc' => 'مدرک تحصیلی'
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getFullPathAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
