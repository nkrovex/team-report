<?php

use App\Http\Controllers\TeamReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('report')->group(function () {
    Route::get('/json', [TeamReportController::class, 'getReportJSON']);
    Route::get('/xml', [TeamReportController::class, 'getReportXML']);
});
