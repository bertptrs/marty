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
     * Reference to the file to include.
     *
     * @var SplFileInfo
     */
    protected $file;

    public function __construct(Container $container, SplFileInfo $file, $name)
    {
        $this->container = $container;
        $this->file = $file;
        $this->name = $name;
    }

    /**
     * Register the plugin with the Smarty instance.
     */
    abstract public function register(Smarty $smarty);
}
