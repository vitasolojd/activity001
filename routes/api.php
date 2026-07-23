<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlowerController;
use Illuminate\Http\Request;

Route::apiResource('flowers', FlowerController::class);

Route::get('/test', function (Request $request) {
    return response()->json([
        'your_input' => $request->input('name')
    ]);
});