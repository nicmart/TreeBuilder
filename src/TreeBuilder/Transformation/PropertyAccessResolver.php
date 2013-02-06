<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2013 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TreeBuilder\Transformation;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class PropertyAccessResolver
 */
class PropertyAccessResolver implements ResolverInterface
{
    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    /**
     * @param PropertyAccessorInterface $accessor
     */
    public function __construct(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(array $args = array())
    {
        $accessor = $this->accessor;
        $path = $args[0];

        return function($item) use ($accessor, $path) {
            return $accessor->getValue($item, $path);
        };
    }


}