# Changelog

All notable changes to `laravel-dynamic-model` will be documented in this file.

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
