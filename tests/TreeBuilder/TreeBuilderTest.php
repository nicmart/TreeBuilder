<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2012 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TreeBuilder\Test;

use TreeBuilder\NodeBuilder;
use TreeBuilder\TreeBuilder;

/**
 * Unit tests for class TreeBuilderTest
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class TreeBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TreeBuilder
     */
    protected $treeBuilder;

    public function setUp()
    {
        $this->treeBuilder = new TreeBuilder(function($element) { return $element['baseKey']; });
    }

    /**
     * @param $selector
     * @return NodeBuilder
     */
    private function mockedNode($selector)
    {
        $mock = $this->getMockBuilder('TreeBuilder\NodeBuilder')
            ->setConstructorArgs(array($selector))
            ->setMethods(array('buildValue'))
            ->getMockForAbstractClass()
        ;

        $mock->expects($this->any())
            ->method('buildValue')
            ->will($this->returnCallback($selector))
        ;

        return $mock;
    }
    
    public function testAddChildAndGetChildren()
    {
        $child1 = $this->mockedNode(function ($element) { return $element['key1'];});
        $child2 = $this->mockedNode(function ($element) { return $element['key2'];});

        $this->treeBuilder
            ->addChild($child1)
            ->addChild($child2)
        ;

        $this->assertEquals(array($child1, $child2), $this->treeBuilder->getChildren());
    }

    public function testBuildValue()
    {
        $child1 = $this->mockedNode(function ($element) { return $element['key1'];});
        $child2 = $this->mockedNode(function ($element) { return $element['key2'];});
        $child3 = $this->mockedNode(function ($element) { return $element['key1'];});

        $child1->key(function() { return 'index1'; });
        $child2->key(function() { return 'index2'; });
        $child3->key(function() { return 'index3'; });

        $this->treeBuilder
            ->addChild($child1)
            ->addChild($child2)
            ->addChild($child3)
        ;

        $element = array(
            'baseKey' => array(
                'key1' => 'value1',
                'key2' => 'value2',
            )
        );

        $expected = array(
            'index1' => 'value1',
            'index2' => 'value2',
            'index3' => 'value1'
        );

        $this->assertEquals($expected, $this->treeBuilder->buildValue($element));
    }

    public function testLeaf()
    {
        $leaf = $this->treeBuilder->leaf();

        $this->assertInstanceOf('TreeBuilder\\LeafBuilder', $leaf);
    }

    public function testLeafSetParentBuilder()
    {
        $leaf = $this->treeBuilder->leaf();

        $this->assertEquals($this->treeBuilder, $leaf->getParent());
    }

    public function testTree()
    {
        $leaf = $this->treeBuilder->tree();

        $this->assertInstanceOf('TreeBuilder\\TreeBuilder', $leaf);
    }

    public function testTreeSetParentBuilder()
    {
        $leaf = $this->treeBuilder->tree();

        $this->assertEquals($this->treeBuilder, $leaf->getParent());
    }

    public function testEach()
    {
        $leaf = $this->treeBuilder->each();

        $this->assertInstanceOf('TreeBuilder\\ForeachTreeBuilder', $leaf);
    }

    public function testEachSetParentBuilder()
    {
        $leaf = $this->treeBuilder->each();

        $this->assertEquals($this->treeBuilder, $leaf->getParent());
    }

    public function testCounterKeySelectorIsAutomaticallySetForChildren()
    {
        $child1 = $this->treeBuilder->leaf();
        $child2 = $this->treeBuilder->tree();
        $child3 = $this->treeBuilder->each();

        $keys = array($child1->buildKey(''), $child2->buildKey(''), $child3->buildKey(''));

        $this->assertInstanceOf('\TreeBuilder\NoKey', $keys[0]);
        $this->assertInstanceOf('\TreeBuilder\NoKey', $keys[1]);
        $this->assertInstanceOf('\TreeBuilder\NoKey', $keys[2]);
    }
}