#!/usr/bin/php
<?php
/**
 * .git/hooks/pre-commit
 * 
 * This pre-commit hooks will check for PHP error (lint), and make sure the code
 * is PSR compliant.
 * 
 * Dependecy: PHP-CS-Fixer (https://github.com/fabpot/PHP-CS-Fixer)
 * 
 * @author  Mardix  http://github.com/mardix 
 * @author  Bert Peters bert@solution-web.nl
 * @since   Sept 4 2012
 * 
 */
 
/**
 * collect all files which have been added, copied or
 * modified and store them in an array called output
 */
exec('git diff --cached --name-status --diff-filter=ACM', $output);


$file = [];
foreach ($output as $file) {

	$fileName = trim(substr($file, 1) );

	/**
	 * Only PHP file
	 */
	if (pathinfo($fileName,PATHINFO_EXTENSION) == "php") {

		/**
		 * Check for error
		 */
		$lint_output = array();
		exec("php -l " . escapeshellarg($fileName), $lint_output, $return);

		if ($return == 0) {

			exec("php vendor/bin/phpmd {$fileName} text all", $phpmd_output, $return);
			if ($return == 0) {

				/**
				 * PHP-CS-Fixer && add it back
				 */
				exec("vendor/bin/php-cs-fixer fix {$fileName} --level=all; git add {$fileName}");
			} else {
				echo implode(PHP_EOL, $phpmd_output);
				exit(1);
			}

		} else {

			echo implode("\n", $lint_output), "\n"; 

			exit(1);

		}

	}
	$files[] = $file;

}

exit(0);
