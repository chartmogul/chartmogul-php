#!/bin/bash
# SessionStart: fast, local-only environment check. Must be <1s and idempotent.
# No network calls, no installs, no docker run - just detect and report.
cd "$CLAUDE_PROJECT_DIR" 2>/dev/null || cd "$(dirname "$0")/../.." || exit 0

WARNINGS=""
INFO=""

# Git branch
BRANCH=$(git branch --show-current 2>/dev/null)
[ -n "$BRANCH" ] && INFO="$INFO\n- Branch: $BRANCH"

# Uncommitted changes
DIRTY=$(git status --porcelain 2>/dev/null | head -5)
if [ -n "$DIRTY" ]; then
  COUNT=$(echo "$DIRTY" | wc -l | tr -d ' ')
  WARNINGS="$WARNINGS\n- $COUNT uncommitted file(s)"
fi

# Lockfile freshness: composer.lock should exist if composer.json does
if [ -f composer.json ] && [ ! -f composer.lock ] && [ ! -d vendor ]; then
  WARNINGS="$WARNINGS\n- composer.lock and vendor/ missing. Run: composer install"
elif [ -f composer.json ] && [ ! -d vendor ]; then
  WARNINGS="$WARNINGS\n- vendor/ missing. Run: composer install"
fi

# Docker image presence (inspect is local metadata, no container started)
if command -v docker &>/dev/null && docker info &>/dev/null 2>&1; then
  if docker image inspect chartmogulphp82 &>/dev/null 2>&1; then
    INFO="$INFO\n- Docker image chartmogulphp82: available"
  else
    WARNINGS="$WARNINGS\n- Docker image chartmogulphp82 not built. Run: make build"
  fi
else
  WARNINGS="$WARNINGS\n- Docker not available. Docker-based commands (make test/lint/analyse) will not work."
fi

# Clear stale file tracker from previous session
rm -f /tmp/claude-php-edited-files 2>/dev/null

# Emit context
CTX=""
[ -n "$WARNINGS" ] && CTX="Warnings:$WARNINGS"
[ -n "$INFO" ] && CTX="$CTX\nEnvironment:$INFO"

if [ -n "$CTX" ]; then
  echo -e "$CTX"
fi

exit 0
