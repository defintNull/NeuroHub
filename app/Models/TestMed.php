<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class TestMed extends User
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'telephone',
        'birthdate',
    ];

    /**
     * Get the user of the testmed.
     */
    public function user() : MorphOne {
        return $this->morphOne(User::class, 'userable');
    }

    /**
     * Get the tests for the testmed.
     */
    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }
}
