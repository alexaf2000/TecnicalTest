<?php

use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\HelloWorld;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/helloworld', HelloWorld::class);

// Budgets
Route::prefix('/budgets')->name('budgets.')->group(function(){
    Route::get('/', [BudgetController::class, 'index'])->name('index');
    Route::post('/', [BudgetController::class, 'store'])->name('store');
});
