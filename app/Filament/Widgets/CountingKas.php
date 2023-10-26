<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\Piutang;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CountingKas extends BaseWidget
{
    // protected static string $view = 'filament.widgets.counting-kas';

    protected function getCards(): array
    {
        $count = Piutang::sum('bill') + Barang::sum('price');
        return [
            Card::make('Saldo KAS', '- ' . currency_IDR((int) $count))
                ->description('Total saldo kas yang terdaftar')
                ->descriptionIcon('heroicon-s-cash')
                ->color('primary'),
        ];
    }
}
