# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres
to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- [CORE] Added `Comparison` structures which provide a clear interface for value
  ordering.
- [CORE] Added `Optional` which can be used to represent "no result" where
  using `null` would cause issues.
- [CORE] Added `Assert` which is a customized version of `webmozart/assert`.
- [CORE] Added ability to assert equality between two iterables in PHPUnit.
- [REPO] Added git hooks which run php lint and phpcs on all php files prior to
  committing.
- [REPO] Added `opcache` extension in docker workspace to improve performance of
  psalm.
- [REPO] Added editorconfig.

### Changed

- [REPO] Updated usability of `make` targets with a `make help` and small
  descriptions.
- [CORE] Renamed interface `ObjectEquality` to `Equable`, including the PHPUnit
  comparator.
- [CORE] Renamed `Values::equalsOneIn` to `Values::equalsAnyIn`.
- [CORE] Renamed `Values::equalsOneOf` to `Values::equalsAnyOf`.
- [CORE] Renamed PHPUnit extension to allow for multiple extensions.

## [0.0.1] - 2023-12-01

Initial release.

[Unreleased]: https://github.com/php-addition-repository/par/compare/0.0.1...HEAD

[0.0.1]: https://github.com/php-addition-repository/par/tree/0.0.1
