<?php

namespace marty\plugins;

use marty\UnresolvableParameterException;
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

    /**
     * Call a template plugin.
     *
     * @param array $params The parameters assigned to the view.
     * @param Smarty $smarty Note: not actually a smarty object, but something internal.
     * @return mixed whatever the plugin does
     * @throws UnresolvableParameterException
     * @throws \ReflectionException
     */
    public function call(array $params, $smarty)
    {
        $this->loadPlugin();

        $parameters = [
            'params' => $params,
            'smarty' => $smarty,
        ];

        return $this->callWithParameters('smarty_compiler_' . $this->name, $parameters);
    }
}
