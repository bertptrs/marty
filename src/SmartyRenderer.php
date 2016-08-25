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
     * @return string The resulting view.
     * @throws SmartyException should anything fail with template parsing.
     */
    public function render($__view__, array $variables)
    {
        $smarty = $this->smarty;
        $smarty->clearAllAssign();

        foreach ($variables as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch($__view__);
    }
}
