<?php

use Illuminate\Support\Collection;
use Optimus\Architect\Architect;

require_once __DIR__.'/Model/Resource.php';

class Controller
{
    private $idCounts = [];

    private $architect;

    public function __construct()
    {
        $this->architect = new Architect;
    }

    public function getEloquentCollection(array $eagerLoad = array(), array $modes = [])
    {
        $collection = Resource::with($eagerLoad)->get();

        return $this->architect->parseData($collection, $modes, 'collection');
    }

    public function getCollection($rows, array $modes = [], $children = false, $childrensChildren = false, $array = false, $stringKeys = false)
    {
        $collection = $this->createCollection($rows, $children, $childrensChildren, $array, $stringKeys);

        return $this->architect->parseData($collection, $modes, 'collection');
    }

    private function createCollection($rows = 2, $children = false, $childrensChildren = false, $array = false, $stringKeys = false, $level = 1)
    {
        if (!array_key_exists($level, $this->idCounts)) {
            $this->idCounts[$level] = 0;
        }

        $data = [];
        for ($i=1;$i<=$rows;$i++) {
            $this->idCounts[$level]++;

            $newId = (!$stringKeys ? $this->idCounts[$level] : 'aaa'.dechex($this->idCounts[$level]));
            $tmp = [
                'id' => $newId,
                'title' => 'Resource ' . $i,
                'singleChildren' => [
                    'id' => $newId,
                    'name' => 'Single child'
                ]
            ];

            if (is_int($children)) {
                $key = $level === 1 ? 'children' : 'nestedChildren';
                $tmp[$key] = $this->createCollection($children, $childrensChildren, false, $array, $stringKeys, ($level+1));
            }

            $data[] = $tmp;
        }

        return $array === true ? $data : Collection::make($data);
    }
}
