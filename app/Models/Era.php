<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description'])]
class Era extends Model
{
    public const DEFAULTS = [
        'Pra-Kemerdekaan' => 'Perlawanan terhadap penjajahan sebelum era pergerakan nasional.',
        'Pergerakan Nasional' => 'Kebangkitan nasional hingga menjelang proklamasi.',
        'Revolusi Kemerdekaan' => 'Perjuangan mempertahankan kemerdekaan setelah proklamasi.',
        'Orde Lama' => 'Masa awal pemerintahan Indonesia merdeka.',
        'Orde Baru' => 'Masa pemerintahan setelah Orde Lama.',
    ];

    public static function createDefaultsFor(User $user): void
    {
        foreach (self::DEFAULTS as $name => $description) {
            $user->eras()->firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function heroNotes(): HasMany
    {
        return $this->hasMany(HeroNote::class);
    }
}
