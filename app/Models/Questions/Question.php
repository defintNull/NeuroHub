<?php

namespace App\Models\Questions;

use App\Models\results\QuestionResult;
use App\Models\Scores\OperationOnScore;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'section_id',
        'progressive',
        'questionable_id',
        'questionable_type',
    ];

    /**
     * Get the section that owns the question.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the questionable model (specialized questions).
     */
    public function questionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the question results that are owned by the question.
     */
    public function questionresults(): HasMany
    {
        return $this->hasMany(QuestionResult::class);
    }

}
