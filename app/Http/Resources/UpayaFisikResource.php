<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpayaFisikResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'upaya_fisik_id' => $this->upaya_fisik_id,
        ];
    }
}
