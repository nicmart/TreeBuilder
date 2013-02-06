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

/**
 * Interface ResolverInterface
 *
 * Objects that implements this interface converts, through the method "resolve", an array
 * to a valid callback or invokable object
 */
interface ResolverInterface
{
    /**
     * Resolve an array of arguments to a callable
     *
     * @param array $args
     *
     * @return callable
     */
    public function resolve(array $args = array());
}
