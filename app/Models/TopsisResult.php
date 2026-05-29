<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopsisResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'alternative_id',
        'd_positive',
        'd_negative',
        'preference_score',
        'rank',
        'calculated_at',
    ];

    protected $casts = [
        'd_positive' => 'float',
        'd_negative' => 'float',
        'preference_score' => 'float',
        'rank' => 'integer',
        'calculated_at' => 'datetime',
    ];

    public function alternative()
    {
        return $this->belongsTo(Alternative::class, 'alternative_id');
    }
}
