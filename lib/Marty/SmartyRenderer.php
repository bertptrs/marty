<?php

namespace Marty;

use Smarty;
use mako\config\Config;

class SmartyRenderer implements \mako\view\renderers\RendererInterface
{

    private $templateName;
    private $variables;
	private $globalVariables;
	private $cachePath;
	private $smarty;
	/**
	 * Configuration handle.
	 */
	private static $config;

    public function __construct($view, array $variables)
    {

        $this->variables = $variables;

		$this->templateName = $view;

		$this->cachePath = null;
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

        return $this->smarty->fetch($this->templateName);
	}
	
	public function assign($key, $value) {
		$this->variables[$key] = $value;
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
		if ($this->cachePath == null) {
			$smarty->setTemplateDir(Config::get("marty::smarty.templateDir"));
		} else {
			$smarty->setTemplateDir($this->cachePath);
		}
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

	public function setCachePath($path) {
		$this->cachePath = $path;
	}

	/**
	 * Load a static configuration reference
	 *
	 * @param Config $config configuration instance
	 */
	public static function loadConfig(Config $config) {
		static::$config = $config;
	}
}
