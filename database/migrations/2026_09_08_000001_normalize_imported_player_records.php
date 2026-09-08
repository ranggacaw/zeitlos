<?php

use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Team\PlayerResolver;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $players = app(PlayerResolver::class);

        MatchRoster::query()
            ->whereNotNull('guest_name')
            ->eachById(function (MatchRoster $roster) use ($players): void {
                if (blank($roster->guest_name)) {
                    return;
                }

                $roster->update([
                    'player_id' => $players->resolve(
                        $roster->guest_name,
                        $roster->role === MatchRoster::ROLE_GOALKEEPER ? 'Goalkeeper' : 'Player',
                    )->getKey(),
                    'guest_name' => null,
                ]);
            });

        MatchEvent::query()
            ->where(function ($query): void {
                $query->whereNotNull('guest_scorer_name')
                    ->orWhereNotNull('guest_assist_name');
            })
            ->eachById(function (MatchEvent $event) use ($players): void {
                $attributes = [];

                if (filled($event->guest_scorer_name)) {
                    $attributes['scorer_id'] = $players->resolve($event->guest_scorer_name, 'Player')->getKey();
                    $attributes['guest_scorer_name'] = null;
                }

                if (filled($event->guest_assist_name)) {
                    $attributes['assist_player_id'] = $players->resolve($event->guest_assist_name, 'Player')->getKey();
                    $attributes['guest_assist_name'] = null;
                }

                if ($attributes !== []) {
                    $event->update($attributes);
                }
            });
    }

    public function down(): void {}
};
