<?php

namespace Marty;

use \mako\Config;

class SmartyRenderer implements \mako\view\renderer\RendererInterface
{

	private $smarty;
	private $templateName;

	public function __construct($view, array $variables, array $globalVariables)
	{

		// Initialize a smarty instance
		$this->smarty = new \Smarty();
		$this->smarty->setTemplateDir(Config::get("marty::smarty.templateDir"));
		$this->smarty->setCompileDir(Config::get("marty::smarty.compileDir"));
		$this->smarty->setCacheDir(Config::get("marty::smarty.cacheDir"));
		$this->smarty->setConfigDir(Config::get("marty::smarty.configDir"));

		// Assign the view-variables.
		$this->assignVariables($variables);

		// By lack of a better way, assign the globals as well.
		$this->assignVariables($globalVariables);

		$this->templateName = $view;
	}

	public function render()
	{
		return $this->smarty->fetch($this->templateName);
	}

	private function assignVariables($variables)
	{
		foreach ($variables as $key => $value)
		{
			$this->smarty->assign($key, $value);
		}
	}
}

