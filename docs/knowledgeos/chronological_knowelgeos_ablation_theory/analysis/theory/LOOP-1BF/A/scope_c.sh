#!/usr/bin/env bash
# scope_c.sh: Protocol C (PROTOCOL-C.md). Bounded, read-only (HTTP GET only) host-side scoping of one commit.
# Raw responses stay in a private temp dir; only identity-free derived fields reach OUT.
# Usage: scope_c.sh OUT_DIR WORKTREE_DIR
# Exit: 0 ok | 2 precondition | 3 refused path | 4 budget hit (partial output) | 5 identity guard | 6 write failure
SELF="$(readlink -f -- "${BASH_SOURCE[0]}")" || exit 2   # resolved before any cd
export LC_ALL=C TZ=UTC; umask 077
REPO="dr-roshyara/nrna1"; SHA="e4724a444231681f1a455ffaeea488a554183093"
T0="2026-08-04T00:30:25Z"; WF="2026-08-03T00:00:00Z"; WE="2026-08-06T00:00:00Z"
BUDGET=40; PR_CAP=5; EV_PAGES=3; API="https://api.github.com"
CHANNELS="commit prs reviews timeline checks status runs protection branch_rules rulesets events"

[ $# -eq 2 ] || { echo "usage: ${SELF##*/} OUT_DIR WORKTREE_DIR" >&2; exit 2; }
for t in jq curl sha256sum readlink mktemp awk grep cut wc; do
  command -v "$t" >/dev/null 2>&1 || { echo "refused: '$t' not found" >&2; exit 2; }; done
WTREE="$(readlink -f -- "$2")"
[ -n "$WTREE" ] && [ -d "$WTREE" ] || { echo "refused: WORKTREE_DIR is not a directory" >&2; exit 2; }
OUT="$(readlink -m -- "$1")"
inside() {   # PATH under WORKTREE_DIR, or its nearest existing ancestor inside any git work tree
  case "$1/" in "$WTREE"/*) return 0 ;; esac
  local a="$1"; while [ ! -d "$a" ]; do a="${a%/*}"; [ -n "$a" ] || a=/; done
  command -v git >/dev/null 2>&1 && git -C "$a" rev-parse --is-inside-work-tree >/dev/null 2>&1; }
[ -e "$OUT" ] && { echo "refused: OUT exists (no overwrite)" >&2; exit 3; }
inside "$OUT" && { echo "refused: OUT is inside a work tree" >&2; exit 3; }
TMP="$(mktemp -d "${TMPDIR:-/tmp}/scope_c.XXXXXXXX")" || exit 6
cleanup() { case "${TMP##*/}" in scope_c.????????) [ -d "$TMP" ] && rm -rf -- "$TMP" ;; esac; }
trap cleanup EXIT
inside "$TMP" && { echo "refused: TMPDIR is inside a work tree" >&2; exit 3; }
R="$TMP/raw"; S="$TMP/stage"; mkdir "$R" "$S" || exit 6

N=0; HIT=0; MODE=curl
printf 'seq\tchannel\tendpoint\thttp_status\n' > "$S/calls.tsv"
get() {   # get KEY CHANNEL PATH: one GET -> $R/KEY.json + $R/KEY.status; returns 1 once the budget is spent
  local k="$1" p="$3" s="" h=(-H "Accept: application/vnd.github+json" -H "X-GitHub-Api-Version: 2022-11-28")
  if [ "$N" -ge "$BUDGET" ]; then HIT=1; echo 000 > "$R/$k.status"; echo '{}' > "$R/$k.json"; return 1; fi
  N=$((N + 1))
  if [ "$MODE" = gh ]; then
    gh api -X GET -i "${h[@]}" "$p" > "$R/$k.http" 2>/dev/null
    s="$(head -n1 "$R/$k.http" | awk '{print $2}')"; awk 'b{print; next} /^\r?$/{b=1}' "$R/$k.http" > "$R/$k.json"
  else s="$(curl -sS -X GET -o "$R/$k.json" -w '%{http_code}' "${h[@]}" "$API/$p" 2>/dev/null)"; fi
  echo "${s:-000}" > "$R/$k.status"
  jq -e . "$R/$k.json" >/dev/null 2>&1 || echo '{}' > "$R/$k.json"
  printf '%s\t%s\t%s\t%s\n' "$N" "$2" "${p//"$REPO"/REPO}" "${s:-000}" >> "$S/calls.tsv"; }
st() { cat "$R/$1.status" 2>/dev/null || echo 000; }
agg() { local s; for s in ${1//,/ }; do [ "$s" = 200 ] || { echo "$s"; return; }; done; echo 200; }
exist() { if [ "$1" != 200 ]; then echo NOT_ACCESSIBLE
  elif [ "${2:-0}" -gt 0 ] 2>/dev/null; then echo EXISTS; else echo NOT_EVIDENCED_ON_HOST; fi; }
printf 'channel\thttp\texistence\tts_generator\tnorm_state_at_t\tindependence\tnote\n' > "$S/channels.tsv"
row() { printf '%s\t%s\t%s\t%s\tNOT_DETERMINABLE\tINDEPENDENCE_NOT_DETERMINABLE\t%s\n' "$@" >> "$S/channels.tsv"; }
kv() { local f="$S/ch_$1.tsv"; [ -e "$f" ] || printf 'key\tvalue\n' > "$f"; cat >> "$f"; }
kvget() { awk -F'\t' -v k="$2" '$1==k{print $2}' "$S/ch_$1.tsv" 2>/dev/null; }
JQD='def arr: if type == "array" then . else [] end;
def inw: (. // "") as $x | $x != "" and $x >= $wf and $x < $we;
def le_t: (. // "") as $x | $x != "" and $x <= $t;
def eq(a; b): if (a // "") == "" or (b // "") == "" then "NOT_DETERMINABLE" elif a == b then "true" else "false" end;
def cnt(f): if $ca == "" then "NOT_DETERMINABLE" else (map(select(f == $ca)) | length) end;
def tally(f): map(f // "none" | tostring) | group_by(.) | map("\(.[0])=\(length)") | join(",");'
jqn() { local f="$1"; shift   # jqn FILTER [jq options]; $ca/$pa (roles) live only in memory
  jq -nr --arg ca "${CA:-}" --arg pa "${PA:-}" --arg t "$T0" --arg wf "$WF" --arg we "$WE" "$@" "$JQD $f"; }

scan() {
  get commit commit "repos/$REPO/commits/$SHA" || return
  CA="$(jq -r '.author.login // empty' "$R/commit.json")"
  jqn '$x[0] | "identity_mapping_available\t\($ca != "")", "author_eq_committer\t\(eq(.author.login; .committer.login))",
    "author_date\t\(.commit.author.date // "")", "committer_date\t\(.commit.committer.date // "")",
    "signature_verified\t\(.commit.verification.verified | tostring)",
    "verification_reason\t\(.commit.verification.reason // "none")"' --slurpfile x "$R/commit.json" | kv commit
  row commit "$(st commit)" "$(exist "$(st commit)" 1)" "author.date,committer.date=CLIENT_SUPPLIED" "role mapping source only"
  jqn '$x[0] | "owner_eq_commit_author\t\(eq(.owner.login; $ca))"' --slurpfile x "$R/repo.json" | kv repository

  get pulls prs "repos/$REPO/commits/$SHA/pulls?per_page=$PR_CAP" || return
  mapfile -t PRS < <(jqn "\$x[0] | arr | map(.number) | sort | .[:$PR_CAP][]" --slurpfile x "$R/pulls.json")
  local npr=${#PRS[@]} n rv="" tl="" nrv ntl
  row prs "$(st pulls)" "$(exist "$(st pulls)" "$npr")" "created_at,merged_at=HOST_GENERATED" "n=$npr; cap_reached=$([ "$npr" -ge "$PR_CAP" ] && echo true || echo false)"
  printf 'pr\tstate\tmerged\tbase_ref\tcreated_at\tmerged_at\tmerged_le_t\tmerged_in_window\tpr_author_eq_commit_author\tmerger_eq_pr_author\tmerger_eq_commit_author\tn_reviews\tn_distinct_reviewers\tn_approvals\tn_approvals_le_t\tn_approvals_in_window\tn_approvals_by_pr_author\tn_approvals_by_commit_author\treviews_truncated\ttimeline_truncated\n' > "$S/prs.tsv"
  printf 'pr\tevent\tn\tn_in_window\tts_generator\n' > "$S/timeline.tsv"
  for n in "${PRS[@]}"; do
    get "pr$n" prs "repos/$REPO/pulls/$n" || break
    get "rv$n" reviews "repos/$REPO/pulls/$n/reviews?per_page=100" || break
    get "tl$n" timeline "repos/$REPO/issues/$n/timeline?per_page=100" || break
    rv="$rv${rv:+,}$(st "rv$n")"; tl="$tl${tl:+,}$(st "tl$n")"; PA="$(jq -r '.user.login // empty' "$R/pr$n.json")"
    jqn '($p[0]) as $p | ($v[0] | arr) as $v | ($l[0] | arr) as $l | ($v | map(select(.state == "APPROVED"))) as $a |
      [$p.number, $p.state, ($p.merged_at != null), ($p.base.ref // ""), $p.created_at, ($p.merged_at // ""),
       ($p.merged_at | le_t), ($p.merged_at | inw), eq($pa; $ca), eq($p.merged_by.login; $pa), eq($p.merged_by.login; $ca),
       ($v | length), ($v | map(.user.login) | unique | length), ($a | length),
       ($a | map(select(.submitted_at | le_t)) | length), ($a | map(select(.submitted_at | inw)) | length),
       (if $pa == "" then "NOT_DETERMINABLE" else ($a | map(select(.user.login == $pa)) | length) end),
       ($a | cnt(.user.login)), (($v | length) >= 100), (($l | length) >= 100)] | map(tostring) | join("\t")' \
      --slurpfile p "$R/pr$n.json" --slurpfile v "$R/rv$n.json" --slurpfile l "$R/tl$n.json" >> "$S/prs.tsv"
    jqn '$l[0] | arr | group_by(.event) | .[] | .[0].event as $e | [$n, $e, length,
       (map(.created_at // .submitted_at // .author.date | inw) | map(select(.)) | length),
       (if $e == "committed" then "author.date=CLIENT_SUPPLIED" elif $e == "reviewed" then "submitted_at=HOST_GENERATED"
        else "created_at=HOST_GENERATED" end)] | map(tostring) | join("\t")' --arg n "$n" --slurpfile l "$R/tl$n.json" >> "$S/timeline.tsv"
  done
  PA=""; nrv="$(awk -F'\t' 'NR>1{s+=$12} END{print s+0}' "$S/prs.tsv")"; ntl="$(awk -F'\t' 'NR>1{s+=$3} END{print s+0}' "$S/timeline.tsv")"
  if [ "$npr" -eq 0 ]; then
    row reviews - NOT_EVIDENCED_ON_HOST "submitted_at=HOST_GENERATED" "not called: no associated PR"
    row timeline - NOT_EVIDENCED_ON_HOST "created_at=HOST_GENERATED; committed=CLIENT_SUPPLIED" "not called: no associated PR"
  elif [ -n "$rv" ]; then
    row reviews "$rv" "$(exist "$(agg "$rv")" "$nrv")" "submitted_at=HOST_GENERATED" "n=$nrv; see prs.tsv"
    row timeline "$tl" "$(exist "$(agg "$tl")" "$ntl")" "created_at=HOST_GENERATED; committed=CLIENT_SUPPLIED" "n=$ntl; see timeline.tsv"
  fi
  [ "$HIT" = 1 ] && return

  get checks checks "repos/$REPO/commits/$SHA/check-runs?per_page=100" || return
  jqn '($x[0].check_runs | arr) as $r | "total_count\t\($x[0].total_count // 0)", "n_returned\t\($r | length)",
    "n_app_github_actions\t\($r | map(select(.app.slug == "github-actions")) | length)",
    "n_started_le_t\t\($r | map(select(.started_at | le_t)) | length)",
    "n_started_in_window\t\($r | map(select(.started_at | inw)) | length)", "conclusions\t\($r | tally(.conclusion))"' \
    --slurpfile x "$R/checks.json" | kv checks
  local nc g=NOT_DETERMINABLE; nc="$(kvget checks n_returned)"
  [ "${nc:-0}" -gt 0 ] && [ "$nc" = "$(kvget checks n_app_github_actions)" ] && g=HOST_GENERATED
  row checks "$(st checks)" "$(exist "$(st checks)" "$nc")" "started_at,completed_at=$g" "app-written; HOST only if all by GitHub Actions"

  get status status "repos/$REPO/commits/$SHA/status" || return
  jqn '($x[0].statuses | arr) as $r | "state\t\($x[0].state // "none")", "total_count\t\($x[0].total_count // 0)",
    "n_created_le_t\t\($r | map(select(.created_at | le_t)) | length)", "n_created_in_window\t\($r | map(select(.created_at | inw)) | length)",
    "n_creator_eq_commit_author\t\($r | cnt(.creator.login))"' --slurpfile x "$R/status.json" | kv status
  row status "$(st status)" "$(exist "$(st status)" "$(kvget status total_count)")" "created_at=HOST_GENERATED" "latest status per context only"

  get runs runs "repos/$REPO/actions/runs?head_sha=$SHA&per_page=100" || return
  jqn '($x[0].workflow_runs | arr) as $r | "total_count\t\($x[0].total_count // 0)", "n_returned\t\($r | length)",
    "n_created_le_t\t\($r | map(select(.created_at | le_t)) | length)", "n_created_in_window\t\($r | map(select(.created_at | inw)) | length)",
    "events\t\($r | tally(.event))", "conclusions\t\($r | tally(.conclusion))", "n_actor_eq_commit_author\t\($r | cnt(.actor.login))",
    "n_triggering_actor_eq_commit_author\t\($r | cnt(.triggering_actor.login))"' --slurpfile x "$R/runs.json" | kv runs
  row runs "$(st runs)" "$(exist "$(st runs)" "$(kvget runs total_count)")" "created_at,run_started_at=HOST_GENERATED; head_commit.timestamp=CLIENT_SUPPLIED" "metadata only; logs not fetched"

  local base nb b pe hist="current state only; historic state not available"
  base="$(awk -F'\t' 'NR==2{print $4}' "$S/prs.tsv")"   # base branch of the lowest-numbered associated PR
  nb="$(awk -F'\t' 'NR>1 && $4!=""{print $4}' "$S/prs.tsv" | sort -u | wc -l)"
  if [ -z "$base" ]; then
    for c in protection branch_rules rulesets; do row "$c" - NOT_EVIDENCED_ON_HOST NOT_DETERMINABLE "not called: no associated PR, no base branch"; done
  else
    b="$(jq -rn --arg b "$base" '$b | @uri')"
    get prot protection "repos/$REPO/branches/$b/protection" || return
    jqn '$x[0] | "scope\tCURRENT_STATE_ONLY", "n_distinct_bases\t'"$nb"'", "required_pull_request_reviews\t\(.required_pull_request_reviews != null)",
      "required_approving_review_count\t\(.required_pull_request_reviews.required_approving_review_count // 0)",
      "enforce_admins\t\(.enforce_admins.enabled // false)", "required_status_checks\t\(.required_status_checks != null)",
      "required_signatures\t\(.required_signatures.enabled // false)", "push_restrictions\t\(.restrictions != null)",
      "allow_force_pushes\t\(.allow_force_pushes.enabled // false)"' --slurpfile x "$R/prot.json" | kv protection
    pe="$(exist "$(st prot)" 1)"
    [ "$(st prot)" = 404 ] && [ "$(jq -r '.message? // ""' "$R/prot.json")" = "Branch not protected" ] && pe=NOT_EVIDENCED_ON_HOST
    row protection "$(st prot)" "$pe" NOT_DETERMINABLE "$hist"
    get brules branch_rules "repos/$REPO/rules/branches/$b?per_page=100" || return
    jqn '$x[0] | arr | "scope\tCURRENT_STATE_ONLY", "n_active_rules\t\(length)", "types\t\(tally(.type))"' --slurpfile x "$R/brules.json" | kv branch_rules
    row branch_rules "$(st brules)" "$(exist "$(st brules)" "$(kvget branch_rules n_active_rules)")" NOT_DETERMINABLE "$hist"
    get rsets rulesets "repos/$REPO/rulesets?per_page=100&includes_parents=true" || return
    { printf 'id\ttarget\tenforcement\tcreated_at\tupdated_at\tupdated_le_t\n'
      jqn '$x[0] | arr | sort_by(.id) | .[] | [.id, .target, .enforcement, (.created_at // ""), (.updated_at // ""), (.updated_at | le_t)]
        | map(tostring) | join("\t")' --slurpfile x "$R/rsets.json"; } > "$S/rulesets.tsv"
    row rulesets "$(st rsets)" "$(exist "$(st rsets)" "$(($(wc -l < "$S/rulesets.tsv") - 1))")" "created_at,updated_at=HOST_GENERATED" "$hist"
  fi

  local p c o nw ee
  for ((p = 1; p <= EV_PAGES; p++)); do   # page only while the window start has not been reached
    get "ev$p" events "repos/$REPO/events?per_page=100&page=$p" || break
    [ "$(st "ev$p")" = 200 ] || break
    c="$(jq 'if type == "array" then length else 0 end' "$R/ev$p.json")"; o="$(jq -r '[.[]?.created_at?] | min // ""' "$R/ev$p.json")"
    [ "$c" -lt 100 ] && break; [[ "$o" < "$WF" ]] && break
  done
  [ "$(st ev1)" = 000 ] && return
  jq -s '[.[] | if type == "array" then .[] else empty end]' "$R"/ev?.json > "$R/ev_all.json"
  jqn '($x[0] | arr) as $a | ($a | map(select(.created_at | inw))) as $w | ($a | map(.created_at) | min // "") as $o |
    "n_returned\t\($a | length)", "oldest_created_at\t\($o)", "newest_created_at\t\($a | map(.created_at) | max // "")",
    "window_covered\t\($o != "" and $o < $wf)", "n_in_window\t\($w | length)", "types_in_window\t\($w | tally(.type))",
    "n_in_window_actor_eq_commit_author\t\($w | cnt(.actor.login))"' --slurpfile x "$R/ev_all.json" | kv events
  nw="$(kvget events n_in_window)"
  if [ "$(st ev1)" != 200 ]; then ee=NOT_ACCESSIBLE; elif [ "$(kvget events window_covered)" = true ]; then ee="$(exist 200 "$nw")"
  elif [ "${nw:-0}" -gt 0 ]; then ee=EXISTS; else ee=NOT_RETAINED; fi
  row events "$(st ev1)" "$ee" "created_at=HOST_GENERATED" "retention assumed <=90 days and <=300 events (unchecked)"
}

if command -v gh >/dev/null 2>&1; then MODE=gh; get probe_gh mode rate_limit; [ "$(st probe_gh)" = 200 ] || MODE=curl; fi
[ "$MODE" = curl ] && get probe_curl mode rate_limit
COVERAGE="authenticated (gh api)"
[ "$MODE" = curl ] && COVERAGE="UNAUTHENTICATED curl: public repo only, 60 calls/h; protection needs admin and is expected NOT_ACCESSIBLE"
get repo repository "repos/$REPO"
jqn '$x[0] | "visibility\t\(.visibility // (if .private == true then "private" elif .private == false then "public" else "unknown" end))",
  "owner_type\t\(.owner.type // "unknown")", "created_at\t\(.created_at // "")"' --slurpfile x "$R/repo.json" | kv repository
row repository "$(st repo)" "$(exist "$(st repo)" 1)" "created_at=HOST_GENERATED" "visibility=$(kvget repository visibility)"
if [ "$(st repo)" = 200 ]; then scan; WHY="call budget reached"; else WHY="repository GET returned $(st repo)"; fi
for c in $CHANNELS; do cut -f1 "$S/channels.tsv" | grep -qx "$c" || row "$c" - NOT_ACCESSIBLE NOT_DETERMINABLE "not called: $WHY"; done

jq -Rn --arg mode "$MODE" --arg cov "$COVERAGE" --arg sha "$SHA" --arg t "$T0" --arg wf "$WF" --arg we "$WE" \
  --arg rh "$(printf %s "$REPO" | sha256sum | cut -c1-64)" --arg sh "$(sha256sum < "$SELF" | cut -c1-64)" \
  --argjson n "$N" --argjson bud "$BUDGET" --argjson hit "$HIT" \
  '[inputs | split("\t")] as $rows | {protocol: "C", mode: $mode, coverage: $cov, commit: $sha, repo_slug_sha256: $rh,
   script_sha256: $sh, t: $t, window: {from: $wf, to: $we}, calls: $n, budget: $bud, budget_hit: ($hit == 1),
   channels: ($rows[1:] | map([$rows[0], .] | transpose | map({(.[0]): .[1]}) | add))}' "$S/channels.tsv" > "$S/summary.json" || exit 6
( cd "$S" && sha256sum -- *.tsv summary.json > SHA256SUMS ) || exit 6

# Identity guard, fail closed: fixed patterns plus every account handle and git author string seen in the raw data.
{ jq -r '.. | objects | (.login? // empty), (select(has("email")) | .name? // empty)' "$R"/*.json 2>/dev/null
  printf '%s\n' "${REPO%%/*}"; } | awk 'length >= 3' | sort -u > "$R/ids.txt"
guard() { ! grep -rIlE '@|login|avatar_url|html_url|name"' -- "$1" && ! grep -rIlwF -f "$R/ids.txt" -- "$1"; }
guard "$S" || { echo "IDENTITY GUARD FAILED: nothing written to OUT" >&2; exit 5; }
mkdir -p -- "$OUT" && cp -- "$S"/* "$OUT"/ || exit 6
guard "$OUT" || { echo "IDENTITY GUARD FAILED on OUT: quarantine $OUT by hand" >&2; exit 5; }
( cd "$OUT" && sha256sum -c --quiet SHA256SUMS ) || exit 6
echo "mode=$MODE calls=$N/$BUDGET budget_hit=$HIT"
[ "$HIT" = 1 ] && exit 4
exit 0
