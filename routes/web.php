<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\TransaksiImportController;
use App\Http\Controllers\MatrixController;
use App\Http\Controllers\HistoryController;

// Route untuk halaman home
Route::get('/', [HomeController::class, 'home'])->name('home');

// Halaman upload CSV
Route::get('/upload', [TransaksiImportController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi/import', [TransaksiImportController::class, 'import'])->name('transaksi.import');
Route::get('/transaksi/export', [TransaksiImportController::class, 'export'])->name('transaksi.export');

// Routes untuk AHP Matrix
Route::get('/matrix/{id_transaksi?}', [MatrixController::class, 'index'])->name('matrix.index');
Route::post('/matrix/process', [MatrixController::class, 'process'])->name('matrix.process');
Route::get('/matrix/result/{id_transaksi}', [MatrixController::class, 'result'])->name('matrix.result');

// Halaman Perangkingan
Route::get('/ranking/{id_transaksi?}', [RankingController::class, 'ranking'])->name('rankingPage');

Route::get('/transaksi/{id}', [TransaksiImportController::class, 'detail'])->name('detail');
Route::get('/transaksi/{id}/edit', [TransaksiImportController::class, 'edit'])->name('transaksi.edit');
Route::put('/transaksi/{id}', [TransaksiImportController::class, 'update'])->name('transaksi.update');
Route::delete('/transaksi/{id}', [TransaksiImportController::class, 'delete'])->name('transaksi.delete');

// History routes
Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
Route::get('/history/detail/{id}', [HistoryController::class, 'detail'])->name('history.detail');
Route::delete('/history/delete/{id}', [HistoryController::class, 'delete'])->name('history.delete');