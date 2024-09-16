<?php

namespace App\Models\Results;

use App\Models\Questions\MultipleQuestion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class MultipleQuestionResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'value',
        'multiple_question_id',
        'score',
    ];

    /**
     * Get the multiple question result's question result.
     */
    public function questionresult(): MorphOne
    {
        return $this->morphOne(QuestionResult::class, 'questionable');
    }

    /**
     * Get the multiple question result's multiple question result.
     */
    public function multiplequestion(): BelongsTo
    {
        return $this->belongsTo(MultipleQuestion::class, 'multiple_question_id');
    }
}
