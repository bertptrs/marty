<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();

$rules = [
    '@PSR2' => true,
    '@PHP80Migration' => true,
    '@PHP81Migration' => true,
    'single_quote'=> true,
    'heredoc_indentation' => false, // incompatible with PHP 7.2
];

// Some rule sets include options that are invalid for PHP 7.2, disable those.
foreach ([
    'method_argument_space',
    'no_whitespace_before_comma_in_array',
    'trailing_comma_in_multiline',
    ] as $block) {
    $rules[$block] = ['after_heredoc' => false];
}

$config
    ->setUsingCache(true)
    ->setRules($rules)
    ->setFinder($finder);

return $config;
