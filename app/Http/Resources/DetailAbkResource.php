<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailAbkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_tugas' => $this->uraianTugasDiajukan ? $this->uraianTugasDiajukan->nama_tugas : null,
            'bobot' => $this->waktu_penyelesaian,
        ];
    }
}
