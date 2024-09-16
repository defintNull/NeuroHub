<?php

namespace App\Models\Results;

use App\Models\Questions\ValueQuestion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ValueQuestionResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'value',
        'value_question_id',
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
     * Get the value question result's value question result.
     */
    public function valuequestion(): BelongsTo
    {
        return $this->belongsTo(ValueQuestion::class, 'value_question_id');
    }
}
