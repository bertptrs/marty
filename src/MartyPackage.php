<?php

namespace marty;

use mako\application\Package;
use Smarty;
use Symfony\CS\Config\Config;

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
            ->registerRenderer('.tpl', 'marty\SmartyRenderer');
    }

    private function registerSmartyClass()
    {
        $this->container->register('Smarty',
            function (Container $container) {
            return $container->call(function (Config $config) {
                    $smarty = new Smarty();
                    $smarty->setTemplateDir($config->get("marty::smarty.templateDir"));

                    $smarty->setCompileDir($config->get("marty::smarty.compileDir"));

                    $smarty->setCaching(Smarty::CACHING_OFF);
                    $smarty->setCompileCheck(true);
                    $smarty->addPluginsDir($config->get("marty::smarty.pluginDirs"));

                    return $smarty;
                });
        });
    }
}
