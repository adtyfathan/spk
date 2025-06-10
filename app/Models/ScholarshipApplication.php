<?php

// app/Models/ScholarshipApplication.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipApplication extends Model
{
    use HasFactory;

    protected $table = 'scholarship_applications';

    protected $fillable = [
        'nama',
        'jurusan',
        'ipk',
        'pengalaman_organisasi',
        'penghasilan_orang_tua',
        'kontribusi_sosial',
        'jumlah_tanggungan',
        'semester',
        'total_score',
        'eligibility',
        'calculation_details'
    ];

    protected $casts = [
        'calculation_details' => 'array',
        'ipk' => 'decimal:2',
        'total_score' => 'decimal:2'
    ];

    public function getEligibilityColorAttribute()
    {
        return match($this->eligibility) {
            'Sangat Layak' => 'text-green-600',
            'Layak' => 'text-blue-600',
            'Cukup Layak' => 'text-yellow-600',
            'Kurang Layak' => 'text-red-600',
            default => 'text-gray-600'
        };
    }
}