<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'braces' => [
            'position_after_anonymous_constructs' => 'same',
        ],
        'array_syntax' => ['syntax' => 'short'],
        'no_trailing_whitespace' => true,
        'no_extra_blank_lines' => true,
        'indentation_type' => true,
        'array_indentation' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        // 'method_chaining_indentation' => true, // отключено
    ])
    ->setFinder($finder);
