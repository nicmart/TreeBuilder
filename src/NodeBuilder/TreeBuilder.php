<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2012 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NodeBuilder;

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
    private $children;
    /**
     * @param mixed $element
     * @return mixed
     */
    public function buildValue($element)
    {
        $result = array();
        $baseElement = $this->baseElement($element);

        foreach ($this->getChildren() as $child) {
            list($key, $value) = $child->buildKeyAndValue($baseElement);
            $result[$key] = $value;
        }

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
     * @param null $baseSelector
     * @return LeafBuilder
     */
    public function leaf($baseSelector = null)
    {
        $leaf = new LeafBuilder($baseSelector);

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
        $tree = new TreeBuilder($baseSelector);

        $tree->setParent($this);

        return $tree;
    }
}