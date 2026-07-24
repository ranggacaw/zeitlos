<?php

namespace Database\Seeders;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@zeitlos.test'],
            [
                'name' => 'Zeitlos Admin',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        $players = collect([
            ['name' => 'Anton Keller', 'jersey_number' => 1, 'position' => 'Goalkeeper', 'photo_path' => 'players/anton-keller.jpg'],
            ['name' => 'Milan Becker', 'jersey_number' => 4, 'position' => 'Defender', 'photo_path' => 'players/milan-becker.jpg'],
            ['name' => 'Jonas Hartmann', 'jersey_number' => 8, 'position' => 'Midfielder', 'photo_path' => 'players/jonas-hartmann.jpg'],
            ['name' => 'Leo Fischer', 'jersey_number' => 10, 'position' => 'Forward', 'photo_path' => 'players/leo-fischer.jpg'],
            ['name' => 'Noah Wagner', 'jersey_number' => 11, 'position' => 'Forward', 'photo_path' => 'players/noah-wagner.jpg'],
            ['name' => 'Felix Roth', 'jersey_number' => 23, 'position' => 'Midfielder', 'photo_path' => 'players/felix-roth.jpg'],
        ])->mapWithKeys(function (array $player) {
            $model = Player::updateOrCreate(
                ['jersey_number' => $player['jersey_number']],
                $player + [
                    'is_active' => true,
                    'joined_at' => now()->subYears(2)->toDateString(),
                    'goals_adjustment' => 0,
                    'assists_adjustment' => 0,
                ]
            );

            return [$model->jersey_number => $model];
        });

        $upcomingMatch = FootballMatch::updateOrCreate(
            ['opponent' => 'Riverside FC', 'match_date' => now()->addWeek()->toDateString()],
            [
                'match_time' => '19:30',
                'venue' => 'Sportpark Mitte, Platz 2',
                'maps_url' => 'https://maps.example.com/sportpark-mitte',
                'ticket_price' => 0,
                'dress_code' => 'Black home kit',
                'facilities' => 'Changing rooms, showers, floodlights',
                'notes' => 'Meet 45 minutes before kickoff.',
                'payment_label' => 'Pitch share',
                'payment_amount' => 8,
                'payment_due_at' => now()->addDays(5),
                'payment_instructions' => 'Transfer to the team account before match day.',
                'whatsapp_announcement' => 'Zeitlos vs Riverside FC. Confirm availability and payment in WhatsApp.',
                'status' => FootballMatch::STATUS_SCHEDULED,
                'zeitlos_score' => null,
                'opponent_score' => null,
            ]
        );

        $finishedMatch = FootballMatch::updateOrCreate(
            ['opponent' => 'Old Town United', 'match_date' => now()->subWeek()->toDateString()],
            [
                'match_time' => '20:00',
                'venue' => 'Arena Nord',
                'maps_url' => 'https://maps.example.com/arena-nord',
                'ticket_price' => 0,
                'dress_code' => 'White away kit',
                'facilities' => 'Changing rooms and showers',
                'notes' => 'Strong second half performance.',
                'payment_label' => 'Pitch share',
                'payment_amount' => 8,
                'payment_due_at' => now()->subDays(10),
                'payment_instructions' => 'Settled after the match.',
                'whatsapp_announcement' => 'Finished: Zeitlos 3-1 Old Town United.',
                'status' => FootballMatch::STATUS_FINISHED,
                'zeitlos_score' => 3,
                'opponent_score' => 1,
            ]
        );

        foreach ([$upcomingMatch, $finishedMatch] as $match) {
            foreach ($players as $player) {
                MatchRoster::updateOrCreate(
                    ['match_id' => $match->id, 'player_id' => $player->id],
                    ['guest_name' => null, 'role' => $player->position === 'Goalkeeper' ? MatchRoster::ROLE_GOALKEEPER : MatchRoster::ROLE_PLAYER]
                );
            }
        }

        MatchRoster::updateOrCreate(
            ['match_id' => $upcomingMatch->id, 'guest_name' => 'Guest Striker'],
            ['player_id' => null, 'role' => MatchRoster::ROLE_PLAYER]
        );

        foreach ([
            ['minute' => 12, 'scorer_id' => $players[10]->id, 'assist_player_id' => $players[8]->id],
            ['minute' => 48, 'scorer_id' => $players[11]->id, 'assist_player_id' => $players[10]->id],
            ['minute' => 76, 'scorer_id' => $players[10]->id, 'assist_player_id' => $players[23]->id],
        ] as $event) {
            MatchEvent::updateOrCreate(
                ['match_id' => $finishedMatch->id, 'minute' => $event['minute']],
                $event + ['event_type' => MatchEvent::TYPE_GOAL]
            );
        }
    }
}
