<?php

namespace App\Filament\Resources\Players\Tables;

use App\Team\GuestPlayerRestorer;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PlayersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jersey_number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('position')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('photo_path')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('joined_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('goals')
                    ->state(fn ($record): int => $record->goalsCount())
                    ->numeric()
                    ->sortable(),
                TextColumn::make('assists')
                    ->state(fn ($record): int => $record->assistsCount())
                    ->numeric()
                    ->sortable(),
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
                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All players')
                    ->trueLabel('Active players')
                    ->falseLabel('Inactive players'),
                Filter::make('guest_recovery_candidates')
                    ->label('Guest recovery candidates')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereNull('jersey_number')
                        ->whereNull('photo_path')
                        ->whereNull('joined_at')
                        ->where('goals_adjustment', 0)
                        ->where('assists_adjustment', 0)
                        ->where('appearances_adjustment', 0)
                        ->where(fn (Builder $query): Builder => $query
                            ->whereHas('rosterEntries')
                            ->orWhereHas('scoredEvents')
                            ->orWhereHas('assistedEvents'))),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('restoreGuests')
                        ->label('Restore selected as guests')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Restore selected players as guests?')
                        ->modalDescription('This restores each selected player reference to a guest name, then permanently deletes the selected player record. Review your selection carefully.')
                        ->action(fn (Collection $records) => app(GuestPlayerRestorer::class)->restore($records)),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
