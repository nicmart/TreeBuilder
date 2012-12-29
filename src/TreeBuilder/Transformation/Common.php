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
 * A collection of common transformations
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class Common
{
    /**
     * Returns the element of an array
     *
     * @param array $array  The value to transform
     * @param string $key   The name of the item key
     * @return mixed
     */
    public static function element($array, $key)
    {
        return $array[$key];
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function identity($value)
    {
        return $value;
    }

    /**
     * Returns a constant value
     *
     * @param $value
     * @param $constant
     * @return mixed
     */
    public static function value($value, $constant)
    {
        return $constant;
    }

    /**
     * @param $object
     * @param string $property
     * @return mixed
     */
    public static function property($object, $property)
    {
        return $object->$property;
    }

    /**
     * Call a method of the object and returns its results
     *
     * @param $object
     * @param string $methodName
     * @return mixed
     */
    public static function method($object, $methodName)
    {
        $args = func_get_args();
        $object = array_shift($args);
        $methodName = array_shift($args);

        return call_user_func_array(array($object, $methodName), $args);
    }
}