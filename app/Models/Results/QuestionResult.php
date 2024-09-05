<?php

namespace App\Models\Results;

use App\Models\Questions\Question;
use App\Models\Results\SectionResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class QuestionResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question_id',
        'section_result_id',
        'progressive',
        'questionable_id',
        'questionable_type',
    ];

    /**
     * Get the section result that owns the question result.
     */
    public function sectionresult(): BelongsTo
    {
        return $this->belongsTo(SectionResult::class);
    }

    /**
     * Get the question that owns the question result.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Get the questionable model result (specialized question result).
     */
    public function questionable(): MorphTo
    {
        return $this->morphTo();
    }
}
