<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PendidikanFormalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'jenjang' => $this->jenjang,
            'jurusan' => $this->jurusan,
        ];
    }
}
