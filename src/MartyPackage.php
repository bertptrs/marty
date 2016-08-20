<?php

namespace marty;

use mako\application\Package;
use mako\config\Config;
use mako\syringe\Container;
use Smarty;

class MartyPackage extends Package
{
    protected $packageName = "marty";

    protected function bootstrap()
    {
        $this->registerSmartyClass();
        $this->registerViewRenderer();
    }

    private function registerViewRenderer()
    {
        $this->container->get('view')
            ->registerRenderer('.tpl', function () {
                return $this->container->getFresh('marty\SmartyRenderer');
            });
    }

    private function registerSmartyClass()
    {
        $this->container->register('Smarty',
            function (Container $container) {
            return $container->call(function (Config $config, PluginLoader $loader) {
                    $smarty = new Smarty();
                    $smarty->setTemplateDir($config->get("marty::smarty.templateDir"));

                    $smarty->setCompileDir($config->get("marty::smarty.compileDir"));

                    $smarty->setCaching(Smarty::CACHING_OFF);

                    $loader->loadPlugins($config->get('marty::smarty.pluginDirs'), $smarty);

                    return $smarty;
                });
        });
    }
}
