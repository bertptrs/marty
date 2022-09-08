<?php

namespace marty;

use mako\syringe\Container;
use ReflectionParameter;

/**
 * Parameter resolver for Smarty functions.
 *
 * This class implements the interaction with the Mako dependency container.
 */
class ParameterResolver
{
    /** @var Container */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Resolve a parameter for use.
     *
     * @param ReflectionParameter $parameter
     * @param array $provided
     * @return mixed the resolved parameter, by reference. This is necessary since block the block "repeat" parameter
     *               is always by reference.
     * @throws UnresolvableParameterException when no resolution can be made
     */
    public function &resolveParameter(ReflectionParameter $parameter, array $provided)
    {
        $name = $parameter->getName();
        if (array_key_exists($name, $provided)) {
            return $provided[$name];
        } else {
            $parameter = $this->resolveDIParameter($parameter);
            return $parameter;
        }
    }

    private function resolveDIParameter(ReflectionParameter $parameter)
    {
        try {
            $type = $parameter->getType();
            if ($type == null) {
                throw new UnresolvableParameterException($parameter);
            }

            return $this->container->get($type->getName());
        } catch (\Throwable $ex) {
            throw new UnresolvableParameterException($parameter, $ex);
        }
    }
}
