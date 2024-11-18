<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FungsiPekerjaanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'fungsi_pekerjaan_id' => $this->fungsi_pekerjaan_id,
        ];
    }
}
