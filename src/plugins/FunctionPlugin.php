<?php

namespace marty\plugins;

use Smarty;
use Smarty_Internal_Template;

/**
 * Plugin for template functions.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class FunctionPlugin extends BasePlugin
{
    public function call(array $params, Smarty_Internal_Template $template)
    {
        $this->loadPlugin();

        $parameters = [
            'params'   => $params,
            'template' => $template,
        ];

        return $this->callWithParameters('smarty_function_' . $this->name, $parameters);
    }

    public function register(Smarty $smarty)
    {
        $smarty->registerPlugin('function', $this->name, [$this, 'call']);
    }
}
