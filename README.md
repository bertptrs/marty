# Marty

Marty is a simple Smarty view renderer for the Mako framework.

## Requirements

Marty has been tested on mako 3.6.2. Any other version _may_ work, but has not been tried.

## Installation

Marty can be added to your Mako project using composer! Just add it to your requirements

```js
{
	/* Stuff */
	require: {
		/* Your other requirements */
		"bertptrs/marty": "*"
	}
	/* The rest of your composer.json*/
}
```

## Usage

Once installed, you will need to add it to your packages list. You do this by adding it to the "Web" packages. This is located in the file `app/config/application.php`.

```php
<?php
return [
	// …
	'packages' => [
		// …
		'web' => [
			'marty\MartyPackage',
		],
	],
	// …
];
?>
```

This enables the Smarty View renderer for every view with the ".tpl" file extension.


### Configuration

Configuration can be done by changing the `marty::smarty` configuration. Just refer to the [Mako documentation on package configuration](http://makoframework.com/docs/3.6/getting-started:configuration#cascading_configuration) for details.

The configuration properties are as follows:

| Configuration property | Type     | Description                                         | Default                                                                                              |
|------------------------|----------|-----------------------------------------------------|------------------------------------------------------------------------------------------------------|
| smarty.templateDir     | string   | The location to find smarty templates.              | Your `app/views` directory.                                                                          |
| smarty.compileDir      | string   | The location to store compiled templates.           | A new directory `smarty` in your `app/storage` directory.                                            |
| smarty.pluginDirs      | string[] | A list of directories to search for smarty plugins. | The Smarty plugins and sysplugins directories. Refer to [the Smarty documentation for info on how to use this](http://www.smarty.net/docs/en/plugins.tpl)                                              |

## To do

If I find the time, I would like to add support for:

* Smarty config files.
* Smarty caching.
* Smarty dynamic cache control.
* Anything else I fail to think of.
