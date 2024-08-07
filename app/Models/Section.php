<?php

namespace App\Models;

use App\Models\Questions\Question;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
    ];

    /**
     * Get the test that owns section.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get the section associated with section.
     */
    public function sections(): MorphMany
    {
        return $this->morphMany(Section::class, 'sectionable');
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
        return $this->hasMany(Question::class);
    }
}
