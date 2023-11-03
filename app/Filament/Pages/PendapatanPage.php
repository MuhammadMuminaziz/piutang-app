<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CountingPendapatan;
use App\Models\Kredit;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Pages\Actions\Action as ActionsAction;

class PendapatanPage extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static string $view = 'filament.pages.pendapatan-page';

    protected static ?string $title = 'Pendapatan Harian';

    protected function getTableQuery(): Builder
    {
        return Kredit::orderBy('id', 'desc')->whereDate('created_at', now());
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('piutang.id_customer')->label('Id Customer')
                ->searchable()
                ->sortable(),
            TextColumn::make('created_at')->label('Tgl Transaksi')
                ->searchable()
                ->sortable()
                ->dateTime('d F Y'),
            TextColumn::make('piutang.no_faktur')->label('No Faktur')
                ->searchable()
                ->sortable(),
            TextColumn::make('piutang.name_cathier')->label('Nama Kasir')
                ->searchable()
                ->sortable(),
            TextColumn::make('piutang.name')->label('Nama Pelanggan')
                ->searchable()
                ->sortable(),
            TextColumn::make('price')->label('Bayar')
                ->searchable()
                ->sortable()
                ->money('idr', true),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('history')->label('Lihat')
                ->icon('heroicon-s-eye')
                ->url(fn ($record) => url('/piutangs/' . $record['piutang_id']))
        ];
    }

    protected function getActions(): array
    {
        return [
            ActionsAction::make('print')->label('Print')
                ->url(fn () => route('download.pendapatan'))
                ->openUrlInNewTab(),
            ActionsAction::make('download')->label('Download')
                ->action(fn () => $this->download()),
        ];
    }

    public function download()
    {
        $credits = Kredit::orderBy('id', 'desc')->whereDate('created_at', now())->get();
        $data = [
            'tanggal' => date('d F Y'),
            'pendapatan' => $credits->sum('price')
        ];

        if ($credits->count() != 0) {
            $pdf = Pdf::loadview('pdf.histories', compact('credits', 'data'))->setOption(['defaultFont' => 'sans-serif'])->setPaper('a4','landscape')->output();
            return response()->streamDownload(
                fn () => print($pdf),
                "Laporan Pendapatan " . date('d F Y') . '.pdf'
            );
        } else {
            Notification::make()
                ->warning()
                ->title('Opps..')
                ->body('Laporan Pendapatan belum tersedia')
                ->send();
        }
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CountingPendapatan::class
        ];
    }
}
