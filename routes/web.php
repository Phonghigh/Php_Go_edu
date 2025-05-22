<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ScoreController;

// Route test đơn giản để kiểm tra API hoạt động
Route::get('/hello', function () {
    return response()->json(['message' => 'Hello API']);
});

// Các route chính cho ScoreController
Route::get('/api/scores/{regNo}', [ScoreController::class, 'show']);
Route::get('/api/reports/levels', [ScoreController::class, 'levels']);
Route::get('/api/top10', [ScoreController::class, 'top10']);

Route::get('/', function () {
    return view('welcome');
});
