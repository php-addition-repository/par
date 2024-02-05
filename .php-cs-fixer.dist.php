<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PHP83Migration' => true,
        '@PER-CS2.0' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'continue',
                'declare',
                'return',
                'throw',
                'try'
            ]
        ],
        'braces_position' => [
            'allow_single_line_anonymous_functions' => true,
            'allow_single_line_empty_anonymous_classes' => true
        ],
        'global_namespace_import' => [
            'import_classes' => true,
        ]
    ])
    ->setFinder($finder);
