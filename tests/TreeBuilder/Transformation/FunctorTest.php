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
 * Unit tests for class Functor
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
use TreeBuilder\Transformation\Functor;

class FunctorTest extends \PHPUnit_Framework_TestCase
{
    public function testCompose()
    {
        $add = function ($a, $b) { return $a + $b; };
        $opposite = function($n) { return -$n; };
        $double = function($n) { return 2 * $n; };

        $composition = Functor::compose($opposite, $double, $add);

        $this->assertEquals(-10, $composition(2, 3));
        $this->assertEquals(-30, $composition(5, 10));
        $this->assertEquals(8, $composition(-5, 1));
    }
}