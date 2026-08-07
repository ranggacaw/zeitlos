<?php

namespace App\Filament\Pages;

use App\Models\MatchEvent;
use App\Models\Player;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use UnitEnum;

class Leaderboard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Leaderboard corrections';

    protected static ?string $slug = 'leaderboard';

    protected static ?string $navigationLabel = 'Leaderboard';

    protected static string|UnitEnum|null $navigationGroup = 'Team management';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                EmbeddedTable::make(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => Player::query()
                ->withCount(['scoredEvents as event_goals' => fn ($query) => $query->where('event_type', MatchEvent::TYPE_GOAL)])
                ->withCount(['assistedEvents as event_assists' => fn ($query) => $query->where('event_type', MatchEvent::TYPE_GOAL)])
                ->withCount(['rosterEntries as roster_appearances'])
                ->orderByDesc('is_active')
                ->orderByRaw('jersey_number is null')
                ->orderBy('jersey_number')
                ->orderBy('name'))
            ->paginated(false)
            ->defaultSort('jersey_number')
            ->columns([
                TextColumn::make('jersey_number')
                    ->label('#')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position')
                    ->placeholder('-')
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('event_goals')
                    ->label('Event goals')
                    ->numeric(),
                TextColumn::make('goals_adjustment')
                    ->label('Goals adj.')
                    ->numeric(),
                TextColumn::make('adjusted_goals')
                    ->label('Goals')
                    ->state(fn (Player $record): int => (int) $record->event_goals + (int) $record->goals_adjustment)
                    ->numeric(),
                TextColumn::make('event_assists')
                    ->label('Event assists')
                    ->numeric(),
                TextColumn::make('assists_adjustment')
                    ->label('Assists adj.')
                    ->numeric(),
                TextColumn::make('adjusted_assists')
                    ->label('Assists')
                    ->state(fn (Player $record): int => (int) $record->event_assists + (int) $record->assists_adjustment)
                    ->numeric(),
                TextColumn::make('roster_appearances')
                    ->label('Roster apps')
                    ->numeric(),
                TextColumn::make('appearances_adjustment')
                    ->label('Apps adj.')
                    ->numeric(),
                TextColumn::make('adjusted_appearances')
                    ->label('Appearances')
                    ->state(fn (Player $record): int => (int) $record->roster_appearances + (int) $record->appearances_adjustment)
                    ->numeric(),
            ])
            ->recordActions([
                Action::make('editAdjustments')
                    ->label('Edit stats')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->fillForm(fn (Player $record): array => [
                        'goals_adjustment' => $record->goals_adjustment,
                        'assists_adjustment' => $record->assists_adjustment,
                        'appearances_adjustment' => $record->appearances_adjustment,
                    ])
                    ->schema([
                        TextInput::make('goals_adjustment')
                            ->label('Goals adjustment')
                            ->required()
                            ->integer(),
                        TextInput::make('assists_adjustment')
                            ->label('Assists adjustment')
                            ->required()
                            ->integer(),
                        TextInput::make('appearances_adjustment')
                            ->label('Appearances adjustment')
                            ->required()
                            ->integer(),
                    ])
                    ->action(function (array $data, Player $record): void {
                        $record->update([
                            'goals_adjustment' => $data['goals_adjustment'],
                            'assists_adjustment' => $data['assists_adjustment'],
                            'appearances_adjustment' => $data['appearances_adjustment'],
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Stats updated')
                            ->send();
                    }),
            ]);
    }
}
