<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
    $mbConfig->defaultBranch('main');
    $mbConfig->packageDirectories([__DIR__ . '/packages']);
    $mbConfig->packageDirectoriesExcludes([__DIR__ . '/packages/docs']);
    $mbConfig->packageAliasFormat('<major>.<minor>.x-dev');

    $mbConfig->dataToAppend(
        [
            ComposerJsonSection::AUTOLOAD_DEV => [
                'psr-4' => [
                    'App\\' => 'src/',
                ],
            ],
            ComposerJsonSection::REQUIRE_DEV => [
                "roave/security-advisories" => "dev-master",
                "phpunit/phpunit" => "^10.4",
                "psalm/plugin-phpunit" => "^0.18",
                "squizlabs/php_codesniffer" => "^3.7",
                "symplify/monorepo-builder" => "^11.2",
                "vimeo/psalm" => "^5.16"
            ],
        ]
    );
};
