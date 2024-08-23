<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visit extends Model
{
    use HasFactory;


    /**
     * Get the patients that owns the Visit
     *
     * @return \IlluminatePatientDatabase\Eloquent\atipatient_idoTo
     */
    public function patients(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function meds(): BelongsTo
    {
        return $this->belongsTo(Med::class, 'med_id');
    }

    /**
     * Get all of the interviews for the Visit
     *
     * @return \Illuminate\Database\Interviewquent\Relations\visit_idny
     */
    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'visit_id');
    }
}
