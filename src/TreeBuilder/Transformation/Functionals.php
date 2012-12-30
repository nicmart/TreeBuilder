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
 * This class is a collection of common operations of functions, like compositions, partials and curring
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class Functionals
{
    /**
     * Convert a function to another one that accepts only one array argument.
     * In this way, if $g = Functionals::args_to_array($f),
     * then $g(array($a,$b)) = $f($a, $b) for each $a, $b.
     *
     * @param callable $function
     * @return callable
     */
    public static function args_to_array($function)
    {
        return function(array $argumentsArray = array()) use ($function) {
            return call_user_func_array($function, $argumentsArray);
        };
    }

    /**
     * Returns the composition of a list of functions.
     *
     * @param callable $function The leftmost function of the composition chain
     * @param callable $function,... An unlimited list of callables to compose
     *
     * @return callable
     */
    public static function compose(/* $function1, $function2, ... */)
    {
        $functions = func_get_args();

        if (count($functions) === 1) {
            static::validate($functions[0]);
            return $functions[0];
        }

        $firstFunction = array_shift($functions);

        static::validate($firstFunction);

        return function() use($firstFunction, $functions) {

            $partial = call_user_func_array(array('\TreeBuilder\Transformation\Functionals', 'compose'), $functions);

            return call_user_func($firstFunction, call_user_func_array($partial, func_get_args()));
        };
    }

    private static function validate($function)
    {
        if (!is_callable($function))
            throw new \InvalidArgumentException('All functions must be callable objects');
    }
}