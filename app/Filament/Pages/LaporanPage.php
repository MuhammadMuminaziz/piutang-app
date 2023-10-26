<?php

namespace App\Filament\Pages;

use App\Models\Piutang;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.laporan-page';

    protected static ?string $title = 'Laporan';

    protected static ?int $navigationSort = 4;

    protected function getTableQuery(): Builder
    {
        return Piutang::orderBy('id', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id_customer')->label('Id Customer')
                ->searchable()
                ->sortable(),
            TextColumn::make('name')->label('Nama')
                ->searchable()
                ->sortable(),
            TextColumn::make('address')->label('Alamat')
                ->searchable()
                ->sortable()
                ->limit(35),
            TextColumn::make('bill')->label('Piutang')
                ->searchable()
                ->sortable()
                ->money('idr', true),
            TextColumn::make('updated_at')->label('Terakhir diubah')
                ->searchable()
                ->sortable()
                ->dateTime('d F Y')
        ];
    }

    protected function getActions(): array
    {
        return [
            Action::make('download')->label('Download Laporan')
                ->action(fn () => $this->download())
        ];
    }

    public function download()
    {
        $piutangs = Piutang::orderBy('id', 'desc')->get();
        if ($piutangs->count() != 0) {
            $pdf = Pdf::loadview('pdf.laporan', compact('piutangs'))->setOption(['defaultFont' => 'sans-serif'])->output();
            return response()->streamDownload(
                fn () => print($pdf),
                "Laporan Piutang " . date('d F Y') . '.pdf'
            );
        } else {
            Notification::make()
                ->warning()
                ->title('Opps..')
                ->body('Laporan piutang belum tersedia')
                ->send();
        }
    }
}
