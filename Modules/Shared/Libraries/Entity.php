<?php

namespace Modules\Shared\Libraries;

use \Modules\Shared\Interfaces\EntityInterface;

class Entity implements EntityInterface
{

    protected $datamap = [];

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function getDataMap(): array
    {

        return $this->datamap;
    }

    public function fill($object, $attributes)
    {


        foreach ($attributes as $name => $value) {

            if (property_exists($object, $name)) {

                $object->$name = $value;
            }

        }

        foreach ($object as $name => $value) {

            if (is_null($value)) {

                unset($object->$name);
            }

        }
        return $object;
    }

    public function getArray(): array
    {
        $box = get_object_vars($this);
        unset($box['datamap']);
        $counter = 0;
        foreach ($this->datamap as $checkKey => $newKey) {
            foreach ($box as $oldKey => $value) {

                if ($oldKey == $checkKey) {
                    $box[$newKey] = $value;
                    unset($box[$oldKey]);
                }
                $counter++;
            }

        }


        return $box;
    }


}
