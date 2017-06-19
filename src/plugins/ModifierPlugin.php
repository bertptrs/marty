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
        $parameters = [
            'value' => $arguments[0],
            'params' => array_slice($arguments, 1)
        ];

        return $this->callWithParameters('smarty_modifier_' . $this->name, $parameters);
    }
}
