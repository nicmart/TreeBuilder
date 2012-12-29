<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2012 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TreeBuilder\Test\Transformation;

use TreeBuilder\Transformation\TransformationProvider;

/**
 * Unit tests for class TransformationProviderTest
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class TransformationProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TransformationProvider
     */
    private $provider;

    public function setUp()
    {
        $this->provider = new TransformationProvider;
    }
    
    public function testRegisterTransformation()
    {
        $transformation = function($value) { return $value . $value; };

        $this->provider->register('transf', $transformation);

        $this->assertEquals($transformation, $this->provider->get('transf'));
    }

    public function testHasTranformation()
    {
        $this->provider->register('transf', function(){});

        $this->assertTrue($this->provider->has('transf'));
        $this->assertFalse($this->provider->has('unregistered-transf'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetThrowAnExceptionIfTransformationIsNotRegistered()
    {
        $this->provider->get('xxx');
    }

    public function testGetCurriesArgumentsAfterTheFirst()
    {
        $add = function($a, $b, $c) { return $a + $b + $c; };

        $this->provider->register('add', $add);

        $curried = $this->provider->get('add', 2, 3);

        $this->assertEquals(6, $curried(1));
        $this->assertEquals(7, $curried(2));
        $this->assertEquals(8, $curried(3));
    }

    public function testImportStatics()
    {
        $this->provider->importStatics('\\TreeBuilder\\Test\Transformation\\MethodsCollection');

        $this->assertEquals('static_method_1', call_user_func($this->provider->get('static_method_1'), ''));
        $this->assertEquals('static_method_2', call_user_func($this->provider->get('static_method_2'), ''));

        $this->assertFalse($this->provider->has('method1'));
    }

    public function testImportStaticsWithSpecifiedListOfMethods()
    {
        $this->provider->importStatics('\\TreeBuilder\\Test\Transformation\\MethodsCollection', array('static_method_1'));

        $this->assertEquals('static_method_1', call_user_func($this->provider->get('static_method_1'), ''));

        $this->assertFalse($this->provider->has('static_method_2'));
    }

    public function testImportMethods()
    {
        $collection = new MethodsCollection();

        $this->provider->importMethods($collection);

        $this->assertEquals('method1', call_user_func($this->provider->get('method1'), ''));
        $this->assertEquals('static_method_2', call_user_func($this->provider->get('static_method_2'), ''));

        $this->assertFalse($this->provider->has('protected_method1'));
    }

    public function testImportMethodsWithPrefix()
    {
        $collection = new MethodsCollection();

        $this->provider->importMethods($collection, array(), 'prefix_');

        $this->assertTrue($this->provider->has('prefix_static_method_1'));
        $this->assertTrue($this->provider->has('prefix_method1'));
    }
}

class MethodsCollection
{
    protected function protected_method1() { return 'protected_method1'; }

    public static function static_method_1() { return 'static_method_1'; }

    public static function static_method_2() { return 'static_method_2'; }

    public function method1() { return 'method1'; }

    public function method2() { return 'method2'; }
}