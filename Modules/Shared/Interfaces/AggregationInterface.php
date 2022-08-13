<?php

namespace Modules\Shared\Interfaces;


interface AggregationInterface
{
    public function aggregate(array $pipeLine);


    public function aggregatePagination(array $pipeLine);
}
