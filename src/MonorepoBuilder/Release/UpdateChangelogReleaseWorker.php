<?php

declare(strict_types=1);

namespace App\MonorepoBuilder\Release;

use DateTime;
use PharIo\Version\Version;
use Symfony\Component\String\AbstractString;
use Symfony\Component\String\UnicodeString;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;

final class UpdateChangelogReleaseWorker implements ReleaseWorkerInterface
{
    /**
     * @var string
     * @see https://regex101.com/r/X3W0Fg/1
     */
    private const UNRELEASED_HEADLINE_REGEX = '/^\#\# \[Unreleased\]$/m';

    /**
     * @var string
     * @see https://regex101.com/r/kGF5MH/1
     */
    private const UNRELEASED_HEADLINK_REGEX = '/^\[Unreleased\]: .+$/m';

    public function work(Version $version): void
    {
        $changelogFilePath = getcwd() . '/CHANGELOG.md';
        if (!file_exists($changelogFilePath)) {
            return;
        }

        $changelogFileContent = new UnicodeString(file_get_contents($changelogFilePath));
        $previousVersion = $this->determinePreviousReleasedVersion($changelogFileContent);
        $changelogFileContent = $changelogFileContent->replaceMatches(
            self::UNRELEASED_HEADLINE_REGEX,
            $this->createNewHeadline($version)
        )
            ->replaceMatches(
                self::UNRELEASED_HEADLINK_REGEX,
                $this->createNewHeadLink($version) . PHP_EOL . $this->createNewVersionLink($version, $previousVersion)
            );

        file_put_contents($changelogFilePath, $changelogFileContent->toString());
    }

    public function getDescription(Version $version): string
    {
        return sprintf(
            'Update `CHANGELOG.md` to include version "%s" and a fresh "Unreleased" section',
            $version->getOriginalString()
        );
    }

    private function createNewHeadline(Version $version): string
    {
        $dateTime = new DateTime();
        return implode(PHP_EOL, [
            '## [Unreleased]',
            '',
            '_Nothing yet._',
            '',
            sprintf('## [%s] - %s', $version->getVersionString(), $dateTime->format('Y-m-d'))
        ]);
    }

    private function createNewHeadLink(Version $version): string
    {
        return sprintf(
            '[Unreleased]: https://github.com/php-addition-repository/par/compare/%s...HEAD',
            $version->getOriginalString()
        );
    }

    private function createNewVersionLink(Version $newVersion, ?Version $previousVersion): string
    {
        if (!$previousVersion) {
            return sprintf(
                '[%s]: https://github.com/php-addition-repository/par/tree/%s',
                $newVersion->getVersionString(),
                $newVersion->getOriginalString()
            );
        }

        return sprintf(
            '[%s]: https://github.com/php-addition-repository/par/compare/%s...%s',
            $newVersion->getVersionString(),
            $newVersion->getOriginalString(),
            $previousVersion->getOriginalString()
        );
    }

    private function determinePreviousReleasedVersion(AbstractString $changelogFileContent): ?Version
    {
        $lastVersionStringMatch = $changelogFileContent->match('/^## \[(\d.\d.\d)\] - .+$/m');
        if (count($lastVersionStringMatch) > 1) {
            return new Version($lastVersionStringMatch[1]);
        }

        return null;
    }
}
