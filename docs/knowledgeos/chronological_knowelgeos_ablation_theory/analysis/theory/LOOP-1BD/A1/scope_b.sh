#!/usr/bin/env bash
# scope_b.sh (r1) -- Protocol B scoping. Read-only; existence/metadata only. No network, no host access.
# Emits COUNTS ONLY: no names, emails, ref names, URLs or path strings leave TMP; paths -> 16-hex ids.
# The ONLY content read beyond existence/counts is commit/tag object headers for signature PRESENCE
# (Q1/Q2/Q7 for K-SIG strictly require it); no file blob is ever read.
# Usage (from anywhere inside the working clone):  OUT=/abs/empty/dir bash scope_b.sh
# Exit: 0 ok | 1 ok-with-unexpected-codes or identity-guard fail | 2 refused | 3 capture invalid
set -u -o pipefail
export LC_ALL=C TZ=UTC GIT_PAGER=cat PAGER=cat GIT_CONFIG_NOSYSTEM=1 GIT_CONFIG_GLOBAL=/dev/null \
  GIT_OPTIONAL_LOCKS=0 GIT_TERMINAL_PROMPT=0 GIT_NO_LAZY_FETCH=1 GIT_NO_REPLACE_OBJECTS=1
unset GIT_DIR GIT_WORK_TREE GIT_INDEX_FILE GIT_OBJECT_DIRECTORY GIT_ALTERNATE_OBJECT_DIRECTORIES GIT_NAMESPACE \
  GIT_CONFIG_PARAMETERS GIT_CONFIG_COUNT   # N4: no injected config; repo-local config stays readable (declared)
die() { printf 'scope_b: %s\n' "$2" >&2; exit "$1"; }

# ---- resolve OWN absolute path and hash it BEFORE any cd (corrects earlier relative-BASH_SOURCE defect) ----
SELF="${BASH_SOURCE[0]}"; case "$SELF" in /*) ;; *) SELF="$PWD/$SELF" ;; esac
SELF="$(realpath -- "$SELF")" || die 2 "cannot resolve own path"
SELFSHA="$(sha256sum < "$SELF" | cut -d' ' -f1)" || die 2 "cannot hash self"
# N4: these overrides are passed to EVERY git call
git() { command git -c core.quotepath=off -c log.mailmap=false -c color.ui=never -c log.follow=false -c diff.renames=false \
  -c log.decorate=false -c core.fsmonitor=false -c log.showSignature=false "$@"; }

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
printf 'git_version\t%s\nrepo_local_config\tREADABLE(not isolated; declared)\n' "$(git --version)" > "$OUT/00_env.tsv"
ND=NOT_DETERMINABLE_BY_EXISTENCE_CHECK; IND=INDEPENDENCE_NOT_DETERMINABLE
finish() { ( cd -- "$OUT" && find . -type f ! -name SHA256SUMS -print | sort | xargs -r -d '\n' sha256sum ) > "$OUT/SHA256SUMS"; exit "$1"; }

# ---- capture validity + THE ONE tip set (B1/N5/N12) -> tracked path universe at tips ----
SH="$(git rev-parse --is-shallow-repository 2>/dev/null)"; ok env.shallow $? 0
PC="$(git config --get extensions.partialClone)"; ok cfg.partialClone $? 0 1
if [ "$SH" != false ] || [ -n "$PC" ]; then printf 'overall\tCAPTURE_INVALID\n' > "$OUT/71_overall.tsv"; finish 3; fi
# tip set = refs/heads/**, refs/remotes/**, refs/tags/** only (never refs/stash, refs/notes/**); tags peeled; commit-type only
git for-each-ref --format='%(objectname) %(objecttype)' refs/heads refs/remotes refs/tags > "$TMP/refs" 2>>"$TMP/e"; ok refs $? 0
: > "$TMP/t0"
while read -r o ty; do case "$ty" in commit) printf '%s\n' "$o" >> "$TMP/t0" ;;
  tag) git rev-parse --verify -q "$o^{commit}" < /dev/null >> "$TMP/t0" 2>>"$TMP/e"; ok peel $? 0 1 ;; esac; done < "$TMP/refs"
sort -u "$TMP/t0" > "$TMP/tips"
[ -s "$TMP/tips" ] || { printf 'overall\tCAPTURE_INVALID\n' > "$OUT/71_overall.tsv"; finish 3; }
: > "$TMP/paths"   # N9: read with -z; an embedded newline is carried as \001 and restored before any pathspec/hash use
while IFS= read -r t; do git ls-tree -r -z --name-only --full-tree "$t" < /dev/null 2>>"$TMP/e" | tr '\n\0' '\001\n' >> "$TMP/paths"
  ok lstree "${PIPESTATUS[0]}" 0; done < "$TMP/tips"
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

# ---- B3/B4: executor set E = AUTHOR e-mails (%ae only) of non-merge commits touching the Protocol-A r1 CODE/TEST
# ROOTS over the full history of the tip set; every candidate-channel GLOB pattern and docs/knowledgeos/** excluded.
ROOTS=( app routes database resources config bootstrap lang public tests )
CHG=( '.github/workflows/**' '.gitlab-ci.yml' '**/jenkinsfile' '.circleci/**' 'azure-pipelines.yml' '.drone.yml' 'bitbucket-pipelines.yml'
  '.woodpecker*' '.woodpecker*/**' '.githooks/**' '.husky/**' '.pre-commit-config.yaml' '**/lefthook.yml' '**/.lefthook.yml' '**/codeowners' '**/*.tfstate' )
for t in program_status status ruling rulings register registry decision decisions adoption adoptions approval approvals signoff signoffs progress inforce norm; do
  for x in md markdown txt rst json yaml yml toml csv tsv; do CHG+=( "**/$t.$x" "**/$t[._-]*.$x" "**/$t/**/*.$x" "**/$t[._-]*/**/*.$x" ); done; done
for t in deploy deployment release environment env infra terraform; do CHG+=( "**/$t" "**/$t/**" "**/$t[._-]*" "**/$t[._-]*/**" ); done
PS=( ); for r in "${ROOTS[@]}"; do PS+=( ":(literal)$r" ); done
PS+=( ':(exclude,glob,icase)docs/knowledgeos/**' ); for g in "${CHG[@]}"; do PS+=( ":(exclude,glob,icase)$g" ); done
git log --stdin --no-merges --format='%ae' -- "${PS[@]}" < "$TMP/tips" 2>>"$TMP/e" | tr 'A-Z' 'a-z' | awk 'NF' | sort -u > "$TMP/E"
ok writers.exec "${PIPESTATUS[0]}" 0; nE=$(awk 'END{print NR}' "$TMP/E")
# B2: norm-register paths (K-STATUS rulings/register subset); N_c = their authors MINUS candidate c's own paths
awk -F'\t' '$1=="K-STATUS"{p=$0; sub(/^[^\t]*\t/,"",p); if (tolower(p) ~ /(^|\/)(rulings?|register|registry|decisions?|norm|inforce|authoriz)/) print p}' \
  "$TMP/cand" | sort -u > "$TMP/npaths"

# ---- per-channel metrics (counts only; W, commits, N_c all via --stdin over the one tip set) + path-id map ----
: > "$OUT/61_path_ids.tsv"; : > "$OUT/60_channel_metrics.tsv"
for ch in K-CI K-HOOK K-CODEOWNERS K-STATUS K-DEPLOY; do
  awk -v c="$ch" 'index($0, c "\t")==1{print substr($0, length(c)+2)}' "$TMP/cand" | sort -u > "$TMP/cp"
  np=$(awk 'END{print NR}' "$TMP/cp"); : > "$TMP/W"; nc=0
  while IFS= read -r p; do pr="${p//$'\001'/$'\n'}"
    id="$(printf '%s' "$pr" | sha256sum | cut -c1-16)"; printf '%s\t%s\n' "$id" "$ch" >> "$OUT/61_path_ids.tsv"
    git log --stdin --no-merges --format='%ae' -- ":(literal)$pr" < "$TMP/tips" > "$TMP/w1" 2>>"$TMP/e"; ok "w.$id" $? 0
    nc=$((nc + $(awk 'END{print NR}' "$TMP/w1"))); cat "$TMP/w1" >> "$TMP/W"
  done < "$TMP/cp"
  tr 'A-Z' 'a-z' < "$TMP/W" | awk 'NF' | sort -u > "$TMP/Wu"
  comm -23 "$TMP/npaths" "$TMP/cp" > "$TMP/ncp"; nnp=$(awk 'END{print NR}' "$TMP/ncp"); : > "$TMP/Nc"
  if [ -s "$TMP/ncp" ]; then NPS=( ); while IFS= read -r p; do NPS+=( ":(literal)${p//$'\001'/$'\n'}" ); done < "$TMP/ncp"
    git log --stdin --no-merges --format='%ae' -- "${NPS[@]}" < "$TMP/tips" 2>>"$TMP/e" | tr 'A-Z' 'a-z' | awk 'NF' | sort -u > "$TMP/Nc"
    ok "writers.norm.$ch" "${PIPESTATUS[0]}" 0; fi
  nw=$(awk 'END{print NR}' "$TMP/Wu"); nn=$(awk 'END{print NR}' "$TMP/Nc")
  oe=$(comm -12 "$TMP/Wu" "$TMP/E" | awk 'END{print NR}'); on=$(comm -12 "$TMP/Wu" "$TMP/Nc" | awk 'END{print NR}')
  printf '%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n' "$ch" "$np" "$nc" "$nw" "$oe" "$on" "$nnp" "$nn" >> "$OUT/60_channel_metrics.tsv"
done

# ---- K-SIG: signature PRESENCE only (justified object-header read; keyring not consulted) ----
git rev-list --stdin < "$TMP/tips" 2>>"$TMP/e" | sort -u > "$TMP/allc"; ok revlist "${PIPESTATUS[0]}" 0
nS=0; while IFS= read -r c; do
  git cat-file commit "$c" < /dev/null 2>>"$TMP/e" | awk '/^$/{exit} /^gpgsig/{s=1} END{exit (s?0:1)}' && nS=$((nS+1)); done < "$TMP/allc"
nTS=0; while IFS= read -r line; do set -- $line; [ "${2:-}" = tag ] || continue
  git cat-file tag "$1" < /dev/null 2>>"$TMP/e" | grep -qE '^-----BEGIN (PGP|SSH) SIGNATURE-----$' && nTS=$((nTS+1)); done < "$TMP/refs"
printf 'commits_total\t%s\ncommits_signed\t%s\ntags_signed\t%s\n' "$(awk 'END{print NR}' "$TMP/allc")" "$nS" "$nTS" > "$OUT/58_signatures.tsv"

# ---- K-LOCALHOOK: local .git/hooks presence (LOCAL_ONLY, unversioned) ----
HOOKS="applypatch-msg commit-msg post-checkout post-commit post-merge post-receive post-update pre-applypatch pre-commit pre-merge-commit pre-push pre-rebase pre-receive prepare-commit-msg update"
nh=0; for hk in $HOOKS; do [ -e "$GD/hooks/$hk" ] && nh=$((nh+1)); done
hp=unset; git config --get core.hooksPath > /dev/null 2>&1 && hp=set
printf 'standard_hooks_present\t%s\ncore_hooksPath\t%s\n' "$nh" "$hp" > "$OUT/56_local_hooks.tsv"

# ---- answers Q1..Q8 (deterministic; every outcome reachable; never a forced negative) ----
NA7='NOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)'
{
printf '#channel\tQ1_exists\tQ2_records\tQ3_controls\tQ4_ts_independent\tQ5_norm_at_t\tQ6_complete\tQ7_independent\tQ8_reproducible\n'
awk -F'\t' -v ND="$ND" -v IND="$IND" -v nE="$nE" 'BEGIN{OFS="\t"; na="NOT_APPLICABLE(Q1)"}
{ ch=$1; np=$2+0; nw=$4+0; oe=$5+0; on=$6+0; nnp=$7+0; nn=$8+0
  if (np==0){ print ch,"NOT_EVIDENCED_AT_TIPS",na,na,na,na,na,na,na; next }
  cfg=(ch=="K-CI"||ch=="K-HOOK"||ch=="K-CODEOWNERS")
  q2=cfg?"CONFIG_RULE(by type; content not read)":(ch=="K-STATUS"?"STATUS/NORM_RECORD(by type; content not read)":"STATE_RECORD_BY_TYPE(content not read)")
  ov="overlap_exec=" oe ";overlap_norm=" (nnp>0 ? on : "NA")
  if (ch=="K-STATUS") q7="SELF_ATTESTED[norm_register;" ov "]"
  else if (nnp==0) q7=IND "[no_norm_source_distinct_from_candidate;" ov "]"
  else if (cfg) q7=IND "[mechanism_host_enforced_unobservable;" ov "]"
  else if (nw==0 || nn==0 || nE==0) q7=IND "[empty_set;W=" nw ";N_c=" nn ";E=" nE "]"
  else if (on>0) q7="NOT_INDEPENDENT[overlap_norm=" on "]"
  else if (oe>0) q7="NOT_INDEPENDENT[overlap_exec=" oe "]"
  else q7="INDEPENDENT_AT_IDENTITY_COUNT_LEVEL[aliases_unresolved]"
  print ch,"YES_IN_CAPTURE",q2,"CONTROLLED_BY_WRITERS[n=" nw "]","NO_CLIENT_SET(git recording times are client-generated)",ND,ND "[COMPLETE_VS_HOST=NOT_SHOWN]",q7,"YES_LOCAL(content-addressed; rerunnable on same ref set)" }' \
  "$OUT/60_channel_metrics.tsv"
read -r nC nSg nTg < <(awk -F'\t' '{v[$1]=$2} END{print v["commits_total"]+0, v["commits_signed"]+0, v["tags_signed"]+0}' "$OUT/58_signatures.tsv")
if [ "$nSg" -gt 0 ] || [ "$nTg" -gt 0 ]; then
  printf 'K-SIG\tYES_IN_CAPTURE\tIDENTITY_ATTESTATION(presence only; not verified)\t%s(keyring not consulted)\tNO_CLIENT_SET(signer clock)\t%s\t%s[COMPLETE_VS_HOST=NOT_SHOWN]\t%s(signatures not verified)\tYES_LOCAL(presence only)\n' "$ND" "$ND" "$ND" "$IND"
else printf "K-SIG\tNOT_EVIDENCED_AT_TIPS\t$NA7\n"; fi
read -r hn hpv < <(awk -F'\t' '{v[$1]=$2} END{print v["standard_hooks_present"]+0, v["core_hooksPath"]}' "$OUT/56_local_hooks.tsv")
if [ "$hn" -gt 0 ] || [ "$hpv" = set ]; then
  printf 'K-LOCALHOOK\tYES_LOCAL_ONLY\tCONFIG_RULE(by type; content not read)\tLOCAL_MACHINE\tNONE\t%s\t%s[COMPLETE_VS_HOST=NOT_SHOWN]\t%s(local, unversioned)\tNO_LOCAL_ONLY(not versioned; not reproducible from the ref set)\n' "$ND" "$ND" "$IND"
else printf "K-LOCALHOOK\tNOT_EVIDENCED_LOCALLY\t$NA7\n"; fi
for hc in K-HOST-AUDIT K-HOST-BRANCHPROT K-HOST-PR-APPROVALS K-CI-RUNS K-HOST-MEMBERS; do
  printf '%s\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\tNOT_CHECKABLE_LOCALLY\n' "$hc"; done
} > "$OUT/70_answers.tsv"

# ---- overall: headline COMPUTED from the per-channel Q7 values (N2/N14) + fail-closed identity guard (N6) ----
q7n() { awk -F'\t' -v r="$1" 'NR>1 && $8 ~ r {c++} END{print c+0}' "$OUT/70_answers.tsv"; }
nI=$(q7n '^INDEPENDENT_AT_IDENTITY_COUNT_LEVEL'); nD=$(q7n '^INDEPENDENCE_NOT_DETERMINABLE'); nX=$(q7n '^NOT_INDEPENDENT'); nA=$(q7n '^SELF_ATTESTED')
if [ "$nI" -gt 0 ]; then HL=LOCAL_INDEPENDENT_CANDIDATE_AT_IDENTITY_COUNT_LEVEL; elif [ "$nD" -gt 0 ]; then HL=LOCAL_INDEPENDENCE_NOT_DETERMINABLE
elif [ $((nX+nA)) -gt 0 ]; then HL=LOCAL_NO_INDEPENDENT_CANDIDATE_OBSERVED; else HL=LOCAL_NO_CANDIDATE_EVIDENCED; fi
nNE=$(awk -F'\t' 'NR>1 && $2 ~ /^NOT_EVIDENCED/{c++} END{print c+0}' "$OUT/70_answers.tsv")
printf 'headline\t%s\ncomplete_vs_host\tNOT_SHOWN\nindependent_at_identity_count_level\t%s\nopen_local_candidates\t%s\nnot_independent_by_overlap\t%s\nself_attested\t%s\nnot_evidenced\t%s\nhost_channels\t5\n' \
  "$HL" "$nI" "$nD" "$nX" "$nA" "$nNE" > "$OUT/71_overall.tsv"
code=0; [ -s "$ERR" ] && code=1
grep -rlE '@|BEGIN (PGP|SSH) SIGNATURE' -- "$OUT" > "$TMP/hit" 2>/dev/null; g=$?
if [ "$g" -eq 1 ]; then printf 'identity_guard\tPASS\n' >> "$OUT/71_overall.tsv"
elif [ "$g" -eq 0 ]; then printf 'identity_guard\tFAIL(%s files)\n' "$(awk 'END{print NR}' "$TMP/hit")" >> "$OUT/71_overall.tsv"; code=1
else printf 'identity_guard\tFAIL(grep_rc=%s)\n' "$g" >> "$OUT/71_overall.tsv"; code=1; fi
finish "$code"
