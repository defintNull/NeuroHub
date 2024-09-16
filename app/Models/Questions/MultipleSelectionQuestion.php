<?php

namespace App\Models\Questions;

use App\Models\Results\MultipleSelectionQuestionResult;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class MultipleSelectionQuestion extends Model
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
        'fields',
        'scores',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fields' => AsArrayObject::class,
            'scores' => AsArrayObject::class,
        ];
    }

    /**
     * Get the question's question.
     */
    public function question(): MorphOne
    {
        return $this->morphOne(Question::class, 'questionable');
    }

    /**
     * Get the multiple question results that are owned by the multiple question.
     */
    public function multipleselectionquestionresults(): HasMany
    {
        return $this->hasMany(MultipleSelectionQuestionResult::class);
    }
}
