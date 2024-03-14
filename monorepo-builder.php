<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use App\MonorepoBuilder\Release\UpdateChangelogReleaseWorker;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;

return static function(MBConfig $mbConfig): void {
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
                'phpunit/phpunit' => '^10.4',
                'roave/security-advisories' => 'dev-master',
                'symfony/string' => '^6.4',
                'symplify/monorepo-builder' => '^11.2',
            ],
        ]
    );

    $mbConfig->workers([
        UpdateReplaceReleaseWorker::class,
        SetCurrentMutualDependenciesReleaseWorker::class,
        UpdateChangelogReleaseWorker::class,
        TagVersionReleaseWorker::class,
        PushTagReleaseWorker::class,
        SetNextMutualDependenciesReleaseWorker::class,
        UpdateBranchAliasReleaseWorker::class,
        PushNextDevReleaseWorker::class,
    ]);
};
