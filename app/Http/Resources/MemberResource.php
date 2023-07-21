<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'member_id' => $this->id,
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'vouchers' => VoucherResource::collection($this->user->vouchers)
        ];
    }
}
