<?php

namespace App\Models\Scores;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OperationOnScore extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scorable_id',
        'scorable_type',
        'formula',
        'conversion',
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
        ];
    }

    /**
     * Get the parent scorable model (test, section or question).
     */
    public function scorable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'scorable_type', 'scorable_id');
    }
}
