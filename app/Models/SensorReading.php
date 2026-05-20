<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'n',
        'p',
        'k',
        'ph',
        'soil_type',
        'recommendations',
        'readings_count',
        'location_data'
    ];

    protected $casts = [
        'n' => 'decimal:2',
        'p' => 'decimal:2',
        'k' => 'decimal:2',
        'ph' => 'decimal:2',
        'readings_count' => 'integer',
        'location_data' => 'array'
    ];

    /**
     * Get the farm that owns the sensor reading.
     */
    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    /**
     * Get the NPK values as an array
     */
    public function getNpkValues(): array
    {
        return [
            'n' => $this->n,
            'p' => $this->p,
            'k' => $this->k
        ];
    }

    /**
     * Get sensor data as an array
     */
    public function getSensorData(): array
    {
        return [
            'n' => $this->n,
            'p' => $this->p,
            'k' => $this->k,
            'ph' => $this->ph,
        ];
    }

    /**
     * Get analysis data
     */
    public function getAnalysis(): array
    {
        return [
            'soil_type' => $this->soil_type,
            'recommendations' => $this->recommendations
        ];
    }

    /**
     * Get location data
     */
    public function getLocationData(): ?array
    {
        return $this->location_data;
    }
} 