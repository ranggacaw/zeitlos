<?php

namespace App\Filament\Resources\Players\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
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
                        TextInput::make('photo_path')
                            ->maxLength(255),
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
