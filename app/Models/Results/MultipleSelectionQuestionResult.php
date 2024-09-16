<?php

namespace App\Models\Results;

use App\Models\Questions\MultipleSelectionQuestion;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class MultipleSelectionQuestionResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'value',
        'multiple_selection_question_id',
        'score',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => AsArrayObject::class,
        ];
    }

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
    public function multipleselectionquestion(): BelongsTo
    {
        return $this->belongsTo(MultipleSelectionQuestion::class, 'multiple_selection_question_id');
    }
}
