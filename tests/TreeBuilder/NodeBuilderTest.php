<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2012 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TreeBuilder\Test\NodeBuilderTest;

use TreeBuilder\NodeBuilder;
use TreeBuilder\Transformation\TransformationProvider;

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
     * @param null|callable $selector
     * @param mixed $provider
     * @return NodeBuilder
     */
    private function mockedNodeBuilder($selector = null, TransformationProvider $provider = null)
    {
        $mock = $this->getMockBuilder('\\TreeBuilder\\NodeBuilder')
            ->setConstructorArgs(array($selector, $provider))
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

    public function testBuildKeyAndValueCallsBaseSelectorOnlyOnce()
    {
        $i = 0;
        $selector = function ($element) use (&$i) {
            $i++;

            return $element;
        };

        $nodeBuilder = new MockedNodeBuilder($selector);
        $nodeBuilder->buildKeyAndValue('any');

        $this->assertEquals(1, $i);
    }

    public function testWithTransformationProvider()
    {
        $providerMock = $this->getMock('TreeBuilder\\Transformation\\TransformationProvider');

        $providerMock
            ->expects($this->any())
            ->method('get')
            ->with($this->equalTo('arrayValue'), $this->equalTo('key'))
            ->will($this->returnValue(function($value) { return $value['key']; }))
        ;
        $mock = $this->mockedNodeBuilder(null, $providerMock);

        $mock->key('arrayValue', 'key');

        //$this->assertEquals('value', $mock->buildKey(array('key' => 'value')));
    }
}

class MockedNodeBuilder extends NodeBuilder
{
    /**
     * @param mixed $element
     * @return mixed
     */
    public function buildValue($element)
    {
        return $this->baseElement($element);
    }
}