<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (Gate::allows('admin')) {
            return [
                'id' => $this->id,
                'username' => $this->username,
                'role' => $this->role,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ];
            
        }

        return [
            'id' => $this->id,
            'username' => $this->username,
        ];
    }
}
