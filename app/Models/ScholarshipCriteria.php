<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipCriteria extends Model
{
    use HasFactory;

    protected $table = 'scholarship_criteria';

    protected $fillable = [
        'nama_kriteria',
        'bobot',
        'jenis_kriteria',
        'is_active'
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function subCriteria()
    {
        return $this->hasMany(ScholarshipSubCriteria::class, 'criteria_id')->orderBy('sort_order');
    }

    public function activeSubCriteria()
    {
        return $this->subCriteria()->where('is_active', true);
    }
}