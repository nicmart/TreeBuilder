<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2013 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Treebuilder\Test\Transformation;

use TreeBuilder\Transformation\PropertyAccessResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Unit tests for class PropertyAccessResolverTest
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class PropertyAccessResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PropertyAccessorInterface
     */
    protected $accessor;

    /**
     * @var PropertyAccessResolver
     */
    protected $resolver;

    public function setUp()
    {
        $this->accessor = $this->getMockBuilder('Symfony\Component\PropertyAccess\PropertyAccessorInterface')
            ->getMock()
        ;

        $this->accessor->expects($this->any())
            ->method('getValue')
            ->will($this->returnCallback(function($item, $path){
               static $i = 0;
               return array($item, $path, $i++);
            }))
        ;

        $this->resolver = new PropertyAccessResolver($this->accessor);
    }
    
    public function testResolve()
    {
        $callback = $this->resolver->resolve(array('a.b.c'));

        $this->assertEquals(array('value0', 'a.b.c', 0), $callback('value0'));
        $this->assertEquals(array('value1', 'a.b.c', 1), $callback('value1'));
        $this->assertEquals(array('value0', 'a.b.c', 2), $callback('value0'));
    }
}