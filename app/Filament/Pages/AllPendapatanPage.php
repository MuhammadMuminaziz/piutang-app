<?php

namespace App\Filament\Pages;

use App\Models\Kredit;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action as ActionsAction;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class AllPendapatanPage extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.all-pendapatan-page';

    protected static ?string $title = 'Semua';

    protected static ?string $navigationGroup = 'Pendapatan';

    public $fromDate;
    public $untilDate;
    public $pendapatan;

    public function mount(): void
    {
        $this->pendapatan = Kredit::sum('price');
    }

    protected function getTableQuery(): Builder
    {
        return Kredit::orderBy('id', 'desc');
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
            TextColumn::make('name_cathier')->label('Nama Kasir')
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
                ->url(fn () => route('download.pendapatan', ['type' => 'semua', 'fromdate' => $this->fromDate, 'untildate' => $this->untilDate]))
                ->openUrlInNewTab(),
            ActionsAction::make('download')->label('Download')
                ->action(fn () => $this->download($this->fromDate, $this->untilDate)),
        ];
    }

    public function getTableFilters(): array
    {
        return [
            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            function (Builder $query, $date): Builder {
                                $this->fromDate = $date;
                                $this->changePendapatan(fromDate: $date);
                                return $query->whereDate('created_at', '>=', $date);
                            },
                        )
                        ->when(
                            $data['created_until'],
                            function (Builder $query, $date): Builder {
                                $this->untilDate = $date;
                                $this->changePendapatan(untilDate: $date);
                                return $query->whereDate('created_at', '<=', $date);
                            },
                        );
                })
            ];
    }

    public function download($fromDate = null, $untilDate = null)
    {
        $credits = Kredit::orderBy('id', 'desc')
            ->when($fromDate, function(Builder $query, $fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($untilDate, function(Builder $query, $untilDate) {
                $query->whereDate('created_at', '<=', $untilDate);
            })->get();

        $data = [
            'tanggal' => date('d F Y'),
            'pendapatan' => $credits->sum('price')
        ];

        if ($credits->count() != 0) {
            $pdf = Pdf::loadview('pdf.histories', compact('credits', 'data'))->setOption(['defaultFont' => 'sans-serif'])->output();
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

    public function changePendapatan($fromDate = null, $untilDate = null)
    {
        $this->pendapatan = Kredit::when($fromDate, function(Builder $query, $fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        })->when($untilDate, function(Builder $query, $untilDate) {
            $query->whereDate('created_at', '<=', $untilDate);
        })->sum('price');
    }
}
