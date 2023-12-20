# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog],
and this project adheres to [Semantic Versioning].

[Keep a Changelog]: https://keepachangelog.com/en/1.0.0/
[Semantic Versioning]: https://semver.org/spec/v2.0.0.html

## [6.1.0] - 2023-12-20
- Support customer notes

## [6.0.0] - 2023-10-30

### Removed
- Support for old pagination using `page` query params.

## [5.1.3] - 2023-10-03

### Added
- Support for cursor based pagination (#114)
- Linted the project with phpcs and phpcbf (#114)

## [5.1.2] - 2023-09-25

### Added
- Support for PHP 8.2 (#112)

### Fixed
- Undefined constant `ChartMogul\DEFAULT_MAX_RETRIES` (#112)
