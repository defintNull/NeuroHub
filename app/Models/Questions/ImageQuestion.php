<?php

namespace App\Models\Questions;

use App\Models\Results\ImageQuestionResult;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ImageQuestion extends Model
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
        'images',
        'scores'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'images' => AsArrayObject::class,
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
     * Get the image question results that are owned by the image question.
     */
    public function imagequestionresults(): HasMany
    {
        return $this->hasMany(ImageQuestionResult::class);
    }
}
