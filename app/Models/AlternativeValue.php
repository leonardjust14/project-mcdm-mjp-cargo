<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlternativeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'alternative_id',
        'criteria_id',
        'value',
    ];

    protected $casts = [
        'value' => 'float',
    ];

    public function alternative()
    {
        return $this->belongsTo(Alternative::class, 'alternative_id');
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }
}
