<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2012 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TreeBuilder\Test\LeafBuilderTest;

use TreeBuilder\LeafBuilder;

/**
 * Unit tests for class LeafBuilder
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class LeafBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LeafBuilder
     */
    protected $leafBuilder;

    public function setUp()
    {
        $this->leafBuilder = new LeafBuilder(function($element) { return $element['key']; });
    }
    
    public function testValueAndBuildValue()
    {
        $valueSelector = function ($selectedElement) {
            return $selectedElement['subkey'];
        };

        $element = array('key' => array('subkey' => 'value'));

        $this->leafBuilder->value($valueSelector);

        $this->assertEquals('value', $this->leafBuilder->buildValue($element));
    }
}