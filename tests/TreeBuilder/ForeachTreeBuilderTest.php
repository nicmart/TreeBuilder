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
use TreeBuilder\ForeachTreeBuilder;

/**
 * Unit tests for class ForeachTreeBuilder
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class ForeachTreeBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TreeBuilder
     */
    protected $treeBuilder;

    public function setUp()
    {
        $this->treeBuilder = new ForeachTreeBuilder(function($element) { return $element['baseKey']; });
    }

    /**
     * @param callable $selector
     * @param null|callable $valueSelector
     * @return NodeBuilder
     */
    private function mockedNode($selector, $valueSelector = null)
    {
        if (!isset($valueSelector))
            $valueSelector = $selector;

        $mock = $this->getMockBuilder('TreeBuilder\NodeBuilder')
            ->setConstructorArgs(array($selector))
            ->setMethods(array('buildValue'))
            ->getMockForAbstractClass()
        ;

        $mock->expects($this->any())
            ->method('buildValue')
            ->will($this->returnCallback($valueSelector))
        ;

        return $mock;
    }

    public function testBuildValue()
    {
        $identity = function($element) { return $element; };
        $name = function($element) { return $element['name']; };

        $child1 = $this->mockedNode($identity, $name);
        $child2 = $this->mockedNode($identity, $name);

        $child1->key(function($element) { return (int) $element['id']; });
        $child2->key(function($element) { return (int) $element['id'] + 10; });

        $this->treeBuilder
            ->addChild($child1)
            ->addChild($child2)
        ;

        $element = array(
            'baseKey' => array(
                array(
                    'id' => '1',
                    'name' => 'John'
                ),
                array(
                    'id' => '2',
                    'name' => 'Kate'
                ),
                array(
                    'id' => '3',
                    'name' => 'William'
                ),
            )
        );

        $expected = array(
            1 => 'John',
            2 => 'Kate',
            3 => 'William',
            11 => 'John',
            12 => 'Kate',
            13 => 'William',
        );

        $this->assertEquals($expected, $this->treeBuilder->buildValue($element));
    }
}