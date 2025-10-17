<?php

/*
 * This file is part of seb-jean/zodiac-sign-bundle.
 *
 * (c) Sébastien JEAN <sebastien.jean76@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!file_exists(__DIR__.'/src')) {
    exit(0);
}

$fileHeaderParts = [
    <<<'EOF'
        This file is part of seb-jean/zodiac-sign-bundle.

        (c) Sébastien JEAN <sebastien.jean76@gmail.com>

        EOF,
    <<<'EOF'

        For the full copyright and license information, please view the LICENSE
        file that was distributed with this source code.
        EOF,
];

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRules([
        '@PHP81Migration' => true,
        '@PHPUnit91Migration:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'protected_to_private' => false,
        'header_comment' => [
            'header' => implode('', $fileHeaderParts),
            'validator' => implode('', [
                '/',
                preg_quote($fileHeaderParts[0], '/'),
                '(?P<EXTRA>.*)??',
                preg_quote($fileHeaderParts[1], '/'),
                '/s',
            ]),
        ],
        'php_unit_attributes' => true,
    ])

    ->setRiskyAllowed(true)
    ->setFinder(
        (new PhpCsFixer\Finder())
            ->in([
                __DIR__.'/src',
                __DIR__.'/tests',
            ])
            ->append([__FILE__])
    )
    ->setCacheFile('.php-cs-fixer.cache')
    ;
