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
 * The base class for tree building.
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
abstract class NodeBuilder
{
    private $parent;
    private $baseSelector;
    private $keySelector;

    /**
     * @var TransformationProvider
     */
    private $transformationProvider;

    private $cacheBaseElement = false;
    private $cachedBaseElement;

    /**
     * @param null|callable $baseSelector
     * @param TransformationProvider $provider
     */
    public function __construct($baseSelector = null, TransformationProvider $provider = null)
    {
        if (!isset($provider))
            $provider = new TransformationProvider();

        $this->provider = $provider;

        if (!isset($baseSelector))
            $baseSelector = function($value) { return $value; };

        $this->baseSelector = $this->resolveSelector($baseSelector);
        $this->keySelector = $this->resolveSelector(function($value) { return $value; });
    }

    /**
     * @param NodeBuilder   $parent
     * @return NodeBuilder  The current instance
     */
    public function setParent(NodeBuilder $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Returns the parent node
     *
     * @return null|NodeBuilder The parent node
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the selector for the key for this node.
     * @param mixed $keySelector      A valid NodeBuilder selector
     *
     * @return NodeBuilder      The current instance
     * @throws \InvalidArgumentException
     */
    public function key($keySelector)
    {
        if (!$this->isValidSelector($keySelector) && $keySelector !== null)
            $keySelector = func_get_args();

        $this->keySelector = $this->resolveSelector($keySelector);

        return $this;
    }

    /**
     * For fluent tree building
     *
     * @return NodeBuilder|null The parent node
     */
    public function end()
    {
        return $this->getParent();
    }

    /**
     * @param mixed $element
     * @return mixed
     */
    abstract public function buildValue($element);

    /**
     * @param mixed $element
     * @return mixed
     */
    public function buildKey($element)
    {
        $keySelector = $this->keySelector;

        return $keySelector($this->baseElement($element));
    }

    /**
     * Call this function for performance reson. In this way the baseElement
     * selector is called only once.
     *
     * @param mixed $element    The origin element
     * @return array            An array of two elements, the builded key and the builded value
     */
    public function buildKeyAndValue($element)
    {
        $this->cacheBaseElement = true;

        $key = $this->buildKey($element);
        $value = $this->buildValue($element);

        $this->cacheBaseElement = false;
        $this->cachedBaseElement = null;

        return array($key, $value);

    }

    /**
     * @return TransformationProvider
     */
    public function getTransformationProvider()
    {
        return $this->provider;
    }

    /**
     * Each node builder is an invokable object
     *
     * @param $value
     * @return mixed
     */
    public function __invoke($value)
    {
        return $this->buildValue($value);
    }

    /**
     * @param $element
     * @return mixed
     */
    protected function baseElement($element)
    {
        if (!$this->cacheBaseElement || !isset($this->cachedBaseElement)) {
            $baseSelector = $this->baseSelector;
            $this->cachedBaseElement = $baseSelector($element);
        }

        return $this->cachedBaseElement;
    }

    /**
     * @param mixed $selector   A closure or an invokable object
     * @throws \InvalidArgumentException
     * @return bool
     */
    protected function validateSelector($selector)
    {
        if (!$this->isValidSelector($selector)) {
            throw new \InvalidArgumentException('You have provided an invalid selector. A selector must be
                a closure or an invokable object'
            );
        };
    }

    /**
     * @param $selector
     * @return bool
     */
    protected function isValidSelector($selector)
    {
        return is_object($selector) && method_exists($selector, '__invoke');
    }

    /**
     * @param mixed $selector
     * @return mixed|object
     */
    protected function resolveSelector($selector)
    {
        $resolved = $selector;

        if (!is_object($selector) || !method_exists($selector, '__invoke')) {
            try {
                $resolved = call_user_func_array(array($this->provider, 'get'), $selector);
            } catch (\Exception $e) {

            }
        }
        $this->validateSelector($resolved);

        return $resolved;
    }
}