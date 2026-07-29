<?php

namespace App\Filament\Resources\FootballMatches\Tables;

use App\Models\FootballMatch;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                    ->searchable(),
                TextColumn::make('ticket_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('dress_code')
                    ->searchable(),
                TextColumn::make('payment_label')
                    ->searchable(),
                TextColumn::make('payment_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_due_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('zeitlos_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('opponent_score')
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
                SelectFilter::make('status')
                    ->options([
                        FootballMatch::STATUS_SCHEDULED => 'Scheduled',
                        FootballMatch::STATUS_LIVE => 'Live',
                        FootballMatch::STATUS_FINISHED => 'Finished',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
