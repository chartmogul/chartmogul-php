#!/bin/bash
# PostToolUse hook (Edit|Write): run php-cs-fixer on the single edited file.
# Must be fast - no tests, no full-codebase lint.

INPUT=$(cat)
FILE=$(echo "$INPUT" | jq -r '.tool_input.file_path // empty')

if [[ "$FILE" == *.php ]]; then
  if docker image inspect chartmogulphp82 &>/dev/null 2>&1; then
    docker run --rm -w /src -v "$(pwd):/src" chartmogulphp82 \
      bash -c "vendor/bin/php-cs-fixer fix --no-interaction --quiet '$FILE' 2>/dev/null"
  elif [ -x vendor/bin/php-cs-fixer ]; then
    vendor/bin/php-cs-fixer fix --no-interaction --quiet "$FILE" 2>/dev/null
  fi
fi

exit 0
