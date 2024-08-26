<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireResult extends Model
{
    use HasFactory;

    /**
     * Get the interview that owns the QuestionnaireResult
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class, 'interview_id');
    }
}
