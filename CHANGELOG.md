# Changelog

All notable changes to `intent-doc/laravel` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.0] - 2026-01-14

### Added

- Initial alpha release
- Fluent API for documenting Laravel routes with `->intent()`
- Support for intent name, description, rules, request examples, and response examples
- Artisan command `intent-doc:generate` to generate documentation
- Automatic generation of `intent-doc/` folder with `api-doc.json` and interactive `index.html`
- Multiple output formats: JSON, Markdown, HTML
- Beautiful interactive documentation viewer with search functionality
- Support for PHP 8.2, 8.3, and 8.4
- Support for Laravel 10.x, 11.x, and 12.x
- Auto-discovery of service provider
- Comprehensive README with examples
- MIT License

[Unreleased]: https://github.com/intent-doc/laravel/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/intent-doc/laravel/releases/tag/v0.1.0
