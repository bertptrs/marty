<?php

namespace marty;

use mako\application\services\Service;
use marty\SmartyRenderer;

class MartyRendererService extends Service {

	public function register() {
		$this->container->get("view")->registerViewRenderer(".tpl",
			"\marty\SmartyRenderer");
		SmartyRenderer::loadConfig($this->get("config"));
	}
}
