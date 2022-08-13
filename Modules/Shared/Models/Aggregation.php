<?php

namespace Modules\Shared\Models;


use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Shared\Interfaces\AggregationInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class Aggregation extends Model implements AggregationInterface
{


    public function aggregate(array $pipeLine)
    {

        if (empty($pipeLine)) {
            throw  new Exception(' aggregate pile line can not be empty');
        }


        $builder = DB::table($this->table);
        if (isset($pipeLine['select'])) {

            $select = str_replace("\n", '', $pipeLine['select']);
            $select = explode(',', $select);
            for ($i = 0; $i <= count($select) - 1; $i++) {
                $select[$i] = trim($select[$i], " ");
            }
            $builder->select($select);
        }


        if (isset($pipeLine['join'])) {
            foreach ($pipeLine['join'] as $item) {
                if ($item['mode'] == 'left') {
                    $builder->leftJoin($item['table'], $item['arg1'], $item['condition'], $item['arg2']);
                } else if ($item['mode'] == 'right') {
                    $builder->rightJoin($item['table'], $item['arg1'], $item['condition'], $item['arg2']);
                } else {
                    $builder->join($item['table'], $item['arg1'], $item['condition'], $item['arg2']);
                }

            }

        }

        if (isset($pipeLine['whereIn'])) {
            $builder->whereIn($pipeLine['whereIn']['key'], $pipeLine['whereIn']['value']);
        }
        if (isset($pipeLine['whereNotIn'])) {
            $builder->whereNotIn($pipeLine['whereNotIn']['key'], $pipeLine['whereNotIn']['value']);
        }
        if (isset($pipeLine['orWhereIn'])) {
            $builder->orWhereIn($pipeLine['orWhereIn']['key'], $pipeLine['orWhereIn']['value']);
        }
        if (isset($pipeLine['orWhereNotIn'])) {
            $builder->orWhereNotIn($pipeLine['orWhereNotIn']['key'], $pipeLine['orWhereNotIn']['value']);
        }


        if (isset($pipeLine['where'])) {
            $builder->where($pipeLine['where']);
        }
        if (isset($pipeLine['orWhere'])) {
            $builder->orWhere($pipeLine['orWhere']);
        }

        if (isset($pipeLine['like'])) {
            $pipeLine['like'] = $this->likePerpetration($pipeLine['like']);
            $builder->where($pipeLine['like']);
        }
        if (isset($pipeLine['orLike'])) {
            $pipeLine['orLike'] = $this->likePerpetration($pipeLine['orLike']);
            $builder->orWhere($pipeLine['orLike']);
        }




        if (isset($pipeLine['groupBy'])) {
            $builder->groupBy($pipeLine['groupBy']);
        }
        if (isset($pipeLine['having'])) {
            $builder->having($pipeLine['having']);
        }
        if (isset($pipeLine['orHaving'])) {
            $builder->orHaving($pipeLine['orHaving']);
        }
        if (isset($pipeLine['havingRaw'])) {
            $builder->havingRaw($pipeLine['orHaving']);
        }
        if (isset($pipeLine['orHavingRaw'])) {
            $builder->orHavingRaw($pipeLine['orHaving']);
        }


        if (isset($pipeLine['sort']) && isset($pipeLine['order'])) {
            $builder->orderBy($pipeLine['sort'], $pipeLine['order']);
        }

        if (isset($pipeLine['limit'])) {
            $builder->limit($pipeLine['limit']);
        }
        if (isset($pipeLine['offset'])) {
            $builder->offset($pipeLine['offset']);
        }

        return $builder->get();

    }

    public function aggregatePagination(array $pipeLine)
    {


        if (empty($pipeLine)) {
            throw  new Exception(' aggregate pile line can not be empty');
        }


        $builder = DB::table($this->table);
        if (isset($pipeLine['select'])) {
            $select = str_replace("\n", '', $pipeLine['select']);
            $select = explode(',', $select);
            for ($i = 0; $i <= count($select) - 1; $i++) {
                $select[$i] = trim($select[$i], " ");
            }
            $builder->select($select);
        }


        if (isset($pipeLine['join'])) {
            foreach ($pipeLine['join'] as $item) {
                if ($item['mode'] == 'left') {
                    $builder->leftJoin($item['table'], $item['arg1'], $item['condition'], $item['arg2']);
                } else if ($item['mode'] == 'right') {
                    $builder->rightJoin($item['table'], $item['arg1'], $item['condition'], $item['arg2']);
                } else {
                    $builder->join($item['table'], $item['arg1'], $item['condition'], $item['arg2']);
                }

            }

        }

        if (isset($pipeLine['whereIn'])) {
            $builder->whereIn($pipeLine['whereIn']['key'], $pipeLine['whereIn']['value']);
        }
        if (isset($pipeLine['whereNotIn'])) {
            $builder->whereNotIn($pipeLine['whereNotIn']['key'], $pipeLine['whereNotIn']['value']);
        }
        if (isset($pipeLine['orWhereIn'])) {
            $builder->orWhereIn($pipeLine['orWhereIn']['key'], $pipeLine['orWhereIn']['value']);
        }
        if (isset($pipeLine['orWhereNotIn'])) {
            $builder->orWhereNotIn($pipeLine['orWhereNotIn']['key'], $pipeLine['orWhereNotIn']['value']);
        }


        if (isset($pipeLine['where'])) {

            $builder->where($pipeLine['where']);
        }
        if (isset($pipeLine['orWhere'])) {

            $builder->orWhere($pipeLine['orWhere']);
        }

        if (isset($pipeLine['like'])) {
            $pipeLine['like'] = $this->likePerpetration($pipeLine['like']);
            $builder->where($pipeLine['like']);
        }
        if (isset($pipeLine['orLike'])) {
            $pipeLine['orLike'] = $this->likePerpetration($pipeLine['orLike']);
            $builder->orWhere($pipeLine['orLike']);
        }


        if (isset($pipeLine['groupBy'])) {
            $builder->groupBy($pipeLine['groupBy']);
        }
        if (isset($pipeLine['having'])) {
            $builder->having($pipeLine['having']);
        }
        if (isset($pipeLine['orHaving'])) {
            $builder->orHaving($pipeLine['orHaving']);
        }
        if (isset($pipeLine['havingRaw'])) {
            $builder->havingRaw($pipeLine['orHaving']);
        }
        if (isset($pipeLine['orHavingRaw'])) {
            $builder->orHavingRaw($pipeLine['orHaving']);
        }


        if (isset($pipeLine['sort']) && isset($pipeLine['order'])) {
            $builder->orderBy($pipeLine['sort'], $pipeLine['order']);
        }

        if (isset($pipeLine['limit'])) {
            $builder->limit($pipeLine['limit']);
        }
        if (isset($pipeLine['offset'])) {
            $builder->offset($pipeLine['offset']);
        }


        $pager = new  LengthAwarePaginator($builder->get(), $this->count(), $pipeLine['limit'], $pipeLine['page'],
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);

        $pager = $pager->toArray();

        return [
            'data' => $pager['data'],
            'pager' => paginationFields($pager, $pipeLine['page'])];


    }

    private function likePerpetration(mixed $like)
    {
        for ($i = 0; $i < count($like); $i++) {

            $like[$i][1]='like';
            $like[$i][2]='%' .$like[$i][2].'%';
        }

        return $like;
    }


}
