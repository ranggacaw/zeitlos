<?php

namespace App\Filament\Resources\FootballMatches\Pages;

use App\Filament\Resources\FootballMatches\FootballMatchResource;
use App\Models\FootballMatch;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Team\WhatsAppMatchTemplateImport;
use App\Team\WhatsAppRosterText;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class ManageFootballMatchRosters extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = FootballMatchResource::class;

    protected static ?string $title = 'Manage roster';

    protected static ?string $breadcrumb = 'Roster';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => MatchRoster::query()
                ->where('match_id', $this->getRecord()->getKey())
                ->with('player'))
            ->paginated(false)
            ->defaultSort('role')
            ->groups([
                Group::make('role')
                    ->label('Role')
                    ->getTitleFromRecordUsing(fn (MatchRoster $record): string => $record->role === MatchRoster::ROLE_GOALKEEPER ? 'Goalkeepers' : 'Squad')
                    ->collapsible(),
            ])
            ->defaultGroup('role')
            ->columns([
                TextColumn::make('display_name')
                    ->label('Name')
                    ->state(fn (MatchRoster $record): ?string => $record->player?->name ?? $record->guest_name),
                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === MatchRoster::ROLE_GOALKEEPER ? 'Goalkeeper' : 'Player'),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->visible(fn (): bool => $this->getRecord()->status !== FootballMatch::STATUS_FINISHED)
                    ->successRedirectUrl(fn (): string => $this->getResourceUrl('rosters'))
                    ->action(fn (MatchRoster $record) => $record->delete()),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        $match = $this->getRecord();
        $roster = $match->rosterEntries()->with('player')->get();
        $whatsappText = app(WhatsAppRosterText::class)->build($match, $roster);

        return $schema
            ->components([
                Section::make('WhatsApp message')
                    ->description('Copyable roster text generated from the current match roster.')
                    ->schema([
                        Html::make(view('filament.football-matches.pages.rosters.whatsapp-panel', [
                            'whatsappText' => $whatsappText,
                        ])->render()),
                    ])
                    ->collapsible(),
                EmbeddedTable::make(),
            ]);
    }

    public function getSubheading(): ?string
    {
        $match = $this->getRecord();

        $parts = array_filter([
            'vs '.$match->opponent,
            $match->match_date?->format('j M Y'),
            $match->match_time,
            $match->venue,
        ]);

        if ($match->status === FootballMatch::STATUS_FINISHED) {
            $parts[] = 'Finalized · read-only';
        }

        return filled($parts) ? implode(' · ', $parts) : null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('editMatch')
                ->label('Edit match')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->color('gray')
                ->url(fn () => $this->getResourceUrl('edit')),
            Action::make('addRosterEntry')
                ->label('Add roster entry')
                ->icon(Heroicon::OutlinedPlus)
                ->visible(fn (): bool => $this->getRecord()->status !== FootballMatch::STATUS_FINISHED)
                ->schema([
                    Select::make('player_id')
                        ->label('Player')
                        ->options(Player::query()->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->helperText('Choose either an existing player or a guest name.')
                        ->rule(function (Get $get) {
                            return function (string $attribute, mixed $value, \Closure $fail) use ($get) {
                                if (filled($value) && filled($get('guest_name'))) {
                                    $fail('Choose either an existing player or a guest name, not both.');
                                }
                            };
                        }),
                    TextInput::make('guest_name')
                        ->label('Guest name')
                        ->maxLength(255)
                        ->required(fn (Get $get): bool => blank($get('player_id')))
                        ->validationMessages([
                            'required' => 'Select a player or enter a guest name.',
                        ])
                        ->rule(function (Get $get) {
                            return function (string $attribute, mixed $value, \Closure $fail) use ($get) {
                                if (filled($value) && filled($get('player_id'))) {
                                    $fail('Choose either an existing player or a guest name, not both.');
                                }
                            };
                        }),
                    Select::make('role')
                        ->label('Role')
                        ->options([
                            MatchRoster::ROLE_PLAYER => 'Player',
                            MatchRoster::ROLE_GOALKEEPER => 'Goalkeeper',
                        ])
                        ->required()
                        ->default(MatchRoster::ROLE_PLAYER),
                ])
                ->action(function (array $data): void {
                    MatchRoster::create([
                        'match_id' => $this->getRecord()->getKey(),
                        'player_id' => $data['player_id'] ?? null,
                        'guest_name' => $data['guest_name'] ?? null,
                        'role' => $data['role'],
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Roster entry added')
                        ->send();
                }),
            Action::make('importWhatsAppTemplate')
                ->label('Import WhatsApp template')
                ->visible(fn (): bool => $this->getRecord()->status !== FootballMatch::STATUS_FINISHED)
                ->schema([
                    Textarea::make('template')
                        ->label('WhatsApp template')
                        ->rows(16)
                        ->required()
                        ->helperText('Paste the group message to fill match details and roster names.'),
                    Toggle::make('replace_roster')
                        ->label('Replace current roster')
                        ->default(true),
                ])
                ->action(function (array $data): void {
                    $result = app(WhatsAppMatchTemplateImport::class)->import(
                        $this->getRecord(),
                        $data['template'],
                        $data['replace_roster'] ?? true,
                    );

                    $this->record->refresh();

                    Notification::make()
                        ->success()
                        ->title('WhatsApp template imported')
                        ->body($result['roster_count'].' roster entries imported.')
                        ->send();
                }),
        ];
    }
}
