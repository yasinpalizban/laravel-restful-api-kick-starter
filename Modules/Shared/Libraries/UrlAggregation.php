<?php namespace Modules\Shared\Libraries;


use Illuminate\Http\Request;
use Modules\Shared\Interfaces\UrlAggregationInterface;


class  UrlAggregation implements UrlAggregationInterface
{

    private const  Point = ".";
    private int $limit;
    private int $offset;
    private string $range;
    private int $page;
    private string $filed;
    private array $q;
    private string $order;
    private string $sort;
    private int $foreignKey;
    private string $tableName;
    private array $pipeLine;


    public function __construct(Request $request)
    {

        $this->range = $request->get('range') ?? '1to10';
        $this->sort = $request->get('sort') ?? 'id';
        $this->order = $request->get('order') ?? 'desc';
        $this->page = $request->get('page') ?? 1;
        $this->limit = $request->get('limit') ?? '10';
        $this->offset = $request->get('offset') ?? '0';
        $this->filed = $request->get('filed') ?? '';
        $this->foreignKey = $request->get('foreignKey') ?? 0;
        $this->q = [];
        isset($_GET['q']) ? parse_str($request->get('q'), $this->q) : $this->q = [];
        $this->tableName = '';
        $this->pipeLine = [];


    }


    /**
     * @param array $dataMap
     */
    public function dataMap(array $dataMap): void
    {

        $object = [];
        $isEqual = false;
        foreach ($this->q as $key => $value) {

            foreach ($dataMap as $needle => $hook) {
                if ($needle == $key) {

                    $object[$hook] = $value;
                    $isEqual = true;
                }
            }
            if ($isEqual == true) {
                $isEqual = false;
            } else {
                $object[$key] = $value;
            }

        }
        $this->q = $object;
    }


    /**
     * @return array
     */
    public function decodeQueryParam(): UrlAggregation
    {
        $whiteList = ['whereIn',
            'whereNotIn',
            'orWhereIn',
            'orWhereNotIn',
            'orHavingIn',
            'havingNotIn'];

        $temp = [];
        $object = null;
        $counter = 0;
        foreach ($this->q as $key => $value) {
            $object = json_decode($value);
            if (in_array($object->fun, $whiteList)) {
                if (isset($object->jin)) {
                    $temp['key'] = $object->jin . self::Point . $key;
                } else {
                    $temp['key'] = $this->tableName . $key;

                }
                $temp['value'] = $object->val;
            } else if (isset($object->sgn) && !empty($object->sgn) && is_array($object->sgn)) {


                foreach ($object->sgn as $sgn) {

                    if (isset($object->jin)) {

                        $temp[$counter]=[$object->jin . self::Point . $key ,$object->sgn[$counter], $object->val[$counter]];
                    } else {

                        $temp[$counter]=[$this->tableName . $object->sgn[$counter], $object->val[$counter]];
                    }
                    $counter++;
                }
            } else if (isset($object->sgn) && !empty($object->sgn) && is_string($object->sgn)) {



                if (isset($object->jin)) {
                    $temp=[$object->jin . self::Point . $key,$object->sgn, $object->val];
                } else {
                    $temp=[$this->tableName . $key,$object->sgn,$object->val];
                }
                $temp=[$temp];
            } else {

                if (isset($object->jin)) {
                    $temp=[$object->jin . self::Point . $key, $object->val];
                } else {
                    $temp=[$this->tableName . $key, $object->val];
                }
                $temp=[$temp];
            }
            $counter = 0;


            $this->pipeLine[str_replace(' ', '', $object->fun)] = $temp;
            $temp = [];
        }


        return $this;
    }


    private
    function selectFields(string $query): string
    {
        if ($this->filed != '') {
            return $this->filed;
        } else {
            return $query;
        }

    }

    /**
     * @return int
     */
    public
    function getForeignKey(): int
    {
        return $this->foreignKey;
    }


    /**
     * @param string $append
     */
    public
    function setTableName(string $append): UrlAggregation
    {
        $this->tableName = $append . self::Point;
        $this->tableName = $str = str_replace(' ', '', $this->tableName);
        return $this;
    }

    public
    function encodeQueryParam(string $key, string $value, string $function, string $sign, string $joinWith = ''): string
    {


        $data = ['fun' => $function, 'val' => $value, 'sgn' => $sign];
        if (strlen($joinWith) > 0)
            $data['jin'] = $joinWith;

        return http_build_query(array($key => [json_encode($data)]));


    }

    public
    function getPipeLine(?array $defaultPipeLine = null): array
    {
        $this->pipeLine['offset'] = $this->offset;
        $this->pipeLine['limit'] = $this->limit;
        $this->pipeLine['page'] = $this->page;

        if (isset($defaultPipeLine['order'])) {
            $this->pipeLine['order'] = $defaultPipeLine['order'];

        } else {
            $this->pipeLine['order'] = $this->order;
        }
        if (isset($defaultPipeLine['sort'])) {

            $this->pipeLine['sort'] = strlen($this->tableName) ? $this->tableName . $defaultPipeLine['sort'] : $defaultPipeLine['sort'];

        } else {
            $this->pipeLine['sort'] = strlen($this->tableName) ? $this->tableName . $this->sort : $this->sort;

        }

        $this->pipeLine['select'] = (isset($defaultPipeLine['select'])) ? $this->selectFields($defaultPipeLine['select']) : $this->tableName . '*';
        if (isset($defaultPipeLine['join'])) {
            $this->pipeLine['join'] = $defaultPipeLine['join'];

        }
        if (!empty($defaultPipeLine)) {
            foreach ($defaultPipeLine as $key => $value) {
                if ($key != 'select' && $key != 'sort' && $key != 'order' && $key != 'join' && $key != 'where' && array_key_exists($key, $this->pipeLine)) {

                    $this->pipeLine[$key] = array_merge($this->pipeLine[$key], $value);
                }
            }
        }
        if (isset($defaultPipeLine['where']) && !isset($this->pipeLine['where'])) {
            $this->pipeLine['where'] = $defaultPipeLine['where'];

        } else if (isset($defaultPipeLine['where']) && isset($this->pipeLine['where'])) {
            $this->pipeLine['where'] = array_merge($this->pipeLine['where'], $defaultPipeLine['where']);
        }


        return $this->pipeLine;
    }

    public function clearPipeLine(): void
    {
        $this->pipeLine = [];
        $this->tableName = '';
    }
}
