<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('jobs.index');
});


Route::get('jobs', [JobController::class, 'index'])->name('jobs.index');
Route::post('jobs', [JobController::class, 'store'])->name('jobs.store');
Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');
Route::patch('jobs/{job}/toggle', [JobController::class, 'toggleCompleted'])->name('jobs.toggleCompleted');
