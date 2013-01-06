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
 * TreeBuilder class
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class TreeBuilder extends NodeBuilder
{
    /**
     * @var NodeBuilder[]
     */
    private $children = array();
    /**
     * @param mixed $element
     * @return mixed
     */
    public function buildValue($element)
    {
        $result = array();
        $baseElement = $this->baseElement($element);

        $this->addBuildedValueToResult($baseElement, $result);

        return $result;
    }

    /**
     * @param NodeBuilder $node     The child node
     * @return TreeBuilder          The current instance
     */
    public function addChild(NodeBuilder $node)
    {
        $this->children[] = $node;

        return $this;
    }

    /**
     * @return NodeBuilder[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Returns a leaf builder linked to this node
     *
     * @param mixed $baseSelector
     * @return \TreeBuilder\LeafBuilder
     */
    public function leaf($baseSelector = null)
    {
        $baseSelector = $this->resolveSelector(func_get_args());

        $leaf = new LeafBuilder($baseSelector, $this->getTransformationProvider());

        $this->addChild($leaf);
        $leaf->setParent($this);

        return $leaf;
    }

    /**
     * Returns a tree builder linked to this node
     * @param null $baseSelector
     * @return TreeBuilder
     */
    public function tree($baseSelector = null)
    {
        $baseSelector = $this->resolveSelector(func_get_args());

        $tree = new TreeBuilder($baseSelector, $this->getTransformationProvider());

        $this->addChild($tree);
        $tree->setParent($this);

        return $tree;
    }

    /**
     * Returns a foreach-tree-builder linked to this node
     * @param null $baseSelector
     * @return ForeachTreeBuilder
     */
    public function each($baseSelector = null)
    {
        $baseSelector = $this->resolveSelector(func_get_args());

        $tree = new ForeachTreeBuilder($baseSelector, $this->getTransformationProvider());

        $this->addChild($tree);
        $tree->setParent($this);

        return $tree;
    }

    /**
     * Given a baselement, iterates through children and build key-value pairs, and add
     * to the given $result array
     *
     * @param $baseElement
     * @param array $result
     *
     * @return $this
     */
    protected function addBuildedValueToResult($baseElement, array &$result)
    {
        foreach ($this->getChildren() as $child) {
            list($key, $value) = $child->buildKeyAndValue($baseElement);
            if ($key instanceof NoKey) {
                $result[] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $this;
    }
}