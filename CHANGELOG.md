# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog],
and this project adheres to [Semantic Versioning].

[Keep a Changelog]: https://keepachangelog.com/en/1.0.0/
[Semantic Versioning]: https://semver.org/spec/v2.0.0.html

## [6.0.0] - 2023-09-18

### Added
- Support for cursor based pagination to `::all()` endpoints.
- Changelog and 6.0.0 upgrade instructions.
- PHP 8.2 support #112

### Fixed
- Undefined constant `ChartMogul\DEFAULT_MAX_RETRIES` #112

### Removed
- Support for PHP 7.x as 7.4 reached EOL in 2022.
