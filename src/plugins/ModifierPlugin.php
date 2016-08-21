<?php

namespace marty\plugins;

use Smarty;

/**
 * Plugin for modifier functions
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class ModifierPlugin extends BasePlugin
{

    public function register(Smarty $smarty)
    {
        $smarty->registerPlugin('modifier', $this->name, [$this, 'call']);
    }

    public function call()
    {
        $this->loadPlugin();

        $arguments  = func_get_args();
        $parameters = ['value' => $arguments[0]];

        for ($i = 1; $i < count($arguments); $i++) {
            $parameters["param$i"] = $arguments[$i];
        }

        return $this->container->call("smarty_modifier_" . $this->name, $parameters);
    }
}
