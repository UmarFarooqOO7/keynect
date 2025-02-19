<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class UserSignupsChart extends ChartWidget
{
    protected static ?string $heading = 'User Signups Trend';
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $users = User::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'New users',
                    'data' => $users->pluck('count')->toArray(),
                    'backgroundColor' => '#36a2eb',
                    'borderColor' => '#36a2eb',
                ],
            ],
            'labels' => $users->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('M d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
