<?php

namespace App\Filament\Resources\FootballMatches\Pages;

use App\Filament\Resources\FootballMatches\FootballMatchResource;
use App\Models\FootballMatch;
use Filament\Actions\CreateAction;
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
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'scheduled' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', FootballMatch::STATUS_SCHEDULED)),
            'live' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', FootballMatch::STATUS_LIVE)),
            'finished' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', FootballMatch::STATUS_FINISHED)),
        ];
    }
}
