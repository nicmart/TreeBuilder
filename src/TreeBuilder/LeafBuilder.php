<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2012 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TreeBuilder;

use TreeBuilder\Transformation\TransformationProvider;

/**
 * LeafBuilder class
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class LeafBuilder extends NodeBuilder
{
    private $valueSelector;

    /**
     * @param mixed $baseSelector
     * @param Transformation\TransformationProvider $provider
     */
    public function __construct($baseSelector = null, TransformationProvider $provider = null)
    {
        parent::__construct($baseSelector, $provider);

        $this->valueSelector = function($element) { return $element; };
    }

    /**
     * Specify the selector used to retrieve node value
     *
     * @param mixed $valueSelector
     */
    public function value($valueSelector)
    {
        $this->valueSelector = $this->resolveSelector($valueSelector);
    }

    /**
     * Build the value
     *
     * @param mixed $element
     * @return mixed
     */
    public function buildValue($element)
    {
        $valueSelector = $this->valueSelector;

        return $valueSelector($this->baseElement($element));
    }
}