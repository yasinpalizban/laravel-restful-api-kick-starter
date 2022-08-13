<?php
namespace Modules\Auth\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionUserResource  extends JsonResource
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
            'userId' => $this->user_id,
            'permissionId' => $this->permission_id,
            'actions' => $this->actions,
    ];

    }
}
