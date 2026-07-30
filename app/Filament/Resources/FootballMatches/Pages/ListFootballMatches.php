<?php

namespace App\Filament\Resources\FootballMatches\Pages;

use App\Filament\Resources\FootballMatches\FootballMatchResource;
use App\Models\FootballMatch;
use App\Team\WhatsAppMatchTemplateImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListFootballMatches extends ListRecords
{
    protected static string $resource = FootballMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('createFromWhatsAppTemplate')
                ->label('Create from WhatsApp template')
                ->schema([
                    TextInput::make('opponent')
                        ->label('Opponent / match title')
                        ->default('Internal Game')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('template')
                        ->label('WhatsApp template')
                        ->rows(16)
                        ->required()
                        ->helperText('Paste the group message to create the match and roster automatically.'),
                ])
                ->action(function (array $data): void {
                    $match = app(WhatsAppMatchTemplateImport::class)->create($data['template'], $data['opponent']);

                    Notification::make()
                        ->success()
                        ->title('Match created from WhatsApp template')
                        ->send();

                    $this->redirect(FootballMatchResource::getUrl('rosters', ['record' => $match], shouldGuessMissingParameters: true));
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'scheduled' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', FootballMatch::STATUS_SCHEDULED)),
            'starting' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', FootballMatch::STATUS_STARTING)),
            'live' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', FootballMatch::STATUS_LIVE)),
            'finished' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', FootballMatch::STATUS_FINISHED)),
        ];
    }
}
