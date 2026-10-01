#!/usr/bin/env bash
# scope-r2.sh -- frozen by SPEC-r2 Part C. Existence and metadata only. Do not edit.
# Capture = the same local ref set as rank0-r2.sh. Read-only. No network. No host access. No `set -e`.
# Writes NO personal identities: no names, e-mails, user names, ref names, URLs, CODEOWNERS owners or path strings.
# Paths appear only as 16-hex SHA-256 ids; identities are compared inside a private temp dir; only counts are written.
# Usage (from anywhere inside the working clone):  OUT=/absolute/path/to/new/empty/dir bash scope-r2.sh
# Exit: 0 completed | 1 completed with unexpected exit codes or identity-guard failure | 2 refused to start | 3 capture invalid
set -u -o pipefail
export LC_ALL=C TZ=UTC GIT_PAGER=cat PAGER=cat GIT_CONFIG_NOSYSTEM=1 GIT_CONFIG_GLOBAL=/dev/null \
  GIT_NO_REPLACE_OBJECTS=1 GIT_OPTIONAL_LOCKS=0 GIT_NO_LAZY_FETCH=1 GIT_TERMINAL_PROMPT=0
unset GIT_DIR GIT_WORK_TREE GIT_INDEX_FILE GIT_OBJECT_DIRECTORY GIT_ALTERNATE_OBJECT_DIRECTORIES GIT_NAMESPACE GIT_CEILING_DIRECTORIES
TAB=$'\t'
die() { printf 'scope-r2: %s\n' "$2" >&2; exit "$1"; }

# ---- P0 preconditions (exit 2) ----
OUT="${OUT:-}"; [ -n "$OUT" ] || die 2 "OUT is not set"
case "$OUT" in /*) ;; *) die 2 "OUT must be an absolute path" ;; esac
[ "$(git rev-parse --is-inside-work-tree 2>/dev/null)" = true ] || die 2 "not inside a work tree"
[ "$(git rev-parse --is-bare-repository 2>/dev/null)" = false ] || die 2 "bare repository"
TOP="$(git rev-parse --show-toplevel 2>/dev/null)" || die 2 "no toplevel"
GD="$(git rev-parse --absolute-git-dir 2>/dev/null)" || die 2 "no git dir"
OUT="$(realpath -m -- "$OUT")" || die 2 "realpath failed"
TOPR="$(realpath -- "$TOP")"; GDR="$(realpath -- "$GD")"
for d in "$TOPR" "$GDR"; do case "$OUT/" in "$d"/*) die 2 "OUT must be outside the repository" ;; esac; done
if [ -e "$OUT" ]; then { [ -d "$OUT" ] && [ -z "$(ls -A -- "$OUT")" ]; } || die 2 "OUT exists and is not an empty directory"; fi
TMP="$(mktemp -d)" || die 2 "mktemp failed"; TMP="$(realpath -- "$TMP")"
case "$TMP/" in "$TOPR"/*|"$GDR"/*|"$OUT"/*) rm -rf -- "$TMP"; die 2 "TMPDIR must be outside the repository and OUT" ;; esac
trap 'rm -rf -- "$TMP"' EXIT
mkdir -p -- "$OUT" || die 2 "cannot create OUT"
cd -- "$TOP" || die 2 "cannot cd to the toplevel"

ERR="$OUT/00_errors.tsv"; : > "$ERR"; : > "$TMP/stderr"
ok() { local l="$1" rc="$2" a; shift 2; for a in "$@"; do [ "$rc" -eq "$a" ] && return 0; done; printf '%s\t%s\n' "$l" "$rc" >> "$ERR"; return 1; }
g() { git --no-replace-objects -c core.quotepath=off -c core.fsmonitor=false -c log.showSignature=false \
  -c log.mailmap=false -c log.decorate=false -c log.showRoot=true -c diff.renames=false -c color.ui=never "$@" 2>>"$TMP/stderr"; }
cnt() { awk 'END{print NR}' "$1"; }
iso() { if [ "$1" = - ]; then echo -; else date -u -d "@$1" +%Y-%m-%dT%H:%M:%SZ; fi; }
finish() {
  cnt "$TMP/stderr" > "$OUT/00_stderr_line_count.txt"
  ( cd -- "$OUT" && find . -type f ! -name SHA256SUMS -print | sort | xargs -r -d '\n' sha256sum ) > "$OUT/SHA256SUMS"
  sha256sum < "$OUT/SHA256SUMS" | cut -d' ' -f1
  exit "$1"
}
SS="$(sha256sum < "${BASH_SOURCE[0]}")"; ok script.sha256 $? 0; printf '%s\n' "${SS%% *}" > "$OUT/00_script.sha256"
SPAN_LO_ISO=2026-08-02T00:00:00Z
SPAN_LO="$(date -u -d "$SPAN_LO_ISO" +%s)"; SPAN_HI="$(date -u -d 2026-08-16T00:00:00Z +%s)"

# ---- S0 environment and validity (same gate as rank-0) ----
SH="$(g rev-parse --is-shallow-repository)"; ok env.shallow $? 0
PC="$(g config --get extensions.partialClone)"; ok cfg.partialClone $? 0 1
PR="$(g config --get-regexp '^remote\..*\.(promisor|partialclonefilter)$')"; ok cfg.promisor $? 0 1
NPR="$(printf '%s' "$PR" | awk 'NF{c++} END{print c+0}')"
printf 'git_version\t%s\nis_shallow\t%s\npartial_clone\t%s\npromisor_or_filter_entries\t%s\n' "$(git --version)" "$SH" \
  "$([ -n "$PC" ] && echo set || echo unset)" "$NPR" > "$OUT/00_env.tsv"
if [ "$SH" != false ] || [ -n "$PC" ] || [ "$NPR" != 0 ]; then printf 'overall\tCAPTURE_INVALID\n' > "$OUT/71_overall.tsv"; finish 3; fi

# ---- S1 capture (ref names stay in TMP; only a hash and counts are written) ----
g for-each-ref --format='%(objectname)%09%(objecttype)%09%(*objectname)%09%(*objecttype)%09%(refname)' > "$TMP/refs.raw"; ok refs $? 0
sort -t "$TAB" -k5,5 "$TMP/refs.raw" > "$TMP/refs.tsv"; ok refs.sort $? 0
RS="$(sha256sum < "$TMP/refs.tsv")"; ok refs.hash $? 0; printf '%s\n' "${RS%% *}" > "$OUT/50_capture_refs.sha256"   # equals SHA-256 of rank-0 01_refs.tsv
awk -F'\t' '$2=="commit"{print $1; next} $2=="tag" && $4=="commit"{print $3}' "$TMP/refs.tsv" | sort -u > "$TMP/tips"
awk -F'\t' '{c = ($5 ~ /^refs\/heads\//) ? "heads" : (($5 ~ /^refs\/remotes\//) ? "remotes" : (($5 ~ /^refs\/tags\//) ? "tags" : (($5 ~ /^refs\/notes\//) ? "notes" : "other"))); n[c]++}
  END{printf "heads\t%d\nremotes\t%d\ntags\t%d\nnotes\t%d\nother\t%d\n", n["heads"], n["remotes"], n["tags"], n["notes"], n["other"]}' "$TMP/refs.tsv" > "$OUT/50b_ref_counts.tsv"
if [ ! -s "$TMP/tips" ]; then printf 'overall\tCAPTURE_INVALID\n' > "$OUT/71_overall.tsv"; finish 3; fi

# ---- S2 path universe over history (SC-3) ----
g log --stdin -m --no-renames --name-only --format= < "$TMP/tips" > "$TMP/pa.raw"; ok paths.history $? 0
awk 'NF' "$TMP/pa.raw" | sort -u > "$TMP/paths_all"
: > "$TMP/pt.raw"
while IFS= read -r t <&3; do g ls-tree -r --name-only --full-tree "$t" < /dev/null >> "$TMP/pt.raw"; ok "lstree.$t" $? 0; done 3< "$TMP/tips"
sort -u "$TMP/pt.raw" > "$TMP/paths_tips"
NA=$(cnt "$TMP/paths_all"); NT=$(comm -12 "$TMP/paths_all" "$TMP/paths_tips" | awk 'END{print NR}')
printf 'paths_ever\t%s\npaths_ever_and_at_some_tip\t%s\npaths_ever_not_at_any_tip\t%s\npaths_ever_under_dot_github\t%s\n' \
  "$NA" "$NT" "$((NA - NT))" "$(awk '/^[.]github\//' "$TMP/paths_all" | awk 'END{print NR}')" > "$OUT/51_path_universe.tsv"

# ---- S3 candidate channels (fixed vocabulary; whole tokens; SC-7) ----
AWK_CH=$(cat <<'EOF'
BEGIN { FS = "\t"
  split("register registers ruling rulings decision decisions", a, " "); for (i in a) REG[a[i]] = 1
  split("session sessions", a, " "); for (i in a) SESS[a[i]] = 1
  PROG["progress"] = 1
  split("status audit audits ledger ledgers adopt adopted adoption adoptions approval approvals signoff signoffs queue workplan workplans context norm norms inforce", a, " "); for (i in a) STAT[a[i]] = 1
  split("md markdown txt rst adoc org json yaml yml csv tsv toml log xml html", a, " "); for (i in a) DOCX[a[i]] = 1 }
{ p = $0; q = tolower(p); w = "/" q; b = q; sub(/.*\//, "", b)
  e = ""; if (b ~ /[.][^.]+$/) { e = b; sub(/.*[.]/, "", e) }
  if (w ~ /^\/[.]github\/workflows\// || q == ".gitlab-ci.yml" || b == "jenkinsfile" || w ~ /^\/[.]circleci\// || q == "azure-pipelines.yml" || q == ".drone.yml" || q == "bitbucket-pipelines.yml" || w ~ /^\/[.]buildkite\// || q == ".woodpecker.yml" || w ~ /^\/[.]woodpecker\//) print "K-CI\t" p "\tci-path"
  if (w ~ /^\/[.]githooks\// || w ~ /^\/[.]husky\// || q == ".pre-commit-config.yaml" || b == "lefthook.yml" || b == ".lefthook.yml") print "K-HOOK\t" p "\thook-path"
  if (b == "codeowners") print "K-CFG\t" p "\tcodeowners"
  if (e != "" && !(e in DOCX)) next
  n = split(q, t, /[^a-z0-9]+/); r = ""; s = ""; gg = ""; x = ""
  for (i = 1; i <= n; i++) { k = t[i]; if (k == "") continue
    if (i < n && ((k == "in" && t[i+1] == "force") || (k == "sign" && t[i+1] == "off") || (k == "work" && t[i+1] == "plan"))) k = k t[i+1]
    if (r == "" && (k in REG)) r = k
    if (s == "" && (k in SESS)) s = k
    if (gg == "" && (k in PROG)) gg = k
    if (x == "" && (k in STAT)) x = k }
  if (r != "") print "K-REG\t" p "\t" r
  if (s != "") print "K-SESS\t" p "\t" s
  if (gg != "") print "K-PROG\t" p "\t" gg
  if (x != "") print "K-STATUS\t" p "\t" x }
EOF
)
awk "$AWK_CH" "$TMP/paths_all" | sort -u > "$TMP/cand.tsv"; ps=("${PIPESTATUS[@]}"); ok cand "${ps[0]}" 0

# ---- S4 per-path history metrics (ids only) ----
declare -A SIG
sigof() { if [ -z "${SIG[$1]:-}" ]; then g cat-file commit "$1" < /dev/null > "$TMP/obj"; ok "obj.$1" $? 0
  SIG[$1]="$(awk '/^$/{exit} /^gpgsig/{s=1} END{print (s ? "SIGNED" : "UNSIGNED")}' "$TMP/obj")"; fi; SIGR="${SIG[$1]}"; }
mkdir -p "$TMP/w" "$TMP/hc"; : > "$TMP/pm.tsv"; : > "$TMP/pid.tsv"
cut -f2 "$TMP/cand.tsv" | sort -u > "$TMP/cpaths"
while IFS= read -r p <&3; do
  id="$(printf '%s' "$p" | sha256sum | cut -c1-16)"
  g log --stdin --no-merges --full-history --no-renames --name-status --format='::c::%x09%H%x09%ct%x09%ae%x09%ce' -- ":(literal)$p" < "$TMP/tips" > "$TMP/h"; ok "hist.$id" $? 0
  awk -F'\t' '$1=="::c::"{print $2 "\t" $3}' "$TMP/h" > "$TMP/hc/$id"
  awk -F'\t' '$1=="::c::"{print tolower($4); print tolower($5)}' "$TMP/h" | awk 'NF' | sort -u > "$TMP/w/$id"
  nc=$(cnt "$TMP/hc/$id")
  lo=$(awk -F'\t' 'NR==1{m=$2} $2<m{m=$2} END{print (NR ? m : "-")}' "$TMP/hc/$id")
  hi=$(awk -F'\t' 'NR==1{m=$2} $2>m{m=$2} END{print (NR ? m : "-")}' "$TMP/hc/$id")
  sp=$(awk -F'\t' -v a="$SPAN_LO" -v b="$SPAN_HI" '$2>=a && $2<b{c++} END{print c+0}' "$TMP/hc/$id")
  dl=$(awk -F'\t' '$1 ~ /^D/{c++} END{print (c ? 1 : 0)}' "$TMP/h")
  grep -Fxq -- "$p" "$TMP/paths_tips"; rc=$?; ok "attip.$id" "$rc" 0 1; tp=$((rc == 0 ? 1 : 0))
  ns=0; while IFS="$TAB" read -r c _ <&4; do sigof "$c"; if [ "$SIGR" = SIGNED ]; then ns=$((ns + 1)); fi; done 4< "$TMP/hc/$id"
  printf '%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n' "$id" "$nc" "$lo" "$hi" "$sp" "$dl" "$tp" "$ns" "$(cnt "$TMP/w/$id")" >> "$TMP/pm.tsv"
  printf '%s\t%s\n' "$p" "$id" >> "$TMP/pid.tsv"
done 3< "$TMP/cpaths"

# ---- S5 writer sets (ACTOR_SET_ID; kept in TMP; only counts leave) ----
EXC=(':(exclude,glob,icase)**/*.md' ':(exclude,glob,icase)**/*.markdown' ':(exclude,glob,icase)**/*.txt' ':(exclude,glob,icase)**/*.rst'
     ':(exclude,glob,icase)**/*.adoc' ':(exclude,glob,icase)**/*.org')
for d in doc docs governance ruling rulings decision decisions adr adrs session sessions log logs notes plan plans planning minutes; do
  EXC+=(":(exclude,glob,icase)**/$d/**"); done
g log --stdin --no-merges --full-history --format='%ae%n%ce' -- . "${EXC[@]}" < "$TMP/tips" > "$TMP/we.raw"; ok writers.exec $? 0
tr 'A-Z' 'a-z' < "$TMP/we.raw" | awk 'NF' | sort -u > "$TMP/w_exec"; ps=("${PIPESTATUS[@]}"); ok we.tr "${ps[0]}" 0
awk -F'\t' '$1=="K-REG"{print $2}' "$TMP/cand.tsv" | sort -u > "$TMP/cp_reg"
: > "$TMP/w_reg.raw"
while IFS= read -r p <&3; do id="$(awk -F'\t' -v p="$p" '$1==p{print $2; exit}' "$TMP/pid.tsv")"; cat "$TMP/w/$id" >> "$TMP/w_reg.raw"; done 3< "$TMP/cp_reg"
sort -u "$TMP/w_reg.raw" > "$TMP/w_reg"

# ---- S6 per-path and per-channel metrics (no identities, no path strings) ----
: > "$OUT/61_path_metrics.tsv"
while IFS="$TAB" read -r id nc lo hi sp dl tp ns nw <&3; do
  oe=$(comm -12 "$TMP/w/$id" "$TMP/w_exec" | awk 'END{print NR}'); orr=$(comm -12 "$TMP/w/$id" "$TMP/w_reg" | awk 'END{print NR}')
  cls=$(awk -F'\t' -v i="$id" 'FILENAME==ARGV[1]{if ($2==i) P[$1]=1; next} ($2 in P){printf "%s%s:%s", (n++ ? "," : ""), $1, $3} END{print ""}' "$TMP/pid.tsv" "$TMP/cand.tsv")
  ext=$(awk -F'\t' -v i="$id" '$2==i{b=$1; sub(/.*\//, "", b); e="none"; if (b ~ /[.][A-Za-z0-9]{1,8}$/) {e=tolower(b); sub(/.*[.]/, "", e)}; print e; exit}' "$TMP/pid.tsv")
  printf '%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n' "$id" "$cls" "$ext" "$nc" "$(iso "$lo")" "$(iso "$hi")" "$sp" "$dl" "$tp" "$ns" "$nw" "$oe" "$orr" >> "$OUT/61_path_metrics.tsv"
done 3< "$TMP/pm.tsv"
sort -o "$OUT/61_path_metrics.tsv" "$OUT/61_path_metrics.tsv"
: > "$OUT/60_channel_metrics.tsv"
for ch in K-REG K-SESS K-PROG K-STATUS K-CFG K-CI K-HOOK; do
  awk -F'\t' -v c="$ch" '$1==c{print $2}' "$TMP/cand.tsv" | sort -u > "$TMP/cp"
  : > "$TMP/cids"; while IFS= read -r p <&3; do awk -F'\t' -v p="$p" '$1==p{print $2; exit}' "$TMP/pid.tsv" >> "$TMP/cids"; done 3< "$TMP/cp"
  np=$(cnt "$TMP/cids"); : > "$TMP/cc"; : > "$TMP/cw"; at=0; de=0
  while IFS= read -r id <&3; do cat "$TMP/hc/$id" >> "$TMP/cc"; cat "$TMP/w/$id" >> "$TMP/cw"
    at=$((at + $(awk -F'\t' -v i="$id" '$1==i{print $7}' "$TMP/pm.tsv"))); de=$((de + $(awk -F'\t' -v i="$id" '$1==i{print $6}' "$TMP/pm.tsv"))); done 3< "$TMP/cids"
  sort -u "$TMP/cc" > "$TMP/ccu"; sort -u "$TMP/cw" > "$TMP/cwu"
  ncm=$(cnt "$TMP/ccu")
  lo=$(awk -F'\t' 'NR==1{m=$2} $2<m{m=$2} END{print (NR ? m : "-")}' "$TMP/ccu"); hi=$(awk -F'\t' 'NR==1{m=$2} $2>m{m=$2} END{print (NR ? m : "-")}' "$TMP/ccu")
  sp=$(awk -F'\t' -v a="$SPAN_LO" -v b="$SPAN_HI" '$2>=a && $2<b{c++} END{print c+0}' "$TMP/ccu")
  sg=0; while IFS="$TAB" read -r c _ <&3; do sigof "$c"; if [ "$SIGR" = SIGNED ]; then sg=$((sg + 1)); fi; done 3< "$TMP/ccu"
  oe=$(comm -12 "$TMP/cwu" "$TMP/w_exec" | awk 'END{print NR}'); orr=$(comm -12 "$TMP/cwu" "$TMP/w_reg" | awk 'END{print NR}')
  printf '%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n' "$ch" "$np" "$at" "$de" "$ncm" "$(iso "$lo")" "$(iso "$hi")" "$sp" "$sg" "$(cnt "$TMP/cwu")" "$oe" "$orr" >> "$OUT/60_channel_metrics.tsv"
done

# ---- S7 CODEOWNERS: counts of rules and owner-token types only ----
: > "$OUT/62_codeowners_counts.tsv"
awk -F'\t' '$1=="K-CFG"{print $2}' "$TMP/cand.tsv" | sort -u > "$TMP/cp_cfg"
while IFS= read -r p <&3; do
  id="$(awk -F'\t' -v p="$p" '$1==p{print $2; exit}' "$TMP/pid.tsv")"; H="$(awk -F'\t' 'NR==1{print $1}' "$TMP/hc/$id")"
  if [ -z "$H" ]; then printf '%s\tno_nonmerge_commit\t0\t0\t0\t0\t0\n' "$id" >> "$OUT/62_codeowners_counts.tsv"; continue; fi
  g cat-file -e "$H:$p" < /dev/null; rc=$?; ok "coexists.$id" "$rc" 0 1 128
  if [ "$rc" -eq 0 ]; then
    g cat-file blob "$H:$p" < /dev/null > "$TMP/co"; ok "coblob.$id" $? 0
    awk -v pid="$id" '/^[ \t]*#/ || NF == 0 {next}
      {r++; for (j = 2; j <= NF; j++) {o = $j; if (o ~ /^@[^\/]+\/./) tm++; else if (o ~ /^@/) us++; else if (o ~ /@/) em++; else ot++}}
      END{printf "%s\tpresent_at_latest_touch\t%d\t%d\t%d\t%d\t%d\n", pid, r, tm, us, em, ot}' "$TMP/co" >> "$OUT/62_codeowners_counts.tsv"
  else printf '%s\tdeleted_at_latest_touch\t0\t0\t0\t0\t0\n' "$id" >> "$OUT/62_codeowners_counts.tsv"; fi
done 3< "$TMP/cp_cfg"

# ---- S8 local hooks (LOCAL_ONLY; standard names only) ----
HOOKS="applypatch-msg commit-msg fsmonitor-watchman p4-changelist p4-post-changelist p4-pre-submit p4-prepare-changelist post-applypatch post-checkout post-commit post-index-change post-merge post-receive post-rewrite post-update pre-applypatch pre-auto-gc pre-commit pre-merge-commit pre-push pre-rebase pre-receive prepare-commit-msg push-to-checkout reference-transaction sendemail-validate update"
{ g config --get core.hooksPath > /dev/null; rc=$?; ok cfg.hooksPath "$rc" 0 1; printf 'core.hooksPath\t%s\n' "$([ "$rc" -eq 0 ] && echo set || echo unset)"
  nh=0; for hk in $HOOKS; do if [ -e "$GD/hooks/$hk" ]; then printf 'hook\t%s\tpresent\n' "$hk"; nh=$((nh + 1)); fi; done
  ls -1A -- "$GD/hooks" > "$TMP/hk" 2>/dev/null; rc=$?; ok ls.hooks "$rc" 0 2
  printf 'standard_hooks_present\t%s\n' "$nh"
  printf 'other_non_sample_entries\t%s\n' "$(awk -v known="$HOOKS" 'BEGIN{n=split(known, a, " "); for (i = 1; i <= n; i++) k[a[i]] = 1} !($0 in k) && $0 !~ /[.]sample$/ {c++} END{print c+0}' "$TMP/hk")"
} > "$OUT/56_local_hooks_LOCAL_ONLY.tsv"
NHK=$(awk -F'\t' '$1=="standard_hooks_present"{print $2}' "$OUT/56_local_hooks_LOCAL_ONLY.tsv"); HKP=$(awk -F'\t' '$1=="core.hooksPath"{print $2}' "$OUT/56_local_hooks_LOCAL_ONLY.tsv")

# ---- S9 signatures, tags, notes (presence only; keyring not consulted) ----
g rev-list --stdin < "$TMP/tips" > "$TMP/allc"; ok revlist $? 0
nS=0; nU=0; while IFS= read -r c <&3; do sigof "$c"; if [ "$SIGR" = SIGNED ]; then nS=$((nS + 1)); else nU=$((nU + 1)); fi; done 3< "$TMP/allc"
nTA=0; nTS=0
while IFS="$TAB" read -r o ty _ _ rn <&3; do
  case "$rn" in refs/tags/*) ;; *) continue ;; esac
  [ "$ty" = tag ] || continue
  nTA=$((nTA + 1)); g cat-file tag "$o" < /dev/null > "$TMP/tg"; ok "tag.$o" $? 0
  grep -qE '^-----BEGIN (PGP|SSH) SIGNATURE-----$' "$TMP/tg"; rc=$?; ok "tagsig.$o" "$rc" 0 1; [ "$rc" -eq 0 ] && nTS=$((nTS + 1))
done 3< "$TMP/refs.tsv"
nTL=$(awk -F'\t' '$5 ~ /^refs\/tags\// && $2=="commit"' "$TMP/refs.tsv" | awk 'END{print NR}')
nN=$(awk -F'\t' '$5 ~ /^refs\/notes\//' "$TMP/refs.tsv" | awk 'END{print NR}')
printf 'commits_signed\t%s\ncommits_unsigned\t%s\ntags_annotated\t%s\ntags_annotated_signed\t%s\ntags_lightweight\t%s\nnotes_refs\t%s\n' \
  "$nS" "$nU" "$nTA" "$nTS" "$nTL" "$nN" > "$OUT/58_signature_counts.tsv"

# ---- S10 answers Q1-Q8 (frozen mapping, C.5-r2) ----
AWK_ANS=$(cat <<'EOF'
BEGIN { FS = "\t"; OFS = "\t"; ND = "NOT_DETERMINABLE_BY_EXISTENCE_CHECK"; NA = "NOT_APPLICABLE(Q1)" }
{ ch = $1; np = $2 + 0; lo = $6; sp = $8 + 0; oe = $11 + 0; orr = $12 + 0
  cfg = (ch == "K-CFG" || ch == "K-CI" || ch == "K-HOOK")
  if (np == 0) { print ch, "NOT_EVIDENCED_IN_CAPTURE", NA, NA, NA, NA, NA, NA, NA; next }
  q2 = (ch == "K-REG") ? "NORM_TEXT,ADOPTION_EVENT(by channel type; content not read)" : (cfg ? "CONFIG_RULE(by channel type)" : ND "(content not read)")
  if (ch == "K-REG") q3 = "NORM_MAKER(by construction)"
  else if (oe && orr) q3 = "MIXED[ACTOR_SET_ID]"
  else if (oe) q3 = "INCLUDES_EXECUTOR_WRITERS[ACTOR_SET_ID]"
  else if (orr) q3 = "INCLUDES_REGISTER_WRITERS[ACTOR_SET_ID]"
  else q3 = ND
  q4 = "CLIENT_SET(git recording times);content_timestamps=" ND
  if (cfg) q5 = "NO_BY_TYPE(configuration rule, not a norm-state record)"
  else if (ch == "K-REG") q5 = "NO_AS_NIF_A(norm-maker channel)"
  else if (oe || orr) q5 = "NO_AS_NIF_A[ACTOR_SET_ID](writers overlap register or executor writers)"
  else q5 = ND
  k2 = (lo != "-" && lo < span_lo) ? "INDICATED[SOURCE_STATED]" : "NOT_INDICATED"
  k3 = cfg ? (sp ? "CHANGED_IN_CAPTURE_DURING_CORPUS_SPAN" : "NO_CHANGE_IN_CAPTURE(not shown stable)") : "NOT_APPLICABLE"
  q6 = ND "[K1=" ND ";K2=" k2 ";K3=" k3 ";K4=" ND ";COMPLETE_SHOWN=NOT_REACHABLE_BY_THIS_PROTOCOL]"
  i1 = (ch == "K-REG") ? "FAILS(norm-maker)" : ((oe || orr) ? "FAILS[ACTOR_SET_ID]" : ND)
  q7 = "NOT_INDEPENDENT_AS_GIT_RECORD(I3 fails: client-set recording times);I1=" i1 ";I2=" ND ";I4=" ND ";content_level=" ND
  q8 = "YES_LOCAL(content-addressed; rerunnable on the same ref set)"
  print ch, "YES_IN_CAPTURE", q2, q3, q4, q5, q6, q7, q8 }
EOF
)
awk -v span_lo="$SPAN_LO_ISO" "$AWK_ANS" "$OUT/60_channel_metrics.tsv" > "$OUT/70_answers.tsv"; ok answers $? 0
ND=NOT_DETERMINABLE_BY_EXISTENCE_CHECK
{ if [ "$nS" -gt 0 ] || [ "$nTS" -gt 0 ]; then
    printf 'K-SIG\tYES_IN_CAPTURE\tIDENTITY_ATTESTATION\t%s(keyring not consulted)\tCLIENT_SET(signer clock)\tNO_BY_TYPE(identity attestation, not a norm-state record)\t%s\t%s(signatures not verified)\tYES_LOCAL(presence only)\n' "$ND" "$ND" "$ND"
  else printf 'K-SIG\tNOT_EVIDENCED_IN_CAPTURE\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\n'; fi
  if [ "$nN" -gt 0 ]; then
    printf 'K-NOTES\tYES_IN_CAPTURE\t%s(content not read)\t%s\tCLIENT_SET(git recording times)\t%s\t%s\tNOT_INDEPENDENT_AS_GIT_RECORD(I3 fails);content_level=%s\tYES_LOCAL\n' "$ND" "$ND" "$ND" "$ND" "$ND"
  else printf 'K-NOTES\tNOT_EVIDENCED_IN_CAPTURE\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\n'; fi
  if [ "$NHK" -gt 0 ] || [ "$HKP" = set ]; then
    printf 'K-LOCALHOOK\tYES_LOCAL_ONLY\tCONFIG_RULE(by channel type)\t%s(local machine)\tNONE\tNO_BY_TYPE\t%s\t%s\tNO(local, unversioned)\n' "$ND" "$ND" "$ND"
  else printf 'K-LOCALHOOK\tNOT_EVIDENCED_LOCALLY\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\n'; fi
  for hc in K-HOST-CFG K-HOST-PR K-HOST-AUDIT K-HOST-MEMBERS K-CI-RUNS; do
    printf '%s' "$hc"; for q in 1 2 3 4 5 6 7 8; do printf '\t%s(HOST_NOT_AUTHORIZED)' "$ND"; done; printf '\n'; done
} >> "$OUT/70_answers.tsv"

# ---- S11 overall result (SC-1, SC-2: never a negative) ----
OPEN=$(awk -F'\t' '($1=="K-SESS" || $1=="K-PROG" || $1=="K-STATUS" || $1=="K-NOTES") && $2=="YES_IN_CAPTURE" && $6 ~ /^NOT_DETERMINABLE/' "$OUT/70_answers.tsv" | awk 'END{print NR}')
EXCL=$(awk -F'\t' '$2 ~ /^YES/ && $6 ~ /^NO_/' "$OUT/70_answers.tsv" | awk 'END{print NR}')
NEV=$(awk -F'\t' '$2 ~ /^NOT_EVIDENCED/' "$OUT/70_answers.tsv" | awk 'END{print NR}')
printf 'headline\tNIF_A_NOT_DETERMINABLE_BY_EXISTENCE_CHECK\nnif_a_channel_exists\tNOT_REACHABLE_BY_THIS_PROTOCOL\ncomplete_shown\tNOT_REACHABLE_BY_THIS_PROTOCOL\nopen_local_candidates\t%s\nopen_host_channels\t5\nexcluded_local_channels\t%s\nnot_evidenced_local_channels\t%s\ndcs_v1\tDCS_v0(located paths: see 61, classes K-REG,K-SESS,K-PROG)\nec_v1\tLOCAL_REF_SET(CI runs %s)\n' \
  "$OPEN" "$EXCL" "$NEV" "$ND" > "$OUT/71_overall.tsv"
code=0; [ -s "$ERR" ] && code=1
grep -rlF -- '@' "$OUT" > "$TMP/at"; rc=$?; ok guard "$rc" 0 1
if [ "$rc" -eq 0 ]; then printf 'identity_guard\tFAIL(%s files)\n' "$(cnt "$TMP/at")" >> "$OUT/71_overall.tsv"; code=1
else printf 'identity_guard\tPASS\n' >> "$OUT/71_overall.tsv"; fi
finish "$code"