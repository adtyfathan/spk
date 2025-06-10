<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipSubCriteria extends Model
{
    use HasFactory;

    protected $table = 'scholarship_sub_criteria';

    protected $fillable = [
        'criteria_id',
        'nama_sub_kriteria',
        'deskripsi',
        'nilai',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function criteria()
    {
        return $this->belongsTo(ScholarshipCriteria::class, 'criteria_id');
    }
}