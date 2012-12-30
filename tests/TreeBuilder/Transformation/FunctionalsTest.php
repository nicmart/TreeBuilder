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
 * Unit tests for class Functionals
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
use TreeBuilder\Transformation\Functionals;

class FunctionalsTest extends \PHPUnit_Framework_TestCase
{
    public function testCompose()
    {
        $add = function ($a, $b) { return $a + $b; };
        $opposite = function($n) { return -$n; };
        $double = function($n) { return 2 * $n; };

        $composition = Functionals::compose($opposite, $double, $add);

        $this->assertEquals(-10, $composition(2, 3));
        $this->assertEquals(-30, $composition(5, 10));
        $this->assertEquals(8, $composition(-5, 1));
    }

    public function testComposeWithOneArgument()
    {
        $add = function ($a, $b) { return $a + $b; };

        $composition = Functionals::compose($add);

        $this->assertEquals(5, $composition(2, 3));
        $this->assertEquals(15, $composition(5, 10));
        $this->assertEquals(-4, $composition(-5, 1));
    }

    public function testArgsToArray()
    {
        $add = function ($a, $b) { return $a + $b; };

        $add2 = Functionals::args_to_array($add);

        $this->assertEquals($add(1,2), $add2(array(1, 2)));
        $this->assertEquals($add(3,24), $add2(array(3, 24)));
    }
}