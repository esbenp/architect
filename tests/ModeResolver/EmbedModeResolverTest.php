<?php

require_once __DIR__.'/../Controller.php';
require_once __DIR__.'/../DatabaseTestCase.php';

class EmbedModeResolverTest extends DatabaseTestCase
{
    public function testEmbedModeResolverOnVanillaCollections()
    {
        $controller = new Controller;

        $modes = ['children' => 'embed'];
        $parsed = $controller->getCollection(2, $modes, 2)['collection'];

        $first = $parsed->get(0)['children'];
        $this->assertEquals([
            1, 2
        ], [
            $first->get(0)['id'],
            $first->get(1)['id']
        ]);

        $second = $parsed->get(1)['children'];
        $this->assertEquals([
            3, 4
        ], [
            $second->get(0)['id'],
            $second->get(1)['id']
        ]);
    }

    public function testEmbedModeResolverOnNestedChildrenOnVanillaCollections()
    {
        $controller = new Controller;

        $modes = [
            'children.nestedChildren' => 'embed'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, 2)['collection'];

        $first = $parsed->get(0)['children'][0]['nestedChildren'];
        $this->assertEquals([
            1, 2
        ], [
            $first->get(0)['id'],
            $first->get(1)['id']
        ]);

        $second = $parsed->get(1)['children'][1]['nestedChildren'];
        $this->assertEquals([
            7, 8
        ], [
            $second->get(0)['id'],
            $second->get(1)['id']
        ]);
    }

    public function testEmbedModeResolverOnArrayCollections()
    {
        $controller = new Controller;

        $modes = [
            'children' => 'embed'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, false, true)['collection'];

        $first = $parsed[0]['children'];
        $this->assertEquals([
            1, 2
        ], [
            $first[0]['id'],
            $first[1]['id']
        ]);

        $second = $parsed[1]['children'];
        $this->assertEquals([
            3, 4
        ], [
            $second[0]['id'],
            $second[1]['id']
        ]);
    }

    public function testEmbedModeResolverOnNestedChildrenOnArrayCollections()
    {
        $controller = new Controller;

        $modes = [
            'children.nestedChildren' => 'embed'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, 2, true)['collection'];

        $first = $parsed[0]['children'][0]['nestedChildren'];
        $this->assertEquals([
            1, 2
        ], [
            $first[0]['id'],
            $first[1]['id']
        ]);

        $second = $parsed[1]['children'][1]['nestedChildren'];
        $this->assertEquals([
            7, 8
        ], [
            $second[0]['id'],
            $second[1]['id']
        ]);
    }

    public function testEmbedModeResolverOnEloquentCollections()
    {
        $controller = new Controller;

        $modes = [
            'children' => 'embed'
        ];
        $parsed = $controller->getEloquentCollection([
            'children'
        ], $modes)['collection'];

        $first = $parsed->get(0)->children;
        $this->assertEquals([
            1, 2
        ], [
            $first->get(0)->id,
            $first->get(1)->id
        ]);

        $second = $parsed->get(1)->children;
        $this->assertEquals([
            3, 4
        ], [
            $second->get(0)->id,
            $second->get(1)->id
        ]);
    }

    public function testEmbedModeResolverOnNestedChildrenOnEloquentCollections()
    {
        $controller = new Controller;

        $modes = [
            'children.nestedChildren' => 'embed'
        ];
        $parsed = $controller->getEloquentCollection([
            'children',
            'children.nestedChildren'
        ], $modes)['collection'];

        $first = $parsed->get(0)->children->get(0)->nestedChildren;
        $this->assertEquals([
            1, 2
        ], [
            $first->get(0)->id,
            $first->get(1)->id
        ]);

        $second = $parsed->get(1)->children->get(0)->nestedChildren;
        $this->assertEquals(3, $second->get(0)->id);
    }
}
