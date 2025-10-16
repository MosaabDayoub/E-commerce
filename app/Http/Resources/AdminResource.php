<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id??null,
            'name' => $this->name??null,
            'email' => $this->email??null,
            'avatar' => $this->getFirstMediaUrl('avatar'),
            'email_verified' => !is_null($this->email_verified_at),
            'email_verified_at' => $this->email_verified_at?->toDateTimeString()??null,
            'created_at' => $this->created_at->toDateTimeString()??null,
            'updated_at' => $this->updated_at->toDateTimeString()??null,
            'permissions' => $this->when(
                $this->relationLoaded('roles'),
                $this->getPermissionNames()
            ),
            'roles' => $this->when(
                $this->relationLoaded('roles'),
                $this->getRoleNames()
            )
        ];
    }       
}