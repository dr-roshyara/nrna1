#!/usr/bin/env bash
# Records every Claude Code session in readme-session-id.md at the repository root.
# SessionStart: one "start" row (id, time, folder, how it started).
# SessionEnd:   one "end" row (id, time, why it ended, the session's first prompt as "worked on").
# Reads the hook's JSON from stdin. Never blocks a session: all errors are swallowed by the caller.
set -u
event="${1:-}"
root="${CLAUDE_PROJECT_DIR:-$(pwd)}"
log="$root/readme-session-id.md"
input="$(cat)"

sid="$(printf '%s' "$input" | jq -r '.session_id // "unknown"')"
cwd="$(printf '%s' "$input" | jq -r '.cwd // ""')"
transcript="$(printf '%s' "$input" | jq -r '.transcript_path // ""')"
now="$(date -u +%Y-%m-%dT%H:%M:%SZ)"
rel="${cwd#"$root"}"; rel="${rel:-/}"

clean() { tr '\n\r|' '   ' | sed 's/  */ /g' | cut -c1-"$1"; }

if [ ! -s "$log" ]; then
  cat > "$log" <<'EOF'
# Claude Code sessions in this repository

Written automatically by `.claude/scripts/session-id-log.sh` (SessionStart and SessionEnd hooks). Resume a session with `claude --resume <session id>` from the folder shown.

| Time (UTC) | Event | Session id | Folder | Detail / worked on |
|---|---|---|---|---|
EOF
fi

case "$event" in
  start)
    src="$(printf '%s' "$input" | jq -r '.source // ""')"
    printf '| %s | start (%s) | `%s` | `%s` | |\n' "$now" "$src" "$sid" "$rel" >> "$log"
    ;;
  end)
    reason="$(printf '%s' "$input" | jq -r '.reason // ""')"
    topic=""
    if [ -n "$transcript" ] && [ -r "$transcript" ]; then
      topic="$(jq -r 'select(.type=="user" and (.isMeta|not)) | .message.content
                 | if type=="string" then . else (map(select(.type=="text") | .text) | join(" ")) end
                 | gsub("[\\n\\r]+"; " ")' \
               "$transcript" 2>/dev/null \
               | command grep -v -E '^\s*$|^<(command|local-command|system-reminder)' | head -1 | clean 140)"
    fi
    printf '| %s | end (%s) | `%s` | `%s` | %s |\n' "$now" "$reason" "$sid" "$rel" "$topic" >> "$log"
    ;;
esac
exit 0
