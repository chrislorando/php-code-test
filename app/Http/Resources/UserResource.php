<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'role' => $this->when(isset($this->role), $this->role),
            'created_at' => $this->created_at->toISOString(),
            'orders_count' => $this->whenCounted('orders'),
            'can_edit' => $this->when($request->routeIs('users.index'), function() use ($request) {
                return $request->user() ? $request->user()->can('update', $this->resource) : false;
            }),
        ];

    }
}
