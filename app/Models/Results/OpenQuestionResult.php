<?php

namespace App\Models\Results;

use App\Models\Questions\OpenQuestion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OpenQuestionResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'value',
        'open_question_id',
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
    public function openquestion(): BelongsTo
    {
        return $this->belongsTo(OpenQuestion::class, 'open_question_id');
    }
}
