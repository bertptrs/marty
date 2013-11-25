<?php

namespace Marty;

use \mako\Config;
use \Smarty;

class MartyConfig
{

	/**
	 * @var string
	 */
	private static $template_dir = Config::get("marty::smarty.templateDir");

	/**
	 * @var string
	 */
	private static $compile_dir = Config::get("marty::smarty.cacheDir");

	/**
	 * @var string
	 */
	private static $cache_dir = Config::get("marty::smarty.cacheDir");

	/**
	 * @var string
	 */
	private static $config_dir = Config::get("marty::smarty.configDir");

	private function __construct()
	{
	}

	/**
	 * Create a Smarty instance.
	 *
	 * @return Smarty A smarty instance.
	 */
	public static function getSmartyInstance()
	{
		$smarty = new Smarty();
		$smarty->setTemplateDir(static::$template_dir);
		$smarty->setCompileDir(static::$compile_dir);
		$smarty->setCacheDir(static::$cache_dir);
		$smarty->seConfigDir(static::$config_dir);
	}

	public static function getTemplateDir()
	{
		return static::$template_dir;
	}

	public static function getCompileDir()
	{
		return static::$compile_dir;
	}

	public static function getCacheDir()
	{
		return static::$cache_dir;
	}

	public static function getConfigDir()
	{
		return static::$config_dir;
	}

	public static function setTemplateDir($template_dir)
	{
		static::$template_dir = $template_dir;
	}

	public static function setCacheDir($cache_dir)
	{
		static::$cache_dir = $cache_dir;
	}

	public static function setCompileDir($compile_dir)
	{
		static::$compile_dir = $compile_dir;
	}

	public static function setConfigDir($config_dir)
	{
		static::$config_dir = $config_dir;
	}

}
