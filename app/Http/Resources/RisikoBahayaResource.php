<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RisikoBahayaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'bahaya_fisik' => $this->bahaya_fisik,
            'penyebab' => $this->penyebab,
        ];
    }
}
