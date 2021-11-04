<?php
use App\Http\Controllers\FormController;
use App\Http\Controllers\HomeController;
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

Auth::routes();

Route::resource('form', FormController::class);
Route::get('get-form-field', [FormController::class, 'getFormField'])->name('get-form-fields');
Route::get('view-form/{id}', [HomeController::class, 'viewForm'])->name('view-form');