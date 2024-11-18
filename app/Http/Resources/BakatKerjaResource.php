<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BakatKerjaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'bakat_kerja_id' => $this->bakat_kerja_id,
        ];
    }
}
