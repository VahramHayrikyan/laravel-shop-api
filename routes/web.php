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
    return view('welcome');
});

Route::get('/upload-file', [\App\Http\Controllers\FileController::class, 'createForm']);
Route::get('/download/{id}', [\App\Http\Controllers\FileController::class, 'download']);

Route::post('/upload-file', [\App\Http\Controllers\FileController::class, 'fileUpload'])->name('fileUpload');

Route::get('/show/{flight}', [\App\Http\Controllers\FlightController::class, 'make']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
