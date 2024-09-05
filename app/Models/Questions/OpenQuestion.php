<?php

namespace App\Models\Questions;

use App\Models\Results\OpenQuestionResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OpenQuestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'text',
    ];

    /**
     * Get the question's question.
     */
    public function question(): MorphOne
    {
        return $this->morphOne(Question::class, 'questionable');
    }

    /**
     * Get the open question results that are owned by the open question.
     */
    public function openquestionresults(): HasMany
    {
        return $this->hasMany(OpenQuestionResult::class);
    }
}
