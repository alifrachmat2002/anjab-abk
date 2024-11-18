<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemperamenKerjaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'temperamen_kerja_id' => $this->temperamen_kerja_id,
        ];
    }
}
