<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UraianTugasResource;
use App\Models\AbkJabatan;
use App\Models\DetailAbk;
use App\Models\UraianTugas;
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
        
        $detailAbk = DetailAbk::where('abk_jabatan_id', $abkJabatan->id)->get();
        
        $uraians = UraianTugas::whereIn('id', $detailAbk->pluck('uraian_tugas_diajukan_id'))->get();
        
        return UraianTugasResource::collection($uraians);
    }
}
