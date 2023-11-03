<?php

namespace App\Filament\Widgets;

use App\Models\Kredit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CountingPendapatan extends BaseWidget
{
    // protected static string $view = 'filament.widgets.counting-pendapatan';
    protected function getCards(): array
    {
        $count = Kredit::whereDate('created_at', now())->sum('price');
        return [
            Card::make('Pendapatan Harian', currency_IDR((int) $count))
                ->description('Total pendapatan harian yang terdaftar')
                ->descriptionIcon('heroicon-s-cash')
                ->color('primary'),
        ];
    }
}
