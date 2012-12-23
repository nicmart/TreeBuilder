<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2012 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NodeBuilder\Test\NodeBuilderTest;

use NodeBuilder\NodeBuilder;

/**
 * Unit tests for class NodeBuilderTest
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class NodeBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var NodeBuilder */
    protected $builder;

    public function setUp()
    {
    }

    /**
     * @param null $selector
     * @return NodeBuilder
     */
    private function mockedNodeBuilder($selector = null)
    {
        $mock = $this->getMockBuilder('\\NodeBuilder\\NodeBuilder')
            ->setConstructorArgs(array($selector))
            ->setMethods(array('buildValue'))
            ->getMock()
        ;

        $mock
            ->expects($this->any())
            ->method('buildValue')
            ->will($this->returnValue('buildedValue'))
        ;

        return $mock;
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorAcceptsOnlyValidSelectors()
    {
        $this->mockedNodeBuilder('invalidSelector');
    }

    public function testSetAndGetParent()
    {
        $parent = $this->mockedNodeBuilder(function(){ return 'ca'; });

        $child = $this->mockedNodeBuilder();
        $child->setParent($parent);

        $this->assertEquals($parent, $child->getParent());
    }

    public function testKeyAndBuildKey()
    {
        $builder = $this->mockedNodeBuilder(function($element) { return $element['a']; });
        $builder->key(function($element) { return $element['key']; });

        $element = array('a' => array('key' => 'mykey'));

        $this->assertEquals('mykey', $builder->buildKey($element));
    }

    public function testEndIsAnAliasForParent()
    {
        $parent = $this
            ->mockedNodeBuilder()
            ->key(function(){ return 'parent'; })
        ;
        $builder = $this->mockedNodeBuilder()->setParent($parent);

        $this->assertEquals($parent, $builder->end());
    }

    public function testBuildKeyAndValue()
    {
        $builder = $this->mockedNodeBuilder()
            ->key(function() { return 'buildedKey'; })
        ;

        $this->assertEquals(array('buildedKey', 'buildedValue'), $builder->buildKeyAndValue('any'));
    }
}