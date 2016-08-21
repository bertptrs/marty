<?php

namespace marty\plugins;

use mako\syringe\Container;
use Smarty;
use SplFileInfo;

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
        $this->path = $path;
        $this->name = $name;
    }

    /**
     * Register the plugin with the Smarty instance.
     */
    abstract public function register(Smarty $smarty);

    protected function loadPlugin()
    {
        require_once $this->path;
    }
}
