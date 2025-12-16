<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'internal_id' => $this->internal_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => "{$this->first_name} {$this->last_name}",
            'has_access' => (bool) $this->has_access,
            'access_logs_count' => $this->access_logs_count,
            'department' => [
                'id' => $this->department->id,
                'name' => $this->department->name,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
