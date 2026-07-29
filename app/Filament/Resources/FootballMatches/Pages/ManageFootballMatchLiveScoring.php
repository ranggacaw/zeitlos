<?php

namespace App\Filament\Resources\FootballMatches\Pages;

use App\Filament\Resources\FootballMatches\FootballMatchResource;
use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class ManageFootballMatchLiveScoring extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = FootballMatchResource::class;

    protected static ?string $title = 'Live scoring';

    protected static ?string $breadcrumb = 'Live scoring';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function content(Schema $schema): Schema
    {
        $match = $this->match();

        return $schema
            ->components([
                Section::make('Scoreboard')
                    ->description('Current match status and score context.')
                    ->schema([
                        Html::make(view('filament.football-matches.pages.live-scoring.summary', [
                            'match' => $match,
                            'scoringPlayers' => $this->scoringPlayers(),
                        ])->render()),
                    ]),
                Section::make('Goal timeline')
                    ->description('Recorded goals and assists for this match.')
                    ->schema([
                        EmbeddedTable::make(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => MatchEvent::query()
                ->where('match_id', $this->match()->getKey())
                ->where('event_type', MatchEvent::TYPE_GOAL)
                ->with(['scorer', 'assistPlayer']))
            ->paginated(false)
            ->defaultSort('minute')
            ->columns([
                TextColumn::make('minute')
                    ->placeholder('No minute')
                    ->formatStateUsing(fn (?int $state): ?string => $state !== null ? $state."'" : null),
                TextColumn::make('scorer.name')
                    ->label('Scorer'),
                TextColumn::make('assistPlayer.name')
                    ->label('Assist')
                    ->placeholder('None'),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->modalHeading('Delete goal?')
                    ->modalDescription('This removes the goal from scoring totals.')
                    ->successNotificationTitle('Goal deleted')
                    ->action(fn (MatchEvent $record) => $record->delete()),
            ]);
    }

    public function getSubheading(): ?string
    {
        $match = $this->match();

        $parts = array_filter([
            'vs '.$match->opponent,
            $match->match_date?->format('j M Y'),
            $match->match_time,
            $match->venue,
        ]);

        return filled($parts) ? implode(' · ', $parts) : null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('editMatch')
                ->label('Back to match')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->color('gray')
                ->url(fn (): string => $this->getResourceUrl('edit')),
            Action::make('markLive')
                ->label('Start live match')
                ->icon(Heroicon::OutlinedBolt)
                ->visible(fn (): bool => $this->match()->status === FootballMatch::STATUS_SCHEDULED)
                ->action(function (): void {
                    $this->match()->update([
                        'status' => FootballMatch::STATUS_LIVE,
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Match is live')
                        ->send();
                }),
            Action::make('recordGoal')
                ->label('Record goal')
                ->icon(Heroicon::OutlinedPlus)
                ->schema([
                    Select::make('scorer_id')
                        ->label('Scorer')
                        ->options(fn (): array => $this->scoringPlayerOptions())
                        ->searchable()
                        ->required(),
                    Select::make('assist_player_id')
                        ->label('Assist')
                        ->options(fn (): array => $this->scoringPlayerOptions())
                        ->searchable(),
                    TextInput::make('minute')
                        ->numeric()
                        ->minValue(0),
                ])
                ->action(function (array $data): void {
                    MatchEvent::create([
                        'match_id' => $this->match()->getKey(),
                        'scorer_id' => $data['scorer_id'],
                        'assist_player_id' => $data['assist_player_id'] ?? null,
                        'event_type' => MatchEvent::TYPE_GOAL,
                        'minute' => $data['minute'] ?? null,
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Goal recorded')
                        ->send();
                }),
            Action::make('finalizeMatch')
                ->label('Finalize match')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success')
                ->schema([
                    TextInput::make('zeitlos_score')
                        ->label('Zeitlos score')
                        ->required()
                        ->integer()
                        ->minValue(0)
                        ->default(fn (): ?int => $this->match()->zeitlos_score),
                    TextInput::make('opponent_score')
                        ->label('Opponent score')
                        ->required()
                        ->integer()
                        ->minValue(0)
                        ->default(fn (): ?int => $this->match()->opponent_score),
                ])
                ->action(function (array $data): void {
                    $this->match()->update([
                        'status' => FootballMatch::STATUS_FINISHED,
                        'zeitlos_score' => $data['zeitlos_score'],
                        'opponent_score' => $data['opponent_score'],
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Match finalized')
                        ->send();

                    $this->redirect(FootballMatchResource::getUrl('index'));
                }),
        ];
    }

    private function match(): FootballMatch
    {
        return $this->getRecord()->loadMissing(['rosterEntries.player', 'events.scorer', 'events.assistPlayer']);
    }

    /**
     * @return Collection<int, Player>
     */
    private function scoringPlayers(): Collection
    {
        $players = $this->match()->rosterEntries
            ->map(fn ($entry) => $entry->player)
            ->filter();

        if ($players->isEmpty()) {
            $players = Player::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return $players
            ->sortBy('name')
            ->values();
    }

    private function scoringPlayerOptions(): array
    {
        return $this->scoringPlayers()
            ->pluck('name', 'id')
            ->all();
    }
}
