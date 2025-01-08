<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/posts/store', [App\Http\Controllers\Controller::class, 'store']);
Route::get('/getMapData', [App\Http\Controllers\Controller::class, 'getMapData']);
Route::get('/getMitra', [App\Http\Controllers\Controller::class, 'getMitra']);
Route::get('/getDataKerjasama', [App\Http\Controllers\Controller::class, 'getDataKerjasama']);
Route::get('/getMitraPenggiatKerjasama', [App\Http\Controllers\Controller::class, 'getMitraPenggiatKerjasama']);
Route::get('/getProdiPenggiatKerjasama', [App\Http\Controllers\Controller::class, 'getProdiPenggiatKerjasama']);
Route::get('/getMoA', [App\Http\Controllers\Controller::class, 'getMoA']);
Route::get('/getIA', [App\Http\Controllers\Controller::class, 'getIA']);
Route::get('/getProdi', [App\Http\Controllers\Controller::class, 'getProdi']);
Route::get('/getProdiMitra', [App\Http\Controllers\Controller::class, 'getProdiMitra']);
Route::get('/getFakultas', [App\Http\Controllers\Controller::class, 'getFakultas']);
Route::get('/getFakultasMitra', [App\Http\Controllers\Controller::class, 'getFakultasMitra']);
