<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\Piutang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function downloadHistori($id)
    {
        $user = Piutang::find($id);
        Pdf::setOption(['defaultFont' => 'sans-serif']);
        $pdf = Pdf::loadview('pdf.history', compact('user'));
        return $pdf->stream();
    }

    public function downloadPendapatan($type, $fromDate = null, $untilDate = null)
    {
        if ($type === 'harian') {
            $credits = Kredit::orderBy('id', 'desc')->whereDate('created_at', now())->get();
        } else {
            $credits = Kredit::orderBy('id', 'desc')
                ->when($fromDate, function(Builder $query, $fromDate) {
                    $query->whereDate('created_at', '>=', $fromDate);
                })
                ->when($untilDate, function(Builder $query, $untilDate) {
                    $query->whereDate('created_at', '<=', $untilDate);
                })->get();
        }

        $data = [
            'pendapatan' => $credits->sum('price')
        ];
        
        Pdf::setOption(['defaultFont' => 'sans-serif']);
        $pdf = Pdf::loadview('pdf.histories', compact('credits', 'data'));
        return $pdf->stream();
    }

    public function downloadLaporan($fromDate = null, $untilDate = null)
    {
        $piutangs = Piutang::orderBy('id', 'desc')
            ->when($fromDate, function(Builder $query, $fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($untilDate, function(Builder $query, $untilDate) {
                $query->whereDate('created_at', '<=', $untilDate);
            })->get();

        $data['pendapatan'] = $piutangs->sum('bill');

        Pdf::setOption(['defaultFont' => 'sans-serif']);
        $pdf = Pdf::loadview('pdf.laporan', compact('piutangs', 'data'));
        return $pdf->stream();
    }

    public function downloadFaktur($id, $faktur, $date)
    {
        $data = Piutang::find($id);
        $data['no_faktur'] = $faktur;
        $data['tempo'] = $date;

        Pdf::setOption(['defaultFont' => 'sans-serif']);
        $pdf = Pdf::loadview('pdf.faktur', compact('data'));
        return $pdf->stream();
    }
}
