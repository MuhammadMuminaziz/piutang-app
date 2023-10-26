<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PiutangResource\Pages;
use App\Filament\Resources\PiutangResource\RelationManagers;
use App\Models\Kredit;
use App\Models\Piutang;
use Filament\Forms;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Modal\Actions\Action as ActionsAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class PiutangResource extends Resource
{
    protected static ?string $model = Piutang::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Piutang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_customer')->label('Id Customer')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')->label('Nama')
                    ->required()
                    ->maxLength(255),
                Textarea::make('address')->label('Alamat')
                    ->required()
                    ->maxLength(255),
                TextInput::make('bill')->label('Piutang')
                    ->required()
                    ->maxLength(255)
                    ->numeric(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    ->dateTime('d F Y'),
            ])
            ->filters([
                // 
            ])
            ->actions([
                Action::make('bayar')->label('Bayar')
                    ->color('primary')
                    ->icon('heroicon-o-cash')
                    ->modalWidth('md')
                    ->modalHeading('Bayar Piutang')
                    ->mountUsing(fn (Model $record, ComponentContainer $form) => $form->fill([
                        'id' => $record->id,
                        'id_customer' => $record->id_customer,
                        'name' => $record->name,
                        'address' => $record->address,
                        'bill' => $record->bill,
                    ]))
                    ->form([
                        Hidden::make('id'),
                        Hidden::make('id_customer'),
                        Hidden::make('address'),
                        Hidden::make('bill'),
                        TextInput::make('name')->label('Nama')
                            ->disabled(),
                        TextInput::make('price')->label('Bayar')
                            ->required()
                            ->numeric()
                            ->placeholder('Contoh: 100000'),
                    ])
                    ->modalButton('Save change')
                    ->action(fn (array $data) => self::bayar($data)),
                Action::make('faktur')->label('Faktur')
                    ->color('success')
                    ->icon('heroicon-o-download')
                    ->modalWidth('md')
                    ->modalHeading('Generate Faktur')
                    ->mountUsing(fn (Model $record, ComponentContainer $form) => $form->fill([
                        'id' => $record->id,
                        'id_customer' => $record->id_customer,
                        'name' => $record->name,
                        'address' => $record->address,
                        'bill' => $record->bill,
                    ]))
                    ->form([
                        Hidden::make('id'),
                        Hidden::make('id_customer'),
                        Hidden::make('address'),
                        TextInput::make('name')->label('Nama')
                            ->disabled(),
                        TextInput::make('no_faktur')->label('No. Faktur')
                            ->required(),
                        DatePicker::make('tempo')->label('Tanggal Jt Tempo')
                            ->required()
                            ->displayFormat('d-m-Y')
                            ->format('d-m-Y'),
                        TextInput::make('bill')->label('Piutang')
                            ->required()
                            ->numeric()
                            ->disabled(),
                    ])
                    ->modalButton('Download Faktur')
                    ->action(fn (array $data) => self::downloadFaktur($data)),
                ActionGroup::make([
                    ViewAction::make()->label('Histori')
                        ->color('primary')
                        ->icon('heroicon-o-clipboard-list'),
                    Tables\Actions\EditAction::make()
                        ->modalWidth('md'),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePiutangs::route('/'),
            'view' => Pages\HistoryPiutang::route('/{record}'),
        ];
    }

    public static function bayar($data)
    {
        $bill = $data['bill'] - $data['price'];
        Kredit::create([
            'piutang_id' => $data['id'],
            'price' => $data['price']
        ]);

        if ($bill <= 0) {
            $dataPiutang = [
                'bill' => 0,
                'status' => true
            ];
        } else {
            $dataPiutang = [
                'bill' => $bill
            ];
        }

        Piutang::find($data['id'])->update($dataPiutang);
        return Notification::make()
            ->success()
            ->title('Saved')
            ->send();
    }

    public static function downloadFaktur($data)
    {
        $pdf = Pdf::loadview('pdf.faktur', compact('data'))->setOption(['defaultFont' => 'sans-serif'])->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Faktur Piutang " . date('d F Y') . '.pdf'
        );
    }
}
