<?php

use App\Http\Controllers\RaportExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/raports/{raport}/export/pdf',   [RaportExportController::class, 'pdf'])
         ->name('raports.export.pdf');
    Route::get('/raports/{raport}/export/excel', [RaportExportController::class, 'excel'])
         ->name('raports.export.excel');
});