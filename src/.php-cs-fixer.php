<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/controllers')
    ->in(__DIR__ . '/core')
    ->in(__DIR__ . '/helpers')
    ->name('*.php');

$config = new PhpCsFixer\Config();
return $config
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'blank_line_after_opening_tag' => true,
        'blank_line_after_namespace' => true,
        'single_blank_line_at_eof' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'indentation_type' => true,
        'line_ending' => true,
        // Add blank lines between class elements
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'one',
                'method' => 'one',
                'property' => 'one',
                'trait_import' => 'none',
            ],
        ],
        // Remove extra blank lines but keep structure
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'throw',
                'use',
            ],
        ],
    ])
    ->setFinder($finder);
