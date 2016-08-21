<?php

namespace marty\plugins;

use Smarty;

/**
 * Plugin for compiler functions.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class CompilerPlugin extends BasePlugin
{

    public function register(Smarty $smarty)
    {
        $smarty->registerPlugin('compiler', $this->name, [$this, 'call']);
    }

    public function call(array $params, Smarty $smarty)
    {
        $this->loadPlugin();

        $parameters = [
            'params' => $params,
            'smarty' => $smarty,
        ];

        return $this->container->call("smarty_compiler_" . $this->name, $parameters);
    }
}
