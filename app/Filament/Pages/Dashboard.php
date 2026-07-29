<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Zeitlos CMS';

    public function getWidgets(): array
    {
        return [
            AdminOverview::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 1;
    }
}
