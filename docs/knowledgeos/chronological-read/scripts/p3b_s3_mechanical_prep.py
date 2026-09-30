#!/usr/bin/env python3
"""P3b S3 MECHANICAL PREP — §19.1 S3 row and Appendix A.4 of the frozen P3b operating protocol v1.6.5 (G-LOG-0012),
with H-04 = V1-PLUS-ROWS verbatim slice (G-LOG-0007), implementation decisions S3-1 … S3-4 (G-LOG-0008), and the
S2b identity manifest (G-LOG-0013) as the only source of CONTENT bytes.

Produces (only on PASS, and not with --dry-run):
  P3B-ABSENCE-SEARCH.jsonl        stage-1 absence search (A.4). Line 1 = {"header": ...}. Then two record types:
                                    {"record": "LABEL-HITS", label, terms[...], ledger_hits{sid: [[anchor, term_idx], ...]},
                                     raw_hits{sid: [[offset, term_idx], ...]}}          (once per label: S3-2)
                                    {"record": "DIMENSION", label, dimension, hits_ref: label, scope, files_searched,
                                     firewall_skipped, negative_label, population_basis} (one per NOT-EVIDENCED dimension)
                                  The A.4 per-dimension record = DIMENSION record + the LABEL-HITS record it references.
  _batch_input_r2/bundle_index.jsonl  H-04 verbatim-slice INDEX (S3-3). Line 1 = header. Then one record per label:
                                    {working_label, tier, reconciliation_object_sha256, rows: [{source_id, anchor, line,
                                     sha256}], sources: [{source_id, line, sha256, track_tag, ksme20_bucket}],
                                     p2_row_count, distinct_rows}
                                  materialize_bundle() rebuilds the byte-exact bundle from the frozen files and verifies
                                  every hash. No filtering, normalization or field selection.
  P3B-WORKLOAD.json               §19.4 S3 measurements (provisional; recomputed at S3b).

Stage-1 search (A.4, applied exactly; S3-1):
  terms per label = working_label (hyphens kept, and hyphens → spaces) + every notation + every alias
                    (group co-members' notations from §11.4 are NOT added; discrepancy recorded, G-LOG-0008).
  normalization   = NFKC, then LaTeX (versioned table of commands → Unicode; \\cmd{arg} → arg), then casefold for
                    multi-character terms. Single-character terms (after NFKC + LaTeX) match case-sensitively without
                    casefold. Each term also gets its ASCII transliteration variant (versioned table).
  boundaries      = the character before and after a match must not be a word character (Python re \\w). This is
                    identical to A.4's \\b…\\b for word-edged terms and (?<![\\w])…(?![\\w]) for symbol-edged terms.
  scope           = (a) `statement` + `type_signature` of every 03-CONTRIBUTIONS row; (b) the raw text of every CONTENT
                    file of 02-FILES, read as the blob its P3B-IDENTITY-MANIFEST.jsonl row specifies (v1.6.5 A.4),
                    through ContentResolver only; the sha256 of every blob read must equal the row's content_sha256
                    (fail closed: any missing blob or mismatch stops the run; no fallback to the census path).
                    FIREWALL-LIMITED files are skipped and listed.
  offsets         = character offsets in the normalized text (offset_basis recorded).
  negative_label  = NEGATIVE-CENSUS only if (a) and (b) both covered the full lists (no unreadable CONTENT file) and no
                    hit exists for the label; otherwise, with no hit, NEGATIVE-BOUNDED. population_basis = CENSUS (no
                    H-19 addendum is approved, §3.6). The NFKC collapse of mathematical letters is MEASURED here, not
                    fixed (G-LOG-0008).

Track tags (S3-4): per source file, from the KSME-20 directory mapping (hash recorded), marked
PROVISIONAL-PENDING-OMQ-07. Both the §14.5 tag and the finer KSME-20 bucket are recorded.

Identity binding (v1.6.5; F8 of the v1.6.5 confirm pass, G-LOG-0011): ContentResolver is the single code path that
reads CONTENT bytes. It resolves source_id → manifest row → blob (GIT-OBJECT via `git cat-file --batch`, or the
committed PRESERVED-AUDIT-COPY file) and verifies sha256; it never opens historical_path or current_path. S3 itself
does no stage-2 whole-file reading; stage-2 readers (S4/S5) must use ContentResolver.from_manifest(), and every S3
output header records the manifest hashes it was bound to.

Gate: S2 PASS (P3B-TIERS.jsonl header PASS and its body hash equal to the header); S2b PASS (manifest header PASS, body
hash = header = the value recorded in G-LOG-0013, rows = the CONTENT set, no UNRESOLVED row); 03-CONTRIBUTIONS.jsonl,
02-FILES.jsonl and 20-FAMILIES/_derived.json unchanged since S0; frozen protocol hash.

Usage:  python3 p3b_s3_mechanical_prep.py [--dry-run]
Exit:   0 PASS · 1 FAIL (stop; human escalation, §22) · 2 usage/environment error
"""
import collections
import datetime
import hashlib
import json
import os
import platform
import re
import subprocess
import sys
import unicodedata

PROTOCOL = "prompts/20260924_1505_p3b-phase1-continuation-protocol-v1.6.5.md"
PROTOCOL_SHA256 = "b70fc216ea6a1cde5a8efbfc1ea2ac1397f18b519bf1c04c292a44d7415ee3f7"
MANIFEST_BODY_SHA256 = "7768b53bae78ef74f318efa4434c25d521548c5c6e7f6446b095c56cab58b5f6"   # G-LOG-0013
EXPECTED = {"labels": 2497, "contribution_rows": 27906, "content_files": 2767, "firewall_limited_files": 12,
            "absence_dimensions": 20107}
KNOWN_ROW_COUNT_DISCREPANCIES = {"open-by-commission-negative-history-category"}  # G-LOG-0007 condition (b)

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR_REL = "docs/knowledgeos/chronological-read"
CR = os.path.join(REPO_ROOT, CR_REL)
IN_ROWS, IN_FILES, IN_DERIVED = "03-CONTRIBUTIONS.jsonl", "02-FILES.jsonl", "20-FAMILIES/_derived.json"
IN_TIERS, IN_PREFLIGHT, IN_MANIFEST = "P3B-TIERS.jsonl", "P3B-PREFLIGHT.json", "P3B-IDENTITY-MANIFEST.jsonl"
OUT_SEARCH, OUT_INDEX, OUT_WORKLOAD = "P3B-ABSENCE-SEARCH.jsonl", "_batch_input_r2/bundle_index.jsonl", "P3B-WORKLOAD.json"

# ---------------- versioned tables (their hashes are recorded) ----------------
LATEX_TABLE = {
    "alpha": "α", "beta": "β", "gamma": "γ", "delta": "δ", "epsilon": "ε", "varepsilon": "ε", "zeta": "ζ", "eta": "η",
    "theta": "θ", "vartheta": "ϑ", "iota": "ι", "kappa": "κ", "lambda": "λ", "mu": "μ", "nu": "ν", "xi": "ξ", "pi": "π",
    "rho": "ρ", "sigma": "σ", "tau": "τ", "upsilon": "υ", "phi": "φ", "varphi": "φ", "chi": "χ", "psi": "ψ",
    "omega": "ω", "Gamma": "Γ", "Delta": "Δ", "Theta": "Θ", "Lambda": "Λ", "Xi": "Ξ", "Pi": "Π", "Sigma": "Σ",
    "Upsilon": "Υ", "Phi": "Φ", "Psi": "Ψ", "Omega": "Ω",
    "le": "≤", "leq": "≤", "ge": "≥", "geq": "≥", "neq": "≠", "ne": "≠", "in": "∈", "notin": "∉", "subseteq": "⊆",
    "subset": "⊂", "supseteq": "⊇", "supset": "⊃", "cup": "∪", "cap": "∩", "to": "→", "rightarrow": "→",
    "leftarrow": "←", "mapsto": "↦", "Rightarrow": "⇒", "Leftrightarrow": "⇔", "times": "×", "forall": "∀",
    "exists": "∃", "neg": "¬", "land": "∧", "lor": "∨", "wedge": "∧", "vee": "∨", "emptyset": "∅", "varnothing": "∅",
    "infty": "∞", "sum": "∑", "prod": "∏", "cdot": "·", "circ": "∘", "oplus": "⊕", "otimes": "⊗", "vdash": "⊢",
    "models": "⊨", "equiv": "≡", "approx": "≈", "sim": "∼", "preceq": "⪯", "succeq": "⪰", "prec": "≺", "succ": "≻",
    "sqsubseteq": "⊑", "sqsupseteq": "⊒", "top": "⊤", "bot": "⊥", "langle": "⟨", "rangle": "⟩",
}
ASCII_TABLE = {
    "α": "alpha", "β": "beta", "γ": "gamma", "δ": "delta", "ε": "epsilon", "ζ": "zeta", "η": "eta", "θ": "theta",
    "ι": "iota", "κ": "kappa", "λ": "lambda", "μ": "mu", "ν": "nu", "ξ": "xi", "π": "pi", "ρ": "rho", "σ": "sigma",
    "τ": "tau", "υ": "upsilon", "φ": "phi", "χ": "chi", "ψ": "psi", "ω": "omega", "Γ": "Gamma", "Δ": "Delta",
    "Θ": "Theta", "Λ": "Lambda", "Ξ": "Xi", "Π": "Pi", "Σ": "Sigma", "Υ": "Upsilon", "Φ": "Phi", "Ψ": "Psi",
    "Ω": "Omega", "≤": "<=", "≥": ">=", "≠": "!=", "→": "->", "←": "<-", "⇒": "=>", "⇔": "<=>", "↦": "|->",
    "×": "x", "∈": "in", "∉": "notin", "∪": "cup", "∩": "cap", "⊆": "subseteq", "⊂": "subset", "∅": "emptyset",
    "∀": "forall", "∃": "exists", "¬": "not", "∧": "and", "∨": "or", "≡": "==", "≈": "~=", "∞": "inf",
}
# KSME-20 directory mapping (S3-4, provisional): (prefix, ksme20_bucket, §14.5 track_tag); first match wins.
TRACK_MAPPING = [
    ("docs/knowledgeos/brainstorming/verification/gap-discovery/", "TRACK-B-GAP-DISCOVERY", "TRACK-B-GAP-DISCOVERY"),
    ("docs/knowledgeos/brainstorming/verification/", "TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED-MD043",
     "TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED"),
    ("docs/knowledgeos/brainstorming/phase_measure_theory/", "TRACK-A-PHASE-MEASURE", "TRACK-A-PHASE-MEASURE"),
    ("research/knowledgeos-sim/", "LANE-B-SIM", "UNCLASSIFIED"),
    ("docs/knowledgeos/brainstorming/three_model_convergence/", "FIREWALLED-THREE-MODEL-CONVERGENCE", "UNCLASSIFIED"),
    ("docs/knowledgeos/brainstorming/knowledgeos_theory_research/", "FIREWALLED-THEORY-RESEARCH", "UNCLASSIFIED"),
    ("docs/knowledgeos/chronological-read/", "SELF-REFERENTIAL-PIPELINE-OUTPUT", "UNCLASSIFIED"),
]
TRACK_FALLBACK = ("UNCLASSIFIED-NEEDS-DECISION", "UNCLASSIFIED")
WORD = re.compile(r"\w")


def canon(obj):
    return json.dumps(obj, sort_keys=True, separators=(",", ":"), ensure_ascii=False)


def sha256_bytes(b):
    return hashlib.sha256(b).hexdigest()


def sha256_file(rel):
    with open(os.path.join(CR, rel), "rb") as f:
        return sha256_bytes(f.read())


def git(*args):
    return subprocess.check_output(["git", "-C", REPO_ROOT, *args], text=True)


# ---------------- normalization (A.4) ----------------
_LATEX_CMD = re.compile(r"\\([A-Za-z]+)")
_LATEX_ARG = re.compile(r"\\[A-Za-z]+\s*\{([^{}]*)\}")


def latex_normalize(s):
    s = _LATEX_CMD.sub(lambda m: LATEX_TABLE.get(m.group(1), m.group(0)), s)   # table commands → Unicode
    prev = None
    while prev != s:                                                           # \cmd{arg} → arg (innermost first)
        prev = s
        s = _LATEX_ARG.sub(lambda m: m.group(1), s)
    return s


def norm_base(s):
    return latex_normalize(unicodedata.normalize("NFKC", s))


def ascii_variant(s):
    return "".join(ASCII_TABLE.get(ch, ch) for ch in s)


def label_terms(label, node):
    raw = {label, label.replace("-", " ")}
    raw |= {x for x in (node.get("notations") or []) if isinstance(x, str)}
    raw |= {x for x in (node.get("aliases") or []) if isinstance(x, str)}
    out = set()
    for t in raw:
        b = norm_base(t).strip()
        if not b:
            continue
        for v in {b, ascii_variant(b)}:
            v = v.strip()
            if v:
                out.add(v if len(v) == 1 else v.casefold())    # single char: case-sensitive (A.4)
    return sorted(out)


# ---------------- Aho–Corasick (pure Python, deterministic) ----------------
class AhoCorasick:
    def __init__(self, patterns):
        self.goto, self.fail, self.out = [{}], [0], [[]]
        for idx, p in enumerate(patterns):
            node = 0
            for ch in p:
                nxt = self.goto[node].get(ch)
                if nxt is None:
                    nxt = len(self.goto)
                    self.goto[node][ch] = nxt
                    self.goto.append({}); self.fail.append(0); self.out.append([])
                node = nxt
            self.out[node].append(idx)
        queue = collections.deque(self.goto[0].values())
        while queue:
            r = queue.popleft()
            for ch, s in self.goto[r].items():
                queue.append(s)
                f = self.fail[r]
                while f and ch not in self.goto[f]:
                    f = self.fail[f]
                self.fail[s] = self.goto[f].get(ch, 0) if self.goto[f].get(ch, 0) != s else 0
                self.out[s] = self.out[s] + self.out[self.fail[s]]

    def iter(self, text):
        node, goto, fail, out = 0, self.goto, self.fail, self.out
        for i, ch in enumerate(text):
            while node and ch not in goto[node]:
                node = fail[node]
            node = goto[node].get(ch, 0)
            if out[node]:
                for idx in out[node]:
                    yield i, idx          # i = index of the last character of the match


def bounded(text, start, end):
    """A.4 boundary rule: the characters before start and at end are not word characters."""
    return (start == 0 or not WORD.match(text[start - 1])) and (end >= len(text) or not WORD.match(text[end]))


def scan(text_cf, text_cs, ac_multi, pats_multi, single_chars):
    """Yield (offset, pattern) for bounded matches. Multi-char patterns are searched in the casefolded text; single
    characters in the case-sensitive text."""
    for i, idx in ac_multi.iter(text_cf):
        p = pats_multi[idx]
        s = i - len(p) + 1
        if bounded(text_cf, s, i + 1):
            yield s, p
    for ch in single_chars:
        pos = text_cs.find(ch)
        while pos != -1:
            if bounded(text_cs, pos, pos + 1):
                yield pos, ch
            pos = text_cs.find(ch, pos + 1)


def read_blobs(specs):
    """specs: list of 'commit:path'. Returns {spec: bytes or None} via one `git cat-file --batch` process."""
    proc = subprocess.Popen(["git", "-C", REPO_ROOT, "cat-file", "--batch"], stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE)
    out = {}
    for spec in specs:
        proc.stdin.write((spec + "\n").encode("utf-8")); proc.stdin.flush()
        head = proc.stdout.readline().decode("utf-8").rstrip("\n")
        if head.endswith(" missing") or head.endswith(" ambiguous"):
            out[spec] = None
            continue
        size = int(head.split()[2])
        out[spec] = proc.stdout.read(size)
        proc.stdout.read(1)
    proc.stdin.close(); proc.wait()
    return out


class IdentityError(Exception):
    """A CONTENT blob could not be read, or its bytes do not hash to the manifest's content_sha256 (fail closed)."""


class ContentResolver:
    """The single code path for CONTENT bytes (v1.6.5 A.4). source_id → P3B-IDENTITY-MANIFEST.jsonl row → blob.
    GIT-OBJECT: `git cat-file --batch` on blob_spec. PRESERVED-AUDIT-COPY: the committed file at blob_spec (relative to
    the repository root). Every blob is verified against content_sha256. historical_path and current_path are never
    opened. Stage-2 readers (S4/S5) must obtain bytes through this class."""

    def __init__(self, manifest_rows):
        self.rows = {r["source_id"]: r for r in manifest_rows}
        if len(self.rows) != len(manifest_rows):
            raise IdentityError("duplicate source_id in the manifest")
        self.observed = {}                          # source_id → sha256 of the bytes actually read (A.4: recorded)

    @classmethod
    def from_manifest(cls, path=None):
        """Entry point for stage-2 readers (S4/S5): refuses a manifest that is not the S2b PASS manifest pinned in
        G-LOG-0013."""
        path = path or os.path.join(CR, IN_MANIFEST)
        with open(path, "rb") as f:
            lines = [l for l in f.read().split(b"\n") if l]
        header = json.loads(lines[0])["header"]
        body_sha = sha256_bytes(b"\n".join(lines[1:]) + b"\n")
        if header.get("result") != "PASS" or not (body_sha == header.get("output_sha256") == MANIFEST_BODY_SHA256):
            raise IdentityError("manifest is not the S2b PASS manifest pinned in G-LOG-0013")
        return cls([json.loads(l) for l in lines[1:]])

    def read_many(self, source_ids):
        """Return {source_id: bytes}; raise IdentityError on the first unknown id, missing blob or hash mismatch."""
        rows = []
        for sid in source_ids:
            r = self.rows.get(sid)
            if r is None or r.get("content_identity") == "UNRESOLVED" or not r.get("blob_spec"):
                raise IdentityError(f"{sid}: no resolvable manifest row")
            rows.append(r)
        git_specs = [r["blob_spec"] for r in rows if r["blob_spec_type"] == "GIT-OBJECT"]
        try:
            got = read_blobs(git_specs) if git_specs else {}
        except (ValueError, IndexError, OSError) as e:
            raise IdentityError(f"unexpected git cat-file output ({type(e).__name__})") from e
        out = {}
        for r in rows:
            if r["blob_spec_type"] == "GIT-OBJECT":
                data = got.get(r["blob_spec"])
            elif r["blob_spec_type"] == "PRESERVED-AUDIT-COPY":
                try:
                    with open(os.path.join(REPO_ROOT, r["blob_spec"]), "rb") as f:
                        data = f.read()
                except OSError:
                    data = None
            else:
                raise IdentityError(f"{r['source_id']}: unknown blob_spec_type {r['blob_spec_type']!r}")
            if data is None:
                raise IdentityError(f"{r['source_id']}: blob missing ({r['blob_spec']})")
            observed = sha256_bytes(data)
            if observed != r["content_sha256"]:
                raise IdentityError(f"{r['source_id']}: sha256 mismatch for {r['blob_spec']}")
            self.observed[r["source_id"]] = observed
            out[r["source_id"]] = data
        return out

    def read(self, source_id):
        return self.read_many([source_id])[source_id]


def track_of(path):
    for prefix, bucket, tag in TRACK_MAPPING:
        if path.startswith(prefix):
            return tag, bucket
    return TRACK_FALLBACK[1], TRACK_FALLBACK[0]


def materialize_bundle(index_rec, rows_lines, files_lines, objects):
    """Rebuild the byte-exact H-04 bundle for one label and verify every hash. Returns (bundle_dict, ok, problems).
    rows_lines / files_lines are the raw byte lines of 03-CONTRIBUTIONS.jsonl / 02-FILES.jsonl (0-based)."""
    problems = []
    obj = objects.get(index_rec["working_label"])
    if obj is None or sha256_bytes(canon(obj).encode("utf-8")) != index_rec["reconciliation_object_sha256"]:
        problems.append("reconciliation object hash")
    rows = []
    for r in index_rec["rows"]:
        raw = rows_lines[r["line"]]
        if sha256_bytes(raw) != r["sha256"]:
            problems.append(f"row line {r['line']}")
        rows.append(raw.decode("utf-8"))
    sources = []
    for f in index_rec["sources"]:
        raw = files_lines[f["line"]]
        if sha256_bytes(raw) != f["sha256"]:
            problems.append(f"file line {f['line']}")
        sources.append(raw.decode("utf-8"))
    bundle = {"working_label": index_rec["working_label"], "reconciliation_object": obj,
              "rows_verbatim": rows, "sources_verbatim": sources}
    return bundle, not problems, problems


def main():
    dry_run = "--dry-run" in sys.argv[1:]
    if [a for a in sys.argv[1:] if a != "--dry-run"]:
        print("usage error", file=sys.stderr)
        return 2
    failures, notes = [], []

    # ---- gate ----
    try:
        with open(os.path.join(CR, IN_TIERS), "rb") as f:
            tiers_raw = f.read()
        with open(os.path.join(CR, IN_PREFLIGHT), encoding="utf-8") as f:
            pre = json.load(f)
    except OSError as e:
        print(f"FAIL: missing S0/S2 artifact ({type(e).__name__})", file=sys.stderr)
        return 1
    t_lines = tiers_raw.split(b"\n")
    t_header = json.loads(t_lines[0])["header"]
    t_body = [l for l in t_lines[1:] if l]
    t_body_sha = sha256_bytes(b"\n".join(t_body) + b"\n")
    if t_header.get("result") != "PASS":
        failures.append("S2 P3B-TIERS.jsonl result is not PASS")
    if t_body_sha != t_header.get("output_sha256"):
        failures.append("P3B-TIERS.jsonl body hash differs from its header")
    try:
        with open(os.path.join(CR, IN_MANIFEST), "rb") as f:
            man_raw = f.read()
    except OSError as e:
        print(f"FAIL: missing S2b manifest ({type(e).__name__}) — S2b must PASS before S3", file=sys.stderr)
        return 1
    m_lines = [l for l in man_raw.split(b"\n") if l]
    m_header = json.loads(m_lines[0])["header"]
    m_body_sha = sha256_bytes(b"\n".join(m_lines[1:]) + b"\n")
    if m_header.get("result") != "PASS":
        failures.append("S2b manifest result is not PASS")
    if not (m_body_sha == m_header.get("output_sha256") == MANIFEST_BODY_SHA256):
        failures.append("S2b manifest body hash differs from its header or from G-LOG-0013")
    try:
        resolver = ContentResolver([json.loads(l) for l in m_lines[1:]])
    except IdentityError as e:
        print(f"  FAIL: identity (fail closed, v1.6.5 A.4): {e}")
        print("S3 RESULT: FAIL — STOP; human escalation (§22)")
        return 1
    s0 = pre.get("body", {}).get("reference_file_sha256", {})
    input_hashes = {}
    for rel in (IN_ROWS, IN_FILES, IN_DERIVED):
        h = sha256_file(rel)
        input_hashes[rel] = h
        if s0.get(f"{CR_REL}/{rel}") != h:
            failures.append(f"{rel} is not identical to its S0-recorded hash")
    for rel in (IN_TIERS, IN_PREFLIGHT, IN_MANIFEST):
        input_hashes[rel] = sha256_file(rel)
    proto_sha = sha256_file(PROTOCOL)
    if proto_sha != PROTOCOL_SHA256:
        failures.append("frozen protocol hash mismatch")

    # ---- inputs ----
    with open(os.path.join(CR, IN_ROWS), "rb") as f:
        rows_lines = [l for l in f.read().split(b"\n") if l.strip()]
    with open(os.path.join(CR, IN_FILES), "rb") as f:
        files_lines = [l for l in f.read().split(b"\n") if l.strip()]
    rows = [json.loads(l) for l in rows_lines]
    files = [json.loads(l) for l in files_lines]
    with open(os.path.join(CR, IN_DERIVED), encoding="utf-8") as f:
        derived = json.load(f)
    objects, nodes = derived["reconciliation_objects"], derived["nodes"]
    tier_of = {json.loads(l)["working_label"]: json.loads(l)["tier"] for l in t_body}
    file_line_of = {f["source_id"]: i for i, f in enumerate(files)}
    content = [f for f in files if f["status"] == "CONTENT"]
    unresolved_rows = [s for s, r in resolver.rows.items() if r.get("content_identity") == "UNRESOLVED"]
    if set(resolver.rows) != {f["source_id"] for f in content} or unresolved_rows:
        failures.append("S2b manifest rows differ from the CONTENT set or contain UNRESOLVED rows")
    firewall = sorted(f["source_id"] for f in files if f["status"] == "FIREWALL-LIMITED")

    # ---- H-04 bundle index (S3-3) ----
    rows_by_label = collections.defaultdict(list)
    for i, r in enumerate(rows):
        for lab in set(r.get("labels") or []):
            if lab in objects:
                rows_by_label[lab].append(i)
    index_records, rc_discrepancies = [], []
    for lab in sorted(objects):
        idxs = sorted(rows_by_label.get(lab, []), key=lambda i: (rows[i]["source_id"], str(rows[i].get("anchor")), i))
        sids = sorted({rows[i]["source_id"] for i in idxs})
        srcs = []
        for sid in sids:
            li = file_line_of.get(sid)
            if li is None:
                failures.append(f"row source {sid} (label {lab}) not in 02-FILES")
                continue
            tag, bucket = track_of(files[li]["path"])
            srcs.append({"source_id": sid, "line": li, "sha256": sha256_bytes(files_lines[li]),
                         "track_tag": tag, "ksme20_bucket": bucket})
        p2 = objects[lab].get("row_count")
        if p2 != len(idxs):
            rc_discrepancies.append({"label": lab, "p2_row_count": p2, "distinct_rows": len(idxs)})
        index_records.append({
            "working_label": lab, "tier": tier_of.get(lab),
            "reconciliation_object_sha256": sha256_bytes(canon(objects[lab]).encode("utf-8")),
            "rows": [{"source_id": rows[i]["source_id"], "anchor": rows[i].get("anchor"), "line": i,
                      "sha256": sha256_bytes(rows_lines[i])} for i in idxs],
            "sources": srcs, "p2_row_count": p2, "distinct_rows": len(idxs)})
    if {d["label"] for d in rc_discrepancies} != KNOWN_ROW_COUNT_DISCREPANCIES:
        failures.append(f"row_count discrepancies differ from the known set recorded in G-LOG-0007: {rc_discrepancies}")
    # self-test: materialize every bundle and verify every hash
    mat_bytes, mat_bad = 0, 0
    for rec in index_records:
        bundle, ok_b, _ = materialize_bundle(rec, rows_lines, files_lines, objects)
        mat_bad += (not ok_b)
        mat_bytes += len(canon(bundle).encode("utf-8"))
    if mat_bad:
        failures.append(f"{mat_bad} bundles failed hash verification on materialization")

    # ---- stage-1 search (A.4; S3-1, S3-2) ----
    terms_by_label = {lab: label_terms(lab, nodes.get(lab, {})) for lab in objects}
    all_terms = sorted({t for ts in terms_by_label.values() for t in ts})
    multi = [t for t in all_terms if len(t) > 1]
    single = [t for t in all_terms if len(t) == 1]
    ac = AhoCorasick(multi)
    labels_of_term = collections.defaultdict(list)
    for lab, ts in terms_by_label.items():
        for t in ts:
            labels_of_term[t].append(lab)
    ledger_hits = collections.defaultdict(lambda: collections.defaultdict(list))   # label -> sid -> [[anchor, term]]
    for r in rows:
        text = ""
        if r.get("statement"):
            text += str(r["statement"])
        if r.get("type_signature"):
            ts = r["type_signature"]
            text += "\n" + (ts if isinstance(ts, str) else canon(ts))
        base = norm_base(text)
        for _, term in scan(base.casefold(), base, ac, multi, single):
            for lab in labels_of_term[term]:
                ledger_hits[lab][r["source_id"]].append([r.get("anchor"), term])
    if failures:                                   # never read CONTENT bytes through an unverified manifest
        for f_ in failures:
            print(f"  FAIL: {f_}")
        print("S3 RESULT: FAIL — STOP; human escalation (§22)")
        return 1
    try:
        blobs = resolver.read_many([f["source_id"] for f in content])
    except IdentityError as e:
        print(f"  FAIL: identity (fail closed, v1.6.5 A.4): {e}")
        print("S3 RESULT: FAIL — STOP; human escalation (§22)")
        return 1
    identity_summary = {                           # §11.4 (v1.6.5), additive; over the files searched
        "stasis_unobservable": sum(1 for f in content
                                   if resolver.rows[f["source_id"]]["historical_linkage"] == "STASIS-UNOBSERVABLE"),
        "p0_row_exception": sum(1 for f in content
                                if resolver.rows[f["source_id"]]["content_identity"] == "PATH-CONTENT-P0-ROW-MISALIGNED")}
    verified_pairs = sorted(resolver.observed.items())
    verified_blobs = {"count": len(verified_pairs),
                      "encoding": "sha256 of canonical JSON of [[source_id, sha256], ...] sorted by source_id",
                      "digest_sha256_over_sorted_source_id_sha256_pairs": sha256_bytes(canon(verified_pairs).encode("utf-8"))}
    raw_hits = collections.defaultdict(lambda: collections.defaultdict(list))      # label -> sid -> [[offset, term]]
    unreadable, replaced_decodes, file_chars = [], 0, {}
    for f in content:
        b = blobs[f["source_id"]]
        txt = b.decode("utf-8", errors="replace")
        replaced_decodes += ("\ufffd" in txt)
        base = norm_base(txt)
        file_chars[f["source_id"]] = len(base)
        for off, term in scan(base.casefold(), base, ac, multi, single):
            for lab in labels_of_term[term]:
                raw_hits[lab][f["source_id"]].append([off, term])
    full_lists = not unreadable

    # ---- records ----
    search_lines, dims_total, zero_hit_dims, hits_per_label = [], 0, 0, {}
    for lab in sorted(objects):
        terms = terms_by_label[lab]
        tidx = {t: i for i, t in enumerate(terms)}
        lh = {sid: sorted([[a, tidx[t]] for a, t in v], key=lambda x: (str(x[0]), x[1]))
              for sid, v in sorted(ledger_hits.get(lab, {}).items())}
        rh = {sid: sorted([[o, tidx[t]] for o, t in v]) for sid, v in sorted(raw_hits.get(lab, {}).items())}
        n_hits = sum(len(v) for v in lh.values()) + sum(len(v) for v in rh.values())
        hits_per_label[lab] = {"hits": n_hits, "raw_files": sorted(rh)}
        search_lines.append(canon({"record": "LABEL-HITS", "label": lab, "terms": terms,
                                   "ledger_hits": lh, "raw_hits": rh}))
        for dim in objects[lab].get("completeness_absences") or []:
            dims_total += 1
            neg = None
            if n_hits == 0:
                zero_hit_dims += 1
                neg = "NEGATIVE-CENSUS" if full_lists else "NEGATIVE-BOUNDED"
            search_lines.append(canon({
                "record": "DIMENSION", "label": lab, "dimension": dim, "hits_ref": lab,
                "scope": "CORPUS-WIDE" if full_lists else "LEDGER-ONLY-OR-PARTIAL",
                "files_searched": len(content) - len(unreadable), "firewall_skipped": firewall,
                "negative_label": neg, "population_basis": "CENSUS", "identity_summary": identity_summary}))

    # ---- counts / workload ----
    observed = {"labels": len(objects), "contribution_rows": len(rows), "content_files": len(content),
                "firewall_limited_files": len(firewall), "absence_dimensions": dims_total}
    for k, v in EXPECTED.items():
        if observed[k] != v:
            failures.append(f"{k}: expected {v}, observed {observed[k]}")
    if unreadable:
        notes.append(f"{len(unreadable)} CONTENT files unread: negatives are NEGATIVE-BOUNDED")   # unreachable: read_many fails closed
    labels_with_dims = [l for l in objects if objects[l].get("completeness_absences")]
    reading_chars = sum(file_chars.get(sid, 0) for l in labels_with_dims for sid in hits_per_label[l]["raw_files"])
    file_hit_labels = collections.Counter(sid for l in labels_with_dims for sid in hits_per_label[l]["raw_files"])
    hits_dist = sorted(hits_per_label[l]["hits"] for l in labels_with_dims)
    q = lambda p: hits_dist[min(len(hits_dist) - 1, int(p * len(hits_dist)))] if hits_dist else 0
    workload_body = {
        "provisional": "recomputed at S3b without sealed material (§19.1)",
        "absence_dimensions": dims_total, "labels_with_absence_dimensions": len(labels_with_dims),
        "dimensions_with_zero_stage1_hits": zero_hit_dims,
        "stage1_hits_total_over_labels_with_dimensions": sum(hits_dist),
        "hits_per_label_distribution": {"p50": q(.5), "p90": q(.9), "p99": q(.99), "max": hits_dist[-1] if hits_dist else 0},
        "top_labels_by_hits": sorted(({"label": l, "hits": hits_per_label[l]["hits"],
                                       "raw_files": len(hits_per_label[l]["raw_files"])} for l in labels_with_dims),
                                     key=lambda x: -x["hits"])[:15],
        "unique_files_hit": len(file_hit_labels),
        "per_file_label_concentration_top": [{"source_id": s, "labels": n} for s, n in file_hit_labels.most_common(10)],
        "stage2_whole_file_reading_chars_if_every_hit_file_read_once_per_label": reading_chars,
        "bundle_materialized_bytes_total": mat_bytes,
        "unreadable_content_files": unreadable, "decode_replacement_files": replaced_decodes,
        "verified_blob_sha256": {s: h for s, h in verified_pairs},
    }

    ok = not failures
    script_path = os.path.abspath(__file__)
    common = {
        "script_name": "p3b_s3_mechanical_prep.py",
        "script_version": git("hash-object", script_path).strip(),
        "script_committed_unmodified": git("status", "--porcelain", "--", script_path).strip() == "",
        "protocol": PROTOCOL, "protocol_sha256_observed": proto_sha, "seed": None,
        "input_hashes": input_hashes, "s2_link": {"tiers_output_sha256": t_body_sha},
        "identity_binding": {"manifest": IN_MANIFEST, "manifest_file_sha256": input_hashes.get(IN_MANIFEST),
                             "manifest_body_sha256": m_body_sha, "reader": "ContentResolver (sha256-verified, fail closed)",
                             "verified_blobs": verified_blobs, "identity_summary": identity_summary},
        "python_version": platform.python_version(), "platform": platform.platform(),
        "run_timestamp_utc": datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ"),
        "result": "PASS" if ok else "FAIL", "failures": failures, "notes": notes,
    }
    search_body = ("\n".join(search_lines) + "\n").encode("utf-8")
    search_header = dict(common, **{
        "artifact": OUT_SEARCH,
        "parameters": {"terms": "A.4 exactly (S3-1): working_label ±hyphen→space, notations, aliases; §11.4 co-member "
                                "notations NOT added (recorded observation, G-LOG-0008)",
                       "normalization": "NFKC → LaTeX table → casefold (multi-char); single-char case-sensitive",
                       "latex_table_sha256": sha256_bytes(canon(LATEX_TABLE).encode()),
                       "ascii_table_sha256": sha256_bytes(canon(ASCII_TABLE).encode()),
                       "boundary_rule": "chars before/after a match are not \\w (A.4)",
                       "offset_basis": "character offsets in the normalized (casefolded for multi-char) text",
                       "storage": "S3-2: LABEL-HITS once per label + one DIMENSION record per absence dimension"},
        "counts": dict(observed, labels_with_hits=sum(1 for v in hits_per_label.values() if v["hits"]),
                       terms_total=sum(len(t) for t in terms_by_label.values()), distinct_terms=len(all_terms),
                       single_char_terms=len(single)),
        "population_basis": "CENSUS",
        "output_sha256": sha256_bytes(search_body)})
    index_body = ("\n".join(canon(r) for r in index_records) + "\n").encode("utf-8")
    index_header = dict(common, **{
        "artifact": OUT_INDEX,
        "parameters": {"h04": "V1-PLUS-ROWS verbatim slice (G-LOG-0007)", "persistence": "index + deterministic "
                       "materialization (S3-3)", "row_membership": "distinct rows whose labels[] contains the label",
                       "row_order": "(source_id, anchor, line)",
                       "track_mapping": [list(m) for m in TRACK_MAPPING] + [["*", *TRACK_FALLBACK]],
                       "track_mapping_sha256": sha256_bytes(canon(TRACK_MAPPING).encode()),
                       "track_status": "PROVISIONAL-PENDING-OMQ-07 (S3-4)"},
        "row_count_discrepancies": rc_discrepancies,
        "materialization_self_test": {"bundles": len(index_records), "hash_failures": mat_bad,
                                      "materialized_bytes_total": mat_bytes},
        "output_sha256": sha256_bytes(index_body)})
    workload_header = dict(common, **{"artifact": OUT_WORKLOAD,
                                      "output_sha256": sha256_bytes(canon(workload_body).encode("utf-8"))})

    print(f"S3 MECHANICAL PREP: labels {observed['labels']} · rows {observed['contribution_rows']} · content files "
          f"{observed['content_files']} (firewall skipped {len(firewall)}, unreadable {len(unreadable)}) · "
          f"absence dimensions {dims_total} (zero-hit {zero_hit_dims})")
    print(f"  terms {search_header['counts']['terms_total']} (distinct {len(all_terms)}, single-char {len(single)}) · "
          f"labels with hits {search_header['counts']['labels_with_hits']} · stage-1 hits (labels with dims) "
          f"{sum(hits_dist)} · hits/label p50 {q(.5)} p90 {q(.9)} p99 {q(.99)} max {workload_body['hits_per_label_distribution']['max']}")
    print(f"  unique files hit {len(file_hit_labels)} · stage-2 reading if each hit file read once per label: "
          f"{reading_chars/1e6:.1f} M chars")
    print(f"  bundles {len(index_records)} · materialization hash failures {mat_bad} · materialized "
          f"{mat_bytes/1e6:.1f} MB · row_count discrepancies {[d['label'] for d in rc_discrepancies]}")
    print(f"  output sizes: search {len(search_body)/1e6:.1f} MB · index {len(index_body)/1e6:.1f} MB")
    for t in workload_body["top_labels_by_hits"][:8]:
        print(f"    top: {t['label']}: {t['hits']} hits in {t['raw_files']} files")
    for f_ in failures:
        print(f"  FAIL: {f_}")
    for n_ in notes:
        print(f"  NOTE: {n_}")
    if ok and not dry_run:
        os.makedirs(os.path.join(CR, "_batch_input_r2"), exist_ok=True)
        with open(os.path.join(CR, OUT_SEARCH), "wb") as f:
            f.write((canon({"header": search_header}) + "\n").encode("utf-8") + search_body)
        with open(os.path.join(CR, OUT_INDEX), "wb") as f:
            f.write((canon({"header": index_header}) + "\n").encode("utf-8") + index_body)
        with open(os.path.join(CR, OUT_WORKLOAD), "w", encoding="utf-8") as f:
            json.dump({"header": workload_header, "body": workload_body}, f, ensure_ascii=False, indent=1,
                      sort_keys=True)
            f.write("\n")
        print(f"wrote {OUT_SEARCH}, {OUT_INDEX}, {OUT_WORKLOAD}")
    print("S3 RESULT:", "PASS" if ok else "FAIL — STOP; human escalation (§22)")
    return 0 if ok else 1


if __name__ == "__main__":
    sys.exit(main())
