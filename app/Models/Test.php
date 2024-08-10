<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        return $this->morphMany(Section::class, 'sectionable')->orderBy('progressive', 'desc');
    }

    /**
     * Get the testmed that owns the test.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(TestMed::class);
    }

}
