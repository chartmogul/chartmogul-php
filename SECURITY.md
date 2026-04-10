# Security Policy

## Reporting Vulnerabilities

Please report security vulnerabilities via the **"Report a vulnerability"** button on the [Security](https://github.com/chartmogul/chartmogul-php/security) tab of this repository. Do not use public GitHub issues.

## Supported Versions

| Version | Supported |
|---------|-----------|
| 6.x     | Yes       |
| 5.x     | Security fixes only |
| < 5.0   | No        |

## Release Integrity

Every release follows this process:

1. A `v*` tag is pushed to the repository.
2. CI validates that the tag version matches the `$apiVersion` in `src/Http/Client.php` — a mismatch fails the workflow.
3. A [GitHub Release](https://github.com/chartmogul/chartmogul-php/releases) is automatically created with changelog notes extracted from `CHANGELOG.md`.
4. Packagist syncs from the tagged commit.

**Tag protection:** Release tags (`v*`) are protected by GitHub rulesets that block force pushes and deletions. This prevents published versions from being silently replaced.

## Verifying Installations

After installing or updating, you can verify package integrity:

- **Check the locked commit hash:**
  ```sh
  composer show --locked chartmogul/chartmogul-php
  ```
  Compare the `source` reference against the tagged commit on GitHub.

- **Audit dependencies for known vulnerabilities:**
  ```sh
  composer audit
  ```

- **Commit `composer.lock`** to version control so all environments use identical dependency trees.

## Dependency Security

This project runs `composer audit` in CI to detect known vulnerabilities. Dependency updates are reviewed before merging.
