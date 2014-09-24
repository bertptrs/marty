<?php

namespace Marty;

use mako\application\services\Service;
use Marty\SmartyRenderer;

class MartyRendererService extends Service {

	public function register() {
		$this->container->get("view")->registerRenderer(".tpl",
			"\marty\SmartyRenderer");
		SmartyRenderer::loadConfig($this->container->get("config"));
	}
}
