# Marty

[![Build Status](https://travis-ci.org/bertptrs/marty.svg)](https://travis-ci.org/bertptrs/marty) [![Latest Stable Version](https://poser.pugx.org/bertptrs/marty/v/stable)](https://packagist.org/packages/bertptrs/marty) [![Total Downloads](https://poser.pugx.org/bertptrs/marty/downloads)](https://packagist.org/packages/bertptrs/marty) [![License](https://poser.pugx.org/bertptrs/marty/license)](https://packagist.org/packages/bertptrs/marty)

Marty is a simple Smarty view renderer for the Mako framework.

## Requirements

Marty has been tested on all current Mako versions. Any other version
_may_ work, but has not been tested. Unit tests are run against PHP 7.0
and up. Smarty 3 is required, but will be pulled in as part of the
installation.

## Installation

Marty can be added to your Mako project using composer! Just add it to your
requirements

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

Once installed, you will need to add it to your packages list. You do
this by adding it to the "Web" packages. This is located in the file
`app/config/application.php`.

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

Configuration can be done by changing the `marty::smarty` configuration.
Just refer to the [Mako documentation on package
configuration](http://makoframework.com/docs/3.6/getting-started:configuration#cascading_configuration)
for details.

The configuration properties are as follows:

| Configuration property | Type     | Description                                         | Default                                                                                              |
|------------------------|----------|-----------------------------------------------------|------------------------------------------------------------------------------------------------------|
| smarty.templateDir     | string   | The location to find smarty templates.              | Your `app/views` directory.                                                                          |
| smarty.compileDir      | string   | The location to store compiled templates.           | A new directory `smarty` in your `app/storage` directory.                                            |
| smarty.pluginDirs      | string[] | A list of directories to search for smarty plugins. | An empty array. |

### Plugins

Marty supports the plugin structure offered by Smarty, and extends it to
use the dependency injector offered by the Mako framework.

Plugin dirs can be added to the config. The structure for plugins can be
found in the [Smarty Documentation](http://www.smarty.net/docs/en/). Due
to the fact that Mako resolves parameters by name, Smarty plugin
parameters should have their names as specified in the documentation.
This means that the parameters should be named as follows:

- Template functions:
    - parameters should be named `$params`
    - The smarty template should be named `$template`
- Modifiers:
    - The input data should be named `$value`
    - Any other values will be stored in array `$params` in order.
- Block functions:
    - Call parameters should be named `$params`
    - The block content should be named `$content`
    - The template reference should be named `$template`
    - The repetition flag should be named `$repeat`.
- Compiler functions:
    - Call parameters should be named `$params`
    - The Smarty reference should be named `$smarty`

As an example, we can build a small plugin that uses the I18n module of
the framework.

```php

function smarty_modifier_i18n($value, mako\i18n\I18n $i18n) {
    return $i18n->get($value);
}
```

Then, we can use your plugin like this:

```smarty
Lets show {'some.translated.text'|i18n}.
```

## Contributing

If there are any features in Smarty that cannot (easily) be used in the
current package, or if there are Mako features that are hard to bind to
Smarty, feel free to file an issue or a pull request.

Code style and such are ran using GrumPHP. Make sure that your files are
in accordance to those rules.
