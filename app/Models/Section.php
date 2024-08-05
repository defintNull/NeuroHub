<?php

namespace App\Models;

use App\Models\Questions\Question;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'test_id',
        'section_id',
    ];

    /**
     * Get the test that owns section.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get the section associated with section.
     */
    public function section(): HasOne
    {
        return $this->hasOne(Section::class, 'section_id', 'id');
    }

    /**
     * Get the section that owns section.
     */
    public function inversesection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    /**
     * Get the questions for the section model.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
