<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\TestController;
use \App\Http\Controllers\PostController;

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

Route::get('/run-job', [TestController::class, 'runJob']);
Route::get('/testing', [TestController::class, 'register']);

Route::group(['middleware' => ['auth'], 'prefix' => 'post'], function () {
    Route::get('test', [TestController::class, 'index'])->middleware('addLog');

    Route::get('update/{post}', [PostController::class, 'update'])->middleware('addLog');

});
Route::resource('posts', PostController::class)->middleware('auth');

Route::get('file-upload', [FileController::class, 'fileUpload']);
Route::get('download/{id}', [FileController::class, 'download']);
Route::post('file-upload', [FileController::class, 'upload'])->name('fileUpload');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
