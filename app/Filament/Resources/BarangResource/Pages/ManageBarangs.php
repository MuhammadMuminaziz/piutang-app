<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBarangs extends ManageRecords
{
    protected static string $resource = BarangResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('md'),
        ];
    }
}
