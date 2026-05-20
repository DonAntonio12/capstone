<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'user_id',
        'collection_session_id',
        'predicted_nitrogen',
        'predicted_phosphorus',
        'predicted_potassium',
        'prediction_date',
        'prediction_for_date',
        'confidence_score',
        'model_parameters',
        'recommendations'
    ];

    protected $casts = [
        'prediction_date' => 'datetime',
        'prediction_for_date' => 'datetime',
        'predicted_nitrogen' => 'decimal:2',
        'predicted_phosphorus' => 'decimal:2',
        'predicted_potassium' => 'decimal:2',
        'confidence_score' => 'decimal:2',
        'model_parameters' => 'array'
    ];

    // Relationship with Farm
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Sensor Readings
    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class, 'collection_session_id', 'collection_session_id');
    }
} 