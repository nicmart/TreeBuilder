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

/**
 * Unit tests for class TransformationProviderTest
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
use TreeBuilder\Transformation\Common;

class CommonTest extends \PHPUnit_Framework_TestCase
{
    public function testElement()
    {
        $common = new Common();

        $array = array('key1' => 'value1', 'key2' => 123);

        $this->assertEquals('value1', $common->element($array, 'key1'));
        $this->assertEquals(123, $common->element($array, 'key2'));
    }

    public function testIdentity()
    {
        $common = new Common();

        $this->assertEquals(12345, $common->identity(12345));
    }

    public function testProperty()
    {
        $common = new Common;

        $this->assertEquals('ciao', $common->property(new Mock, 'prop'));
    }

    public function testMethod()
    {
        $common = new Common;

        $this->assertEquals('bleahxxxyyy', $common->method(new Mock, 'getBlaBla', 'xxx', 'yyy'));
    }

    public function testCast()
    {
        $common = new Common;

        $value = 'a';

        $this->assertInternalType('bool', $common->cast($value, 'bool'));
        $this->assertInternalType('int', $common->cast($value, 'int'));
        $this->assertInternalType('string', $common->cast(123, 'string'));
        $this->assertInternalType('array', $common->cast($value, 'array'));
        $this->assertInternalType('float', $common->cast($value, 'float'));
    }
}

class Mock
{
    public $prop = 'ciao';

    public function getBlaBla($var1, $var2) { return 'bleah' . $var1 . $var2; }
}