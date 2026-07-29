<?php

namespace App\Filament\Resources\FootballMatches\Pages;

use App\Filament\Resources\FootballMatches\FootballMatchResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditFootballMatch extends EditRecord
{
    protected static string $resource = FootballMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manageRoster')
                ->label('Manage roster')
                ->icon(Heroicon::OutlinedUserGroup)
                ->url(fn (): string => $this->getResourceUrl('rosters')),
            Action::make('liveScoring')
                ->label('Live scoring')
                ->icon(Heroicon::OutlinedBolt)
                ->url(fn (): string => $this->getResourceUrl('live-scoring')),
            DeleteAction::make(),
        ];
    }
}
