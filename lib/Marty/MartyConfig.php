<?php

namespace Marty;

use \mako\Config;
use \Smarty;

/**
 * Class: MartyConfig
 *
 * A static configuration shell around Smarty for the mako framework.
 * 
 * @author Bert Peters <bert.ljpeters@gmail.com>
 *
 */
class MartyConfig
{
	const SMARTY_EXTENSION = ".smarty";
	const SMARTY_EXTENSION_FULL = ".smarty.php";

	const SMARTY_PLUGIN_FUNCTION = "function";
	const SMARTY_PLUGIN_BLOCK    = "block";
	const SMARTY_PLUGIN_MODIFIER = "modifier";
	const SMARTY_PLUGIN_COMPILER = "compiler";

	/**
	 * Whether or not init() has fired yet.
	 *
	 * @see init() The init function.
	 * @var boolean
	 */
	private static $initialized = false;

	/**
	 * Template directory.
	 *
	 * @var string
	 */
	private static $template_dir;

	/**
	 * Compile directory.
	 *
	 * @var string
	 */
	private static $compile_dir;

	/**
	 * Cache directory.
	 *
	 * @var string
	 */
	private static $cache_dir;

	/**
	 * Configuration directory.
	 *
	 * @var string
	 */
	private static $config_dir;

	/**
	 * Current caching mode.
	 *
	 * @var int
	 */
	private static $caching;

	/**
	 * Whether or not to perform a compile check.
	 *
	 * @var boolean
	 */
	private static $compile_check;

	/**
	 * Cache lifetime, in seconds.
	 * @var int
	 */
	private static $cache_lifetime;

	/**
	 * Cache id.
	 *
	 * @var string
	 */
	private static $cache_id = null;

	/**
	 * List of registered plugins.
	 *
	 * @var array
	 */
	private static $registered_plugins = array();

	/**
	 * Dummy constructor
	 *
	 */
	private function __construct()
	{
	}

	/**
	 * Initialize the config manager with the default values.
	 *
	 */
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

		static::registerPlugins($smarty);

		return $smarty;
	}

	private static function registerPlugins(Smarty &$smarty)
	{
		foreach (static::$registered_plugins as $name => $details)
		{
			extract($details);
			$smarty->registerPlugin($type, $name, $callback, $cachable, $cache_attrs);
		}
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

	/**
	 * Get caching mode.
	 *
	 * @return int
	 *
	 */
	public static function getCaching()
	{
		if (!static::$initialized)
			static::init();

		return static::$caching;
	}

	/**
	 * Get CompileCheck
	 *
	 * @return boolean
	 *
	 */
	public static function getCompileCheck()
	{
		if (!static::$initialized)
			static::init();

		return static::$compile_check;
	}

	/**
	 * Get CacheLifetime
	 *
	 * @return int
	 */
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

	/**
	 * Set caching to a given state.
	 *
	 * @param int $caching One of the SMARTY_CACHING_XXX constants.
	 */
	public static function setCaching($caching)
	{
		if (!static::$initialized)
			static::init();

		static::$caching = $caching;
	}

	/**
	 * Set the compile check.
	 *
	 * @param boolean $compile_check
	 */
	public static function setCompileCheck($compile_check)
	{
		if (!static::$initialized)
			static::init();

		static::$compile_check = $compile_check;
	}

	/**
	 * Set the cache lifetime.
	 *
	 * @param int $cache_lifetime
	 */
	public static function setCacheLifetime($cache_lifetime)
	{
		if (!static::$initialized)
			static::init();

		static::$cache_lifetime = $cache_lifetime;
	}

	/**
	 * Set the cache id.
	 *
	 * @param string $cache_id
	 */
	public static function setCacheId($cache_id)
	{
		static::$cache_id = $cache_id;
	}

	/**
	 * Return currently configured cache id.
	 *
	 * @return string|null
	 *
	 */
	public static function getCacheId()
	{
		return static::$cache_id;
	}

	/**
	 * Is the given view cached?
	 *
	 * @param string $view
	 */
	public static function isCached($view)
	{
		if (!static::$initialized)
			static::init();

		$view = str_replace(".", "/", $view);
		$view .= self::SMARTY_EXTENSION_FULL;

		$smarty = static::getSmartyInstance();
		return $smarty->isCached($view, static::$cache_id);
	}


	/**
	 * Register a smarty plugin
	 *
	 * @link http://www.smarty.net/docs/en/api.register.plugin.tpl The original smarty function, for reference.
	 * @param string  $type        The plugin type. Use one of the SMARTY_PLUGIN_XXX constants
	 * @param string  $name        The name for the plugin
	 * @param mixed   $callback    A callback. Can be a string, or an array.
	 * See the smarty docs for more information.
	 * @param boolean $cachable    [optional] Whether this plugin can be
	 * cached.
	 * @param array   $cache_attrs [optional] Attributes that can be cached.
	 *
	 * @throws MartyException      If the plugin is already registered.
	 */
	public static function registerPlugin($type, $name, $callback, $cachable = true, $cache_attrs = array())
	{
		if (array_key_exists($name, static::$registered_plugins))
			throw new MartyException("Plugin already registered.");

		static::$registered_plugins[$name] = array(
			"type"        => $type,
			"callback"    => $callback,
			"cachable"    => $cachable,
			"cache_attrs" => $cache_attrs,
		);
	}

	/**
	 * Unregister named plugin
	 *
	 * @param string $name
	 *
	 * @throws MartyException if the plugin was not already registered.
	 */
	public static function unregisterPlugin($name)
	{
		if (!array_key_exists($name, static::$registered_plugins))
			throw new MartyException("Plugin not registered!");
		unset(static::$registered_plugins[$name]);
	}

}
