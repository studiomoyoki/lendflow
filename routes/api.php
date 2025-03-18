<?php
use App\Http\Controllers\NewYorkTimesController;
use Illuminate\Support\Facades\Route;

Route::get('/nyt/best-sellers',[NewYorkTimesController::class, 'getBestSellers']);
