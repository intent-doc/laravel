# Compatibility Matrix

This matrix shows the results of tests executed locally with Docker.

> **Legend:**
> - ✓ = Tests passed
> - ✗ = Tests failed
> - ⊘ = Unsupported combination

| PHP Version | Laravel 10.* | Laravel 11.* | Laravel 12.* |
|-------------|--------------|--------------|--------------|
| **8.2** | ✓ | ✓ | ✓ |
| **8.3** | ✓ | ✓ | ✓ |
| **8.4** | ✓ | ✓ | ✓ |

---

**Last updated:** 2026-01-14 01:43:31

**Detailed logs:** Check the `test-results/` directory for complete logs of each test.

## How to run the tests

```bash
# Run all tests
./run-tests-docker.sh

# View logs from a specific test
cat test-results/php-8.3-laravel-11.x.log
```
