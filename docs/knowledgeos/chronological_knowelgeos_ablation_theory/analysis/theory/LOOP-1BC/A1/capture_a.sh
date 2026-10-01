#!/usr/bin/env bash
# capture_a.sh -- Protocol A (PROTOCOL-A.md): capture evidence of WP-4C-1 in the local git history.
# Captures evidence only: no norm check, hypothesis, falsifier or score. Read-only: git log/for-each-ref/rev-list
# (plus `git --version`).
# Usage: bash /path/to/capture_a.sh OUTDIR   (from the repository root or any subdirectory of the working clone;
#        OUTDIR must be absent or empty, and must lie outside the work tree and outside .git -- enforced, exit 2)
# Exit: 0 complete | 1 complete with unexpected exit codes (00_errors.tsv; absence then NOT asserted) | 2 refused to start
set -u -o pipefail
# Resolve our own absolute path FIRST, before any cd (r2 self-hash defect).
SELF="$(realpath -e -- "${BASH_SOURCE[0]}" 2>/dev/null)" || { printf 'capture_a: cannot resolve own path\n' >&2; exit 2; }
export LC_ALL=C TZ=UTC GIT_PAGER=cat PAGER=cat GIT_CONFIG_NOSYSTEM=1 GIT_CONFIG_GLOBAL=/dev/null \
  GIT_NO_REPLACE_OBJECTS=1 GIT_OPTIONAL_LOCKS=0 GIT_TERMINAL_PROMPT=0 GIT_NO_LAZY_FETCH=1
unset GIT_DIR GIT_WORK_TREE GIT_INDEX_FILE GIT_OBJECT_DIRECTORY GIT_ALTERNATE_OBJECT_DIRECTORIES GIT_NAMESPACE \
  GIT_CONFIG_PARAMETERS GIT_CONFIG_COUNT GIT_CEILING_DIRECTORIES
TAB=$'\t'
die() { printf 'capture_a: %s\n' "$1" >&2; exit 2; }
[ "$#" -eq 1 ] && [ -n "$1" ] || die "usage: capture_a.sh OUTDIR"
OUT="$(realpath -m -- "$1")" || die "cannot resolve OUTDIR"
if [ -e "$OUT" ]; then { [ -d "$OUT" ] && [ -z "$(ls -A -- "$OUT")" ]; } || die "OUTDIR exists and is not empty"; fi
git --no-pager for-each-ref --count=1 >/dev/null 2>&1 || die "not inside a git repository"

# ---- N1: refuse an OUTDIR inside the work tree or inside .git (before anything is created) ----
# No rev-parse: walk up from the physical cwd exactly as git discovery does (GIT_DIR and GIT_CEILING_DIRECTORIES are
# unset), and refuse if OUT lies inside ANY enclosing directory that holds a .git entry, inside the git directory that
# entry names (.git directory, symlink, or "gitdir:" file), or inside that git directory's commondir.
under() { local d="${2%/}"; case "$1/" in "$d"/*) return 0 ;; esac; return 1; }
gitfile() { # gitfile BASE FILE : absolute path named on the first line of FILE ("gitdir: " prefix removed)
  local l=""; IFS= read -r l < "$2" || [ -n "$l" ] || return 1; l="${l#gitdir: }"
  case "$l" in /*) ;; *) l="$1/$l" ;; esac; realpath -m -- "$l"; }
W="$(pwd -P)" || die "cannot resolve the working directory"
while :; do
  if [ -e "$W/.git" ] || [ -L "$W/.git" ]; then
    under "$OUT" "$W" && die "OUTDIR lies inside the work tree; choose a directory outside the repository"
    GD=""
    if [ -d "$W/.git" ]; then GD="$(realpath -m -- "$W/.git")" || die "cannot resolve .git"
    elif [ -f "$W/.git" ]; then GD="$(gitfile "$W" "$W/.git")" || die "cannot read the .git file"; fi
    if [ -n "$GD" ]; then
      under "$OUT" "$GD" && die "OUTDIR lies inside .git; choose a directory outside the repository"
      if [ -f "$GD/commondir" ]; then
        CD="$(gitfile "$GD" "$GD/commondir")" || die "cannot read commondir"
        under "$OUT" "$CD" && die "OUTDIR lies inside .git; choose a directory outside the repository"
      fi
    fi
  fi
  [ "$W" = / ] && break; W="$(dirname -- "$W")"
done

mkdir -p -- "$OUT" || die "cannot create OUTDIR"
ERR="$OUT/00_errors.tsv"; : > "$ERR"; : > "$OUT/00_stderr.log"
ok() { # ok LABEL RC ALLOWED... : record an unexpected exit code; never aborts
  local l="$1" rc="$2" a; shift 2
  for a in "$@"; do [ "$rc" -eq "$a" ] && return 0; done
  printf '%s\t%s\n' "$l" "$rc" >> "$ERR"; return 1
}
# N2: diff.algorithm and i18n.logOutputEncoding pinned; repository-local .git/config stays readable (disclosed, PROTOCOL-A section 7).
g() { git --no-pager -c core.quotepath=false -c diff.noprefix=false -c diff.mnemonicPrefix=false -c diff.relative=false \
  -c diff.renames=false -c diff.algorithm=myers -c i18n.logOutputEncoding=UTF-8 \
  -c log.showRoot=true -c log.showSignature=false -c color.ui=never "$@" 2>>"$OUT/00_stderr.log"; }
D=(--no-ext-diff --no-textconv --no-renames --no-relative --diff-merges=combined)
finish() { # remove temporaries, hash every output, exit
  rm -f -- "$OUT"/.tmp_*
  ( cd -- "$OUT" && find . -type f ! -name SHA256SUMS -print | sort | xargs -r -d '\n' sha256sum ) > "$OUT/SHA256SUMS" \
    || { printf 'capture_a: hashing failed\n' >&2; exit 1; }
  exit "$1"
}

# ---- awk prelude: hyphen normalisation, match rules T1/T2 (section 1), path classes (section 2) ----
# pclass precedence: ROOT first (first path segment), THEN extension. No segment or basename words are consulted.
FN='BEGIN { FS = "\t"; US = "\036"
  HY = "\342\200\220|\342\200\221|\342\200\222|\342\200\223|\342\200\224|\342\200\225|\342\210\222|\357\271\230|\357\271\243|\357\274\215|\302\255" }
function hit(s,   t, u, nx, r) {
  t = " " s " "; gsub(HY, "-", t); u = t; r = ""
  while (match(u, /[^0-9A-Za-z][Ww][Pp]-4[Cc]-1/)) {
    nx = substr(u, RSTART + RLENGTH, 2)
    if (nx !~ /^[0-9A-Za-z]/ && nx !~ /^[-.][0-9]/) { r = "T1"; break }
    u = substr(u, RSTART + 1) }
  if (t ~ /[^0-9A-Za-z][Ww][Pp]4[Cc]1-/) r = (r == "") ? "T2" : r "+T2"
  return r }
function src(b) { return b ~ /\.(php|vue|js|mjs|cjs|ts|tsx|jsx|css|scss|sass|less|json|sql)$/ }
function pclass(p,   q, i, rt, b) {
  q = tolower(p); i = index(q, "/"); rt = (i > 0) ? substr(q, 1, i - 1) : ""
  b = q; sub(/.*\//, "", b)
  if (rt ~ /^(docs|\.claude|engineering|developer_guide|claude)$/) return "DOC"
  if (rt == "tests") return src(b) ? "TEST" : "OTHER"
  if (rt ~ /^(app|routes|database|resources|config|bootstrap|lang|public)$/) return src(b) ? "CODE" : "OTHER"
  if (b ~ /\.md$/) return "DOC"
  return "OTHER" }
'

# ---- meta (no paths, no names) ----
SS="$(sha256sum < "$SELF")"; ok self.sha256 $? 0
{ printf 'protocol\tA\nscript_sha256\t%s\ngit_version\t%s\n' "${SS%% *}" "$(git --version)"
  printf 'capture\tLOCAL_REF_SET\ncompleteness_vs_host\tNOT SHOWN\n'; } > "$OUT/00_meta.tsv"

# ---- coverage: local refs, tips, commits scanned (section 4) ----
g for-each-ref --format='%(refname)%09%(objecttype)%09%(objectname)%09%(*objecttype)%09%(*objectname)' > "$OUT/.tmp_refs"; ok for-each-ref $? 0
sort -- "$OUT/.tmp_refs" > "$OUT/01_refs.tsv"; ok refs.sort $? 0
TIPS="$OUT/01_tips.txt"
awk -F'\t' '$2=="commit"{print $3} $2=="tag" && $4=="commit"{print $5}' "$OUT/01_refs.tsv" | sort -u > "$TIPS"
if [ ! -s "$TIPS" ]; then # git log --stdin with empty input would fall back to HEAD: refuse to walk
  printf 'no_commit_refs\t-\n' >> "$ERR"
  printf 'result\tUNDETERMINED (CAPTURE_ERROR)\ncompleteness_vs_host\tNOT SHOWN\n' > "$OUT/30_summary.tsv"; finish 1
fi
g rev-list --stdin < "$TIPS" > "$OUT/.tmp_c"; ok rev-list $? 0
sort -- "$OUT/.tmp_c" > "$OUT/02_commits_scanned.txt"
NC=$(awk 'END{print NR}' "$OUT/02_commits_scanned.txt")

# ---- pass 1: messages and trailers ----
g log --stdin --format='%x1eM %H%n%B%n%x1eT%n%(trailers:only,unfold)' < "$TIPS" \
  | awk "$FN"'substr($0, 1, 3) == US "M " {h = substr($0, 4); s = "message"; next}
    $0 == US "T" {s = "trailer"; next}
    {r = hit($0); if (r != "") print h "\t" s "\t" r}' | sort -u > "$OUT/10_message_hits.tsv"
ps=("${PIPESTATUS[@]}"); ok log.msg "${ps[0]}" 0; ok awk.msg "${ps[1]}" 0

# ---- pass 2: changed paths (merges: own changes only, combined) ----
g log --stdin "${D[@]}" --name-only --format='%x1eC %H' < "$TIPS" \
  | awk "$FN"'substr($0, 1, 1) == US {split($0, a, " "); h = a[2]; next}
    $0 != "" {print h "\t" $0 "\t" pclass($0)}' | sort -u > "$OUT/.tmp_changes"
ps=("${PIPESTATUS[@]}"); ok log.paths "${ps[0]}" 0; ok awk.paths "${ps[1]}" 0
awk "$FN"'{r = hit($2); if (r != "") print $1 "\t" $2 "\t" $3 "\t" r}' "$OUT/.tmp_changes" > "$OUT/11_path_hits.tsv"

# ---- pass 3: added diff lines (merges: lines added relative to every parent) ----
# N3: git appends a TAB to a +++ header path that contains a space; it is stripped before the path is classified.
g log --stdin "${D[@]}" -p -U0 --src-prefix=a/ --dst-prefix=b/ --format='%x1eC %H %P' < "$TIPS" \
  | awk "$FN"'substr($0, 1, 1) == US {n = split($0, a, " "); h = a[2]; w = (n - 2 >= 2) ? n - 2 : 1
      pl = ""; for (i = 0; i < w; i++) pl = pl "+"; hdr = 0; next}
    /^diff / {hdr = 1; p = ""; next}
    hdr && /^\+\+\+ / {p = substr($0, 5); sub(/\t$/, "", p); if (p == "/dev/null") p = ""; else if (substr(p, 1, 2) == "b/") p = substr(p, 3); next}
    /^@@/ {hdr = 0; next}
    hdr {next}
    substr($0, 1, w) == pl && $0 ~ /[Ww][Pp]/ {r = hit(substr($0, w + 1)); if (r != "") print h "\t" p "\t" pclass(p) "\t" r}' \
  | sort -u > "$OUT/12_line_hits.tsv"
ps=("${PIPESTATUS[@]}"); ok log.lines "${ps[0]}" 0; ok awk.lines "${ps[1]}" 0

# ---- hit set, its changed paths, dates ----
{ cut -f1 "$OUT/10_message_hits.tsv"; cut -f1 "$OUT/11_path_hits.tsv"; cut -f1 "$OUT/12_line_hits.tsv"; } | awk 'NF' | sort -u > "$OUT/13_hitset.txt"
awk -F'\t' 'FILENAME == ARGV[1] {k[$1]; next} $1 in k' "$OUT/13_hitset.txt" "$OUT/.tmp_changes" > "$OUT/14_changed_paths.tsv"
{ cut -f1,2 "$OUT/11_path_hits.tsv"; cut -f1,2 "$OUT/12_line_hits.tsv"; } | sort -u > "$OUT/.tmp_mp"
: > "$OUT/.tmp_meta"
if [ -s "$OUT/13_hitset.txt" ]; then
  g log --no-walk=unsorted --stdin --date=format-local:%Y-%m-%dT%H:%M:%SZ --format='%H%x09%P%x09%ad%x09%cd' \
    < "$OUT/13_hitset.txt" > "$OUT/.tmp_meta"; ok log.meta $? 0
fi

# ---- classification C1..C5 (section 2) ----
awk "$FN"'
function own(h) {
  if (nk[h] > 0 && (mm[h] || kr[h])) return "C2"
  if (n[h] > 0 && nd[h] == n[h]) return "C3"
  if (mm[h] && !ph[h] && n[h] > 0) return "C4"
  return "C5" }
FILENAME == fm  {np[$1] = split($2, x, " "); ad[$1] = $3; cd[$1] = $4; next}
FILENAME == fc  {n[$1]++; if ($3 == "DOC") nd[$1]++; if ($3 == "CODE" || $3 == "TEST") nk[$1]++; next}
FILENAME == f10 {mm[$1] = 1; seen[$1, $2 ":" $3] = 1; next}
FILENAME == f11 || FILENAME == f12 {ph[$1] = 1; if ($3 == "CODE" || $3 == "TEST") kr[$1] = 1
                 seen[$1, ((FILENAME == f11) ? "path" : "added_line") ":" $4] = 1; next}
FILENAME == fp  {mp[$1] = mp[$1] ";" $2; next}
END {
  cls["C2"] = "EXECUTION-TRACE"; cls["C3"] = "DOC-OR-GOVERNANCE"; cls["C4"] = "MENTION-ONLY"; cls["C5"] = "UNCLASSIFIABLE"
  split("message trailer path added_line", S, " "); split("T1 T2 T1+T2", R, " ")
  for (h in np) {
    mi = ""; for (i = 1; i <= 4; i++) for (j = 1; j <= 3; j++) if (((h, S[i] ":" R[j]) in seen)) mi = mi "," S[i] ":" R[j]
    o = own(h)
    if (np[h] >= 2) { c = "MERGE"; rid = "C1>" o } else { c = cls[o]; rid = o }
    pp = ((h in mp) ? substr(mp[h], 2) : "-")
    print h "\t" ad[h] "\t" cd[h] "\t" c "\t" substr(mi, 2) "\t" pp "\t" rid "\tRECORDING_ORDER" } }
' fm="$OUT/.tmp_meta" fc="$OUT/14_changed_paths.tsv" f10="$OUT/10_message_hits.tsv" f11="$OUT/11_path_hits.tsv" \
  f12="$OUT/12_line_hits.tsv" fp="$OUT/.tmp_mp" "$OUT/.tmp_meta" "$OUT/14_changed_paths.tsv" "$OUT/10_message_hits.tsv" \
  "$OUT/11_path_hits.tsv" "$OUT/12_line_hits.tsv" "$OUT/.tmp_mp" > "$OUT/.tmp_cls"; ok classify $? 0
{ printf 'sha\tauthor_date_utc\tcommitter_date_utc\tclass\tmatched_in\tmatched_paths\trule_id\ttime_label\n'
  sort -- "$OUT/.tmp_cls"; } > "$OUT/20_commits.tsv"
awk -F'\t' 'function js(s) { gsub(/\\/, "\\\\", s); gsub(/"/, "\\\"", s); return "\"" s "\"" }
  function arr(s, sep,   a, k, i, o) { if (s == "-" || s == "") return "[]"; k = split(s, a, sep); o = js(a[1]); for (i = 2; i <= k; i++) o = o "," js(a[i]); return "[" o "]" }
  NR > 1 {printf "{\"sha\":%s,\"author_date_utc\":%s,\"committer_date_utc\":%s,\"class\":%s,\"matched_in\":%s,\"matched_paths\":%s,\"rule_id\":%s,\"time_label\":%s}\n",
    js($1), js($2), js($3), js($4), arr($5, ","), arr($6, ";"), js($7), js($8)}' "$OUT/20_commits.tsv" > "$OUT/20_commits.jsonl"

# ---- summary (sections 4-6) ----
ET="$OUT/.tmp_et"; awk -F'\t' 'NR > 1 && $4 == "EXECUTION-TRACE"' "$OUT/20_commits.tsv" > "$ET"
NET=$(awk 'END{print NR}' "$ET")
EC=$(sort -t "$TAB" -k3,3 -k1,1 "$ET" | awk -F'\t' 'NR == 1 {print $1 "\t" $3}')
EA=$(sort -t "$TAB" -k2,2 -k1,1 "$ET" | awk -F'\t' 'NR == 1 {print $1 "\t" $2}')
code=0
if [ -s "$ERR" ]; then RES="UNDETERMINED (CAPTURE_ERROR)"; code=1
elif [ "$NET" -gt 0 ]; then RES="EXECUTION-TRACE-EVIDENCED-IN-CAPTURE"
else RES="NOT-EVIDENCED-IN-CAPTURE"; fi
{ awk -F'\t' '{c = ($1 ~ /^refs\/heads\//) ? "heads" : ($1 ~ /^refs\/remotes\//) ? "remotes" : ($1 ~ /^refs\/tags\//) ? "tags" : "other"; k[c]++}
    END {printf "refs_total\t%d\nrefs_heads\t%d\nrefs_remotes\t%d\nrefs_tags\t%d\nrefs_other\t%d\n", NR, k["heads"], k["remotes"], k["tags"], k["other"]}' "$OUT/01_refs.tsv"
  printf 'commits_scanned\t%s\nmatching_commits\t%s\n' "$NC" "$(awk 'END{print NR}' "$OUT/13_hitset.txt")"
  for c in EXECUTION-TRACE DOC-OR-GOVERNANCE MENTION-ONLY MERGE UNCLASSIFIABLE; do
    printf 'class_%s\t%s\n' "$c" "$(awk -F'\t' -v c="$c" 'NR > 1 && $4 == c {k++} END{print k + 0}' "$OUT/20_commits.tsv")"; done
  printf 'earliest_execution_trace_by_committer_date\t%s\tRECORDING_ORDER\n' "${EC:--}"
  printf 'earliest_execution_trace_by_author_date\t%s\tRECORDING_ORDER\n' "${EA:--}"
  printf 'time_label\tRECORDING_ORDER (M-TIME: recording != decision != entry into force)\n'
  printf 'capture\tLOCAL_REF_SET\ncompleteness_vs_host\tNOT SHOWN\ncapture_errors\t%s\nresult\t%s\n' "$(awk 'END{print NR}' "$ERR")" "$RES"
} > "$OUT/30_summary.tsv"
finish "$code"
