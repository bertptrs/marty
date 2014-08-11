Marty
=====

Marty is a simple Smarty view renderer for the Mako framework.

Requirements
------------

Marty has been tested on mako 3.6.2. Any other version _may_ work, but has not been tried.

Installation
------------

Marty can be added to your Mako project using composer! Just add it to your requirements

```js
{
	/* Stuff */
	require: {
		/* Your other requirements */
		"solution-web/marty": "*"
	}
	/* The rest of your composer.json*/
}
```

Usage
-----

Once installed, you need to initialize it first. You can do this manually:

```php
mako\Package::init("marty");
```

As you are probably going to use Smarty as your preferred template language, I suggest you add marty to the auto-initialize list.

If Marty is initialized, it will render any view with the extension ".smarty.php". While this is not the _default_ extension for Smarty files (which is .tpl) but it is the best we can do while avoiding conflicts with Mako.

Configuration
-------------

Configuration can be done by changing the `marty::smarty` configuration. Just refer to the [http://makoframework.com/docs/3.6/getting-started:configuration#cascading_configuration](Mako documentation on package configuration) for details.

The configuration properties are as follows:

| Configuration property | Type     | Description                                         | Default                                                                                              |
|------------------------|----------|-----------------------------------------------------|------------------------------------------------------------------------------------------------------|
| smarty.templateDir     | string   | The location to find smarty templates.              | Your `app/views` directory.                                                                          |
| smarty.compileDir      | string   | The location to store compiled templates.           | A new directory `smarty` in your `app/storage` directory.                                            |
| smarty.pluginDirs      | string[] | A list of directories to search for smarty plugins. | The Smarty plugins and sysplugins directories. Refer to [http://www.smarty.net/docs/en/plugins.tpl](the smarty documentation on how to use this.) |

To do
-----

If I find the time, I would like to add support for:

* Smarty config files.
* Smarty caching.
* Smarty dynamic cache control.
* Anything else I fail to think of.
