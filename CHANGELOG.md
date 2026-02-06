# Changelog

All notable changes to `laravel-dynamic-model` will be documented in this file.

## v3.0.4 - 2026-02-06

### What's Changed

* Fix PHP 8.1 deprecation warning in DynamicModelFactory::create() by @Skop-22 in https://github.com/laracraft-tech/laravel-dynamic-model/pull/33

### New Contributors

* @Skop-22 made their first contribution in https://github.com/laracraft-tech/laravel-dynamic-model/pull/33

**Full Changelog**: https://github.com/laracraft-tech/laravel-dynamic-model/compare/v3.0.3...v3.0.4

## v3.0.3 - 2023-04-14

### Improvement

- replaced deprecated doctrine function call 2.0

## v3.0.2 - 2023-04-14

### Improvement

- replaced deprecated doctrine function call

## v3.0.1 - 2023-04-14

### Fixed

- connection issue on different db connection

## v3.0.0 - 2023-04-13

### Added

- now dynamically create real model classes, which can handle different tables and db connections

## v2.1.0 - 2023-04-04

### Changed

- removed factory, made container binding work

## v2.0.1 - 2023-04-03

### Added

- Improved model creating - call `bindyDynamically` in constructor

## v2.0.0 - 2023-04-02

### Added:

- PHPStan
- Style fixer (pint)
- Tests (Pest)
- PHP 8.1 support
- Possible to add db connection to dynamic model (#2)
- Possible to create own dynamic models
