<?php namespace Modules\Auth\Entities;



use Modules\Shared\Libraries\Entity;

class  PermissionGroupEntity extends Entity
{

    public $Id;
    public $groupId;
    public $permissionId;
    public $description;


    public function __construct($attributes)
    {
        $this->fill($this, $attributes);



    }
    protected $datamap = [
        'groupId'=>'group_id' ,
        'permissionId'=>'permission_id' ,
    ];




}
