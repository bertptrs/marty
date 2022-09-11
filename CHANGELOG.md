# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a
Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to
[Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [6.2.0]

### Added

- Added compatibility with PHP 8.0.
- Added compatibility with PHP 8.1.
- Added support for Mako 8.x.
- Added support for Smarty 4.x

### Changed

- (internal) replaced Travis CI with Github Actions after travis-ci.org shut down.

## [6.1.0] - 2020-09-19

### Added

- Add support for Mako Framework 7. Support for Mako 6.x is unchanged.

### Fixed

- Fixed an issue in PHP 7.4 relating to `ReflectionType` string
  conversion.

## [6.0] - 2019-04-09

### Added

- TemplateCompilationTest now supports PHPUnit 6 or higher.
    - Note that this still needs PHP 7.2.

### Changed

- Require Mako 6.0 or compatible
- Require PHP 7.2

## [5.2.3]

### Added

- Add a utility template test class. Use it as a sanity check for your
  views.
- Better type hinting for internal function wrappers.

[Unreleased]: https://github.com/bertptrs/marty/compare/v6.2.0...HEAD
[6.2.0]: https://github.com/bertptrs/marty/compare/v6.1.0...v6.2.0
[6.1.0]: https://github.com/bertptrs/marty/compare/v6.0...v6.1.0
[6.0]: https://github.com/bertptrs/marty/compare/v5.2.3...v6.0
[5.2.3]: https://github.com/bertptrs/marty/compare/v5.2.2...v5.2.3
