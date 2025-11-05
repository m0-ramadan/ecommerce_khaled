<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Website\UserAddressController;


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('addresses')->group(function () {
        Route::get('/', [UserAddressController::class, 'index']);       
        Route::get('/{id}', [UserAddressController::class, 'show']);    
        Route::post('/', [UserAddressController::class, 'store']);      
        Route::put('/{id}', [UserAddressController::class, 'update']); 
        Route::delete('/{id}', [UserAddressController::class, 'destroy']); 
    });

});
