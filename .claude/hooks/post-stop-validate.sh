#!/bin/bash
# Stop hook: runs full linter, static analysis, and test suite.
# Fires once after Claude finishes responding.
# Exit code 2 blocks the turn so Claude can fix issues.

ERRORS=""

if ! docker image inspect chartmogulphp82 &>/dev/null 2>&1; then
  echo '{"decision":"allow","additionalContext":"Skipped validation: Docker image chartmogulphp82 not available."}'
  exit 0
fi

DOCKER="docker run --rm -w /src -v $(pwd):/src -v $HOME/.composer/cache:/root/.composer/cache chartmogulphp82"

# Ensure dependencies are installed
$DOCKER composer install --no-interaction --quiet 2>/dev/null

# 1. Full codebase lint
LINT_OUTPUT=$($DOCKER vendor/bin/php-cs-fixer fix --dry-run --no-interaction src 2>&1)
LINT_EXIT=$?
if [ $LINT_EXIT -ne 0 ]; then
  ERRORS="$ERRORS\n## php-cs-fixer (lint)\n$LINT_OUTPUT"
fi

# 2. Static analysis
PHPSTAN_OUTPUT=$($DOCKER bash -c "composer global require --quiet phpstan/phpstan && phpstan analyse 2>&1")
PHPSTAN_EXIT=$?
if [ $PHPSTAN_EXIT -ne 0 ]; then
  ERRORS="$ERRORS\n## phpstan (static analysis)\n$PHPSTAN_OUTPUT"
fi

# 3. Full test suite
TEST_OUTPUT=$($DOCKER phpunit 2>&1)
TEST_EXIT=$?
if [ $TEST_EXIT -ne 0 ]; then
  ERRORS="$ERRORS\n## phpunit (tests)\n$TEST_OUTPUT"
fi

if [ -n "$ERRORS" ]; then
  REASON=$(echo -e "Validation failed:$ERRORS" | head -c 4000)
  echo "{\"decision\":\"block\",\"reason\":$(echo "$REASON" | jq -Rs .)}"
  exit 2
fi

exit 0
