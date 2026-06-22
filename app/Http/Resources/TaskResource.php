<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'user' => new UserResource($this->whenLoaded('user')),
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'last_update' => $this->updated_at
        ];
    }
}
