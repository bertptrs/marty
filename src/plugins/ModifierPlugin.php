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

    public function call($value, ...$params)
    {
        $this->loadPlugin();

        $parameters = [
            'value' => $value,
            'params' => $params,
        ];

        return $this->callWithParameters('smarty_modifier_' . $this->name, $parameters);
    }
}
