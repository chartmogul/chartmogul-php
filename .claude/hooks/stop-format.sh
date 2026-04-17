#!/bin/bash
# Stop hook: batch-format PHP files touched this turn, run test suite, report issues.
# Tests are fast (~2s) so we include them when files were edited.
# Failures are reported as context, not blocking.
cd "$CLAUDE_PROJECT_DIR" || exit 0

TRACKER="${TMPDIR:-/tmp}/claude-edited-php-files-${CLAUDE_HOOK_SESSION_ID:-default}"

# Skip entirely if no files were edited this turn
[[ -f "$TRACKER" ]] || exit 0

files=$(cat "$TRACKER")
rm -f "$TRACKER"
[[ -n "$files" ]] || exit 0

ctx=""

# Batch php-cs-fixer autocorrect on tracked files
if docker image inspect chartmogulphp82 &>/dev/null 2>&1; then
  file_args=$(echo "$files" | tr '\n' ' ')
  docker run --rm -w /src -v "$(pwd):/src" -v "$HOME/.composer/cache:/root/.composer/cache" chartmogulphp82 \
    bash -c "vendor/bin/php-cs-fixer fix --no-interaction --quiet $file_args" 2>/dev/null || true
  lint_out=$(docker run --rm -w /src -v "$(pwd):/src" -v "$HOME/.composer/cache:/root/.composer/cache" chartmogulphp82 \
    bash -c "vendor/bin/php-cs-fixer fix --dry-run --no-interaction $file_args 2>&1")
elif [[ -x vendor/bin/php-cs-fixer ]]; then
  echo "$files" | xargs vendor/bin/php-cs-fixer fix --no-interaction --quiet 2>/dev/null || true
  lint_out=$(echo "$files" | xargs vendor/bin/php-cs-fixer fix --dry-run --no-interaction 2>&1)
fi
offenses=$(echo "$lint_out" | grep -E "^\s+\d+\)" | head -20)
if [[ -n "$offenses" ]]; then
  ctx+="php-cs-fixer offenses remaining after autocorrect:\n$offenses\n"
fi

# Run tests - only when files were edited this turn (~2s)
if docker image inspect chartmogulphp82 &>/dev/null 2>&1; then
  test_out=$(docker run --rm -w /src -v "$(pwd):/src" -v "$HOME/.composer/cache:/root/.composer/cache" chartmogulphp82 \
    phpunit 2>&1)
  test_exit=$?
elif command -v phpunit &>/dev/null; then
  test_out=$(phpunit 2>&1)
  test_exit=$?
fi

if [[ ${test_exit:-0} -ne 0 ]]; then
  summary=$(echo "$test_out" | grep -E "Tests: [0-9]+" | tail -1)
  failed=$(echo "$test_out" | grep -A2 -E "^(ERRORS!|FAILURES!|There was|There were)" | head -10)
  ctx+="phpunit failed ($summary):\n$failed\n"
fi

if [[ -n "$ctx" ]]; then
  jq -n --arg ctx "$ctx" '{
    "hookSpecificOutput": {
      "hookEventName": "Stop",
      "additionalContext": $ctx
    }
  }'
fi

exit 0
