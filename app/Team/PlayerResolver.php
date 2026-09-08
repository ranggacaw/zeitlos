<?php

namespace App\Team;

use App\Models\Player;
use Illuminate\Support\Str;

class PlayerResolver
{
    public function find(string $name): ?Player
    {
        $name = trim($name);

        return Player::query()
            ->whereRaw('LOWER(TRIM(name)) = ?', [Str::lower($name)])
            ->first();
    }
}
