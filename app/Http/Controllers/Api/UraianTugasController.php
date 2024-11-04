<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailAbkResource;
use App\Http\Resources\UraianTugasResource;
use App\Models\AbkJabatan;
use App\Models\DetailAbk;
use App\Models\UraianTugas;
use App\Models\UraianTugasDiajukan;
use Illuminate\Http\Request;

class UraianTugasController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all uraian tugas
        $uraians = UraianTugas::latest()->get();

        //return a collection of uraian tugas as a resource
        return new UraianTugasResource(true, 'List Data Uraian Tugas', $uraians);
    }

    public function getUraianTugasByJabatanAndSupervisor(Request $request)
    {
       $jabatanId = $request->jabatan_id;
        $supervisorId = $request->supervisor_id;
        
        $abkJabatan = AbkJabatan::where('jabatan_id', $jabatanId)->where('jabatan_tutam_id', $supervisorId)->latest()->first();

        // Eager load uraianTugas
        $detailAbk = DetailAbk::with('uraianTugasDiajukan')
        ->where('abk_jabatan_id', $abkJabatan->id)
        ->get();

        return DetailAbkResource::collection($detailAbk);
    }

    public function getTotalUraianTugasTargetByJabatanAndSupervisor(Request $request)
    {
        $detailAbk = $this->getUraianTugasByJabatanAndSupervisor($request);

        $totalTarget = $detailAbk->sum(function ($item) {
            return $item->waktu_penyelesaian * $item->jumlah_hasil_kerja;
        });

        return response()->json([
            'total_target' => $totalTarget,
        ]);
    }
}