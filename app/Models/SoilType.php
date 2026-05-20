<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoilType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'thresholds',
        'best_crops',
        'image_url',
        'remarks',
        'why_suitable',
    ];

    protected $casts = [
        'thresholds' => 'array',
        'best_crops' => 'array',
    ];
} 