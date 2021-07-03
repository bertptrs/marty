<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();

$config
    ->setUsingCache(true)
    ->setRules([
        '@PSR2'              => true,
        'single_quote'       => true,
    ])->setFinder($finder);

return $config;
