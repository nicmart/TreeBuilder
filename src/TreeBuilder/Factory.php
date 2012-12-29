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

use TreeBuilder\Transformation\Common;
use TreeBuilder\Transformation\TransformationProvider;

/**
 * Build a ready-to-use TreeBuilder object loaded with common transformations
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class Factory
{
    /**
     * Returns a tree node builder loaded with common transformations.
     *
     * @return TreeBuilder
     */
    public static function builder()
    {
        $provider = new TransformationProvider;
        $provider->importStatics('\TreeBuilder\Transformation\Common');

        return new TreeBuilder(null, $provider);
    }
}