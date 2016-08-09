<?php

namespace marty;

use mako\application\Package;

class MartyPackage extends Package
{
    protected $packageName = "marty";

    protected function bootstrap()
    {
        $this->container->get("view")->registerRenderer(".tpl",
            function () {
            return $this->container->get('marty\SmartyRenderer');
        });
    }
}
