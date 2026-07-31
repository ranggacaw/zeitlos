<?php

namespace App\Filament\Resources\Players\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PlayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Roster identity')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('jersey_number')
                            ->numeric(),
                        TextInput::make('position')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->required(),
                        FileUpload::make('photo_path')
                            ->image()
                            ->disk('public')
                            ->directory('players')
                            ->visibility('public')
                            ->dehydrateStateUsing(function ($state, Get $get) {
                                $url = $get('photo_url');

                                if (blank($state) && is_string($url) && preg_match('#^https?://#i', $url)) {
                                    return $url;
                                }

                                return $state;
                            }),
                        TextInput::make('photo_url')
                            ->label('Photo URL')
                            ->dehydrated(false)
                            ->formatStateUsing(fn ($state, $record) => (is_string($record?->photo_path) && preg_match('#^https?://#i', $record->photo_path)) ? $record->photo_path : null)
                            ->afterStateUpdated(fn ($state, Set $set) => filled($state) ? $set('photo_path', $state) : $set('photo_path', null)),
                        DatePicker::make('joined_at'),
                    ]),
                Section::make('Stat corrections')
                    ->columns(2)
                    ->schema([
                        TextInput::make('goals_adjustment')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('assists_adjustment')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }
}
