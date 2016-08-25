<?php

namespace marty\plugins;

use mako\syringe\Container;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;
use RuntimeException;
use Smarty;

/**
 * Plugin base class to inherit other plugins from.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
abstract class BasePlugin
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Name of the plugin
     *
     * @var string
     */
    protected $name;

    /**
     * Path to the plugin file.
     *
     * @var string
     */
    private $path;

    public function __construct(Container $container, $path, $name)
    {
        $this->container = $container;
        $this->path      = $path;
        $this->name      = $name;
    }

    /**
     * Register the plugin with the Smarty instance.
     */
    abstract public function register(Smarty $smarty);

    protected function loadPlugin()
    {
        require_once $this->path;
    }

    /**
     * Resolve the parameters for the plugin, as Mako does not appear to handle
     * the resolution of reference and typed parameters very well.
     *
     * @param string $functionName
     * @param array $params
     * @return mixed Whatever the function returns.
     */
    protected function callWithParameters($functionName, array $params)
    {
        $function = new ReflectionFunction($functionName);

        $functionParameters = [];

        foreach ($function->getParameters() as $parameter) {
            $name = $this->resolveParameter($parameter, $params,
                $functionParameters);
        }

        return $function->invokeArgs($functionParameters);
    }

    private function resolveParameter(ReflectionParameter $parameter,
                                      array $provided,
                                      array &$functionParameters)
    {
        $name = $parameter->getName();
        if (array_key_exists($name, $provided)) {
            if ($parameter->isPassedByReference()) {
                $functionParameters[] = &$provided[$name];
            } else {
                $functionParameters[] = $provided[$name];
            }
        } else {
            $className = $parameter->getClass()->getName();
            try {
                $functionParameters[] = $this->container->get($className);
            } catch (ReflectionException $ex) {
                throw new RuntimeException("Unable to resolve parameter $name, typehint $className.",
                0, $ex);
            }
        }
    }
}
