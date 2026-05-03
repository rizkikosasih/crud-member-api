<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->token,
            'type' => 'bearer',
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'is_active' => $this->user->is_active,
                'created_at' => $this->user->created_at
                    ->timezone('Asia/Jakarta')
                    ->locale('id')
                    ->translatedFormat('l, d F Y H:i:s'),
            ],
            'roles' => $this->user->getRoleNames(),
            'permissions' => $this->user->getAllPermissions()->pluck('name'),
        ];
    }
}
