<?php

namespace App\Models\Results;

use App\Models\results\QuestionResult;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SectionResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'section_id',
        'sectionable_id',
        'sectionable_type',
        'status',
        'progressive',
        'result',
        'score',
        'jump',
    ];

    /**
     * Get the section that owns section result.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * Get the section results associated with section result.
     */
    public function sections(): MorphMany
    {
        return $this->morphMany(SectionResult::class, 'sectionable')->orderBy('progressive', 'asc');
    }

    /**
     * Get the section result that owns section result.
     */
    public function sectionable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'sectionable_type', 'sectionable_id');
    }

    /**
     * Get the questions for the section model.
     */
    public function questionresults(): HasMany
    {
        return $this->hasMany(QuestionResult::class)->orderBy('progressive', 'asc');
    }
}
