<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'telephone',
        'birthdate',
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
}
