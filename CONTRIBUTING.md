# Contributing to IntentDoc

First off, thank you for considering contributing to IntentDoc! It's people like you that make IntentDoc such a great tool.

## Code of Conduct

By participating in this project, you are expected to uphold our Code of Conduct: be respectful, inclusive, and considerate of others.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When you create a bug report, include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps to reproduce the problem**
- **Provide specific examples** - Include code samples, error messages, or screenshots
- **Describe the behavior you observed and what you expected**
- **Include environment details** - PHP version, Laravel version, OS, etc.

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion:

- **Use a clear and descriptive title**
- **Provide a detailed description of the suggested enhancement**
- **Explain why this enhancement would be useful**
- **Include code examples if applicable**

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Follow the coding standards** - We follow PSR-12 coding standards
3. **Write tests** - Add tests for any new functionality
4. **Update documentation** - Update the README.md if needed
5. **Ensure tests pass** - Run `composer test` before submitting
6. **Write a clear commit message** - Follow conventional commit format

#### Pull Request Process

```bash
# Fork and clone the repository
git clone https://github.com/your-username/laravel.git
cd laravel

# Create a new branch
git checkout -b feature/your-feature-name

# Install dependencies
composer install

# Make your changes and add tests
# ...

# Run tests to ensure everything works
composer test

# Commit your changes
git add .
git commit -m "feat: add your feature description"

# Push to your fork
git push origin feature/your-feature-name

# Open a Pull Request on GitHub
```

## Development Setup

### Requirements

- PHP 8.1 or higher
- Composer
- Laravel 9.x, 10.x, or 11.x

### Installation

```bash
# Clone your fork
git clone https://github.com/your-username/laravel.git
cd laravel

# Install dependencies
composer install
```

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage
```

### Coding Standards

We follow PSR-12 coding standards. You can check your code style with:

```bash
# Format code (if you have PHP-CS-Fixer installed)
vendor/bin/php-cs-fixer fix
```

## Project Structure

```
src/
├── Commands/              # Artisan commands
├── Formatters/           # Output format generators (JSON, HTML, Markdown)
├── Http/Controllers/     # HTTP controllers (if any)
├── Laravel/              # Laravel-specific implementations
├── Intent.php            # Core Intent class
├── IntentRegistry.php    # Intent storage
└── IntentDocServiceProvider.php  # Laravel service provider

tests/
├── Feature/              # Feature tests
├── Unit/                 # Unit tests
└── TestCase.php          # Base test case
```

## Commit Message Guidelines

We follow [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` - A new feature
- `fix:` - A bug fix
- `docs:` - Documentation changes
- `style:` - Code style changes (formatting, missing semi-colons, etc.)
- `refactor:` - Code refactoring
- `test:` - Adding or updating tests
- `chore:` - Maintenance tasks

Examples:

```
feat: add support for custom formatters
fix: resolve duplicate intent registration issue
docs: update installation instructions
test: add tests for IntentRegistry
```

## Release Process

Releases are handled by maintainers. We follow [Semantic Versioning](https://semver.org/):

- **Major version** - Incompatible API changes
- **Minor version** - New functionality (backwards-compatible)
- **Patch version** - Bug fixes (backwards-compatible)

## Questions?

Feel free to open an issue with the `question` label if you have any questions about contributing.

## License

By contributing to IntentDoc, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to IntentDoc!
