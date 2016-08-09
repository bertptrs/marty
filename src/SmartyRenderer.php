<?php

namespace marty;

use mako\config\Config;
use mako\view\renderers\RendererInterface;
use Smarty;
use SmartyException;

class SmartyRenderer implements RendererInterface
{
    /**
     * Configuration handle.
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Render the view into a string.
     *
     * @return string The resulting view.
     * @throws SmartyException should anything fail with template parsing.
     */
    public function render($__view__, array $variables)
    {
        $smarty = $this->getInstance();

        foreach ($variables as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch($__view__);
    }

    /**
     * Set up a new Smarty instance.
     *
     * Creates the normal configuration and returns it.
     *
     * @return Smarty a Smarty instance.
     */
    private function getInstance()
    {
        $smarty = new Smarty();

        $config = $this->config;
        $smarty->setTemplateDir($config->get("marty::smarty.templateDir"));

        $smarty->setCompileDir($config->get("marty::smarty.compileDir"));

        $smarty->setCaching(Smarty::CACHING_OFF);
        $smarty->setCompileCheck(true);
        $smarty->addPluginsDir($config->get("marty::smarty.pluginDirs"));

        return $smarty;
    }
}
