# Environment (facts)
- `gh` CLI at /usr/bin/gh. **Auth state: the token is invalid**; the human must re-authenticate before the run. The protocol must also work, with reduced coverage, unauthenticated where the repo is public. That branch must be declared.
- `curl` and `jq` are available (assumed standard; the script must check them and refuse if absent).
- The Linux shell is bash. The session scratchpad is outside the repo.
- **No write API call is permitted:** no POST, PATCH, PUT or DELETE, and no `gh` subcommand that mutates (`gh pr`, `gh run rerun`, `gh repo edit` …). Only `gh api -X GET` / `curl -X GET`.
- **Rate limits:** unauthenticated 60/h, authenticated 5000/h. The protocol must budget its calls (≤ ~40).
