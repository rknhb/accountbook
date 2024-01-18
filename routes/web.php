<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('homebudget/index');
});

Route::get('/', [App\Http\Controllers\HomebudgetController::class, 'index'])->name('index');
Route::post('/post', [App\Http\Controllers\HomebudgetController::class, 'store'])->name('store');
Route::get('/edit/{id}', [App\Http\Controllers\HomebudgetController::class, 'edit'])->name('homebudget.edit');
Route::put('/update', [App\Http\Controllers\HomebudgetController::class, 'update'])->name('homebudget.update');
Route::post('/destroy/{id}', [App\Http\Controllers\HomebudgetController::class, 'destroy'])->name('homebudget.destroy');