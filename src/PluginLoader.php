<?php

namespace marty;

use DirectoryIterator;
use mako\syringe\Container;
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

            switch ($type) {
                case 'modifier':
                    $this->registerModifier($smarty, $file, $name);
                    break;

                case 'function':
                    $this->registerFunction($smarty, $file, $name);
                    break;

                case 'block':
                    $this->registerBlock($smarty, $file, $name);

                case 'compiler':
                    $this->registerCompiler($smarty, $file, $name);
            }
        }
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

    private function registerModifier(Smarty $smarty, SplFileInfo $file, $name)
    {
        $smarty->registerPlugin('modifier', $name,
            function () use ($name, $file) {
            require_once $file->getPathname();

            $arguments  = func_get_args();
            $parameters = ['value' => $arguments[0]];

            for ($i = 1; $i < count($arguments); $i++) {
                $parameters["param$i"] = $arguments[$i];
            }

            return $this->container->call("smarty_modifier_$name", $parameters);
        });
    }

    private function registerFunction(Smarty $smarty, SplFileInfo $file, $name)
    {
        $smarty->registerPlugin('function', $name,
            function (array $params, Smarty_Internal_Template $template) use ($name, $file) {
            require_once $file->getPathname();

            $parameters = [
                'params'   => $params,
                'template' => $template,
            ];

            return $this->container->call("smarty_function_$name", $parameters);
        });
    }

    private function registerBlock(Smarty $smarty, SplFileInfo $file, $name)
    {
        $smarty->registerPlugin('block', $name,
            function (array $params, $content, Smarty_Internal_Template $template, &$repeat) use ($name, $file) {
            require_once $file->getPathname();

            $parameters = [
                'params'   => $params,
                'template' => $template,
                'content'  => $content,
                'repeat'   => $repeat,
            ];

            return $this->container->call("smarty_block_$name", $parameters);
        });
    }

    private function registerCompiler(Smarty $smarty, SplFileInfo $file, $name)
    {
        $smarty->registerPlugin('compiler', $name,
            function (array $params, Smarty $smarty) use ($name, $file) {
            require_once $file->getPathname();

            $parameters = [
                'params' => $params,
                'smarty' => $smarty,
            ];

            return $this->container->call("smarty_compiler_$name", $parameters);
        });
    }
}
