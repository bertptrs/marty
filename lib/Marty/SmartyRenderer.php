<?php

namespace Marty;

class SmartyRenderer implements \mako\view\renderer\RendererInterface
{

	private $smarty;
	private $templateName;
	private $variables;
	private $globalVariables;

	public function __construct($view, array $variables, array $globalVariables)
	{

		$this->variables = $variables;
		$this->globalVariables = $globalVariables;

		$this->templateName = $view;
	}

	public function render()
	{
		$this->smarty = MartyConfig::getSmartyInstance();

		// Assign the view-variables.
		$this->assignVariables($variables);

		// By lack of a better way, assign the globals as well.
		$this->assignVariables($globalVariables);

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

