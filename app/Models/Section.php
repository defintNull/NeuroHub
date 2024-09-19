<?php

namespace App\Models;

use App\Models\Questions\Question;
use App\Models\Results\SectionResult;
use App\Models\Scores\OperationOnScore;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sectionable_id',
        'sectionable_type',
        'progressive',
        'jump',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'conversion' => AsArrayObject::class,
            'jump' => AsArrayObject::class,
        ];
    }

    /**
     * Get the section associated with section.
     */
    public function sections(): MorphMany
    {
        return $this->morphMany(Section::class, 'sectionable')->orderBy('progressive', 'asc');
    }

    /**
     * Get the section that owns section.
     */
    public function sectionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the questions for the section model.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('progressive', 'asc');
    }

    /**
     * Get the section results for the section.
     */
    public function sectionresults(): HasMany
    {
        return $this->hasMany(SectionResult::class);
    }

    /**
     * Get the secion's operationonscore.
     */
    public function operationOnScore(): MorphOne
    {
        return $this->morphOne(OperationOnScore::class, 'scorable');
    }
}
