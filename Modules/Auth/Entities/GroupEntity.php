<?php namespace Modules\Auth\Entities;



use Modules\Shared\Libraries\Entity;

class  GroupEntity extends Entity
{

    public $id;
    public $name;
    public $description;

    public function __construct($attributes)
    {
        $this->fill($this, $attributes);



    }


    protected $datamap = [
    ];





}
