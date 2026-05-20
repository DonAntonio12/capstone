<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoilTest extends Model
{
    protected $fillable = [
        'user_id', 'n', 'p', 'k', 'ph', 'latitude', 'longitude', 'soil_type', 'recommendation', 'prediction'
    ];
}
