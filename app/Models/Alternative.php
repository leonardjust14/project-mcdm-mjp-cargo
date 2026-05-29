<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function values()
    {
        return $this->hasMany(AlternativeValue::class, 'alternative_id');
    }

    public function topsisResults()
    {
        return $this->hasMany(TopsisResult::class, 'alternative_id');
    }
}
