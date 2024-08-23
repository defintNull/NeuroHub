<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Med extends User
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
        'regstatus',
        'user_id'
    ];

    public function user() : MorphOne {
        return $this->morphOne(User::class, 'userable');
    }

    public function visits(): HasMany{
        return $this->hasMany(Visit::class, 'med_id');
    }
}
