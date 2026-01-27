<?php

declare(strict_types=1);

use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src')
    ->name('*.php');


return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP8x4Migration' => true,
        '@PHP8x2Migration:risky' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'case',
                'continue',
                'declare',
                'default',
                'do',
                'exit',
                'for',
                'foreach',
                'goto',
                'if',
                'include',
                'include_once',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
                'while',
                'yield',
                'yield_from',
            ],
        ],
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'trait_import' => 'none',
                'case' => 'none',
                'const' => 'none',
                'property' => 'one',
            ],
        ],
        'final_class' => true,
        'final_internal_class' => ['exclude' => ['ORM\\Entity', '@not-fix']],
        'phpdoc_add_missing_param_annotation' => true,
        'date_time_immutable' => true,
        'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
        'blank_line_between_import_groups' => false,
        'phpdoc_to_comment' => false,
        // strict
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'no_useless_else' => true,
        'concat_space' => ['spacing' => 'one'],
        'static_lambda' => true,
        'date_time_create_from_format_call' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
    ]);
