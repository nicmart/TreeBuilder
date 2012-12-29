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

        $this->value(null);
    }

    /**
     * Specify the selector used to retrieve node value
     *
     * @param mixed $valueSelector
     *
     * @return \TreeBuilder\LeafBuilder The current instance
     */
    public function value($valueSelector)
    {
        $this->valueSelector = $this->resolveSelector(func_get_args());

        return $this;
    }

    /**
     * Build the value
     *
     * @param mixed $element
     * @return mixed
     */
    public function buildValue($element)
    {
        return call_user_func($this->valueSelector, $this->baseElement($element));
    }
}