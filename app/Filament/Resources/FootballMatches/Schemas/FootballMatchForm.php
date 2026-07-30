<?php

namespace App\Filament\Resources\FootballMatches\Schemas;

use App\Models\FootballMatch;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FootballMatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Schedule')
                    ->columns(3)
                    ->schema([
                        TextInput::make('opponent')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('match_date')
                            ->required(),
                        TimePicker::make('match_time'),
                    ]),
                Section::make('Venue and maps')
                    ->columns(2)
                    ->schema([
                        TextInput::make('venue')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('maps_url')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('ticket_price')
                            ->numeric()
                            ->prefix('EUR'),
                        TextInput::make('dress_code')
                            ->maxLength(255),
                        Textarea::make('facilities')
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ]),
                Section::make('Payment')
                    ->columns(2)
                    ->schema([
                        TextInput::make('payment_label')
                            ->maxLength(255),
                        TextInput::make('payment_amount')
                            ->numeric()
                            ->prefix('EUR'),
                        DateTimePicker::make('payment_due_at'),
                        Textarea::make('payment_instructions')
                            ->columnSpanFull(),
                    ]),
                Section::make('WhatsApp announcement')
                    ->schema([
                        Textarea::make('whatsapp_announcement')
                            ->columnSpanFull(),
                    ]),
                Section::make('Status and score')
                    ->columns(3)
                    ->schema([
                        Select::make('status')
                            ->options([
                                FootballMatch::STATUS_SCHEDULED => 'Scheduled',
                                FootballMatch::STATUS_STARTING => 'Starting',
                                FootballMatch::STATUS_LIVE => 'Live',
                                FootballMatch::STATUS_FINISHED => 'Finished',
                            ])
                            ->required()
                            ->default(FootballMatch::STATUS_SCHEDULED),
                        TextInput::make('zeitlos_score')
                            ->numeric(),
                        TextInput::make('opponent_score')
                            ->numeric(),
                    ]),
            ]);
    }
}
