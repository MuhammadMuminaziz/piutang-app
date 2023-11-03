<?php

use App\Http\Controllers\DownloadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::redirect('/', 'login')->name('login');
Route::get('download/histori/{id}', [DownloadController::class, 'downloadHistori'])->name('download.histori');
Route::get('download/laporan/{fromdate?}/{untildate?}', [DownloadController::class, 'downloadLaporan'])->name('download.laporan');
Route::get('download/pendapatan', [DownloadController::class, 'downloadPendapatan'])->name('download.pendapatan');
Route::get('download/faktur/{idPiutang?}/{noFaktur?}/{date?}', [DownloadController::class, 'downloadFaktur'])->name('download.faktur');
