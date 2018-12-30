<?php

namespace marty\plugins;

use marty\ParameterResolver;
use marty\UnresolvableParameterException;
use ReflectionFunction;
use Smarty;

/**
 * Plugin base class to inherit other plugins from.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
abstract class BasePlugin
{
    /**
     * @var ParameterResolver
     */
    protected $resolver;

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

    public function __construct(ParameterResolver $resolver, $path, $name)
    {
        $this->resolver = $resolver;
        $this->path = $path;
        $this->name = $name;
    }

    /**
     * Register the plugin with the Smarty instance.
     *
     * @param $smarty Smarty the instance to register to
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
     * @throws \ReflectionException
     * @throws UnresolvableParameterException
     */
    protected function callWithParameters($functionName, array $params)
    {
        $function = new ReflectionFunction($functionName);

        $functionParameters = [];

        foreach ($function->getParameters() as $parameter) {
            $functionParameters[] = &$this->resolver->resolveParameter($parameter, $params);
        }

        return $function->invokeArgs($functionParameters);
    }
}
