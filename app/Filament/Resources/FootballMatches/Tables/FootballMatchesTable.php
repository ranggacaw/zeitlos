<?php

namespace App\Filament\Resources\FootballMatches\Tables;

use App\Filament\Resources\FootballMatches\FootballMatchResource;
use App\Models\FootballMatch;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FootballMatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('opponent')
                    ->searchable(),
                TextColumn::make('match_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('match_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('venue')
                    ->searchable(),
                TextColumn::make('maps_url')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ticket_price')
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dress_code')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_label')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_amount')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        FootballMatch::STATUS_SCHEDULED => 'Scheduled',
                        FootballMatch::STATUS_STARTING => 'Starting',
                        FootballMatch::STATUS_LIVE => 'Live',
                        FootballMatch::STATUS_FINISHED => 'Finished',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        FootballMatch::STATUS_STARTING => 'warning',
                        FootballMatch::STATUS_LIVE => 'success',
                        FootballMatch::STATUS_FINISHED => 'gray',
                        default => 'info',
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Match status')
                    ->options([
                        FootballMatch::STATUS_SCHEDULED => 'Scheduled',
                        FootballMatch::STATUS_STARTING => 'Starting',
                        FootballMatch::STATUS_LIVE => 'Live',
                        FootballMatch::STATUS_FINISHED => 'Finished',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Edit match')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->iconButton()
                    ->tooltip('Edit match'),
                Action::make('manageRoster')
                    ->label('Manage roster')
                    ->icon(Heroicon::OutlinedUserGroup)
                    ->iconButton()
                    ->tooltip('Manage roster')
                    ->visible(fn (FootballMatch $record): bool => $record->status !== FootballMatch::STATUS_FINISHED)
                    ->url(fn (FootballMatch $record): string => FootballMatchResource::getUrl('rosters', ['record' => $record], shouldGuessMissingParameters: true)),
                Action::make('liveScoring')
                    ->label('Live scoring')
                    ->icon(Heroicon::OutlinedBolt)
                    ->iconButton()
                    ->tooltip('Live scoring')
                    ->visible(fn (FootballMatch $record): bool => $record->status !== FootballMatch::STATUS_FINISHED)
                    ->url(fn (FootballMatch $record): string => FootballMatchResource::getUrl('live-scoring', ['record' => $record], shouldGuessMissingParameters: true)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
