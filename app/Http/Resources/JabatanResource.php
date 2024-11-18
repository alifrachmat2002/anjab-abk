<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JabatanResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return array_merge(parent::toArray($request), [
      'uraian_tugas' => UraianTugasResource::collection($this->whenLoaded('uraianTugas')),
      'pendidikan_formal' => PendidikanFormalResource::collection($this->whenLoaded('pendidikanFormal')),
      'pendidikan_pelatihan' => PendidikanPelatihanResource::collection($this->whenLoaded('pendidikanPelatihan')),
      'pengalaman' => PengalamanResource::collection($this->whenLoaded('pengalaman')),
      'bahan_kerja' => BahanKerjaResource::collection($this->whenLoaded('bahanKerja')),
      'perangkat_kerja' => PerangkatKerjaResource::collection($this->whenLoaded('perangkatKerja')),
      'tanggung_jawab' => TanggungJawabResource::collection($this->whenLoaded('tanggungJawab')),
      'wewenang' => WewenangResource::collection($this->whenLoaded('wewenang')),
      'korelasi_jabatan' => KorelasiJabatanResource::collection($this->whenLoaded('korelasiJabatan')),
      'risiko_bahaya' => RisikoBahayaResource::collection($this->whenLoaded('risikoBahaya')),
      'bakat_kerja' => BakatKerjaResource::collection($this->whenLoaded('bakatKerja')),
      'temperamen_kerja' => TemperamenKerjaResource::collection($this->whenLoaded('temperamenKerja')),
      'minat_kerja' => MinatKerjaResource::collection($this->whenLoaded('minatKerja')),
      'fungsi_pekerjaan' => FungsiPekerjaanResource::collection($this->whenLoaded('fungsiPekerjaan')),
      'upaya_fisik' => UpayaFisikResource::collection($this->whenLoaded('upayaFisik')),
    ]);
  }
}
