# Contributing

Contributions are welcome and appreciated. Please follow the guidelines below to help keep the project consistent and maintainable.

## Reporting Issues

When reporting an issue, please include:
- PHP and Laravel version
- Steps to reproduce
- Expected vs. actual behavior
- Relevant error messages or stack traces, if applicable

## Pull Requests

- Follow PSR-12 / this project's existing code style — run `composer format` before committing (a CI check will fail the PR otherwise).
- Add tests for any new behavior. Run `composer test` and `composer analyse` locally before opening a PR — both must pass.
- One feature/fix per pull request, please.
- Update `CHANGELOG.md` under `[Unreleased]` for any user-facing change.

## Running tests locally

```bash
composer test
composer analyse
composer format
```

Note: `composer test` includes an exhaustive round-trip check across the full supported date range (~36,700 dates), so it's slower than typical package test suites.