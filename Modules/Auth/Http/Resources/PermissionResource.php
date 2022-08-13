<?php
namespace Modules\Auth\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource  extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     *  @param  \Illuminate\Http\Request  $request
     *  @return array
     **/
    public function toArray($request)
    {
        return [
        'id' => $this->id,
        'key' => $this->key,
        'value' => $this->value,
        'description' => $this->description,

    ];

    }
}
