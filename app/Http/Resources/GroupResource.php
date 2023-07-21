<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->withoutWrapping();

        return [
            'group_id' => $this->id,
            'group_name' => $this->group_name,
            'owner' => $this->owner->name,
            'owner_email' => $this->owner->email,
            'owner_id' => $this->owner->id,
            'members' => MemberResource::collection($this->members)
        ];
    }
}
