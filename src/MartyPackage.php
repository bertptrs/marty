<?php

namespace marty;

use mako\application\Package;
use marty\SmartyRenderer;

class MartyPackage extends Package
{
    protected $packageName = "marty";

    protected function bootstrap()
    {
        $config = $this->container->get("config");

        $this->container->get("view")->registerRenderer(".tpl",
            function () use ($config) {
                return new SmartyRenderer($config);
            });
    }
}
