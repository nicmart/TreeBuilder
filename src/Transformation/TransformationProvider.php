<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2012 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Transformation;

/**
 * Class Description
 *
 * @package    TreeBuilder
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class TransformationProvider
{
    /**
     * @var Callable[]
     */
    private $transformations = array();

    /**
     * Register a transformation into the provider.
     * The transformation is supposed to accept as the first value the value to transform,
     * and other optional values as other arguments.
     *
     * @param $name
     * @param $transformation
     * @return TransformationProvider
     */
    public function registerTransformation($name, $transformation)
    {
        $this->validateTransformation($transformation);

        $this->transformations[$name] = $transformation;

        return $this;
    }

    /**
     * Returns a registered transformation.
     * If there are other arguments in addition to the transformation name, a curried version of
     * the transformation is returned.
     *
     * @param $transformationName
     *
     * @throws \InvalidArgumentException
     *
     * @return callable
     */
    public function getTransformation($transformationName)
    {
        $args = func_get_args();
        array_shift($args);

        if (!isset($this->transformations[$transformationName]))
            throw new \InvalidArgumentException("There is no transformation registered with name $name");

        $transformation = $this->transformations[$transformationName];

        if (count($args) === 0)
            return $transformation;

        return function($value) use ($transformation, $args)
        {
            array_unshift($args, $value);

            return call_user_func_array($transformation, $args);
        };
    }

    /**
     *
     * @param $transformationName
     * @param $valueToTransform
     * @return mixed
     */
    public function transform($transformationName, $valueToTransform)
    {
        $transformationArgs = array_slice(func_get_args(), 2);
        array_unshift($transformationArgs, $transformationName);

        $transformation = call_user_func_array(array($this, 'getTransformation'), $transformationArgs);

        return $transformation($valueToTransform);
    }

    private function validateTransformation($transformation)
    {
        if (!is_callable($transformation)) {
            throw new \InvalidArgumentException('The transformation provided is not a valid callable');
        }
    }
}