<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2013 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TreeBuilder;

/**
 * This class acts like TreeBuilder, but instead of selecting baseElement() and applies the children transformations,
 * it iterates through baseElement() and then it applies, for each iteration, the children transformations.
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class ForeachTreeBuilder extends TreeBuilder
{
    /**
     * Iterates through elements of NodeBuilder::baseElement() and for each iteration builds
     * a set of nodes corresponding to children
     *
     * @param mixed $element
     * @return array|mixed
     * @throws \UnexpectedValueException
     */
    public function buildValue($element)
    {
        $result = array();
        $baseElement = $this->baseElement($element);
        $this->keyCounter = 0;

        if (!is_array($baseElement) && !$baseElement instanceof \Traversable)
            throw new \UnexpectedValueException('You have specified a value that the base selector has not transformed into a traversable object');

        foreach ($baseElement as $subBaseElement) {
            foreach ($this->getChildren() as $child) {
                list($key, $value) = $child->buildKeyAndValue($subBaseElement);
                $result[$key] = $value;
            }
        }

        return $result;
    }

}