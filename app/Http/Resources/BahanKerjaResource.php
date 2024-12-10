<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BahanKerjaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'nama' => $this->nama,
            'penggunaan' => $this->penggunaan,
        ];
    }
}