<?php

namespace marty;

use mako\view\renderers\RendererInterface;
use Smarty;
use SmartyException;

class SmartyRenderer implements RendererInterface
{
    private $smarty;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    /**
     * Render the view into a string.
     *
     * @param string $view The filename of the view.
     * @param array $variables Assigned variables
     * @return string The resulting view.
     * @throws SmartyException should anything fail with template parsing.
     */
    public function render(string $view, array $variables) : string
    {
        $smarty = $this->smarty;
        $smarty->clearAllAssign();

        $smarty->assign($variables);

        return $smarty->fetch($view);
    }
}
