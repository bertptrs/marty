<?php

namespace marty;

use Smarty;
use mako\config\Config;

class SmartyRenderer implements \mako\view\renderers\RendererInterface
{

    private $templateName;
    private $variables;
	private $globalVariables;
	private $cachePath;
	/**
	 * Configuration handle.
	 */
	private $config;

    public function __construct(Config $config)
    {
		$this->config = $config;
		$this->cachePath = null;
    }

    /**
	 * Render the view into a string.
	 *
	 * @return string The resulting view.
	 * @throws SmartyException should anything fail with template parsing.
	 */
    public function render($__view__, array $variables) {
		$smarty = $this->getInstance();

		foreach ($variables as $key => $value) {
			$smarty->assign($key, $value);
		}

        return $smarty->fetch();
	}
	
    /**
	 * Set up a new Smarty instance.
	 *
	 * Creates the normal configuration and returns it.
	 *
	 * @return Smarty a Smarty instance.
	 */
    private function getInstance() {
        $smarty = new Smarty();

		$config = $this->config;
		$smarty->setTemplateDir($config->get("marty::smarty.templateDir"));

		if ($this->cachePath == null) {
			$smarty->setCompileDir($config->get("marty::smarty.compileDir"));
		} else {
			$smarty->setCompileDir($this->cachePath);
		}

        $smarty->setCaching(Smarty::CACHING_OFF);
        $smarty->setCompileCheck(true);
        $smarty->addPluginsDir($config->get("marty::smarty.pluginDirs"));

        return $smarty;
    }

	public function setCachePath($path) {
		$this->cachePath = $path;
	}

}
