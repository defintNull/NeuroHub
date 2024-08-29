<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'telephone',
        'birthdate',
        'active',
    ];

    /**
     * Define the relationship between a patient and their medical records.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function medicalrecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function visits(): HasMany{
        return $this->hasMany(Visit::class, 'patient_id');
    }
}
