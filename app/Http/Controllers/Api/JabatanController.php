<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JabatanResource;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
  /**
   * index
   *
   * @return void
   */
  public function index()
  {
    // Get all jabatans
    $jabatans = Jabatan::with([
      'uraianTugas',
      'unsurs',
      'pendidikanFormal',
      'pendidikanPelatihan',
      'pengalaman',
      'bahanKerja',
      'perangkatKerja',
      'tanggungJawab',
      'wewenang',
      'korelasiJabatan',
      'risikoBahaya',
      // 'jabatanUnsur',
      'bakatKerja',
      'temperamenKerja',
      'minatKerja',
      'fungsiPekerjaan',
      'upayaFisik',
    ])->get();

    // Return a collection of jabatans as a resource
    return response()->json([
      'success' => true,
      'message' => 'List Data Jabatan',
      'data' => JabatanResource::collection($jabatans)
    ]);
  }
}
