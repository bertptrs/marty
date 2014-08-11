<?php

use \Smarty;

return [
	"templateDir" => MAKO_APPLICATION_PATH . "/views",
	"compileDir" => MAKO_APPLICATION_PATH . "/storage/smarty/",
	"pluginDirs" => [
		"./plugins",
		"./sysplugins"
	],
];
