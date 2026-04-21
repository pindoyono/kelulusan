<?php

use App\Http\Controllers\CekKelulusanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CekKelulusanController::class, 'index'])->name('cek-kelulusan');
Route::post('/cek', [CekKelulusanController::class, 'cari'])->name('cek-kelulusan.cari');
Route::get('/cek', fn () => redirect('/'));
Route::get('/skl/{kelulusan}/download', [CekKelulusanController::class, 'downloadSkl'])->name('skl.download');
