<?php

use App\Http\Controllers\DropdownController;
use App\Http\Controllers\PupukController;
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
    return view('dashboard');
})->name('home');

Route::resource('kelompok-tani', 'App\Http\Controllers\KelompokTaniController');
Route::resource('petani', 'App\Http\Controllers\PetaniController');

Route::prefix('dropdown')->controller(DropdownController::class)->as('dropdown.')->group(function () {
    Route::get('kelompok-tani', 'getKelompokTani')->name('kelompok-tani');
});
