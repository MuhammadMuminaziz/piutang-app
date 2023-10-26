<?php

namespace App\Filament\Resources\PiutangResource\Pages;

use App\Filament\Resources\PiutangResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePiutangs extends ManageRecords
{
    protected static string $resource = PiutangResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('md'),
        ];
    }
}
