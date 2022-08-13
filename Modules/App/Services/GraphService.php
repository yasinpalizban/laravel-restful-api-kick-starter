<?php

namespace Modules\App\Services;

use Modules\App\Entities\GraphEntity;

use Modules\Shared\Libraries\MainService;


class GraphService extends MainService
{

    private $pieChart = [
["name" => "Mobiles", "value" => 105000],
["name" => "Laptop", "value" => 55000],
["name" => "AC", "value" => 15000],
["name" => "Headset", "value" => 150000],
["name" => "Fridge", "value" => 20000]
];
    private  $pieGrid = [
["name" => "Mobiles", "value" => 8000],
["name" => "Laptop", "value" => 5600],
["name" => "AC", "value" => 5500],
["name" => "Headset", "value" => 15000],
["name" => "Fridge", "value" => 2100]
];


    public function index()
    {


        return [
            'pieGrid' => $this->pieGrid,
            'pieChart' => $this->pieChart,

        ];


    }

    public function create(GraphEntity $graphEntity)
    {

         return [
        'pieGrid' => $this->pieGrid,
        'pieChart' => $this->pieChart,

    ];
    }

}
