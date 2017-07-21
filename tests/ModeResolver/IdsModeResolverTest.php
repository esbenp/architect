<?php

require_once __DIR__.'/../Controller.php';
require_once __DIR__.'/../DatabaseTestCase.php';

class IdsModeResolverTest extends DatabaseTestCase
{
    public function testIdsModeResolverOnVanillaCollections()
    {
        $controller = new Controller;

        $modes = [
            'children' => 'ids',
            'singleChildren' => 'ids'
        ];
        $parsed = $controller->getCollection(2, $modes, 2)['collection'];

        $first = $parsed->get(0)['children']->toArray();
        $this->assertEquals([
            1, 2
        ], $first);
        $this->assertEquals(1, $parsed->get(0)['singleChildren']);

        $second = $parsed->get(1)['children']->toArray();
        $this->assertEquals([
            3, 4
        ], $second);
        $this->assertEquals(2, $parsed->get(1)['singleChildren']);
    }

    public function testIdsModeResolverOnNestedChildrenOnVanillaCollections()
    {
        $controller = new Controller;

        $modes = [
            'children.nestedChildren' => 'ids'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, 2)['collection'];

        $first = $parsed->get(0)['children'][0]['nestedChildren']->toArray();
        $this->assertEquals([
            1, 2
        ], $first);

        $second = $parsed->get(1)['children'][1]['nestedChildren']->toArray();
        $this->assertEquals([
            7, 8
        ], $second);
    }

    public function testIdsModeResolverOnArrayCollections()
    {
        $controller = new Controller;

        $modes = [
            'children' => 'ids',
            'singleChildren' => 'ids'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, false, true)['collection'];

        $first = $parsed[0]['children'];
        $this->assertEquals([
            1, 2
        ], $first);
        $this->assertEquals(1, $parsed[0]['singleChildren']);

        $second = $parsed[1]['children'];
        $this->assertEquals([
            3, 4
        ], $second);
        $this->assertEquals(2, $parsed[1]['singleChildren']);
    }

    public function testIdsModeResolverOnNestedChildrenOnArrayCollections()
    {
        $controller = new Controller;

        $modes = [
            'children.nestedChildren' => 'ids'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, 2, true)['collection'];

        $first = $parsed[0]['children'][0]['nestedChildren'];
        $this->assertEquals([
            1, 2
        ], $first);

        $second = $parsed[1]['children'][1]['nestedChildren'];
        $this->assertEquals([
            7, 8
        ], $second);
    }

    public function testStringIdsModeResolverOnNestedChildrenOnArrayCollections()
    {
        $controller = new Controller;

        $modes = [
            'children.nestedChildren' => 'ids'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, 2, true, true)['collection'];

        $first = $parsed[0]['children'][0]['nestedChildren'];
        $this->assertEquals([
            'aaa1', 'aaa2'
        ], $first);

        $second = $parsed[1]['children'][1]['nestedChildren'];
        $this->assertEquals([
            'aaa7', 'aaa8'
        ], $second);
    }

    public function testIdsModeResolverOnEloquentCollections()
    {
        $controller = new Controller;

        $modes = [
            'children' => 'ids',
            'singleChildren' => 'ids'
        ];
        $parsed = $controller->getEloquentCollection([
            'children',
            'singleChildren'
        ], $modes)['collection']->toArray();

        $first = $parsed[0]['children'];
        $this->assertEquals([
            1, 2
        ], $first);
        $this->assertEquals(1, $parsed[0]['singleChildren']);

        $second = $parsed[1]['children'];
        $this->assertEquals([
            3, 4
        ], $second);
    }

    public function testIdsModeResolverOnNestedChildrenOnEloquentCollections()
    {
        $controller = new Controller;

        $modes = [
            'children.nestedChildren' => 'ids'
        ];
        $parsed = $controller->getEloquentCollection([
            'children',
            'children.nestedChildren'
        ], $modes)['collection']->toArray();

        $first = $parsed[0]['children'][0]['nested_children'];
        $this->assertEquals([
            1, 2
        ], $first);

        $second = $parsed[1]['children'][0]['nested_children'];
        $this->assertEquals([
            3
        ], $second);
    }
}
