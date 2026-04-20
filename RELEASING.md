# Releasing chartmogul-php

## Prerequisites

- You must have push access to the repository
- `git`, `gh`, `jq`, and `php` must be installed
- Tags matching `v*` are protected by GitHub tag protection rulesets
- Releases are immutable once published (GitHub repository setting)

## Release Process

Run the release script from the repository root:

```sh
bin/release.sh <patch|minor|major>
```

The script will:

1. Verify prerequisites and that CI is green on `main`
2. Show any open PRs targeting `main` and ask for confirmation
3. Show PRs merged since the last tag (what's being released) and ask for confirmation
4. Bump the version in `src/Http/Client.php`
5. Stash uncommitted work, create a release branch, commit, push, and open a PR
6. Wait for the PR to be merged (poll every 10s)
7. Tag the merge commit and push the tag
8. Wait for the [release workflow](.github/workflows/release.yml) to complete, which will:
   - Run the full test suite across PHP 8.0, 8.1, and 8.4
   - Verify that `Client.php` version matches the tag
   - Create a GitHub Release with auto-generated release notes
9. Print a link to the GitHub Release

## Changelog

Release notes are auto-generated from merged PR titles by the [release workflow](.github/workflows/release.yml). To ensure useful changelogs:

- Use clear, descriptive PR titles (e.g., "Add External ID field to Contact model")
- Prefix breaking changes with `BREAKING:` so they stand out in release notes
- After the release is created, review and edit the notes on the [Releases page](https://github.com/chartmogul/chartmogul-php/releases) if needed

## Pre-release Versions

For pre-release versions, use a semver pre-release suffix:

```sh
git tag vX.Y.Z-rc1
git push origin vX.Y.Z-rc1
```

These will be automatically marked as pre-releases on GitHub.

## Security

### Repository Protections

- **Immutable releases**: Once a GitHub Release is published, its tag cannot be moved or deleted, and release assets cannot be modified
- **Tag protection rulesets**: `v*` tags cannot be deleted or force-pushed

### Packagist Registry

- Packagist pulls releases directly from GitHub tags - there are no separate publish credentials to protect
- Composer records content hashes in `composer.lock` for all installed packages, ensuring reproducible and tamper-evident installs

### What This Protects Against

- A compromised maintainer account cannot modify or delete existing releases
- Tags cannot be moved to point to different commits after publication
- Since Packagist reads directly from GitHub, there are no long-lived publish tokens to leak

### Repository Settings (Admin)

These settings must be configured by a repository admin:

1. **Immutable Releases**: Settings > General > Releases > Enable "Immutable releases"
2. **Tag Protection Ruleset**: Settings > Rules > Rulesets > New ruleset targeting tags matching `v*` with deletion, force-push, and update prevention
