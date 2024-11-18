<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KorelasiJabatanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'jabatan_relasi_id' => $this->jabatan_relasi_id,
            'dalam_hal' => $this->dalam_hal,
        ];
    }
}
