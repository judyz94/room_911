<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeAccessLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'internal_id' => $this->internal_id,
            'access_granted' => $this->access_granted,
            'attempted_at' => $this->attempted_at->format('Y-m-d H:i:s'),
        ];
    }
}
