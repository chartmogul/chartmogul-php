#!/bin/bash
# Stop hook: batch-format PHP files touched this turn, run test suite, report issues.
# Tests are fast (~2s) so we include them - failures are reported as context, not blocking.
cd "$CLAUDE_PROJECT_DIR" 2>/dev/null || exit 0

TRACKER="/tmp/claude-php-edited-files"
[ -f "$TRACKER" ] || exit 0

# Dedup the tracked files, skip any that no longer exist
FILES=$(sort -u "$TRACKER" | while read -r f; do [ -f "$f" ] && echo "$f"; done)
[ -z "$FILES" ] && rm -f "$TRACKER" && exit 0

CTX=""

# Autoformat with php-cs-fixer
if docker image inspect chartmogulphp82 &>/dev/null 2>&1; then
  DOCKER="docker run --rm -w /src -v $(pwd):/src -v $HOME/.composer/cache:/root/.composer/cache chartmogulphp82"
  FILE_ARGS=$(echo "$FILES" | tr '\n' ' ')
  $DOCKER bash -c "vendor/bin/php-cs-fixer fix --no-interaction --quiet $FILE_ARGS" 2>/dev/null || true

  # Check for remaining lint issues
  LINT_OUTPUT=$($DOCKER bash -c "vendor/bin/php-cs-fixer fix --dry-run --no-interaction $FILE_ARGS 2>&1")
  if [ $? -ne 0 ]; then
    REMAINING=$(echo "$LINT_OUTPUT" | grep -E "^\s+\d+\)" | head -10)
    [ -n "$REMAINING" ] && CTX="php-cs-fixer: unfixed style issues:\n$REMAINING"
  fi

  # Run test suite (~2s, cheap enough to include every turn)
  TEST_OUTPUT=$($DOCKER phpunit 2>&1)
  if [ $? -ne 0 ]; then
    # Extract just the summary and failure lines, keep it short
    FAILURES=$(echo "$TEST_OUTPUT" | grep -A2 -E "^(ERRORS!|FAILURES!|There was|There were)" | head -15)
    [ -n "$FAILURES" ] && CTX="$CTX\nphpunit: test failures:\n$FAILURES"
  fi
elif [ -x vendor/bin/php-cs-fixer ]; then
  echo "$FILES" | xargs vendor/bin/php-cs-fixer fix --no-interaction --quiet 2>/dev/null || true

  LINT_OUTPUT=$(echo "$FILES" | xargs vendor/bin/php-cs-fixer fix --dry-run --no-interaction 2>&1)
  if [ $? -ne 0 ]; then
    REMAINING=$(echo "$LINT_OUTPUT" | grep -E "^\s+\d+\)" | head -10)
    [ -n "$REMAINING" ] && CTX="php-cs-fixer: unfixed style issues:\n$REMAINING"
  fi

  if command -v phpunit &>/dev/null; then
    TEST_OUTPUT=$(phpunit 2>&1)
    if [ $? -ne 0 ]; then
      FAILURES=$(echo "$TEST_OUTPUT" | grep -A2 -E "^(ERRORS!|FAILURES!|There was|There were)" | head -15)
      [ -n "$FAILURES" ] && CTX="$CTX\nphpunit: test failures:\n$FAILURES"
    fi
  fi
fi

# Clear tracker for next turn
rm -f "$TRACKER"

# Only report if there are issues - silent on clean runs
if [ -n "$CTX" ]; then
  jq -n --arg ctx "$CTX" '{"hookSpecificOutput":{"hookEventName":"Stop","additionalContext":$ctx}}'
fi

exit 0
