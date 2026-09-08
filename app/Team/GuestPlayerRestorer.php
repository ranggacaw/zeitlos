<?php

namespace App\Team;

use App\Models\Player;
use Illuminate\Support\Facades\DB;

class GuestPlayerRestorer
{
    public function restore(iterable $players): void
    {
        DB::transaction(function () use ($players): void {
            foreach ($players as $selectedPlayer) {
                $player = Player::query()->lockForUpdate()->find($selectedPlayer->getKey());

                if (! $player) {
                    continue;
                }

                $name = trim($player->name);

                $player->rosterEntries()->update([
                    'player_id' => null,
                    'guest_name' => $name,
                ]);
                $player->scoredEvents()->update([
                    'scorer_id' => null,
                    'guest_scorer_name' => $name,
                ]);
                $player->assistedEvents()->update([
                    'assist_player_id' => null,
                    'guest_assist_name' => $name,
                ]);

                $player->delete();
            }
        });
    }
}
