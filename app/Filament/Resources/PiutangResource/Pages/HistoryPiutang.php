<?php

namespace App\Filament\Resources\PiutangResource\Pages;

use App\Filament\Resources\PiutangResource;
use App\Models\Kredit;
use App\Models\Piutang;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class HistoryPiutang extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = PiutangResource::class;

    protected static ?string $title = 'Histori Bayar';

    public Piutang $record;

    protected static string $view = 'filament.resources.piutang-resource.pages.history-piutang';

    protected function getTableQuery(): Builder
    {
        return Kredit::where('piutang_id', $this->record->id);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('piutang.name')->label('Nama Pelanggan')
                ->searchable()
                ->sortable(),
            TextColumn::make('price')->label('Bayar')
                ->searchable()
                ->sortable()
                ->money('idr', true),
            TextColumn::make('created_at')->label('Dibuat pada')
                ->searchable()
                ->sortable()
                ->dateTime('d F Y'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make()
                ->modalWidth('md')
                ->form([
                    TextInput::make('price')->label('Bayar')
                        ->required()
                        ->numeric()
                ]),
            DeleteAction::make()
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            DeleteBulkAction::make()
        ];
    }
}