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
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Filters\Filter;

class LaporanPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.laporan-page';

    protected static ?string $title = 'Laporan';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 4;

    public $fromDate;
    public $untilDate;

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
                                return $query->whereDate('created_at', '>=', $date);
                            },
                        )
                        ->when(
                            $data['created_until'],
                            function (Builder $query, $date): Builder {
                                $this->untilDate = $date;
                                return $query->whereDate('created_at', '<=', $date);
                            },
                        );
                })
            ];
    }

    protected function getActions(): array
    {
        return [
            Action::make('print')->label('Print')
                ->url(fn () => route('download.laporan', ['fromdate' => $this->fromDate, 'untildate' => $this->untilDate]))
                ->openUrlInNewTab(),
            Action::make('download')->label('Download')
                ->action(fn () => $this->download()),
        ];
    }

    public function download()
    {
        $fromDate = $this->fromDate;
        $untilDate = $this->untilDate;

        $piutangs = Piutang::orderBy('id', 'desc')
            ->when($fromDate != null, function(Builder $query, $fromDate) {
                $query->whereDate('created_at', '>=', $this->fromDate);
            })
            ->when($untilDate != null, function(Builder $query, $untilDate) {
                $query->whereDate('created_at', '<=', $this->untilDate);
            })->get();

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
