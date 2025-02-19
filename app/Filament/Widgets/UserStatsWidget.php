<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Total number of users in the system')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
        ];
    }
}
