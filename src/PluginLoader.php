<?php

namespace marty;

use DirectoryIterator;
use InvalidArgumentException;
use mako\syringe\Container;
use marty\plugins\BasePlugin;
use marty\plugins\BlockPlugin;
use marty\plugins\CompilerPlugin;
use marty\plugins\FunctionPlugin;
use marty\plugins\ModifierPlugin;
use Smarty;
use Smarty_Internal_Template;
use SplFileInfo;

/**
 * Class that loads plugins and adds them to a Smarty instance.
 *
 * This class creates an anonymous function for every method, that will load
 * and call the actual plugin. The plugins should be using the naming scheme
 * as supplied in the documentation for arguments; otherwise autowiring will
 * fail.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class PluginLoader
{
    /**
     * Container reference to call plugins with.
     *
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function loadPlugins(array $pluginDirs, Smarty $smarty)
    {
        foreach ($pluginDirs as $pluginDir) {
            $this->loadPluginDir($pluginDir, $smarty);
        }
    }

    private function loadPluginDir($pluginDir, Smarty $smarty)
    {
        foreach (new DirectoryIterator($pluginDir) as $file) {
            if (!$this->isValidPluginFile($file)) {
                continue;
            }

            list($type, $name, $extension) = explode(".", $file->getBasename());

            $plugin = $this->getPlugin($file, $name, $type);

            $plugin->register($smarty);
        }
    }

    /**
     * Get the plugin object for a given plugin.
     *
     * @param SplFileInfo $file
     * @param string $name
     * @param string $type
     * @return BasePlugin
     */
    private function getPlugin(\SplFileInfo $file, $name, $type)
    {
        $container = $this->container;
        $path = $file->getPathname();
        switch ($type) {
            case 'modifier':
                return new ModifierPlugin($container, $path, $name);

            case 'function':
                return new FunctionPlugin($container, $path, $name);

            case 'block':
                return new BlockPlugin($container, $path, $name);

            case 'compiler':
                return new CompilerPlugin($container, $path, $name);
        }

        throw new InvalidArgumentException("Unable to load plugin of type $type.");
    }

    private function isValidPluginFile(SplFileInfo $file)
    {
        if ($file->isDir() || !$file->isReadable()) {
            return false;
        }

        $parts = explode(".", $file->getBasename());
        if (count($parts) != 3 || $parts[2] != 'php') {
            return false;
        }

        switch ($parts[0]) {
            case 'block':
            case 'compiler':
            case 'function':
            case 'modifier':
                return true;
        }

        return false;
    }
}
