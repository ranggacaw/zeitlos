<?php

namespace App\Team;

use App\Models\Player;
use Illuminate\Support\Str;

class PlayerResolver
{
    public function resolve(string $name, string $defaultPosition): Player
    {
        $name = trim($name);

        return Player::query()
            ->whereRaw('LOWER(TRIM(name)) = ?', [Str::lower($name)])
            ->first()
            ?? Player::create([
                'name' => $name,
                'position' => $defaultPosition,
                'is_active' => true,
            ]);
    }
}
