<?php

require_once __DIR__.'/../Controller.php';
require_once __DIR__.'/../DatabaseTestCase.php';

class SideloadModeResolverTest extends DatabaseTestCase {

    public function testSideloadModeResolverOnVanillaCollections()
    {
        $controller = new Controller;

        $modes = [
            'children' => 'sideload'
        ];
        $parsed = $controller->getCollection(2, $modes, 2);
        $collection = $parsed['collection'];
        $children = $parsed['children'];

        $first = $collection->get(0);
        $this->assertEquals([
            1, 2, 1, 2
        ], [
            $first['children'][0],
            $first['children'][1],
            $children[0]['id'],
            $children[1]['id']
        ]);

        $second = $collection->get(1);
        $this->assertEquals([
            3, 4, 3, 4
        ], [
            $second['children'][0],
            $second['children'][1],
            $children[2]['id'],
            $children[3]['id']
        ]);
    }

    public function testSideloadModeResolverOnNestedChildrenOnVanillaCollections()
    {
        $controller = new Controller;

        $modes = [
            'children.nestedChildren' => 'sideload'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, 2);
        $collection = $parsed['collection'];
        $nestedChildren = $parsed['children.nestedChildren'];

        $first = $collection->get(0);
        $this->assertEquals([
            1, 2, 3, 4, 1, 2, 3, 4
        ], [
            $first['children']->get(0)['nestedChildren']->get(0),
            $first['children']->get(0)['nestedChildren']->get(1),
            $first['children']->get(1)['nestedChildren']->get(0),
            $first['children']->get(1)['nestedChildren']->get(1),
            $nestedChildren->get(0)['id'],
            $nestedChildren->get(1)['id'],
            $nestedChildren->get(2)['id'],
            $nestedChildren->get(3)['id']
        ]);

        $second = $collection->get(1);
        $this->assertEquals([
            5, 6, 7, 8, 5, 6, 7, 8
        ], [
            $second['children']->get(0)['nestedChildren']->get(0),
            $second['children']->get(0)['nestedChildren']->get(1),
            $second['children']->get(1)['nestedChildren']->get(0),
            $second['children']->get(1)['nestedChildren']->get(1),
            $nestedChildren->get(4)['id'],
            $nestedChildren->get(5)['id'],
            $nestedChildren->get(6)['id'],
            $nestedChildren->get(7)['id']
        ]);
    }

    public function testSideloadModeResolverOnParentAndNestedChildrenOnVanillaCollections()
    {
        $controller = new Controller;

        $modes = [
            'children' => 'sideload',
            'children.nestedChildren' => 'sideload'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, 2);
        $collection = $parsed['collection'];
        $children = $parsed['children'];
        $nestedChildren = $parsed['children.nestedChildren'];

        $first = $collection->get(0);
        $this->assertEquals([
            1, 2, 1, 2, 1, 2, 3, 4, 1, 2, 3, 4
        ], [
            $first['children']->get(0),
            $first['children']->get(1),
            $children->get(0)['id'],
            $children->get(1)['id'],
            $children->get(0)['nestedChildren']->get(0),
            $children->get(0)['nestedChildren']->get(1),
            $children->get(1)['nestedChildren']->get(0),
            $children->get(1)['nestedChildren']->get(1),
            $nestedChildren->get(0)['id'],
            $nestedChildren->get(1)['id'],
            $nestedChildren->get(2)['id'],
            $nestedChildren->get(3)['id'],
        ]);

        $second = $collection->get(1);
        $this->assertEquals([
            3, 4, 3, 4, 5, 6, 7, 8, 5, 6, 7, 8
        ], [
            $second['children']->get(0),
            $second['children']->get(1),
            $children->get(2)['id'],
            $children->get(3)['id'],
            $children->get(2)['nestedChildren']->get(0),
            $children->get(2)['nestedChildren']->get(1),
            $children->get(3)['nestedChildren']->get(0),
            $children->get(3)['nestedChildren']->get(1),
            $nestedChildren->get(4)['id'],
            $nestedChildren->get(5)['id'],
            $nestedChildren->get(6)['id'],
            $nestedChildren->get(7)['id'],
        ]);
    }

    public function testSideloadModeResolverOnArrayCollections()
    {
        $controller = new Controller;

        $modes = [
            'children' => 'sideload'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, false, true);
        $collection = $parsed['collection'];
        $children = $parsed['children'];

        $first = $collection[0];
        $this->assertEquals([
            1, 2, 1, 2
        ], [
            $first['children'][0],
            $first['children'][1],
            $children[0]['id'],
            $children[1]['id']
        ]);

        $second = $collection[1];
        $this->assertEquals([
            3, 4, 3, 4
        ], [
            $second['children'][0],
            $second['children'][1],
            $children[2]['id'],
            $children[3]['id']
        ]);
    }

    public function testSideloadModeResolverOnNestedChildrenOnArrayCollections()
    {
        $controller = new Controller;

        $modes = [
            'children.nestedChildren' => 'sideload'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, 2, true);
        $collection = $parsed['collection'];
        $nestedChildren = $parsed['children.nestedChildren'];

        $first = $collection[0];
        $this->assertEquals([
            1, 2, 3, 4, 1, 2, 3, 4
        ], [
            $first['children'][0]['nestedChildren'][0],
            $first['children'][0]['nestedChildren'][1],
            $first['children'][1]['nestedChildren'][0],
            $first['children'][1]['nestedChildren'][1],
            $nestedChildren[0]['id'],
            $nestedChildren[1]['id'],
            $nestedChildren[2]['id'],
            $nestedChildren[3]['id']
        ]);

        $second = $collection[1];
        $this->assertEquals([
            5, 6, 7, 8, 5, 6, 7, 8
        ], [
            $second['children'][0]['nestedChildren'][0],
            $second['children'][0]['nestedChildren'][1],
            $second['children'][1]['nestedChildren'][0],
            $second['children'][1]['nestedChildren'][1],
            $nestedChildren[4]['id'],
            $nestedChildren[5]['id'],
            $nestedChildren[6]['id'],
            $nestedChildren[7]['id']
        ]);
    }

    public function testSideloadModeResolverOnParentAndNestedChildrenOnArrayCollections()
    {
        $controller = new Controller;

        $modes = [
            'children' => 'sideload',
            'children.nestedChildren' => 'sideload'
        ];
        $parsed = $controller->getCollection(2, $modes, 2, 2, true);
        $collection = $parsed['collection'];
        $children = $parsed['children'];
        $nestedChildren = $parsed['children.nestedChildren'];

        $first = $collection[0];
        $this->assertEquals([
            1, 2, 1, 2, 1, 2, 3, 4, 1, 2, 3, 4
        ], [
            $first['children'][0],
            $first['children'][1],
            $children[0]['id'],
            $children[1]['id'],
            $children[0]['nestedChildren'][0],
            $children[0]['nestedChildren'][1],
            $children[1]['nestedChildren'][0],
            $children[1]['nestedChildren'][1],
            $nestedChildren[0]['id'],
            $nestedChildren[1]['id'],
            $nestedChildren[2]['id'],
            $nestedChildren[3]['id'],
        ]);

        $second = $collection[1];
        $this->assertEquals([
            3, 4, 3, 4, 5, 6, 7, 8, 5, 6, 7, 8
        ], [
            $second['children'][0],
            $second['children'][1],
            $children[2]['id'],
            $children[3]['id'],
            $children[2]['nestedChildren'][0],
            $children[2]['nestedChildren'][1],
            $children[3]['nestedChildren'][0],
            $children[3]['nestedChildren'][1],
            $nestedChildren[4]['id'],
            $nestedChildren[5]['id'],
            $nestedChildren[6]['id'],
            $nestedChildren[7]['id'],
        ]);
    }

    public function testSideloadModeResolverOnEloquentCollections()
    {
        $controller = new Controller;

        $modes = [
            'children' => 'sideload'
        ];
        $parsed = $controller->getEloquentCollection([
            'children'
        ], $modes);
        $collection = $parsed['collection'];
        $children = $parsed['children'];

        $first = $collection->get(0);
        $this->assertEquals([
            1, 2, 1, 2
        ], [
            $first->children->get(0),
            $first->children->get(1),
            $children->get(0)->id,
            $children->get(1)->id
        ]);

        $second = $collection->get(1);
        $this->assertEquals([
            3, 4, 3, 4
        ], [
            $second->children->get(0),
            $second->children->get(1),
            $children->get(2)->id,
            $children->get(3)->id
        ]);
    }

    public function testSideloadModeResolverOnNestedChildrenOnEloquentCollections()
    {
        $controller = new Controller;

        $modes = [
            'children.nestedChildren' => 'sideload'
        ];
        $parsed = $controller->getEloquentCollection([
            'children',
            'children.nestedChildren'
        ], $modes);
        $collection = $parsed['collection'];
        $nestedChildren = $parsed['children.nestedChildren'];

        $first = $collection->get(0);
        $this->assertEquals([
            1, 2, 0, 1, 2
        ], [
            $first->children->get(0)->nestedChildren->get(0),
            $first->children->get(0)->nestedChildren->get(1),
            $first->children->get(1)->nestedChildren->count(),
            $nestedChildren->get(0)->id,
            $nestedChildren->get(1)->id
        ]);

        $second = $collection->get(1);
        $this->assertEquals([
            3, 4, 4
        ], [
            $second->children->get(0)->nestedChildren->get(0),
            $second->children->get(1)->nestedChildren->get(0),
            $nestedChildren->get(3)->id
        ]);
    }

    public function testSideloadModeResolverOnParentAndNestedChildrenOnEloquentCollections()
    {
        $controller = new Controller;

        $modes = [
            'children' => 'sideload',
            'children.nestedChildren' => 'sideload'
        ];
        $parsed = $controller->getEloquentCollection([
            'children',
            'children.nestedChildren'
        ], $modes);
        $collection = $parsed['collection'];
        $children = $parsed['children'];
        $nestedChildren = $parsed['children.nestedChildren'];

        $first = $collection->get(0);
        $this->assertEquals([
            1, 2, 1, 2, 1, 2, 0, 1, 2
        ], [
            $first->children->get(0),
            $first->children->get(1),
            $children->get(0)->id,
            $children->get(1)->id,
            $children->get(0)->nestedChildren->get(0),
            $children->get(0)->nestedChildren->get(1),
            $children->get(1)->nestedChildren->count(),
            $nestedChildren->get(0)->id,
            $nestedChildren->get(1)->id
        ]);

        $second = $collection->get(1);
        $this->assertEquals([
            3, 4, 3, 4, 3, 4, 3, 4
        ], [
            $second->children->get(0),
            $second->children->get(1),
            $children->get(2)->id,
            $children->get(3)->id,
            $children->get(2)->nestedChildren->get(0),
            $children->get(3)->nestedChildren->get(0),
            $nestedChildren->get(2)->id,
            $nestedChildren->get(3)->id,
        ]);
    }

    public function testSideloadModeResolverOnSharedChildrenOnEloquentCollections()
    {
        $controller = new Controller;

        $modes = [
            'sharedChildren' => 'sideload'
        ];
        $parsed = $controller->getEloquentCollection([
            'sharedChildren'
        ], $modes);
        $collection = $parsed['collection'];
        $sharedChildren = $parsed['sharedChildren'];

        $first = $collection->get(0);
        $this->assertEquals([
            1, 2
        ], [
            $first->sharedChildren->get(0),
            $first->sharedChildren->get(1)
        ]);

        $second = $collection->get(1);
        $this->assertEquals([
            2
        ], [
            $second->sharedChildren->get(0),
        ]);

        $this->assertEquals(2, $sharedChildren->count());
    }
}
