<?php namespace Modules\App\Entities;



use Modules\Shared\Libraries\Entity;

class  GraphEntity extends Entity
{



    protected $type;
    protected $fromDate;
    protected $toDate;

    protected $datamap = [
        'fromDate' => 'from_date',
        'toDate' => 'to_date',

    ];


}
