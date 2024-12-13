<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PenjualanController;


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

// Route::apiResource('pelanggan', PelangganController::class);
//table pelanggan
Route::get('/pelanggan/count', [PelangganController::class, 'getCountPelanggan']);
Route::get('/pelanggan', [PelangganController::class, 'index']);
Route::post('/pelanggan/store', [PelangganController::class, 'store']);
Route::get('/pelanggan/show/{id}', [PelangganController::class, 'show']);
Route::put('/pelanggan/{id}', [PelangganController::class, 'update']);
Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);

//barang
// Route::apiResource('barang', BarangController::class);
Route::get('/barang/count', [BarangController::class, 'getCountBarang']);
Route::get('/barang', [BarangController::class, 'index']);
Route::post('/barang/store', [BarangController::class, 'store']);
Route::get('/barang/{id}', [BarangController::class, 'show']);
Route::put('/barang/{id}', [BarangController::class, 'update']);
Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

//penjualan
Route::get('/penjualan/count', [PenjualanController::class, 'getCountPenjualan']);
Route::get('/pendapatan/count', [PenjualanController::class, 'getTotalPendapatan']);
Route::get('/penjualan', [PenjualanController::class, 'index']);
Route::post('/penjualan/store', [PenjualanController::class, 'store']);
Route::get('/penjualan/{id}', [PenjualanController::class, 'show']);
Route::put('/penjualan/update/{id}', [PenjualanController::class, 'update']);
Route::delete('/penjualan/delete/{id}', [PenjualanController::class, 'destroy']);
