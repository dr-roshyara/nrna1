#!/usr/bin/env bash
# scope_b.sh -- Protocol B scoping. Read-only; existence/metadata only. No network, no host access.
# Emits COUNTS ONLY: no names, emails, ref names, URLs or path strings leave TMP; paths -> 16-hex ids.
# The ONLY content read beyond existence/counts is commit/tag object headers for signature PRESENCE
# (Q1/Q2/Q7 for K-SIG strictly require it); no file blob is ever read.
# Usage (from anywhere inside the working clone):  OUT=/abs/empty/dir bash scope_b.sh
# Exit: 0 ok | 1 ok-with-unexpected-codes or identity-guard fail | 2 refused | 3 capture invalid
set -u -o pipefail
export LC_ALL=C TZ=UTC GIT_PAGER=cat PAGER=cat GIT_CONFIG_NOSYSTEM=1 GIT_CONFIG_GLOBAL=/dev/null \
  GIT_OPTIONAL_LOCKS=0 GIT_TERMINAL_PROMPT=0 GIT_NO_LAZY_FETCH=1 GIT_NO_REPLACE_OBJECTS=1
unset GIT_DIR GIT_WORK_TREE GIT_INDEX_FILE GIT_OBJECT_DIRECTORY GIT_ALTERNATE_OBJECT_DIRECTORIES GIT_NAMESPACE
die() { printf 'scope_b: %s\n' "$2" >&2; exit "$1"; }

# ---- resolve OWN absolute path and hash it BEFORE any cd (corrects earlier relative-BASH_SOURCE defect) ----
SELF="${BASH_SOURCE[0]}"; case "$SELF" in /*) ;; *) SELF="$PWD/$SELF" ;; esac
SELF="$(realpath -- "$SELF")" || die 2 "cannot resolve own path"
SELFSHA="$(sha256sum < "$SELF" | cut -d' ' -f1)" || die 2 "cannot hash self"

git() { command git -c core.quotepath=off -c log.showsignature=false -c log.mailmap=false -c color.ui=never "$@"; }

# ---- preconditions (exit 2) ----
OUT="${OUT:-}"; [ -n "$OUT" ] || die 2 "OUT not set"
case "$OUT" in /*) ;; *) die 2 "OUT must be an absolute path" ;; esac
[ "$(git rev-parse --is-inside-work-tree 2>/dev/null)" = true ] || die 2 "not inside a work tree"
[ "$(git rev-parse --is-bare-repository 2>/dev/null)" = false ] || die 2 "bare repository"
TOP="$(git rev-parse --show-toplevel)" || die 2 "no toplevel"
GD="$(git rev-parse --absolute-git-dir)" || die 2 "no git dir"
OUT="$(realpath -m -- "$OUT")" || die 2 "realpath OUT failed"
TOPR="$(realpath -- "$TOP")"; GDR="$(realpath -- "$GD")"
for d in "$TOPR" "$GDR"; do case "$OUT/" in "$d"/*) die 2 "OUT must be outside the work tree and .git" ;; esac; done
if [ -e "$OUT" ]; then { [ -d "$OUT" ] && [ -z "$(ls -A -- "$OUT")" ]; } || die 2 "OUT must be an empty directory"; fi
TMP="$(mktemp -d)" || die 2 "mktemp failed"; TMP="$(realpath -- "$TMP")"
case "$TMP/" in "$TOPR"/*|"$GDR"/*|"$OUT"/*) rm -rf -- "$TMP"; die 2 "TMPDIR must be outside the repo and OUT" ;; esac
trap 'rm -rf -- "$TMP"' EXIT
mkdir -p -- "$OUT" || die 2 "cannot create OUT"
cd -- "$TOP" || die 2 "cannot cd to the toplevel"   # the one and only cd; self already resolved+hashed above

ERR="$OUT/00_errors.tsv"; : > "$ERR"; : > "$TMP/e"
ok() { local l="$1" rc="$2" a; shift 2; for a in "$@"; do [ "$rc" = "$a" ] && return 0; done; printf '%s\t%s\n' "$l" "$rc" >> "$ERR"; return 1; }
printf '%s\n' "$SELFSHA" > "$OUT/00_script.sha256"
printf 'git_version\t%s\n' "$(git --version)" > "$OUT/00_env.tsv"
ND=NOT_DETERMINABLE_BY_EXISTENCE_CHECK; IND=INDEPENDENCE_NOT_DETERMINABLE
finish() { ( cd -- "$OUT" && find . -type f ! -name SHA256SUMS -print | sort | xargs -r -d '\n' sha256sum ) > "$OUT/SHA256SUMS"; exit "$1"; }

# ---- capture validity + local ref set -> tracked path universe (existence/scoping only) ----
SH="$(git rev-parse --is-shallow-repository 2>/dev/null)"; ok env.shallow $? 0
PC="$(git config --get extensions.partialClone)"; ok cfg.partialClone $? 0 1
if [ "$SH" != false ] || [ -n "$PC" ]; then printf 'overall\tCAPTURE_INVALID\n' > "$OUT/71_overall.tsv"; finish 3; fi
git for-each-ref --format='%(objectname) %(objecttype) %(*objectname)' > "$TMP/refs" 2>>"$TMP/e"; ok refs $? 0
awk '$2=="commit"{print $1} $2=="tag" && $3!=""{print $3}' "$TMP/refs" | sort -u > "$TMP/tips"
[ -s "$TMP/tips" ] || { printf 'overall\tCAPTURE_INVALID\n' > "$OUT/71_overall.tsv"; finish 3; }
: > "$TMP/paths"
while IFS= read -r t; do git ls-tree -r --name-only --full-tree "$t" < /dev/null >> "$TMP/paths" 2>>"$TMP/e"; ok lstree $? 0; done < "$TMP/tips"
sort -u "$TMP/paths" > "$TMP/pu"; mv "$TMP/pu" "$TMP/paths"

# ---- classify candidate channels (bounded vocabulary; docs/knowledgeos excluded) ----
awk -F'\t' '
{ p=$0; q=tolower(p); b=q; sub(/.*\//,"",b)
  if (q ~ /^docs\/knowledgeos\//) next
  if (q ~ /^[.]github\/workflows\// || q==".gitlab-ci.yml" || b=="jenkinsfile" || q ~ /^[.]circleci\// || q=="azure-pipelines.yml" || q==".drone.yml" || q=="bitbucket-pipelines.yml" || q ~ /^[.]woodpecker/) print "K-CI\t" p
  if (q ~ /^[.]githooks\// || q ~ /^[.]husky\// || q==".pre-commit-config.yaml" || b=="lefthook.yml" || b==".lefthook.yml") print "K-HOOK\t" p
  if (b=="codeowners") print "K-CODEOWNERS\t" p
  e=""; if (b ~ /[.][^.]+$/){e=b; sub(/.*[.]/,"",e)}
  docx=(e=="md"||e=="markdown"||e=="txt"||e=="rst"||e=="json"||e=="yaml"||e=="yml"||e=="toml"||e=="csv"||e=="tsv")
  if (docx && q ~ /(^|\/)(program_status|status|rulings?|register|registry|decisions?|adoptions?|approvals?|signoffs?|progress|inforce|norm)([._/-]|$)/) print "K-STATUS\t" p
  if (q ~ /(^|\/)(deploy|deployment|release|environment|env|infra|terraform)([._/-]|$)/ || e=="tfstate") print "K-DEPLOY\t" p }' \
  "$TMP/paths" | sort -u > "$TMP/cand"

# ---- writer sets (identities stay in TMP; only counts/overlaps leave). E excludes every candidate ----
# channel path + docs/knowledgeos, so a channel's own writers are NOT executors by construction.
cut -f2 "$TMP/cand" | sort -u > "$TMP/candpaths"
EXC=( ':(exclude,glob,icase)docs/knowledgeos/**' )
while IFS= read -r p; do [ -n "$p" ] && EXC+=( ":(exclude,literal)$p" ); done < "$TMP/candpaths"
git log --stdin --no-merges --format='%ae%n%ce' -- . "${EXC[@]}" < "$TMP/tips" 2>>"$TMP/e" | tr 'A-Z' 'a-z' | awk 'NF' | sort -u > "$TMP/E"
ok writers.exec "${PIPESTATUS[0]}" 0
: > "$TMP/N"
awk -F'\t' '$1=="K-STATUS" && tolower($2) ~ /(^|\/)(rulings?|register|registry|decisions?|norm|inforce|authoriz)/ {print $2}' "$TMP/cand" | sort -u > "$TMP/npaths"
if [ -s "$TMP/npaths" ]; then mapfile -t NP < "$TMP/npaths"
  git log --stdin --no-merges --format='%ae%n%ce' -- "${NP[@]}" < "$TMP/tips" 2>>"$TMP/e" | tr 'A-Z' 'a-z' | awk 'NF' | sort -u > "$TMP/N"
  ok writers.norm "${PIPESTATUS[0]}" 0
fi

# ---- per-channel metrics (counts only) + path-id map (no path strings) ----
: > "$OUT/61_path_ids.tsv"; : > "$OUT/60_channel_metrics.tsv"
for ch in K-CI K-HOOK K-CODEOWNERS K-STATUS K-DEPLOY; do
  awk -F'\t' -v c="$ch" '$1==c{print $2}' "$TMP/cand" | sort -u > "$TMP/cp"
  np=$(awk 'END{print NR}' "$TMP/cp"); : > "$TMP/W"; nc=0
  while IFS= read -r p; do
    id="$(printf '%s' "$p" | sha256sum | cut -c1-16)"
    printf '%s\t%s\n' "$id" "$ch" >> "$OUT/61_path_ids.tsv"
    git log --no-merges --format='%ae%n%ce' -- ":(literal)$p" < /dev/null >> "$TMP/W" 2>>"$TMP/e"; ok "w.$id" $? 0
    k=$(git log --no-merges --format='%H' -- ":(literal)$p" < /dev/null 2>>"$TMP/e" | awk 'END{print NR}'); nc=$((nc+k))
  done < "$TMP/cp"
  tr 'A-Z' 'a-z' < "$TMP/W" | awk 'NF' | sort -u > "$TMP/Wu"
  nw=$(awk 'END{print NR}' "$TMP/Wu")
  oe=$(comm -12 "$TMP/Wu" "$TMP/E" | awk 'END{print NR}')
  on=$(comm -12 "$TMP/Wu" "$TMP/N" | awk 'END{print NR}')
  printf '%s\t%s\t%s\t%s\t%s\t%s\n' "$ch" "$np" "$nc" "$nw" "$oe" "$on" >> "$OUT/60_channel_metrics.tsv"
done

# ---- K-SIG: signature PRESENCE only (justified object-header read; keyring not consulted) ----
git rev-list --stdin < "$TMP/tips" 2>>"$TMP/e" | sort -u > "$TMP/allc"; ok revlist "${PIPESTATUS[0]}" 0
nS=0
while IFS= read -r c; do
  git cat-file commit "$c" < /dev/null 2>>"$TMP/e" | awk '/^$/{exit} /^gpgsig/{s=1} END{exit (s?0:1)}' && nS=$((nS+1))
done < "$TMP/allc"
nTS=0
while IFS= read -r line; do set -- $line; [ "${2:-}" = tag ] || continue
  git cat-file tag "$1" < /dev/null 2>>"$TMP/e" | grep -qE '^-----BEGIN (PGP|SSH) SIGNATURE-----$' && nTS=$((nTS+1))
done < "$TMP/refs"
printf 'commits_total\t%s\ncommits_signed\t%s\ntags_signed\t%s\n' "$(awk 'END{print NR}' "$TMP/allc")" "$nS" "$nTS" > "$OUT/58_signatures.tsv"

# ---- K-LOCALHOOK: local .git/hooks presence (LOCAL_ONLY, unversioned) ----
HOOKS="applypatch-msg commit-msg post-checkout post-commit post-merge post-receive post-update pre-applypatch pre-commit pre-merge-commit pre-push pre-rebase pre-receive prepare-commit-msg update"
nh=0; for hk in $HOOKS; do [ -e "$GD/hooks/$hk" ] && nh=$((nh+1)); done
hp=unset; git config --get core.hooksPath > /dev/null 2>&1 && hp=set
printf 'standard_hooks_present\t%s\ncore_hooksPath\t%s\n' "$nh" "$hp" > "$OUT/56_local_hooks.tsv"

# ---- answers Q1..Q8 (deterministic; every outcome reachable; never a forced negative) ----
{
printf '#channel\tQ1_exists\tQ2_records\tQ3_controls\tQ4_ts_independent\tQ5_norm_at_t\tQ6_complete\tQ7_independent\tQ8_reproducible\n'
awk -F'\t' -v ND="$ND" -v IND="$IND" 'BEGIN{OFS="\t"}
{ ch=$1; np=$2+0; nw=$4+0; oe=$5+0; on=$6+0
  if (np==0){ print ch,"NOT_EVIDENCED_IN_CAPTURE","NOT_APPLICABLE(Q1)","NOT_APPLICABLE(Q1)","NOT_APPLICABLE(Q1)","NOT_APPLICABLE(Q1)","NOT_APPLICABLE(Q1)","NOT_APPLICABLE(Q1)","NOT_APPLICABLE(Q1)"; next }
  cfg=(ch=="K-CI"||ch=="K-HOOK"||ch=="K-CODEOWNERS"||ch=="K-DEPLOY")
  q2=cfg?"CONFIG_RULE(by type; content not read)":(ch=="K-STATUS"?"STATUS/NORM_RECORD(by type; content not read)":ND)
  q5=cfg?"NO_BY_TYPE(configuration rule, not a norm-state record)":ND
  if (ch=="K-CI"||ch=="K-HOOK"||ch=="K-CODEOWNERS") q7=IND"[mechanism_host_enforced_unobservable;overlap_exec=" oe ";overlap_norm=" on "]"
  else if (ch=="K-STATUS" && on>0) q7="NORM_SOURCE_NOT_INDEPENDENT[self;overlap_norm=" on "]"
  else if (on>0) q7="NOT_INDEPENDENT[overlap_norm=" on "]"
  else if (oe>0) q7="NOT_INDEPENDENT[overlap_exec=" oe "]"
  else q7=IND"[writer_overlap=0;control_mechanism_unobservable]"
  print ch,"YES_IN_CAPTURE",q2,"CONTROLLED_BY_WRITERS[n=" nw "]","NO_CLIENT_SET(git recording times are client-generated)",q5,ND "[COMPLETE_VS_HOST=NOT_SHOWN]",q7,"YES_LOCAL(content-addressed; rerunnable on same ref set)" }' \
  "$OUT/60_channel_metrics.tsv"
read -r nC nSg nTg < <(awk -F'\t' '{v[$1]=$2} END{print v["commits_total"]+0, v["commits_signed"]+0, v["tags_signed"]+0}' "$OUT/58_signatures.tsv")
if [ "$nSg" -gt 0 ] || [ "$nTg" -gt 0 ]; then
  printf 'K-SIG\tYES_IN_CAPTURE\tIDENTITY_ATTESTATION(presence only; not verified)\t%s(keyring not consulted)\tNO_CLIENT_SET(signer clock)\tNO_BY_TYPE(attestation, not a norm-state record)\t%s[COMPLETE_VS_HOST=NOT_SHOWN]\t%s(signatures not verified)\tYES_LOCAL(presence only)\n' "$ND" "$ND" "$IND"
else
  printf 'K-SIG\tNOT_EVIDENCED_IN_CAPTURE\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\n'
fi
read -r hn hpv < <(awk -F'\t' '{v[$1]=$2} END{print v["standard_hooks_present"]+0, v["core_hooksPath"]}' "$OUT/56_local_hooks.tsv")
if [ "$hn" -gt 0 ] || [ "$hpv" = set ]; then
  printf 'K-LOCALHOOK\tYES_LOCAL_ONLY\tCONFIG_RULE(by type; content not read)\tLOCAL_MACHINE\tNONE\tNO_BY_TYPE\t%s[COMPLETE_VS_HOST=NOT_SHOWN]\t%s(local, unversioned)\tNO_LOCAL_ONLY(not versioned; not reproducible from the ref set)\n' "$ND" "$IND"
else
  printf 'K-LOCALHOOK\tNOT_EVIDENCED_LOCALLY\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\n'
fi
for hc in K-HOST-AUDIT K-HOST-BRANCHPROT K-HOST-PR-APPROVALS K-CI-RUNS K-HOST-MEMBERS; do
  printf '%s\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\n' "$hc"
done
} > "$OUT/70_answers.tsv"

# ---- overall (never a forced negative) + fail-closed identity guard ----
{
printf 'headline\tNIF_A_INDEPENDENCE_NOT_DETERMINABLE_BY_EXISTENCE_CHECK\n'
printf 'complete_vs_host\tNOT_SHOWN\n'
printf 'open_local_candidates\t%s\n' "$(awk -F'\t' 'NR>1 && $8 ~ /^INDEPENDENCE_NOT_DETERMINABLE/{c++} END{print c+0}' "$OUT/70_answers.tsv")"
printf 'not_independent_by_overlap\t%s\n' "$(awk -F'\t' 'NR>1 && $8 ~ /^(NOT_INDEPENDENT|NORM_SOURCE_NOT_INDEPENDENT)/{c++} END{print c+0}' "$OUT/70_answers.tsv")"
printf 'not_evidenced\t%s\n' "$(awk -F'\t' 'NR>1 && $2 ~ /^NOT_EVIDENCED/{c++} END{print c+0}' "$OUT/70_answers.tsv")"
printf 'host_channels\t5\n'
} > "$OUT/71_overall.tsv"
code=0; [ -s "$ERR" ] && code=1
if grep -rlE '@|BEGIN (PGP|SSH) SIGNATURE' -- "$OUT" > "$TMP/hit" 2>/dev/null; then
  printf 'identity_guard\tFAIL(%s files)\n' "$(awk 'END{print NR}' "$TMP/hit")" >> "$OUT/71_overall.tsv"; code=1
else
  printf 'identity_guard\tPASS\n' >> "$OUT/71_overall.tsv"
fi
finish "$code"
