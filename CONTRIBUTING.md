# Contributing to CoinMarketCap API Client

First off, thank you for considering contributing to this project! It's people like you that make this package better for everyone.

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues as you might find out that you don't need to create one. When you are creating a bug report, please include as many details as possible:

- Use a clear and descriptive title
- Describe the exact steps which reproduce the problem
- Provide specific examples to demonstrate the steps
- Describe the behavior you observed after following the steps
- Explain which behavior you expected to see instead and why
- Include code samples and error messages

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, please include:

- Use a clear and descriptive title
- Provide a step-by-step description of the suggested enhancement
- Provide specific examples to demonstrate the steps
- Describe the current behavior and explain which behavior you expected to see instead
- Explain why this enhancement would be useful

### Pull Requests

- Fill in the required template
- Follow the PSR-12 coding style
- Include thoughtfully-worded, well-structured tests
- Document new code based on the Documentation Styleguide
- End all files with a newline

## Development Process

1. Fork the repo and create your branch from `main`
2. Install dependencies: `composer install`
3. Make your changes
4. Add tests for your changes
5. Run the test suite: `composer test`
6. Run static analysis: `composer phpstan`
7. Fix code style: `composer cs-fix`
8. Commit your changes
9. Push to your fork and submit a pull request

## Coding Standards

- Follow PSR-12 coding standards
- Use strict types: `declare(strict_types=1);`
- Use type hints for all parameters and return types
- Use readonly properties where appropriate
- Write PHPDoc blocks for all classes and methods
- Keep methods focused and single-purpose
- Write descriptive variable and method names

## Testing

- Write tests for all new features
- Ensure all tests pass before submitting PR
- Aim for high test coverage
- Use descriptive test method names
- Follow the Arrange-Act-Assert pattern

## Documentation

- Update README.md if needed
- Update CHANGELOG.md following Keep a Changelog format
- Add PHPDoc blocks to all public methods
- Include code examples for new features

## Git Commit Messages

- Use the present tense ("Add feature" not "Added feature")
- Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit the first line to 72 characters or less
- Reference issues and pull requests liberally after the first line

## Questions?

Feel free to contact the maintainer at sovletig@gmail.com

Thank you for your contributions!
