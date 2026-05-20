<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'area',
        'location',
        'latitude',
        'longitude'
    ];

    // Relationship with User (Farmer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Sensor Readings
    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class);
    }

    // Relationship with Predictions
    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }
} 