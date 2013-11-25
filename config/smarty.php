<?php

use \Smarty;

return array(
	"templateDir" => MAKO_APPLICATION_PATH . "/views",
	"cacheDir" => MAKO_APPLICATION_PATH . "/storage/smarty/cache",
	"compileDir" => MAKO_APPLICATION_PATH . "/storage/smarty/compile",
	"configDir" => MAKO_APPLICATION_PATH . "/storage/smarty/config",
	"caching" => Smarty::CACHING_OFF,
	"compileCheck" => true,
	"defaultCacheLifeTime" => 3600,
);
