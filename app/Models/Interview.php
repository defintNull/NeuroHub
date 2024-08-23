<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interview extends Model
{
    use HasFactory;

    /**
     * Get the visits that owns the Interview
     *
     * @return \Illuminate\Database\Eloquent\visit_idn    */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visit_id');
    }

    /**
     * Get all of the questionnaire_results for the Interview
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questionnaire_results(): HasMany
    {
        return $this->hasMany(QuestionnaireResult::class, 'interview_id');
    }
}
