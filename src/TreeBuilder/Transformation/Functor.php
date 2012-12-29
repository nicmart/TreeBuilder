<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2012 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TreeBuilder\Transformation;

/**
 * This class is a collection of common operations of functions, like compositions and curring
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class Functor
{
    public static function compose()
    {
        $functions = func_get_args();

        if (count($functions) === 1) {
            static::validate($functions[0]);
            return $functions[0];
        }

        $firstFunction = array_shift($functions);

        static::validate($firstFunction);

        return function() use($firstFunction, $functions) {

            $partial = call_user_func_array(array('\TreeBuilder\Transformation\Functor', 'compose'), $functions);

            return call_user_func($firstFunction, call_user_func_array($partial, func_get_args()));
        };
    }

    private static function validate($function)
    {
        if (!is_callable($function))
            throw new \InvalidArgumentException('All functions must be callable objects');
    }
}