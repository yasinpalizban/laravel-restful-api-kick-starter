<?php

namespace Modules\Auth\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionUserCollection extends ResourceCollection
{

    private $pager;

    public function __construct($resource)
    {


        if(isset($resource['pager'])){
            $this->pager = $resource['pager'];
            parent::__construct($resource['data']);
        }else{
            parent::__construct($resource);
        }

    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function toArray($request)
    {

        if (isset($this->pager)) {
            return [
                'data' => $this->collection,
                'pager' => $this->pager


            ];
        } else {
            return
                 $this->collection;

        }

    }


}
