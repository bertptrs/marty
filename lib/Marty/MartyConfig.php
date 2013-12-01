<?php

namespace Marty;

use \mako\Config;
use \Smarty;

class MartyConfig
{
	const SMARTY_EXTENSION = ".smarty";
	const SMARTY_EXTENSION_FULL = ".smarty.php";

	/**
	 * @var boolean
	 */
	private static $initialized = false;

	/**
	 * @var string
	 */
	private static $template_dir;

	/**
	 * @var string
	 */
	private static $compile_dir;

	/**
	 * @var string
	 */
	private static $cache_dir;

	/**
	 * @var string
	 */
	private static $config_dir;

	/**
	 * @var int
	 */
	private static $caching;

	/**
	 * @var boolean
	 */
	private static $compile_check;

	/**
	 * @var int
	 */
	private static $cache_lifetime;

	/**
	 * @var string
	 */
	private static $cache_id = null;

	private function __construct()
	{
	}

	private static function init()
	{
		static::$template_dir = Config::get("marty::smarty.templateDir");
		static::$compile_dir = Config::get("marty::smarty.compileDir");
		static::$cache_dir = Config::get("marty::smarty.cacheDir");
		static::$config_dir = Config::get("marty::smarty.configDir");
		static::$caching = Config::get("marty::smarty.caching");
		static::$compile_check = Config::get("marty::smarty.compileCheck");
		static::$cache_lifetime = Config::get("marty::smarty.defaultCacheLifeTime");

		static::$initialized = true;
	}

	/**
	 * Create a Smarty instance.
	 *
	 * @return Smarty A smarty instance.
	 */
	public static function getSmartyInstance()
	{
		if (!static::$initialized)
			static::init();

		$smarty = new Smarty();
		$smarty->setTemplateDir(static::$template_dir);
		$smarty->setCompileDir(static::$compile_dir);
		$smarty->setCacheDir(static::$cache_dir);
		$smarty->setConfigDir(static::$config_dir);
		$smarty->setCaching(static::$caching);
		$smarty->setCompileCheck(static::$compile_check);
		$smarty->setCacheLifetime(static::$cache_lifetime);

		return $smarty;
	}

	/**
	 * Get template directory
	 *
	 * @return string
	 */
	public static function getTemplateDir()
	{
		if (!static::$initialized)
			static::init();

		return static::$template_dir;
	}

	/**
	 * Get compile directory
	 *
	 * @return string
	 */
	public static function getCompileDir()
	{
		if (!static::$initialized)
			static::init();

		return static::$compile_dir;
	}

	/**
	 * Get cache directory.
	 *
	 * @return string
	 */
	public static function getCacheDir()
	{
		if (!static::$initialized)
			static::init();

		return static::$cache_dir;
	}

	/**
	 * Get config directory.
	 *
	 * @return string
	 */
	public static function getConfigDir()
	{
		if (!static::$initialized)
			static::init();

		return static::$config_dir;
	}

	public static function getCaching()
	{
		if (!static::$initialized)
			static::init();

		return static::$caching;
	}

	public static function getCompileCheck()
	{
		if (!static::$initialized)
			static::init();

		return static::$compile_check;
	}

	public static function getCacheLifetime()
	{
		if (!static::$initialized)
			static::init();

		return static::$cache_lifetime;
	}

	/**
	 * Set template directory
	 *
	 * @param	string	$template_dir
	 */
	public static function setTemplateDir($template_dir)
	{
		if (!static::$initialized)
			static::init();

		static::$template_dir = $template_dir;
	}

	/**
	 * Set cache directory
	 *
	 * @param	string	$cache_dir
	 */
	public static function setCacheDir($cache_dir)
	{
		if (!static::$initialized)
			static::init();

		static::$cache_dir = $cache_dir;
	}

	/**
	 * Set compile directory
	 *
	 * @param	string	$compile_dir
	 */
	public static function setCompileDir($compile_dir)
	{
		if (!static::$initialized)
			static::init();

		static::$compile_dir = $compile_dir;
	}

	/**
	 * Set config directory.
	 *
	 * @param	string	$config_dir
	 */
	public static function setConfigDir($config_dir)
	{
		if (!static::$initialized)
			static::init();

		static::$config_dir = $config_dir;
	}

	public static function setCaching($caching)
	{
		if (!static::$initialized)
			static::init();

		static::$caching = $caching;
	}

	public static function setCompileCheck($compile_check)
	{
		if (!static::$initialized)
			static::init();

		static::$compile_check = $compile_check;
	}

	public static function setCacheLifetime($cache_lifetime)
	{
		if (!static::$initialized)
			static::init();

		static::$cache_lifetime = $cache_lifetime;
	}

	public static function setCacheId($cache_id)
	{
		static::$cache_id = $cache_id;
	}

	public static function getCacheId()
	{
		return static::$cache_id;
	}

	public static function isCached($view)
	{
		if (!static::$initialized)
			static::init();

		$view = str_replace(".", "/", $view);
		$view .= self::SMARTY_EXTENSION_FULL;

		$smarty = static::getSmartyInstance();
		return $smarty->isCached($view, static::$cache_id);
	}

}
