#!/bin/bash
# PostToolUse (Edit|Write|MultiEdit): record edited file path for batch processing in Stop.
# Must be <50ms. No formatting, no linting, no git calls.
cd "$CLAUDE_PROJECT_DIR" || exit 0

INPUT=$(cat)
FILE=$(echo "$INPUT" | jq -r '.tool_input.file_path // empty')

# Only track .php files, skip vendored/generated paths
if [[ "$FILE" == *.php ]] && [[ "$FILE" != vendor/* ]] && [[ "$FILE" != */vendor/* ]] && [[ "$FILE" != coverage/* ]] && [[ "$FILE" != */coverage/* ]]; then
  TRACKER="/tmp/claude-edited-php-files-${CLAUDE_HOOK_SESSION_ID:-default}"
  echo "$FILE" >> "$TRACKER"
  sort -u "$TRACKER" -o "$TRACKER"
fi

exit 0
