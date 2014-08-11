<?php

namespace Marty;

use Smarty;
use mako\Config;

class SmartyRenderer implements \mako\view\renderer\RendererInterface
{

    private $templateName;
    private $variables;
    private $globalVariables;
    private $smarty;

    public function __construct($view, array $variables, array $globalVariables)
    {

        $this->variables = $variables;
        $this->globalVariables = $globalVariables;

        $this->templateName = $view;
    }

    /**
	 * Render the view into a string.
	 *
	 * @return string The resulting view.
	 * @throws SmartyException should anything fail with template parsing.
	 */
    public function render()
    {
        $this->smarty = $this->getInstance();

        // Assign the view-variables.
        $this->assignVariables($this->variables);

        // By lack of a better way, assign the globals as well.
        $this->assignVariables($this->globalVariables);

        return $this->smarty->fetch($this->templateName);
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

        $smarty->setCompileDir(Config::get("marty::smarty.compileDir"));
        $smarty->setTemplateDir(Config::get("marty::smarty.templateDir"));
        $smarty->setCaching(Smarty::CACHING_OFF);
        $smarty->setCompileCheck(true);
        $smarty->addPluginsDir(Config::get("marty::smarty.pluginDirs"));

        return $smarty;
    }

    /**
	 * Assign a bunch of variables to Smarty.
	 *
	 * @param $variables array Array of variables to assign.
	 */
    private function assignVariables(array $variables)
    {
        foreach ($variables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
    }
}
