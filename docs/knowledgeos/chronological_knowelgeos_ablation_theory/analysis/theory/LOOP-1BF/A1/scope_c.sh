#!/usr/bin/env bash
# scope_c.sh: Protocol C r1 (PROTOCOL-C.md). Bounded, read-only (HTTP GET only) host-side scoping of one commit.
# Raw responses stay in a private temp dir; only identity-free derived fields reach OUT.
# Usage: scope_c.sh OUT_DIR WORKTREE_DIR
# Exit: 0 ok | 2 precondition | 3 refused path | 4 budget hit (partial output) | 5 identity guard | 6 write failure
SELF="$(readlink -f -- "${BASH_SOURCE[0]}")" || exit 2   # resolved before any cd
export LC_ALL=C TZ=UTC; umask 077
REPO="dr-roshyara/nrna1"; SHA="e4724a444231681f1a455ffaeea488a554183093"
T0="2026-08-04T00:30:25Z"; WF="2026-08-03T00:00:00Z"; WE="2026-08-06T00:00:00Z"
BUDGET=40; PR_CAP=5; EV_PAGES=3; API="https://api.github.com"
CHANNELS="commit prs reviews timeline checks status runs protection branch_rules rulesets events"
DOC422="commit"   # channels whose endpoint documents 422 (PROTOCOL-C §2; assumption to check)

[ $# -eq 2 ] || { echo "usage: ${SELF##*/} OUT_DIR WORKTREE_DIR" >&2; exit 2; }
for t in jq curl git date sha256sum readlink mktemp awk grep cut wc head tr sort; do
  command -v "$t" >/dev/null 2>&1 || { echo "refused: '$t' not found" >&2; exit 2; }; done
WTREE="$(readlink -f -- "$2")"
[ -n "$WTREE" ] && [ -d "$WTREE" ] || { echo "refused: WORKTREE_DIR is not a directory" >&2; exit 2; }
OUT="$(readlink -m -- "$1")"
inside() {   # PATH under WORKTREE_DIR, or its nearest existing ancestor inside any git work tree (git is required)
  case "$1/" in "$WTREE"/*) return 0 ;; esac
  local a="$1"; while [ ! -d "$a" ]; do a="${a%/*}"; [ -n "$a" ] || a=/; done
  git -C "$a" rev-parse --is-inside-work-tree >/dev/null 2>&1; }
[ -e "$OUT" ] && { echo "refused: OUT exists (no overwrite)" >&2; exit 3; }
inside "$OUT" && { echo "refused: OUT is inside a work tree" >&2; exit 3; }
TMP="$(mktemp -d "${TMPDIR:-/tmp}/scope_c.XXXXXXXX")" || exit 6
cleanup() { case "${TMP##*/}" in scope_c.????????) [ -d "$TMP" ] && rm -rf -- "$TMP" ;; esac; }; trap cleanup EXIT
inside "$TMP" && { echo "refused: TMPDIR is inside a work tree" >&2; exit 3; }
R="$TMP/raw"; S="$TMP/stage"; mkdir "$R" "$S" || exit 6

N=0; HIT=0; MODE=curl; HD_FIRST=""; HD_LAST=""; CT_FIRST=""; CT_LAST=""
printf 'seq\tchannel\tendpoint\thttp_status\n' > "$S/calls.tsv"
get() {   # get KEY CHANNEL PATH: one GET -> $R/KEY.{json,status,hdr}; once the budget is spent: no call, status "budget", returns 1
  local k="$1" p="$3" s="" d h=(-H "Accept: application/vnd.github+json" -H "X-GitHub-Api-Version: 2022-11-28")
  if [ "$N" -ge "$BUDGET" ]; then HIT=1; echo budget > "$R/$k.status"; echo '{}' > "$R/$k.json"; return 1; fi
  N=$((N + 1)); CT_LAST="$(date -u +%Y-%m-%dT%H:%M:%SZ)"; CT_FIRST="${CT_FIRST:-$CT_LAST}"
  if [ "$MODE" = gh ]; then
    gh api -X GET -i "${h[@]}" "$p" > "$R/$k.http" 2>/dev/null
    s="$(head -n1 "$R/$k.http" | awk '{print $2}')"; awk 'b{print; next} /^\r?$/{b=1}' "$R/$k.http" > "$R/$k.json"
    awk '/^\r?$/{exit} {print}' "$R/$k.http" > "$R/$k.hdr"
  else s="$(curl -sS -X GET -D "$R/$k.hdr" -o "$R/$k.json" -w '%{http_code}' "${h[@]}" "$API/$p" 2>/dev/null)"; fi
  s="${s:-000}"; jq -e . "$R/$k.json" >/dev/null 2>&1 || { echo '{}' > "$R/$k.json"; [ "$s" = 200 ] && s=200_unparsable; }
  echo "$s" > "$R/$k.status"; d="$(grep -i -m1 '^date:' "$R/$k.hdr" 2>/dev/null | cut -d' ' -f2- | tr -d '\r')"
  [ -n "$d" ] && { HD_LAST="$d"; HD_FIRST="${HD_FIRST:-$d}"; }
  printf '%s\t%s\t%s\t%s\n' "$N" "$2" "${p//"$REPO"/REPO}" "$s" >> "$S/calls.tsv"; }
st() { cat "$R/$1.status" 2>/dev/null || echo 000; }
agg() { local s; [ -n "$1" ] || { echo 000; return; }; for s in ${1//,/ }; do [ "$s" = 200 ] || { echo "$s"; return; }; done; echo 200; }
lab() {   # lab STATUS COUNT CHANNEL: existence label under the one status-gating rule (PROTOCOL-C §2)
  case "$1" in 200) if [ "${2:-0}" -gt 0 ] 2>/dev/null; then echo EXISTS; else echo NOT_EVIDENCED_ON_HOST; fi ;;
    401|403|404) echo NOT_ACCESSIBLE ;; budget) echo "NOT_ACCESSIBLE[not_called_budget]" ;;
    422) case " $DOC422 " in *" $3 "*) echo NOT_ACCESSIBLE ;; *) echo "NOT_ACCESSIBLE[transport]" ;; esac ;;
    *) echo "NOT_ACCESSIBLE[transport]" ;; esac; }
why() { case "$1" in budget) echo "not called: call budget reached" ;; 000) echo "transport failure (000)" ;; *) echo "http $1" ;; esac; }
dep() { if [ "$1" = budget ]; then echo "NOT_ACCESSIBLE[not_called_budget]"; else echo "NOT_ACCESSIBLE[dependency_failed]"; fi; }
printf 'channel\thttp\texistence\tts_generator\tnorm_state_at_t\tindependence\tnote\n' > "$S/channels.tsv"
row() { printf '%s\t%s\t%s\t%s\tNOT_DETERMINABLE\tINDEPENDENCE_NOT_DETERMINABLE\t%s\n' "$@" >> "$S/channels.tsv"; }
crow() {   # crow CH STATUS[,STATUS..] COUNT TS_GENERATOR NOTE: a non-200 note leads with its actual cause
  local s; s="$(agg "$2")"
  row "$1" "$2" "$(lab "$s" "$3" "$1")" "$4" "$([ "$s" = 200 ] || printf '%s; ' "$(why "$s")")$5"; }
kv() {   # kv CH STATUS < key\tvalue lines: every derived value becomes NA unless STATUS is 200
  local f="$S/ch_$1.tsv"; [ -e "$f" ] || printf 'key\tvalue\nhttp\t%s\n' "$2" > "$f"
  awk -F'\t' -v s="$2" 'BEGIN{OFS="\t"} s != "200" && $1 != "scope" && $1 != "n_distinct_bases" {$2 = "NA"} {print}' >> "$f"; }
kvget() { awk -F'\t' -v k="$2" '$1==k{print $2}' "$S/ch_$1.tsv" 2>/dev/null; }
JQD='def arr: if type == "array" then . else [] end;
def inw: (. // "") as $x | $x != "" and $x >= $wf and $x < $we;
def le_t: (. // "") as $x | $x != "" and $x <= $t;
def eq(a; b): if (a // "") == "" or (b // "") == "" then "NOT_DETERMINABLE" elif a == b then "true" else "false" end;
def eqc(a): if $cs != "200" then "NA" else eq(a; $ca) end;
def cnt(f): if $cs != "200" then "NA" elif $ca == "" then "NOT_DETERMINABLE" else (map(select(f == $ca)) | length) end;
def g(s; v): if s == "200" then v else "NA" end;
def tally(f): map(f // "none" | tostring) | group_by(.) | map("\(.[0])=\(length)") | join(",");'
jqn() { local f="$1"; shift   # jqn FILTER [jq options]; $ca/$pa (roles) live only in memory; $cs = commit status
  jq -nr --arg ca "${CA:-}" --arg pa "${PA:-}" --arg cs "$(st commit)" --arg t "$T0" --arg wf "$WF" --arg we "$WE" "$@" "$JQD $f"; }

scan() {
  local n npr ps cap=NA base s0 nb b c rv="" tl="" pst="" nrv ntl nc g p cc o es="" e1 nw ee
  local hist="current state only; historic state not available" nopr="not called: host lists no associated PR"
  local ttg="created_at=HOST_GENERATED; committed=CLIENT_SUPPLIED; no timestamp=NO_TIMESTAMP" na="NOT_APPLICABLE[no_associated_pr]"
  get commit commit "repos/$REPO/commits/$SHA"
  CA=""; [ "$(st commit)" = 200 ] && CA="$(jq -r '.author.login // empty' "$R/commit.json")"
  jqn '$x[0] | "identity_mapping_available\t\($ca != "")", "author_eq_committer\t\(eq(.author.login; .committer.login))",
    "author_date\t\(.commit.author.date // "")", "committer_date\t\(.commit.committer.date // "")", "signature_verified\t\(.commit.verification.verified | tostring)",
    "verification_reason\t\(.commit.verification.reason // "none")"' --slurpfile x "$R/commit.json" | kv commit "$(st commit)"
  crow commit "$(st commit)" 1 "author.date,committer.date=CLIENT_SUPPLIED" "role mapping source only"
  jqn '$x[0] | "owner_eq_commit_author\t\(eqc(.owner.login))"' --slurpfile x "$R/repo.json" | kv repository "$(st commit)"
  get pulls prs "repos/$REPO/commits/$SHA/pulls?per_page=$PR_CAP"; ps="$(st pulls)"; PRS=()   # no-PR branch only on 200 + empty list
  [ "$ps" = 200 ] && mapfile -t PRS < <(jqn "\$x[0] | arr | map(.number) | sort | .[:$PR_CAP][]" --slurpfile x "$R/pulls.json")
  npr=${#PRS[@]}; [ "$ps" = 200 ] && { [ "$npr" -ge "$PR_CAP" ] && cap=true || cap=false; }
  printf 'pr\tpr_http\treviews_http\ttimeline_http\tstate\tmerged\tbase_ref\tcreated_at\tmerged_at\tmerged_le_t\tmerged_in_window\tpr_author_eq_commit_author\tmerger_eq_pr_author\tmerger_eq_commit_author\tn_reviews\tn_distinct_reviewers\tn_approvals\tn_approvals_le_t\tn_approvals_in_window\tn_approvals_by_pr_author\tn_approvals_by_commit_author\treviews_truncated\ttimeline_truncated\n' > "$S/prs.tsv"
  printf 'pr\thttp\tevent\tn\tn_timestamped\tn_in_window\tn_no_timestamp\tts_generator\n' > "$S/timeline.tsv"
  for n in "${PRS[@]}"; do
    get "pr$n" prs "repos/$REPO/pulls/$n"; get "rv$n" reviews "repos/$REPO/pulls/$n/reviews?per_page=100"
    get "tl$n" timeline "repos/$REPO/issues/$n/timeline?per_page=100"
    pst="$pst${pst:+,}$(st "pr$n")"; rv="$rv${rv:+,}$(st "rv$n")"; tl="$tl${tl:+,}$(st "tl$n")"
    PA=""; [ "$(st "pr$n")" = 200 ] && PA="$(jq -r '.user.login // empty' "$R/pr$n.json")"
    jqn '($p[0]) as $p | ($v[0] | arr) as $v | ($l[0] | arr) as $l | ($v | map(select(.state == "APPROVED"))) as $a |
      [$n, $ps, $vs, $ls, g($ps; $p.state), g($ps; $p.merged_at != null), g($ps; $p.base.ref // ""), g($ps; $p.created_at),
       g($ps; $p.merged_at // ""), g($ps; $p.merged_at | le_t), g($ps; $p.merged_at | inw), g($ps; eqc($pa)),
       g($ps; eq($p.merged_by.login; $pa)), g($ps; eqc($p.merged_by.login)), g($vs; $v | length), g($vs; $v | map(.user.login) | unique | length),
       g($vs; $a | length), g($vs; $a | map(select(.submitted_at | le_t)) | length), g($vs; $a | map(select(.submitted_at | inw)) | length),
       g($ps; g($vs; if $pa == "" then "NOT_DETERMINABLE" else ($a | map(select(.user.login == $pa)) | length) end)),
       g($vs; $a | cnt(.user.login)), g($vs; ($v | length) >= 100), g($ls; ($l | length) >= 100)] | map(tostring) | join("\t")' \
      --arg n "$n" --arg ps "$(st "pr$n")" --arg vs "$(st "rv$n")" --arg ls "$(st "tl$n")" \
      --slurpfile p "$R/pr$n.json" --slurpfile v "$R/rv$n.json" --slurpfile l "$R/tl$n.json" >> "$S/prs.tsv"
    if [ "$(st "tl$n")" = 200 ]; then   # per-event timestamp field; events without one are NO_TIMESTAMP, never HOST_GENERATED
      jqn '$l[0] | arr | group_by(.event) | .[] | .[0].event as $e |
        (if $e == "committed" then "author.date" elif $e == "reviewed" then "submitted_at" else "created_at" end) as $f |
        (map(if $e == "committed" then .author.date elif $e == "reviewed" then .submitted_at else .created_at end) | map(select(. != null and . != ""))) as $h |
        [$n, "200", $e, length, ($h | length), ($h | map(select(inw)) | length), (length - ($h | length)),
         (if ($h | length) == 0 then "NO_TIMESTAMP" elif $e == "committed" then "author.date=CLIENT_SUPPLIED"
          else "\($f)=HOST_GENERATED" end)] | map(tostring) | join("\t")' --arg n "$n" --slurpfile l "$R/tl$n.json" >> "$S/timeline.tsv"
    else printf '%s\t%s\tNA\tNA\tNA\tNA\tNA\tNA\n' "$n" "$(st "tl$n")" >> "$S/timeline.tsv"; fi
    [ "$HIT" = 1 ] && break
  done
  PA=""; nrv="$(awk -F'\t' 'NR>1{s+=$15} END{print s+0}' "$S/prs.tsv")"; ntl="$(awk -F'\t' 'NR>1{s+=$4} END{print s+0}' "$S/timeline.tsv")"
  crow prs "$ps" "$npr" "created_at,merged_at=HOST_GENERATED" "n=$([ "$ps" = 200 ] && echo "$npr" || echo NA); cap_reached=$cap; pr_http=${pst:-none}"
  if [ "$ps" = 200 ] && [ "$npr" -eq 0 ]; then
    row reviews - "$na" "submitted_at=HOST_GENERATED" "$nopr"; row timeline - "$na" "$ttg" "$nopr"
  elif [ "$ps" != 200 ]; then
    row reviews - "$(dep "$ps")" "submitted_at=HOST_GENERATED" "not called: PR association lookup $(why "$ps")"
    row timeline - "$(dep "$ps")" "$ttg" "not called: PR association lookup $(why "$ps")"
  else
    crow reviews "$rv" "$nrv" "submitted_at=HOST_GENERATED" "$([ "$(agg "$rv")" = 200 ] && echo "n=$nrv; ")see prs.tsv"
    crow timeline "$tl" "$ntl" "$ttg" "$([ "$(agg "$tl")" = 200 ] && echo "n=$ntl; ")see timeline.tsv"
  fi
  get checks checks "repos/$REPO/commits/$SHA/check-runs?per_page=100"
  jqn '($x[0].check_runs | arr) as $r | ($x[0].total_count // 0) as $tc | "total_count\t\($tc)", "n_returned\t\($r | length)",
    "checks_truncated\t\(($r | length) >= 100 or $tc > ($r | length))", "ts_decision_scope\treturned_page",
    "n_app_github_actions\t\($r | map(select(.app.slug == "github-actions")) | length)", "n_started_le_t\t\($r | map(select(.started_at | le_t)) | length)",
    "n_started_in_window\t\($r | map(select(.started_at | inw)) | length)", "conclusions\t\($r | tally(.conclusion))"' \
    --slurpfile x "$R/checks.json" | kv checks "$(st checks)"
  nc="$(kvget checks n_returned)"; g=NOT_DETERMINABLE
  [ "$(st checks)" = 200 ] && [ "${nc:-0}" -gt 0 ] && [ "$nc" = "$(kvget checks n_app_github_actions)" ] && g=HOST_GENERATED
  crow checks "$(st checks)" "$nc" "started_at,completed_at=$g (returned page only)" "app-written; HOST_GENERATED only if every run on the returned page is by the host CI app"
  get status status "repos/$REPO/commits/$SHA/status?per_page=100"
  jqn '($x[0].statuses | arr) as $r | "state\t\($x[0].state // "none")", "total_count\t\($x[0].total_count // 0)",
    "n_created_le_t\t\($r | map(select(.created_at | le_t)) | length)", "n_created_in_window\t\($r | map(select(.created_at | inw)) | length)",
    "n_creator_eq_commit_author\t\($r | cnt(.creator.login))"' --slurpfile x "$R/status.json" | kv status "$(st status)"
  crow status "$(st status)" "$(kvget status total_count)" "created_at=HOST_GENERATED" "latest status per context only"
  get runs runs "repos/$REPO/actions/runs?head_sha=$SHA&per_page=100"
  jqn '($x[0].workflow_runs | arr) as $r | ($x[0].total_count // 0) as $tc | "total_count\t\($tc)", "n_returned\t\($r | length)",
    "runs_truncated\t\(($r | length) >= 100 or $tc > ($r | length))",
    "n_created_le_t\t\($r | map(select(.created_at | le_t)) | length)", "n_created_in_window\t\($r | map(select(.created_at | inw)) | length)",
    "events\t\($r | tally(.event))", "conclusions\t\($r | tally(.conclusion))", "n_actor_eq_commit_author\t\($r | cnt(.actor.login))",
    "n_triggering_actor_eq_commit_author\t\($r | cnt(.triggering_actor.login))"' --slurpfile x "$R/runs.json" | kv runs "$(st runs)"
  crow runs "$(st runs)" "$(kvget runs total_count)" "created_at,run_started_at=HOST_GENERATED; head_commit.timestamp=CLIENT_SUPPLIED" "metadata only; logs not fetched"
  get rsets rulesets "repos/$REPO/rulesets?per_page=100&includes_parents=true"   # repository-scoped: always called
  jqn '$x[0] | arr | "n_rulesets\t\(length)", "rulesets_truncated\t\(length >= 100)"' --slurpfile x "$R/rsets.json" | kv rulesets "$(st rsets)"
  printf 'id\ttarget\tenforcement\tcreated_at\tupdated_at\tupdated_le_t\n' > "$S/rulesets.tsv"
  [ "$(st rsets)" = 200 ] && jqn '$x[0] | arr | sort_by(.id) | .[] | [.id, .target, .enforcement, (.created_at // ""), (.updated_at // ""),
    (.updated_at | le_t)] | map(tostring) | join("\t")' --slurpfile x "$R/rsets.json" >> "$S/rulesets.tsv"
  crow rulesets "$(st rsets)" "$(kvget rulesets n_rulesets)" "created_at,updated_at=HOST_GENERATED" "repository-scoped; $hist"
  nb="$(awk -F'\t' 'NR>1 && $7!="" && $7!="NA"{print $7}' "$S/prs.tsv" | sort -u | wc -l)"
  if [ "$ps" = 200 ] && [ "$npr" -eq 0 ]; then
    for c in protection branch_rules; do row "$c" - "$na" NOT_DETERMINABLE "$nopr; does not show that the commit's branches are unprotected"; done
  else   # base branch only from a 200 on the lowest-numbered PR
    if [ "$ps" = 200 ]; then s0="$(st "pr${PRS[0]}")"; base="$(awk -F'\t' 'NR==2{print $7}' "$S/prs.tsv")"; cc="lowest PR GET"
    else s0="$ps"; base=""; cc="PR association lookup"; fi
    if [ "$s0" != 200 ] || [ -z "$base" ] || [ "$base" = NA ]; then
      for c in protection branch_rules; do row "$c" - "$(dep "$s0")" NOT_DETERMINABLE "not called: base branch not derivable ($cc $(why "$s0"))"; done
    else
      b="$(jq -rn --arg b "$base" '$b | @uri')"
      get prot protection "repos/$REPO/branches/$b/protection"
      jqn '$x[0] | "scope\tCURRENT_STATE_ONLY", "n_distinct_bases\t'"$nb"'", "required_pull_request_reviews\t\(.required_pull_request_reviews != null)",
        "required_approving_review_count\t\(.required_pull_request_reviews.required_approving_review_count // 0)", "enforce_admins\t\(.enforce_admins.enabled // false)",
        "required_status_checks\t\(.required_status_checks != null)", "required_signatures\t\(.required_signatures.enabled // false)",
        "push_restrictions\t\(.restrictions != null)", "allow_force_pushes\t\(.allow_force_pushes.enabled // false)"' --slurpfile x "$R/prot.json" | kv protection "$(st prot)"
      crow protection "$(st prot)" 1 NOT_DETERMINABLE "$hist"
      get brules branch_rules "repos/$REPO/rules/branches/$b?per_page=100"
      jqn '$x[0] | arr | "scope\tCURRENT_STATE_ONLY", "n_active_rules\t\(length)", "branch_rules_truncated\t\(length >= 100)",
        "types\t\(tally(.type))"' --slurpfile x "$R/brules.json" | kv branch_rules "$(st brules)"
      crow branch_rules "$(st brules)" "$(kvget branch_rules n_active_rules)" NOT_DETERMINABLE "$hist"
    fi
  fi
  for ((p = 1; p <= EV_PAGES; p++)); do   # page only while the window start has not been reached
    get "ev$p" events "repos/$REPO/events?per_page=100&page=$p"; es="$es${es:+,}$(st "ev$p")"; [ "$(st "ev$p")" = 200 ] || break
    c="$(jq 'if type == "array" then length else 0 end' "$R/ev$p.json")"; o="$(jq -r '[.[]?.created_at?] | min // ""' "$R/ev$p.json")"
    [ "$c" -lt 100 ] && break; [[ "$o" < "$WF" ]] && break
  done
  e1="$(agg "$es")"; jq -s '[.[] | if type == "array" then .[] else empty end]' "$R"/ev?.json > "$R/ev_all.json"
  jqn '($x[0] | arr) as $a | ($a | map(select(.created_at | inw))) as $w | ($a | map(.created_at) | min // "") as $o |
    "n_returned\t\($a | length)", "oldest_created_at\t\($o)", "newest_created_at\t\($a | map(.created_at) | max // "")",
    "window_covered\t\($o != "" and $o < $wf)", "n_in_window\t\($w | length)", "types_in_window\t\($w | tally(.type))",
    "n_in_window_actor_eq_commit_author\t\($w | cnt(.actor.login))"' --slurpfile x "$R/ev_all.json" | kv events "$e1"
  nw="$(kvget events n_in_window)"
  if [ "$e1" != 200 ]; then ee="$(lab "$e1" 0 events)"; elif [ "$(kvget events window_covered)" = true ]; then ee="$(lab 200 "$nw")"
  elif [ "${nw:-0}" -gt 0 ]; then ee=EXISTS; else ee=NOT_RETAINED; fi
  row events "$es" "$ee" "created_at=HOST_GENERATED" "$([ "$e1" = 200 ] || printf '%s; ' "$(why "$e1")")retention assumed <=90 days and <=300 events (unchecked)"
}

if command -v gh >/dev/null 2>&1; then MODE=gh; get probe_gh mode rate_limit; [ "$(st probe_gh)" = 200 ] || MODE=curl; fi
[ "$MODE" = curl ] && get probe_curl mode rate_limit
COVERAGE="authenticated"; [ "$MODE" = curl ] && COVERAGE="UNAUTHENTICATED: public repo only, 60 calls/h; protection needs admin and is expected NOT_ACCESSIBLE"
get repo repository "repos/$REPO"
jqn '$x[0] | "visibility\t\(.visibility // (if .private == true then "private" elif .private == false then "public" else "unknown" end))",
  "owner_type\t\(.owner.type // "unknown")", "created_at\t\(.created_at // "")"' --slurpfile x "$R/repo.json" | kv repository "$(st repo)"
crow repository "$(st repo)" 1 "created_at=HOST_GENERATED" "visibility=$(kvget repository visibility)"
[ "$(st repo)" = 200 ] && scan
L="$(dep "$(st repo)")"; WHY="not called: repository GET $(why "$(st repo)")"   # fallback for any channel without a row
[ "$HIT" = 1 ] && { L="NOT_ACCESSIBLE[not_called_budget]"; WHY="not called: call budget reached"; }
for c in $CHANNELS; do cut -f1 "$S/channels.tsv" | grep -qx "$c" || row "$c" - "$L" NOT_DETERMINABLE "$WHY"; done
jq -Rn --arg mode "$MODE" --arg cov "$COVERAGE" --arg sha "$SHA" --arg t "$T0" --arg wf "$WF" --arg we "$WE" \
  --arg rh "$(printf %s "$REPO" | sha256sum | cut -c1-64)" --arg sh "$(sha256sum < "$SELF" | cut -c1-64)" \
  --arg hf "$HD_FIRST" --arg hl "$HD_LAST" --arg cf "$CT_FIRST" --arg cl "$CT_LAST" --argjson n "$N" --argjson bud "$BUDGET" --argjson hit "$HIT" \
  'def na: if . == "" then "NA" else . end; [inputs | split("\t")] as $rows | {protocol: "C", revision: "r1", mode: $mode, coverage: $cov,
   commit: $sha, repo_slug_sha256: $rh, script_sha256: $sh, t: $t, window: {from: $wf, to: $we}, calls: $n, budget: $bud, budget_hit: ($hit == 1),
   observation_time: {host_date_header: {first: ($hf | na), last: ($hl | na), generator: "HOST_GENERATED"},
                      client_clock_utc: {first: ($cf | na), last: ($cl | na), generator: "CLIENT_SUPPLIED"}},
   channels: ($rows[1:] | map([$rows[0], .] | transpose | map({(.[0]): .[1]}) | add))}' "$S/channels.tsv" > "$S/summary.json" || exit 6
( cd "$S" && sha256sum -- *.tsv summary.json > SHA256SUMS ) || exit 6

# Identity guard, fail closed: fixed patterns plus every account handle and git author string seen in the raw data.
# The script's own notes carry no product or proper names, so they cannot trip it.
{ jq -r '.. | objects | (.login? // empty), (select(has("email")) | .name? // empty)' "$R"/*.json 2>/dev/null
  printf '%s\n' "${REPO%%/*}"; } | awk 'length >= 3' | sort -u > "$R/ids.txt"
guard() { ! grep -rIlE '@|login|avatar_url|html_url|name"' -- "$1" && ! grep -rIlwF -f "$R/ids.txt" -- "$1"; }
guard "$S" || { echo "IDENTITY GUARD FAILED: nothing written to OUT" >&2; exit 5; }
mkdir -- "$OUT" || { echo "refused: cannot create OUT (it now exists, or its parent is missing)" >&2; exit 3; }
cp -- "$S"/* "$OUT"/ || exit 6
guard "$OUT" || { echo "IDENTITY GUARD FAILED on OUT: quarantine $OUT by hand" >&2; exit 5; }
( cd "$OUT" && sha256sum -c --quiet SHA256SUMS ) || exit 6
echo "mode=$MODE calls=$N/$BUDGET budget_hit=$HIT"; [ "$HIT" = 1 ] && exit 4
exit 0
