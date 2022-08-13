<?php namespace Modules\Auth\Entities;


use Modules\Shared\Libraries\Entity;

class  PermissionUserEntity extends Entity
{

    public $userId;
    public $permissionId;
    public $description;


    public function __construct($attributes)
    {
        $this->fill($this, $attributes);



    }
    protected $datamap = [
        'userId'=>'user_id' ,
        'permissionId'=>'permission_id' ,
    ];





}
