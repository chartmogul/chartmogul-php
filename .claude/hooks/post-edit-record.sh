#!/bin/bash
# PostToolUse (Edit|Write|MultiEdit): record edited file path for batch processing in Stop.
# Must be <50ms. No formatting, no linting, no git calls.
cd "$CLAUDE_PROJECT_DIR" 2>/dev/null || exit 0

INPUT=$(cat)
FILE=$(echo "$INPUT" | jq -r '.tool_input.file_path // empty')
[ -z "$FILE" ] && exit 0

# Skip generated/vendored paths
case "$FILE" in
  vendor/*|*/vendor/*|coverage/*|*/coverage/*|node_modules/*|*/node_modules/*|dist/*|*/dist/*) exit 0 ;;
esac

# Only track PHP source files
[[ "$FILE" == *.php ]] || exit 0

# Append to tracker (dedup happens at read time in Stop hook)
echo "$FILE" >> /tmp/claude-php-edited-files

exit 0
