<?php

namespace App\Models;

use App\Models\Results\TestResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Interview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visit_id',
        'status',
        'diagnosis',
    ];

    /**
     * Get the visits that owns the Interview
     *
     * @return \Illuminate\Database\Eloquent\visit_idn    */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class, 'visit_id');
    }

    /**
     * Get all of the test results for the Interview
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function testresult(): HasOne
    {
        return $this->hasOne(TestResult::class);
    }
}
