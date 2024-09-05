<?php

namespace App\Models\Results;

use App\Models\Interview;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TestResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'test_id',
        'interview_id',
        'status',
        'result',
    ];

    /**
     * Get the section results for the test result.
     */
    public function sectionresults(): MorphMany
    {
        return $this->morphMany(SectionResult::class, 'sectionable')->orderBy('progressive', 'asc');
    }

    /**
     * Get the interview that owns the test result.
     */
    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class, 'interview_id');
    }

    /**
     * Get the test that owns the test result.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

}
