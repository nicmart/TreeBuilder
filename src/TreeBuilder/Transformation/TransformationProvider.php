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
     * @param string $name              The name of the transformation
     * @param callable $transformation  The transformation
     * @return TransformationProvider   The current instance
     */
    public function register($name, $transformation)
    {
        $this->validateTransformation($transformation);

        $this->transformations[$name] = $transformation;

        return $this;
    }

    /**
     * Returns true if there is a transformation registered with the given name
     *
     * @param string $name      The name of the transformation
     * @return bool             True if the trasnformation is defined, false otherwise
     */
    public function has($name)
    {
        return isset($this->transformations[$name]);
    }

    /**
     * Returns a registered transformation.
     * If there are other arguments in addition to the transformation name, a curried version of
     * the transformation is returned.
     *
     * @param string $transformationName
     *
     * @throws \InvalidArgumentException
     *
     * @return callable
     */
    public function get($transformationName)
    {
        $args = func_get_args();
        array_shift($args);

        if (!$this->has($transformationName))
            throw new \InvalidArgumentException("There is no transformation registered with name $transformationName");

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
     * Performs a transformation using a registered transformation.
     *
     * @param string $transformationName    The name of the transformation
     * @param mixed $valueToTransform       The value to transform
     *
     * @return mixed                        The transformed value
     */
    public function transform($transformationName, $valueToTransform)
    {
        $transformationArgs = array_slice(func_get_args(), 2);
        array_unshift($transformationArgs, $transformationName);

        $transformation = call_user_func_array(array($this, 'get'), $transformationArgs);

        return $transformation($valueToTransform);
    }

    private function validateTransformation($transformation)
    {
        if (!is_callable($transformation)) {
            throw new \InvalidArgumentException('The transformation provided is not a valid callable');
        }
    }
}