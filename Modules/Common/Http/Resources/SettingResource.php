<?php
namespace Modules\Common\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource  extends JsonResource
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
        'createdAt' => $this->created_at,
        'updatedAt' => $this->updated_at,
        'deletedAt' => $this->deleted_at,
    ];
////return parent::toArray($request);
    }
}
