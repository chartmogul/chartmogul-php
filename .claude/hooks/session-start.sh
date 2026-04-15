#!/bin/bash
# SessionStart hook: verify development environment, auto-fix what we can, report status.
# Output is injected as context into the Claude Code session.

WARNINGS=""
INFO=""

# 1. Docker available?
if ! command -v docker &>/dev/null; then
  WARNINGS="$WARNINGS\n- Docker is not installed or not in PATH. Docker-based commands (make test, make lint, make analyse) will not work."
elif ! docker info &>/dev/null 2>&1; then
  WARNINGS="$WARNINGS\n- Docker daemon is not running. Start Docker Desktop to use Docker-based commands."
else
  INFO="$INFO\n- Docker: available"

  # 2. Docker image built? Build if missing.
  if ! docker image inspect chartmogulphp82 &>/dev/null 2>&1; then
    INFO="$INFO\n- Docker image chartmogulphp82 not found, building..."
    if docker build --build-arg VERSION=8.2 --tag=chartmogulphp82 . &>/dev/null; then
      INFO="$INFO\n- Docker image chartmogulphp82: built successfully"
    else
      WARNINGS="$WARNINGS\n- Failed to build Docker image chartmogulphp82. Run 'make build' manually."
    fi
  fi

  if docker image inspect chartmogulphp82 &>/dev/null 2>&1; then
    PHP_VERSION=$(docker run --rm chartmogulphp82 php --version 2>/dev/null | head -1)
    PHPUNIT_VERSION=$(docker run --rm chartmogulphp82 phpunit --version 2>/dev/null | head -1)
    INFO="$INFO\n- $PHP_VERSION"
    INFO="$INFO\n- $PHPUNIT_VERSION"

    # 3. Composer dependencies - install inside container if missing.
    if [ ! -d vendor ]; then
      INFO="$INFO\n- vendor/ missing, running composer install..."
      if docker run --rm -w /src -v "$(pwd):/src" -v "$HOME/.composer/cache:/root/.composer/cache" chartmogulphp82 composer install --no-interaction --quiet 2>/dev/null; then
        INFO="$INFO\n- Composer dependencies: installed"
      else
        WARNINGS="$WARNINGS\n- composer install failed. Run 'make composer install' manually."
      fi
    else
      INFO="$INFO\n- Composer dependencies: installed"
    fi
  fi
fi

# Build output
OUTPUT=""
if [ -n "$WARNINGS" ]; then
  OUTPUT="Environment warnings:$WARNINGS"
fi
if [ -n "$INFO" ]; then
  OUTPUT="$OUTPUT\nEnvironment:$INFO"
fi

if [ -n "$OUTPUT" ]; then
  echo -e "$OUTPUT"
fi

exit 0
