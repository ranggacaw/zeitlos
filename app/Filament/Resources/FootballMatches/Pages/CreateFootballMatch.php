<?php

namespace App\Filament\Resources\FootballMatches\Pages;

use App\Filament\Resources\FootballMatches\FootballMatchResource;
use App\Team\WhatsAppMatchTemplateImport;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateFootballMatch extends CreateRecord
{
    protected static string $resource = FootballMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importWhatsAppTemplate')
                ->label('Import WhatsApp template')
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
}
