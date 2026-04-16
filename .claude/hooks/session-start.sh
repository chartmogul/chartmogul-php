#!/bin/bash
# SessionStart: fast, local-only environment check. Must be <1s and idempotent.
# No network calls, no installs, no docker run - just detect and report.
cd "$CLAUDE_PROJECT_DIR" || exit 0

# Generate a stable session ID and persist via CLAUDE_ENV_FILE
# so the edit tracker and stop hook share the same file path
SESSION_ID="$(date +%s)-$$"
if [[ -n "$CLAUDE_ENV_FILE" ]]; then
  echo "export CLAUDE_HOOK_SESSION_ID='$SESSION_ID'" >> "$CLAUDE_ENV_FILE"
fi

# Clear stale file tracker from previous session
rm -f /tmp/claude-edited-php-files-* 2>/dev/null

ctx=""

# Lockfile freshness
if [[ ! -f composer.lock ]]; then
  ctx+="composer.lock missing - run composer install.\n"
elif [[ composer.json -nt composer.lock ]]; then
  ctx+="composer.lock is stale - run composer install before testing.\n"
fi

# Vendor directory
if [[ ! -d vendor ]]; then
  ctx+="vendor/ missing - run composer install.\n"
fi

# Warn about uncommitted changes
dirty=$(git diff --name-only 2>/dev/null | head -5)
if [[ -n "$dirty" ]]; then
  ctx+="Uncommitted changes:\n$dirty\n"
fi

# Report current branch
branch=$(git branch --show-current 2>/dev/null)
if [[ -n "$branch" ]]; then
  ctx+="Branch: $branch\n"
fi

# Docker image presence (inspect is local metadata, no container started)
if command -v docker &>/dev/null && docker info &>/dev/null 2>&1; then
  if docker image inspect chartmogulphp82 &>/dev/null 2>&1; then
    ctx+="Docker image chartmogulphp82: available\n"
  else
    ctx+="Docker image chartmogulphp82 not built. Run: make build\n"
  fi
else
  ctx+="Docker not available. Docker-based commands (make test/lint/analyse) will not work.\n"
fi

if [[ -n "$ctx" ]]; then
  jq -n --arg ctx "$ctx" '{
    "hookSpecificOutput": {
      "hookEventName": "SessionStart",
      "additionalContext": $ctx
    }
  }'
fi

exit 0
