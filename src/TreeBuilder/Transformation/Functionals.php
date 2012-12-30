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
     * The inverse of @see Functionals::array_to_args
     *
     * @param callable $function
     * @return callable
     */
    public static function array_to_args($function)
    {
        return function() use($function) {
            return call_user_func($function, func_get_args());
        };
    }

    /**
     * Get a partial version of a function, i.e. fix some arguments and returns a function
     * of the remaining arguments.
     *
     * @param callable $function
     * @param array $fixedArgs The array of fixed args. The keys represent the arguments positions
     *                         in the main function arguments list
     * @return callable
     */
    public static function partial($function, array $fixedArgs = array())
    {
        return function() use($function, $fixedArgs) {
            $partialArgs = func_get_args();
            $fullArgs = array();
            $totalArgs = count($partialArgs) + count($fixedArgs);

            for ($i = 0; $i < $totalArgs; $i++) {
                $fullArgs[] = isset($fixedArgs[$i]) ? $fixedArgs[$i] : array_shift($partialArgs);
            }

            return call_user_func_array($function, $fullArgs);
        };
    }

    /**
     * Combine an arbitrary long list of functions into a single function that returns
     * an array in which the n-th element is the result of the n-th function.
     *
     * @param callable $func,... An arbitrary long list of functions
     *
     * @return callable
     */
    public static function combine(/* $func1, $func2, ... */)
    {
        $functions = func_get_args();

        foreach ($functions as $function) {
            static::validate($function);
        }

        return function() use ($functions) {
            $result = array();
            $args = func_get_args();

            foreach ($functions as $function) {
                $result[] = call_user_func_array($function, $args);
            }

            return $result;
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