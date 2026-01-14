<?php

use Illuminate\Support\Facades\Route;
use IntentDoc\Laravel\Http\Controllers\IntentDocController;

Route::get('/intent-doc', [IntentDocController::class, 'index'])->name('intent-doc.index');
Route::get('/intent-doc/api', [IntentDocController::class, 'api'])->name('intent-doc.api');
