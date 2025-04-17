<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'province_id'];

    // اگر نیاز به ارتباط با استان دارید
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
