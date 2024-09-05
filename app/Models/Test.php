<?php

namespace App\Models;

use App\Models\Results\TestResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Test extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'status',
        'test_med_id',
    ];

    /**
     * Get the sections for the test.
     */
    public function sections(): MorphMany
    {
        return $this->morphMany(Section::class, 'sectionable')->orderBy('progressive', 'asc');
    }

    /**
     * Get the testmed that owns the test.
     */
    public function testmed(): BelongsTo
    {
        return $this->belongsTo(TestMed::class);
    }

    /**
     * Get the test result for the test.
     */
    public function testresult(): HasOne
    {
        return $this->hasOne(TestResult::class);
    }

}
