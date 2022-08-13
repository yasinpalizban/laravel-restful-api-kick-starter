<?php namespace Modules\Common\Entities;

use Modules\Shared\Libraries\Entity;

class  SettingEntity extends Entity
{


    public $id;
    public $key;
    public $value;
    public $description;
    public $status;
    public $createdAt;
    public $updatedAt;
    public $deletedAt;
    public function __construct($attributes)
    {
        $this->fill($this, $attributes);



    }
    protected $datamap = [
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        'deletedAt' => 'deleted_at'
    ];


    public function activate()
    {
        $this->status = 1;
        return $this;

    }

    public function deactivate()
    {
        $this->status = 0;

        return $this;
    }

    public function isActivated(): bool
    {
        return isset($this->status) && $this->status== true;
    }



    public function createdAt()
    {


        $this->created_at = date('Y-m-d H:i:s', time());

        return $this;
    }

    public function updatedAt()
    {

        $this->updated_at = date('Y-m-d H:i:s', time());

        return $this;
    }


}
