<?php namespace Modules\Auth\Entities;


use Modules\Shared\Libraries\Entity;

class  PermissionEntity extends Entity
{

    public $id;
    public $name;
    public $description;
    public $active;


    public function __construct($attributes)
    {
        $this->fill($this, $attributes);



    }

    protected $datamap = [
    ];



}
