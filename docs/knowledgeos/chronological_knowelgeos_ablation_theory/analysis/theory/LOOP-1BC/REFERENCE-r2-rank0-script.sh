#!/usr/bin/env bash
# rank0-r2.sh -- frozen by SPEC-r2 Part B. Do not edit.
# Capture = the local ref set (every ref printed by `git for-each-ref`) of the ordinary working clone.
# Completeness of the capture is NOT shown. Read-only. No network. No host access. No `set -e`.
# Usage (from anywhere inside the working clone):  OUT=/absolute/path/to/new/empty/dir bash rank0-r2.sh
# Exit: 0 completed (read 80_status.tsv) | 1 completed with unexpected exit codes (CAPTURE_ERROR)
#       2 refused to start (nothing searched) | 3 stopped before the walk (CAPTURE_INVALID)
set -u -o pipefail
export LC_ALL=C TZ=UTC GIT_PAGER=cat PAGER=cat GIT_CONFIG_NOSYSTEM=1 GIT_CONFIG_GLOBAL=/dev/null \
  GIT_NO_REPLACE_OBJECTS=1 GIT_OPTIONAL_LOCKS=0 GIT_NO_LAZY_FETCH=1 GIT_TERMINAL_PROMPT=0
unset GIT_DIR GIT_WORK_TREE GIT_INDEX_FILE GIT_OBJECT_DIRECTORY GIT_ALTERNATE_OBJECT_DIRECTORIES GIT_NAMESPACE GIT_CEILING_DIRECTORIES
TAB=$'\t'
die() { printf 'rank0-r2: %s\n' "$2" >&2; exit "$1"; }

# ---- P0 preconditions (exit 2: nothing is searched) ----
OUT="${OUT:-}"; [ -n "$OUT" ] || die 2 "OUT is not set"
case "$OUT" in /*) ;; *) die 2 "OUT must be an absolute path" ;; esac
[ "$(git rev-parse --is-inside-work-tree 2>/dev/null)" = true ] || die 2 "not inside a work tree"
[ "$(git rev-parse --is-bare-repository 2>/dev/null)" = false ] || die 2 "bare repository"
TOP="$(git rev-parse --show-toplevel 2>/dev/null)" || die 2 "no toplevel"
GD="$(git rev-parse --absolute-git-dir 2>/dev/null)" || die 2 "no git dir"
OUT="$(realpath -m -- "$OUT")" || die 2 "realpath failed"
for d in "$(realpath -- "$TOP")" "$(realpath -- "$GD")"; do
  case "$OUT/" in "$d"/*) die 2 "OUT must be outside the repository" ;; esac
done
if [ -e "$OUT" ]; then { [ -d "$OUT" ] && [ -z "$(ls -A -- "$OUT")" ]; } || die 2 "OUT exists and is not an empty directory"; fi
mkdir -p -- "$OUT" || die 2 "cannot create OUT"
cd -- "$TOP" || die 2 "cannot cd to the toplevel"

ERR="$OUT/00_errors.tsv"; LOG="$OUT/00_stderr.log"; : > "$ERR"; : > "$LOG"
ok() { # ok LABEL RC ALLOWED... : record an unexpected exit code; never aborts
  local l="$1" rc="$2" a; shift 2
  for a in "$@"; do [ "$rc" -eq "$a" ] && return 0; done
  printf '%s\t%s\n' "$l" "$rc" >> "$ERR"; return 1
}
g() { git --no-replace-objects -c core.quotepath=off -c core.fsmonitor=false -c log.showSignature=false \
  -c log.mailmap=false -c log.decorate=false -c log.showRoot=true -c diff.renames=false \
  -c diff.noprefix=false -c diff.mnemonicPrefix=false -c diff.relative=false -c color.ui=never "$@" 2>>"$LOG"; }
lst() { # lst LABEL FILE git-args... : git must exit 0; keep non-empty lines, sorted and unique
  local l="$1" f="$2" ps; shift 2
  g "$@" > "$f.tmp"; ok "$l" $? 0
  awk 'NF' "$f.tmp" | sort -u > "$f"; ps=("${PIPESTATUS[@]}"); ok "$l.awk" "${ps[0]}" 0; ok "$l.sort" "${ps[1]}" 0
  rm -f -- "$f.tmp"
}
cnt() { awk 'END{print NR}' "$1"; }
finish() { # finish EXIT : hash every output file; print the SHA-256 of SHA256SUMS
  ( cd -- "$OUT" && find . -type f ! -name SHA256SUMS -print | sort | xargs -r -d '\n' sha256sum ) > "$OUT/SHA256SUMS"
  sha256sum < "$OUT/SHA256SUMS" | cut -d' ' -f1
  exit "$1"
}

# ---- frozen patterns (B.2); HY = ASCII hyphen or one of 11 Unicode hyphen/dash code points, as UTF-8 bytes ----
HY=$'(-|\xe2\x80\x90|\xe2\x80\x91|\xe2\x80\x92|\xe2\x80\x93|\xe2\x80\x94|\xe2\x80\x95|\xe2\x88\x92|\xef\xb9\x98|\xef\xb9\xa3|\xef\xbc\x8d|\xc2\xad)'
LB='(^|[^0-9A-Za-z])'; RB='($|[^0-9A-Za-z])'
CORE="WP${HY}4C${HY}1"
PAT="${LB}${CORE}${RB}"                        # git/grep: line-anchored ERE
PATX="[^0-9A-Za-z]${CORE}[^0-9A-Za-z]"          # awk: applied to " " s " "
SEP="(${HY}|[ _./])"
VCORE="[Ww][Pp]${SEP}?4${SEP}?[Cc]${SEP}?1"
VAR="${LB}${VCORE}${RB}"; VARX="[^0-9A-Za-z]${VCORE}[^0-9A-Za-z]"
WPANY="WP${HY}[0-9]+[A-Za-z]?(${HY}[0-9]+)?"; WPC="^${CORE}\$"
R89X="[^0-9A-Za-z]R${HY}89[^0-9A-Za-z]"
RP72="${LB}R${HY}72${RB}"; RP87="${LB}R${HY}87${RB}"; RP89="${LB}R${HY}89${RB}"
CTRL='WP-4B RED'
DX=(--no-renames --no-ext-diff --no-textconv)

# ---- S0 environment (no paths, no names) and preflight validity ----
{ printf 'git_version\t%s\n' "$(git --version)"
  printf 'is_bare\t%s\n' "$(g rev-parse --is-bare-repository)"
  printf 'is_shallow\t%s\n' "$(g rev-parse --is-shallow-repository)"; } > "$OUT/00_env.tsv"
PC="$(g config --get extensions.partialClone)"; ok cfg.partialClone $? 0 1
PR="$(g config --get-regexp '^remote\..*\.(promisor|partialclonefilter)$')"; ok cfg.promisor $? 0 1
NPR="$(printf '%s' "$PR" | awk 'NF{c++} END{print c+0}')"
printf 'partial_clone\t%s\npromisor_or_filter_entries\t%s\n' "$([ -n "$PC" ] && echo set || echo unset)" "$NPR" >> "$OUT/00_env.tsv"
SS="$(sha256sum < "${BASH_SOURCE[0]}")"; ok script.sha256 $? 0; printf '%s\n' "${SS%% *}" > "$OUT/00_script.sha256"
SH="$(g rev-parse --is-shallow-repository)"
if [ "$SH" != false ] || [ -n "$PC" ] || [ "$NPR" != 0 ]; then
  printf 'existence\tCAPTURE_INVALID\nreason\tshallow_or_partial_clone\n' > "$OUT/80_status.tsv"; finish 3
fi

# ---- S1 capture = local ref set ----
g for-each-ref --format='%(objectname)%09%(objecttype)%09%(*objectname)%09%(*objecttype)%09%(refname)' > "$OUT/01_refs.tmp"; ok refs $? 0
sort -t "$TAB" -k5,5 "$OUT/01_refs.tmp" > "$OUT/01_refs.tsv"; ok refs.sort $? 0; rm -f -- "$OUT/01_refs.tmp"
awk -F'\t' -v nc="$OUT/01b_noncommit_refs.tsv" 'BEGIN{printf "" > nc}
  $2=="commit"{print $1; next} $2=="tag" && $4=="commit"{print $3; next} {print $2 "\t" $5 > nc}' "$OUT/01_refs.tsv" | sort -u > "$OUT/01c_tips.txt"
ps=("${PIPESTATUS[@]}"); ok tips.awk "${ps[0]}" 0; ok tips.sort "${ps[1]}" 0
awk -F'\t' '{c = ($5 ~ /^refs\/heads\//) ? "heads" : (($5 ~ /^refs\/remotes\//) ? "remotes" : (($5 ~ /^refs\/tags\//) ? "tags" : "other")); n[c]++}
  END{printf "heads\t%d\nremotes\t%d\ntags\t%d\nother\t%d\n", n["heads"], n["remotes"], n["tags"], n["other"]}' "$OUT/01_refs.tsv" > "$OUT/01d_ref_counts.tsv"
TIPS="$OUT/01c_tips.txt"
if [ ! -s "$TIPS" ]; then printf 'existence\tCAPTURE_INVALID\nreason\tno_commit_refs\n' > "$OUT/80_status.tsv"; finish 3; fi
lst commits "$OUT/02_commits.txt" rev-list --stdin < "$TIPS"
cnt "$OUT/02_commits.txt" > "$OUT/02_commit_count.txt"

# ---- S2 positive control (empty => CAPTURE_INVALID) ----
lst control "$OUT/05_control.txt" log --stdin -F --grep="$CTRL" --format=%H < "$TIPS"

# ---- S3 primary passes over the whole captured history (no lower bound, no date filter) ----
lst msg "$OUT/10_msg_pat.txt"           log --stdin -E --grep="$PAT" --format=%H < "$TIPS"
lst dnm "$OUT/11_diff_pat_nonmerge.txt" log --stdin --no-merges "${DX[@]}" -G"$PAT" --format=%H < "$TIPS"
lst dm  "$OUT/12_diff_pat_merge.txt"    log --stdin --merges --diff-merges=first-parent "${DX[@]}" -G"$PAT" --format=%H < "$TIPS"
g log --stdin --no-merges "${DX[@]}" --name-status --format='::commit::%x09%H' < "$TIPS" > "$OUT/13a_paths_nonmerge.tsv"; ok paths.nonmerge $? 0
g log --stdin --merges --diff-merges=combined "${DX[@]}" --name-only --format='::commit::%x09%H' < "$TIPS" > "$OUT/13b_paths_merge_own.tsv"; ok paths.merge $? 0
: > "$OUT/14_path_pat.tsv"; : > "$OUT/24_path_var.tsv"
awk -F'\t' -v pat="$PATX" -v var="$VARX" -v fp="$OUT/14_path_pat.tsv" -v fv="$OUT/24_path_var.tsv" '
  $1=="::commit::" && NF==2 {h=$2; next}
  NF==0 {next}
  { if (FILENAME ~ /13b_paths_merge_own[.]tsv$/) {st="O"; p=$0} else {st=$1; p=$2}
    s=" " p " "
    if (s ~ pat) print h "\t" st "\t" p > fp; else if (s ~ var) print h "\t" st "\t" p > fv }' \
  "$OUT/13a_paths_nonmerge.tsv" "$OUT/13b_paths_merge_own.tsv"; ok pathscan $? 0
sort -u -o "$OUT/14_path_pat.tsv" "$OUT/14_path_pat.tsv"; ok sort14 $? 0
sort -u -o "$OUT/24_path_var.tsv" "$OUT/24_path_var.tsv"; ok sort24 $? 0

# ---- S4 ref names, and commits exclusive to PAT-named refs ----
awk -F'\t' -v pat="$PATX" -v var="$VARX" '{s=" " $5 " "; t = ($2=="commit") ? $1 : (($2=="tag" && $4=="commit") ? $3 : "-")
  if (s ~ pat) print "PAT\t" t "\t" $5; else if (s ~ var) print "VAR\t" t "\t" $5}' "$OUT/01_refs.tsv" > "$OUT/15_refname_hits.tsv"; ok refscan $? 0
awk -F'\t' -v pat="$PATX" '(" " $5 " ") !~ pat {t = ($2=="commit") ? $1 : (($2=="tag" && $4=="commit") ? $3 : ""); if (t != "") print "^" t}' \
  "$OUT/01_refs.tsv" | sort -u > "$OUT/16a_negative_tips.txt"
ps=("${PIPESTATUS[@]}"); ok neg.awk "${ps[0]}" 0; ok neg.sort "${ps[1]}" 0
: > "$OUT/16_ref_exclusive.tsv"
while IFS="$TAB" read -r kind tip rn <&3; do
  [ "$kind" = PAT ] && [ "$tip" != "-" ] || continue
  { printf '%s\n' "$tip"; cat "$OUT/16a_negative_tips.txt"; } | g rev-list --no-merges --stdin > "$OUT/16.tmp"
  ps=("${PIPESTATUS[@]}"); ok "refx.$tip" "${ps[1]}" 0
  awk -v r="$rn" 'NF{print $1 "\t" r}' "$OUT/16.tmp" >> "$OUT/16_ref_exclusive.tsv"
done 3< "$OUT/15_refname_hits.tsv"
rm -f -- "$OUT/16.tmp"; sort -u -o "$OUT/16_ref_exclusive.tsv" "$OUT/16_ref_exclusive.tsv"

# ---- S5 hit set (R0-3: every primary source feeds it) ----
{ cat "$OUT/10_msg_pat.txt" "$OUT/11_diff_pat_nonmerge.txt" "$OUT/12_diff_pat_merge.txt"
  cut -f1 "$OUT/14_path_pat.tsv"; cut -f1 "$OUT/16_ref_exclusive.tsv"; } | awk 'NF' | sort -u > "$OUT/90_hitset.txt"

# ---- S6 variant passes (reported separately; never counted) ----
lst vmsg "$OUT/20_msg_var.txt"           log --stdin -E --grep="$VAR" --format=%H < "$TIPS"
lst vdnm "$OUT/21_diff_var_nonmerge.txt" log --stdin --no-merges "${DX[@]}" -G"$VAR" --format=%H < "$TIPS"
lst vdm  "$OUT/22_diff_var_merge.txt"    log --stdin --merges --diff-merges=first-parent "${DX[@]}" -G"$VAR" --format=%H < "$TIPS"
{ cat "$OUT/20_msg_var.txt" "$OUT/21_diff_var_nonmerge.txt" "$OUT/22_diff_var_merge.txt"; cut -f1 "$OUT/24_path_var.tsv"
  awk -F'\t' '$1=="VAR" && $2!="-"{print $2}' "$OUT/15_refname_hits.tsv"; } | awk 'NF' | sort -u | comm -23 - "$OUT/90_hitset.txt" > "$OUT/29_variant_only_commits.txt"

# ---- S7 anchors: when text naming R-72 / R-87 / R-89 was recorded (never decision or force) ----
for n in 72 87 89; do v="RP$n"
  lst "a$n.msg"  "$OUT/40_anchor_R${n}_msg.txt"  log --stdin -E --grep="${!v}" --format=%H < "$TIPS"
  lst "a$n.diff" "$OUT/41_anchor_R${n}_diff.txt" log --stdin "${DX[@]}" -G"${!v}" --format=%H < "$TIPS"
done

# ---- S8 per-hit features ----
AWK_LINES=$(cat <<'EOF'
function iscomment(s) { sub(/^[ \t]+/, "", s); return (s ~ /^(#|\/\/|\/\*|\*|--|;|<!--|%|"""|'''|[Rr][Ee][Mm][ \t])/) }
/^diff / { hdr = 1; op = ""; np = ""; next }
hdr && /^--- / { op = ($0 == "--- /dev/null") ? "" : substr($0, 7); next }
hdr && /^\+\+\+ / { np = ($0 == "+++ /dev/null") ? "" : substr($0, 7); next }
/^@@/ { hdr = 0; next }
hdr { next }
/^\+/ { t = substr($0, 2); s = " " t " "
        if (s ~ pat) print h "\tA\t" np "\t" (iscomment(t) ? "COMMENT" : "CODE") "\t" t
        if (s ~ r89) print h "\tC89\t" np "\t-\t-"
        next }
/^-/ { t = substr($0, 2); if ((" " t " ") ~ pat) print h "\tR\t" (op != "" ? op : np) "\t-\t" t; next }
EOF
)
: > "$OUT/91_meta.tsv"; : > "$OUT/92_msg.tsv"; : > "$OUT/92b_sig.tsv"; : > "$OUT/93_changes.tsv"; : > "$OUT/94_lines.tsv"
if [ -s "$OUT/90_hitset.txt" ]; then
  g log --stdin --no-walk=unsorted --format='%H%x09%P%x09%at%x09%ct%x09%aI%x09%cI' < "$OUT/90_hitset.txt" > "$OUT/91_meta.tsv"; ok meta $? 0
fi
T="$OUT/.t"
while IFS= read -r h <&3; do
  np="$(awk -F'\t' -v h="$h" '$1==h{print split($2, a, " "); exit}' "$OUT/91_meta.tsv")"
  g log -1 --format=%s "$h" < /dev/null > "$T"; ok "subj.$h" $? 0; awk -v h="$h" '{print h "\tS\t" $0}' "$T" >> "$OUT/92_msg.tsv"
  g log -1 --format='%(trailers:only,unfold,valueonly)' "$h" < /dev/null > "$T"; ok "trl.$h" $? 0; awk -v h="$h" 'NF{print h "\tT\t" $0}' "$T" >> "$OUT/92_msg.tsv"
  g log -1 --format=%b "$h" < /dev/null > "$T"; ok "body.$h" $? 0; awk -v h="$h" '{print h "\tB\t" $0}' "$T" >> "$OUT/92_msg.tsv"
  g cat-file commit "$h" < /dev/null > "$T"; ok "obj.$h" $? 0
  awk -v h="$h" '/^$/{exit} /^gpgsig/{s=1} END{print h "\t" (s ? "SIGNED" : "UNSIGNED")}' "$T" >> "$OUT/92b_sig.tsv"
  if [ "${np:-0}" -ge 2 ]; then
    g show "${DX[@]}" --diff-merges=combined --name-only --format= "$h" < /dev/null > "$T"; ok "own.$h" $? 0
    awk -v h="$h" 'NF{print h "\tO\t-\t" $0}' "$T" >> "$OUT/93_changes.tsv"
    g show "${DX[@]}" --diff-merges=first-parent -U0 --src-prefix=a/ --dst-prefix=b/ --format= "$h" < /dev/null > "$T"; ok "fpdiff.$h" $? 0
  else
    g show "${DX[@]}" --name-status --format= "$h" < /dev/null > "$T"; ok "ns.$h" $? 0
    awk -F'\t' -v h="$h" 'NF{print h "\tN\t" $1 "\t" $2}' "$T" >> "$OUT/93_changes.tsv"
    g show "${DX[@]}" -U0 --src-prefix=a/ --dst-prefix=b/ --format= "$h" < /dev/null > "$T"; ok "diff.$h" $? 0
  fi
  awk -v h="$h" -v pat="$PATX" -v r89="$R89X" "$AWK_LINES" "$T" >> "$OUT/94_lines.tsv"; ok "lines.$h" $? 0
done 3< "$OUT/90_hitset.txt"
rm -f -- "$T"

# ---- S9 classification (B.3), mechanical ----
AWK_CLASS=$(cat <<'EOF'
BEGIN { FS = "\t" }
function isdoc(p,   q, b, w) {
  q = tolower(p); w = "/" q "/"; b = q; sub(/.*\//, "", b)
  if (w ~ /^\/[.]github\/workflows\// || q == ".gitlab-ci.yml" || w ~ /^\/[.]circleci\// || b == "jenkinsfile") return 0
  if (q ~ /[.](md|markdown|txt|rst|adoc|org)$/) return 1
  if (w ~ /\/(docs?|governance|rulings?|decisions?|adrs?|sessions?|logs?|notes|plans?|planning|minutes)\//) return 1
  if (b ~ /^(registers?|rulings?|decisions?|queue|work[-_]?plans?|plans?|backlog|roadmap|status|progress|context|sessions?|minutes|adrs?)([-_.][^\/]*)?[.](ya?ml|json|csv|tsv|toml)$/) return 1
  return 0
}
function wpscan(s,   t) {
  first = ""; other = 0
  while (match(s, wpany)) { t = substr(s, RSTART, RLENGTH); if (first == "") first = t; if (t !~ wpc) other = 1; s = substr(s, RSTART + RLENGTH) }
}
FILENAME == f91 { np[$1] = split($2, a, " "); at[$1] = $3; ct[$1] = $4; ord[++n] = $1; next }
FILENAME == f93 {
  if ($2 == "O") own[$1 SUBSEP $4] = 1
  if (!isdoc($4)) { nd[$1]++; if (($2 == "O" || $3 ~ /^[AMT]/) && (" " $4 " ") ~ pat) pn[$1] = 1 }
  next }
FILENAME == f92 {
  s = " " $3 " "
  if ($2 == "S") subj[$1] = $3
  if (s ~ pat) { if ($2 == "S") S[$1] = 1; else if ($2 == "T") T[$1] = 1; else B[$1] = 1 }
  if (s ~ r89) c89[$1] = 1
  next }
FILENAME == f94 {
  if ($2 == "A") { m = (np[$1] >= 2) ? (($1 SUBSEP $3) in own) : 1
                   if (m && !isdoc($3)) { if ($4 == "COMMENT") cm[$1] = 1; else al[$1] = 1 } }
  else if ($2 == "R") rl[$1] = 1
  else if ($2 == "C89") c89[$1] = 1
  next }
FILENAME == f16 { rx[$1] = 1; next }
FILENAME == f92b { sg[$1] = $2; next }
END {
  for (i = 1; i <= n; i++) {
    h = ord[i]; wpscan(subj[h]); fnt = (first != "" && first !~ wpc)
    k = nd[h] + 0; msg = (S[h] || T[h] || B[h])
    if (k >= 1 && (S[h] || T[h]) && !fnt) { c = "ET-1"; z = "E1" }
    else if (k >= 1 && msg) { c = "ET-2"; z = "E2a" }
    else if (al[h]) { c = "ET-2"; z = "E2b" }
    else if (pn[h]) { c = "ET-2"; z = "E2c" }
    else if (rx[h] && k >= 1) { c = "ET-2"; z = "E2d" }
    else { c = "MENTION"; z = (np[h] >= 2 && msg) ? "M-MERGE" : (cm[h] ? "M-COMMENT" : (rl[h] ? "M-REMOVAL" : (k == 0 ? "M-DOC" : "M-OTHER"))) }
    f = ""
    if (other) f = f ",MIXED"
    if (fnt) f = f ",FIRST_WP_NOT_TARGET"
    if (c89[h]) f = f ",CITES_R89"
    if (cm[h]) f = f ",COMMENT_HIT"
    if (rl[h]) f = f ",REMOVAL_HIT"
    if (rx[h]) f = f ",ON_TARGET_NAMED_REF"
    if (np[h] >= 2) f = f ",MERGE"
    if (sg[h] == "SIGNED") f = f ",SIGNED"
    f = (f == "") ? "-" : substr(f, 2)
    print h "\t" c "\t" z "\t" f "\t" np[h] "\t" k "\t" at[h] "\t" ct[h]
  }
}
EOF
)
awk -v f91="$OUT/91_meta.tsv" -v f92="$OUT/92_msg.tsv" -v f92b="$OUT/92b_sig.tsv" -v f93="$OUT/93_changes.tsv" \
    -v f94="$OUT/94_lines.tsv" -v f16="$OUT/16_ref_exclusive.tsv" -v pat="$PATX" -v r89="$R89X" -v wpany="$WPANY" -v wpc="$WPC" \
    "$AWK_CLASS" "$OUT/91_meta.tsv" "$OUT/93_changes.tsv" "$OUT/92_msg.tsv" "$OUT/94_lines.tsv" "$OUT/16_ref_exclusive.tsv" "$OUT/92b_sig.tsv" \
  | sort > "$OUT/95_classification.tsv"
ps=("${PIPESTATUS[@]}"); ok classify "${ps[0]}" 0; ok classify.sort "${ps[1]}" 0
awk -F'\t' '$2=="ET-1" || $2=="ET-2"{print $1}' "$OUT/95_classification.tsv" | sort > "$OUT/96_et.txt"
awk -F'\t' '$2=="ET-1"{print $1}' "$OUT/95_classification.tsv" | sort > "$OUT/96_et1.txt"

# ---- S10 ancestry, earliest traces, order labels, CLOCK_ANOMALY (B.4) ----
isanc() { g merge-base --is-ancestor "$1" "$2" < /dev/null; local rc=$?; ok "anc.$1.$2" "$rc" 0 1; return "$rc"; }
: > "$OUT/97_et_ancestry.tsv"
while IFS= read -r a <&3; do
  while IFS= read -r b <&4; do
    [ "$a" = "$b" ] && continue
    if isanc "$a" "$b"; then printf '%s\t%s\n' "$a" "$b" >> "$OUT/97_et_ancestry.tsv"; fi
  done 4< "$OUT/96_et.txt"
done 3< "$OUT/96_et.txt"
MINAWK='BEGIN{FS="\t"; while ((getline l < only) > 0) e[l]=1; while ((getline l < anc) > 0) {split(l, p, "\t"); if ((p[1] in e) && (p[2] in e)) d[p[2]]=1}} ($1 in e) && !($1 in d)'
awk -v only="$OUT/96_et.txt"  -v anc="$OUT/97_et_ancestry.tsv" "$MINAWK" "$OUT/96_et.txt"  > "$OUT/97b_et_minimal.txt"
awk -v only="$OUT/96_et1.txt" -v anc="$OUT/97_et_ancestry.tsv" "$MINAWK" "$OUT/96_et1.txt" > "$OUT/97c_et1_minimal.txt"
cat "$OUT/05_control.txt" "$OUT"/40_anchor_R*_msg.txt "$OUT"/41_anchor_R*_diff.txt | awk 'NF' | sort -u > "$OUT/98a_nodes.txt"
cat "$OUT/97b_et_minimal.txt" "$OUT/97c_et1_minimal.txt" | sort -u > "$OUT/98b_min.txt"
cat "$OUT/98a_nodes.txt" "$OUT/96_et.txt" | sort -u > "$OUT/98c.tmp"
: > "$OUT/98c_dates.tsv"
if [ -s "$OUT/98c.tmp" ]; then g log --stdin --no-walk=unsorted --format='%H%x09%at%x09%ct' < "$OUT/98c.tmp" > "$OUT/98c_dates.tsv"; ok dates $? 0; fi
rm -f -- "$OUT/98c.tmp"
: > "$OUT/98d_pairs_raw.tsv"
while IFS= read -r e <&3; do
  while IFS= read -r x <&4; do
    [ "$e" = "$x" ] && continue
    if isanc "$e" "$x"; then r=E_BEFORE_X; elif isanc "$x" "$e"; then r=X_BEFORE_E; else r=NONE; fi
    printf '%s\t%s\t%s\n' "$e" "$x" "$r" >> "$OUT/98d_pairs_raw.tsv"
  done 4< "$OUT/98a_nodes.txt"
done 3< "$OUT/98b_min.txt"
AWK_ORDER=$(cat <<'EOF'
function sgn(v) { return (v > 0) - (v < 0) }
function chk(a, d) { if ((a in ct) && (d in ct) && ct[d] + 0 < ct[a] + 0) { bad[a] = 1; bad[d] = 1; print a "\t" d > fanom } }
BEGIN { FS = "\t"; printf "" > fanom
  while ((getline l < fd) > 0) { split(l, p, "\t"); at[p[1]] = p[2]; ct[p[1]] = p[3] }
  while ((getline l < fa) > 0) { split(l, p, "\t"); chk(p[1], p[2]) }
  while ((getline l < fr) > 0) { split(l, p, "\t"); if (p[3] == "E_BEFORE_X") chk(p[1], p[2]); else if (p[3] == "X_BEFORE_E") chk(p[2], p[1]) }
  close(fr)
  while ((getline l < fr) > 0) { split(l, p, "\t"); e = p[1]; x = p[2]
    if (p[3] != "NONE") lab = "RECORDING_ORDER\t" p[3]
    else if (bad[e] || bad[x] || !(e in ct) || !(x in ct)) lab = "NOT_IDENTIFIABLE\t-"
    else { sa = sgn(at[e] - at[x]); sc = sgn(ct[e] - ct[x])
           lab = (sa == sc && sa != 0) ? ("SOURCE_STATED_ORDER\t" (sa < 0 ? "E_BEFORE_X" : "X_BEFORE_E")) : "NOT_IDENTIFIABLE\t-" }
    print e "\t" x "\t" lab }
}
EOF
)
awk -v fd="$OUT/98c_dates.tsv" -v fa="$OUT/97_et_ancestry.tsv" -v fr="$OUT/98d_pairs_raw.tsv" -v fanom="$OUT/98e_clock_anomalies.tsv" \
  "$AWK_ORDER" < /dev/null | sort > "$OUT/98f_order_labels.tsv"
ps=("${PIPESTATUS[@]}"); ok order "${ps[0]}" 0; ok order.sort "${ps[1]}" 0
sort -u -o "$OUT/98e_clock_anomalies.tsv" "$OUT/98e_clock_anomalies.tsv"
: > "$OUT/99a_refclass.tsv"
while IFS= read -r e <&3; do
  g for-each-ref --contains "$e" --format='%(refname)' < /dev/null > "$OUT/.c"; ok "contains.$e" $? 0
  awk -v e="$e" '{if ($0 ~ /^refs\/heads\//) h++; else if ($0 ~ /^refs\/remotes\//) r++; else if ($0 ~ /^refs\/tags\//) t++; else o++}
    END{printf "%s\theads=%d\tremotes=%d\ttags=%d\tother=%d\n", e, h, r, t, o}' "$OUT/.c" >> "$OUT/99a_refclass.tsv"
done 3< "$OUT/98b_min.txt"
rm -f -- "$OUT/.c"

# ---- S11 status (B.9) and canonical key ----
nC=$(cnt "$OUT/05_control.txt"); nH=$(cnt "$OUT/90_hitset.txt"); nE=$(cnt "$OUT/96_et.txt"); nE1=$(cnt "$OUT/96_et1.txt")
nM=$(cnt "$OUT/97b_et_minimal.txt"); nM1=$(cnt "$OUT/97c_et1_minimal.txt"); nV=$(cnt "$OUT/29_variant_only_commits.txt")
code=0
if [ -s "$ERR" ]; then ex=CAPTURE_ERROR; code=1
elif [ "$nC" -eq 0 ]; then ex=CAPTURE_INVALID
elif [ "$nE" -gt 0 ]; then ex=EXECUTION_TRACE_FOUND
elif [ "$nH" -gt 0 ]; then ex=MENTION_ONLY
elif [ "$nV" -gt 0 ]; then ex=VARIANT_ONLY
else ex=NOT-EVIDENCED-IN-CAPTURE; fi
weak=no; if [ "$nM" -gt 0 ] && [ "$nM1" -eq 0 ]; then weak=WEAK_TRACE; fi
printf 'existence\t%s\ncapture\tLOCAL_REF_SET\ncompleteness\tNOT_SHOWN\ncontrol_commits\t%s\nhit_commits\t%s\net_total\t%s\net1\t%s\net_minimal\t%s\net1_minimal\t%s\nweak_trace\t%s\nvariant_only_commits\t%s\nclock_anomaly_pairs\t%s\n' \
  "$ex" "$nC" "$nH" "$nE" "$nE1" "$nM" "$nM1" "$weak" "$nV" "$(cnt "$OUT/98e_clock_anomalies.tsv")" > "$OUT/80_status.tsv"
cat "$OUT/01_refs.tsv" "$OUT/02_commits.txt" "$OUT/05_control.txt" "$OUT/10_msg_pat.txt" "$OUT/11_diff_pat_nonmerge.txt" \
    "$OUT/12_diff_pat_merge.txt" "$OUT/14_path_pat.tsv" "$OUT/15_refname_hits.tsv" "$OUT/16_ref_exclusive.tsv" "$OUT/29_variant_only_commits.txt" \
    "$OUT"/40_anchor_R*_msg.txt "$OUT"/41_anchor_R*_diff.txt "$OUT/90_hitset.txt" "$OUT/95_classification.tsv" "$OUT/97_et_ancestry.tsv" \
    "$OUT/97b_et_minimal.txt" "$OUT/97c_et1_minimal.txt" "$OUT/98e_clock_anomalies.tsv" "$OUT/98f_order_labels.tsv" "$OUT/80_status.tsv" \
  | sha256sum | cut -d' ' -f1 > "$OUT/99_canonical_key.txt"
finish "$code"