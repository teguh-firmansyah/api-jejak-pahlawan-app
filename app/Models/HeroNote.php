<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['era_id', 'name', 'birth_year', 'death_year', 'ascension_year', 'summary'])]
class HeroNote extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function era(): BelongsTo
    {
        return $this->belongsTo(Era::class);
    }
}
