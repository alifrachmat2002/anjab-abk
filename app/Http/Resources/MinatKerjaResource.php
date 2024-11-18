<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MinatKerjaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'minat_kerja_id' => $this->minat_kerja_id,
        ];
    }
}
