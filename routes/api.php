<?php

use App\Http\Controllers\AbkController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware(Authenticate::using('sanctum'));

//jabatans
Route::apiResource('/jabatans', App\Http\Controllers\Api\JabatanController::class);
Route::apiResource('/jabatandiajukan', App\Http\Controllers\JabatanDiajukanAjuanController::class);
Route::get('/jabatanabk',[AbkController::class, 'getJabatanABK'])->name('api.jabatanabk');
Route::get('/jabatanabk/parent',[AbkController::class, 'getJabatanABKParent'])->name('api.jabatanabk.parent');

Route::get('/uraian-tugas-by-jabatan-and-supervisor', [App\Http\Controllers\Api\UraianTugasController::class, 'getUraianTugasByJabatanAndSupervisor']);

Route::get('/yearly-total-uraian-tugas-target-by-jabatan-and-supervisor', [App\Http\Controllers\Api\UraianTugasController::class, 'getTotalUraianTugasTargetByJabatanAndSupervisor']);