#!/usr/bin/env python3
"""P3b S5 shared library (plan v2.3.2, G-LOG-0041). Imported by every S5 tool; never executed on its own.

Scope and guarantees:
  * Discovery-only loaders. Every loader returns S5-population (Tier-U/Z discovery) or discovery-population data only.
    Census files that also hold hold-out records (02, 03, 08, 31, _derived.json) are read ONLY through the filtering
    loaders below, which drop any record naming a non-discovery label or citing a hold-out file (addendum §2, §4).
  * Hold-out lists (`holdout_sets`) are returned to filter/scanner code only. Callers must never print, log or pass
    them to an agent (addendum §5). Functions here never print them.
  * `assert_sealed()` is called at the start and end of every S5 tool (plan §M item 2).
  * `header()` builds the §19.5 header (script blob, parameters, input hashes, output sha256, versions, platform).
  * `int_seed()` derives the integer seeds of plan §G.3 (SeedSequence spawn → int), so frozen A.6's
    `random.Random(seed)` can be used with a recorded integer.
  * `ALLOWLIST` is plan §M item 6; `check_inputs()` refuses anything else.
"""
import datetime
import hashlib
import importlib.util
import json
import os
import platform
import re
import subprocess
import sys
import unicodedata

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("p3b_discovery_io", os.path.join(_HERE, "p3b_discovery_io.py"))
dio = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(dio)
s3 = dio.s3
CR, REPO_ROOT, canon, sha256_bytes = s3.CR, s3.REPO_ROOT, s3.canon, s3.sha256_bytes

PROTOCOL = "prompts/20260924_2311_p3b-phase1-continuation-protocol-v1.7.md"
PROTOCOL_SHA256 = "38021aa4328c1fae23edf2eeab068d1a8d446d6197aac6567681a72687502d12"
PLAN = "audit-p3b/20260925_0106_s5-plan-v2.3.md"
PLAN_SHA256 = "f89a16824efaeea08d54fbc857b4cf36184cb698deb482550026fe194b3c7de1"
SEAL_ID = "HS-3d32dd44d162"
HUBS = "P3B-S5-HUBS.jsonl"
HUBS_BODY_SHA256 = "58aed9606435047c6a57b3863218b08c7c435c21ec55491f85d423da39aa7f46"
POPULATION_BASIS = f"DISCOVERY-POPULATION ({SEAL_ID})"
MODEL_ID = "claude-opus-5-5"                  # the [1m] context variant is the same model (plan header)

# plan §M item 6: the only files S5 tooling may read (relative to CR). Exact names, plus the narrow patterns below.
# Narrowed after the independent infrastructure review (G-LOG-0042, M1): no directory-wide prefixes.
ALLOWLIST = (
    "P3B-SAMPLE-PLAN.jsonl", HUBS, "P3B-DISCOVERY-SEARCH.jsonl", "_batch_input_r2/bundle_index_discovery.jsonl",
    "20-FAMILIES/_derived.json", "03-CONTRIBUTIONS.jsonl", "02-FILES.jsonl", "31-RECONCILIATION-PAIRS.jsonl",
    "08-OVERLAP-REGISTER.jsonl", "P3B-IDENTITY-MANIFEST.jsonl", "P3B-HOLDOUT-SEAL.json",
    "P3B-HOLDOUT-QUARANTINE.jsonl", "P3B-INPUT-MANIFEST.json", "P3B-STATE.json", PROTOCOL, PLAN,
    "audit-p3b/20260925_0150_s5-operations-spec.md", "P3B-GOVERNANCE-LOG.md", "_batch_manifest_p3b_r2.jsonl",
    "audit-p3b/20260928_BINARY-DECISIONS.json",                          # v2.6-DC3 binary decision records (G-LOG-0099)
    "P3B-BATCH-SNAPSHOT.json", "P3B-PASS-SNAPSHOT.json", "P3B-RESEARCH-REGISTER.jsonl", "P3B-P1-GAP-CAPTURE.jsonl",
    "P3B-CROSS-CANDIDATES.jsonl", "P3B-CROSS-BLIND.jsonl", "P3B-CROSS-REVEAL.jsonl", "audit-p3b/S5A-PASS-RECORD.json",
    "P3B-ESCALATIONS.jsonl",                                                   # §22 escalation records (G-LOG-0045)
    "P3B-CORRECTIONS.jsonl",                   # rev3 §12.3 correction log (P3B-COR-####); §21 AUDIT-UPHELD (G-LOG-0106)
)
ALLOW_PATTERNS = (
    r"_batch_input_r2/s5/(rev\d+/)?[A-Za-z0-9._-]+(/[A-Za-z0-9._-]+)?",   # S5 slices (per contract revision), the flags extract
    r"_batch_input_r2/s4-pilot-r22/[A-Za-z0-9._-]+\.json",             # S4 R2.2 slices (comparison / fixtures only)
    r"prompts/2026(09(2[5-9]|30)|1[0-2]\d\d)_\d{4}_p3b-(agent-contract-r2|pass-contract)\.md",   # S5 contracts
    r"audit-p3b/(?!S5A-PASS-RECORD)S5A?-[A-Za-z0-9._-]+\.jsonl?",     # S5 artifacts only (the pass record: exact name)
    r"ledger-p3b-r2/(OB\d{4}-R2(\.\d+|S)?|OT\d{4}-R2|OA\d{4}-R2)/[A-Za-z0-9._-]+\.jsonl",   # S5 runs
    r"ledger-p3b-r2/OB\d{4}-R5(-L\d{2}(U\d{2}|S)?)?/([A-Za-z0-9._-]+\.jsonl|R5-BATCH\.json)",   # contract revision 5 runs
    r"ledger-p3b-r2/OB\d{4}-R6(-L\d{2}(U\d{2}|S)?)?/[A-Za-z0-9._-]+\.jsonl?",   # contract revision 6 runs
    r"ledger-p3b-r2/OB\d{4}-R7(-L\d{2}(U\d{2}|S)?)?/[A-Za-z0-9._-]+\.jsonl?",   # contract revision 7 runs + assembly
    r"ledger-p3b-r2/(S4-R22/PB0[2-5]|S4-PILOT-R2-002)/[A-Za-z0-9._-]+\.jsonl",       # S4 R2.2 (comparison only)
    r"pilot-s5-decomp/[A-Za-z0-9._-]+(/[A-Za-z0-9._-]+)?",             # decomposition pilot (non-production, G-LOG-0052)
    r"audit-p3b/H06-TRANCHE-\d{4}\.json",                              # EG-9 H-06 tranche files (G-LOG-0106)
    r"ledger-p3b-r2/OB\d{4}-R7\.A\d+/RETIREMENT-(INTENT|COMPLETE)\.json",   # EG-6 retirement records (G-LOG-0106)
)
# family .md files of S5-population labels only, readable ONLY for FAMILY_MD_PURPOSE: building the S5 slice inputs
# (§9.8 step-1 input; G-LOG-0045 item 1). Never a research source, never a corpus expansion, never any other purpose.
FAMILY_MD = r"20-FAMILIES/(?P<label>[A-Za-z0-9][A-Za-z0-9._-]*)\.md"
FAMILY_MD_PURPOSE = "s5-slice-construction"
# a file readable only for one named purpose
PURPOSE_ONLY = {"09-ORCHESTRATOR-FLAGS.md": "o22-extract"}
FORBIDDEN = ("P3B-HOLDOUT-SEALED-S3.jsonl", "P3B-ABSENCE-SEARCH.jsonl", "_batch_input_r2/bundle_index.jsonl",
             "P3B-WORKLOAD.json", "P3B-TIERS.jsonl", "11-OBJECT-INDEX.jsonl", "11-UNRESOLVED-CANDIDATES.jsonl",
             "09-READ-STATUS.jsonl", "ledger/")
_POP_CACHE = []


class S5Error(RuntimeError):
    pass


def rel(path):
    """CR-relative path after resolving symlinks (realpath). A path resolving outside CR is returned as None."""
    p = os.path.realpath(path if os.path.isabs(path) else os.path.join(CR, path))
    root = os.path.realpath(CR)
    return os.path.relpath(p, root) if os.path.commonpath([p, root]) == root else None


def _s5_labels():
    if not _POP_CACHE:
        with open(os.path.join(CR, "P3B-SAMPLE-PLAN.jsonl"), encoding="utf-8") as f:
            _POP_CACHE.append(frozenset(json.loads(l)["working_label"] for l in list(f)[1:] if l.strip()))
    return _POP_CACHE[0]


def check_inputs(paths, purpose=None):
    """Refuse any input outside the allowlist or on the forbidden list (plan §M item 6). Symlinks are resolved; a path
    resolving outside the repository's chronological-read directory is refused."""
    bad = []
    for p in paths:
        r = rel(p)
        if r is None:
            bad.append("<outside chronological-read>")
            continue
        r = r.replace(os.sep, "/")
        if any(r == f or r.startswith(f) for f in FORBIDDEN):
            bad.append(r)
        elif r in ALLOWLIST or any(re.fullmatch(pat, r) for pat in ALLOW_PATTERNS):
            continue
        elif PURPOSE_ONLY.get(r) is not None and PURPOSE_ONLY[r] == purpose:
            continue
        else:
            m = re.fullmatch(FAMILY_MD, r)
            if not (m and purpose == FAMILY_MD_PURPOSE and m.group("label") in _s5_labels()):
                bad.append(r)
    if bad:
        raise S5Error(f"inputs outside the S5 allowlist or forbidden: {len(bad)} path(s)")   # paths not echoed (M2)
    return True


def sha256_file(path):
    with open(path if os.path.isabs(path) else os.path.join(CR, path), "rb") as f:
        return hashlib.sha256(f.read()).hexdigest()


def git(*a):
    return subprocess.run(["git", "-C", REPO_ROOT, *a], capture_output=True, text=True).stdout.strip()


def jl(path):
    check_inputs([path])
    with open(os.path.join(CR, path), encoding="utf-8") as f:
        return [json.loads(line) for line in f if line.strip()]


# ---------------------------------------------------------------- seal / hold-out (filters only; never printed)
def assert_sealed():
    """Plan §M item 2. Raises unless the seal is SEALED as HS-3d32dd44d162 with the approved file-list hash."""
    seal = dio.load_seal()
    if seal.get("state") != "SEALED" or seal.get("seal_id") != SEAL_ID:
        raise S5Error("hold-out not SEALED as " + SEAL_ID)
    dio.discovery_resolver()                  # verifies the approved hold-out file-list hash (G-LOG-0016)
    return True


def holdout_sets():
    """(H, HF, allowed_mentions) for filter and scanner code only. Never print, log or pass to an agent."""
    seal = dio.load_seal()
    return frozenset(seal["holdout_labels"]), frozenset(seal["holdout_files"]), \
        {s: frozenset(v) for s, v in seal.get("hf_sid_mentions_in_discovery_bundles", {}).items()}


def holdout_pattern(H):
    """Addendum §3 name matching: the label string with no label character [A-Za-z0-9._-] immediately before or after
    it; for §6 quarantine a match is also tested after removing one trailing '.' (the optional '\\.' below)."""
    return re.compile(r"(?<![A-Za-z0-9._-])(" + "|".join(map(re.escape, sorted(H, key=len, reverse=True)))
                      + r")\.?(?![A-Za-z0-9._-])")


# G-LOG-0045 item 3 (strict range rule): an S-id range whose closed interval contains a hold-out S-id cites that
# hold-out file implicitly; it counts as one hold-out citation per spanned hold-out id outside the §4 listed mentions.
# The text is first normalized for range detection only (_range_norm): literal \uXXXX escapes decoded, NFKC, format
# characters (Cf: soft hyphen, zero-width space ...) dropped, every dash (Pd), minus, tilde, wave dash and hyphen
# bullet mapped to '-'. Separators are then: dashes (spaced or repeated; not an arrow '->'), two or more dots (spaced
# or not), the words to/through/thru/until/till/bis/up to/through to (any case, optionally dash-wrapped), and
# 'between|from S.. and|to S..'. NOT ranges by human ruling (G-LOG-0045: quarantine only notation with sufficient
# semantic evidence of an S-id interval): '/', ':' and arrows ('->', '=>', U+2192 ...). The second 'S' is optional; an abbreviated second
# endpoint (S0101-20) replaces the trailing digits; matches are zero-width lookaheads, so chained ranges are read
# pairwise; a reversed range is read low..high. A text matcher cannot enumerate every notation: the contract also
# forbids ranges outright, and any residual form is a recorded observation, not an accepted channel.
_DASHLIKE = set("~−⁃〜～⁓")
_WORDS = r"(?i:up\s+to|through\s+to|through|thru|until|till|bis\s+zu|bis|to)"
_RANGE_SEP = (r"(?:\s*(?:-\s*)+(?!>)"                          # -, --, - -   (an arrow '->' is not a range)
              r"|\s*\.(?:\s*\.)+\s*"                            # .., ..., . .
              r"|\s*-*\s*" + _WORDS + r"\s*-*\s*)")
SID_RANGE = re.compile(r"(?<![A-Za-z0-9])(?=S(\d{4})" + _RANGE_SEP + r"S?(\d{1,4})(?![0-9]))")
SID_BETWEEN = re.compile(r"(?i:between|from)\s+S(\d{4})\s+(?i:and|to)\s+S?(\d{1,4})(?![0-9])")


def _range_norm(text):
    t = re.sub(r"\\u([0-9a-fA-F]{4})", lambda m: chr(int(m.group(1), 16)), text)
    t = unicodedata.normalize("NFKC", t)
    out = []
    for ch in t:
        cat = unicodedata.category(ch)
        if cat == "Cf":
            continue
        out.append("-" if cat == "Pd" or ch in _DASHLIKE else ch)
    return "".join(out)


def sid_range_spans(text, HF):
    """The hold-out S-ids implicitly cited by S-id ranges in `text` (a set; never printed)."""
    nums = {int(s[1:]) for s in HF}
    t = _range_norm(text)
    out = set()
    for a, b in SID_RANGE.findall(t) + SID_BETWEEN.findall(t):
        b = a[:4 - len(b)] + b                          # abbreviated endpoint: S0101-20 -> S0101..S0120
        lo, hi = sorted((int(a), int(b)))
        out |= {f"S{n:04d}" for n in nums if lo <= n <= hi}
    return out


def quarantine_hits(text, label=None, sets=None):
    """Counts only: (hold-out label names found, hold-out S-ids cited outside the §4 listed mentions, explicitly or
    through an S-id range; G-LOG-0045 item 3)."""
    H, HF, allowed = sets or holdout_sets()
    n_labels = len(holdout_pattern(H).findall(text)) if H else 0
    ok_mentions = {s for s, labs in allowed.items() if label is not None and label in labs}
    cited = (set(re.findall(r"\bS\d{4}\b", text)) & HF) | sid_range_spans(text, HF)
    n_files = len(cited - ok_mentions)
    return n_labels, n_files


# ---------------------------------------------------------------- discovery-only loaders
def s5_population():
    """{label: sample-plan record} for the 1,975 Tier-U/Z discovery labels."""
    return {r["working_label"]: r for r in jl("P3B-SAMPLE-PLAN.jsonl")[1:]}


def hubs():
    with open(os.path.join(CR, HUBS), encoding="utf-8") as f:
        lines = f.read().split("\n")
    body = "\n".join(l for l in lines[1:] if l) + "\n"
    if sha256_bytes(body.encode("utf-8")) != HUBS_BODY_SHA256:
        raise S5Error("hub list body hash differs from the frozen value (G-LOG-0033)")
    return {json.loads(l)["working_label"]: json.loads(l) for l in lines[1:] if l}


def discovery_search():
    recs = jl("P3B-DISCOVERY-SEARCH.jsonl")
    hits = {r["label"]: r for r in recs[1:] if r["record"] == "LABEL-HITS"}
    dims = {}
    for r in recs[1:]:
        if r["record"] == "DIMENSION":
            dims.setdefault(r["label"], []).append(r)
    return hits, dims


def discovery_labels():
    hits, dims = discovery_search()
    return frozenset(hits) | frozenset(dims)


def bundle_index():
    return {r["working_label"]: r for r in jl("_batch_input_r2/bundle_index_discovery.jsonl")[1:]}


def files_meta():
    """02-FILES rows for discovery files only (hold-out files dropped)."""
    _, HF, _ = holdout_sets()
    return {r["source_id"]: r for r in jl("02-FILES.jsonl") if r["source_id"] not in HF}


def discovery_pairs():
    """P3a pairs whose two labels are both discovery labels (no pair crosses the H-19 split)."""
    D = discovery_labels()
    return [p for p in jl("31-RECONCILIATION-PAIRS.jsonl") if p.get("a") in D and p.get("b") in D]


def derived_groups():
    """P2a groups restricted to S5-population members; notations/aliases for S5-population nodes only."""
    pop = s5_population()
    check_inputs(["20-FAMILIES/_derived.json"])
    dv = json.load(open(os.path.join(CR, "20-FAMILIES", "_derived.json"), encoding="utf-8"))
    groups = [{"group_id": g["group_id"], "kind": g["kind"], "members": sorted(m for m in g["members"] if m in pop)}
              for g in dv["groups"]]
    nodes = {lab: {"notations": n.get("notations") or [], "aliases": n.get("aliases") or []}
             for lab, n in dv["nodes"].items() if lab in pop}
    return groups, nodes


def file_degree():
    """Plan §I: degree = number of discovery labels (2,452) whose rows cite the file."""
    bi, D, deg = bundle_index(), discovery_labels(), {}
    for lab, r in bi.items():
        if lab in D:
            for s in {x["source_id"] for x in r.get("rows", [])}:
                deg[s] = deg.get(s, 0) + 1
    return deg


# ---------------------------------------------------------------- matching bands (plan §H.1; pre-S5 only)
def band_row(n):
    return "1" if n <= 1 else "2-3" if n <= 3 else "4-9" if n <= 9 else ">=10"


def band_pair(n):
    return "0" if n == 0 else "1" if n == 1 else ">=2"


def band_prov(provs):
    if not provs:
        return "none-PRIMARY"
    p = sum(1 for x in provs if x == "PRIMARY")
    return "all-PRIMARY" if p == len(provs) else "none-PRIMARY" if p == 0 else "mixed"


def to_date(v):
    """02-FILES best_historical_date: ISO date (EXPLICIT) or epoch seconds (MTIME basis) -> UTC date, else None."""
    s = str(v).strip() if v is not None else ""
    if re.fullmatch(r"\d{9,11}", s):
        return datetime.datetime.fromtimestamp(int(s), datetime.timezone.utc).date()
    try:
        return datetime.date.fromisoformat(s[:10])
    except ValueError:
        return None


def band_span(dates):
    ds = sorted(d for d in (to_date(x) for x in dates) if d)
    if len(ds) < 2:
        return "<1d"
    days = (ds[-1] - ds[0]).days
    return "<1d" if days < 1 else "1-7d" if days <= 7 else ">7d"


def band_degree(n):
    return "<=5" if n <= 5 else "6-10" if n <= 10 else ">10"


def label_bands(pop=None):
    """{label: {row, pair, prov, span, degree, has_formal_rows}} for S5-population labels, from pre-S5 data only."""
    pop = pop or s5_population()
    bi, fm, deg = bundle_index(), files_meta(), file_degree()
    pc = {}
    for p in discovery_pairs():
        pc[p["a"]] = pc.get(p["a"], 0) + 1
        pc[p["b"]] = pc.get(p["b"], 0) + 1
    formal = set()
    for c in jl("03-CONTRIBUTIONS.jsonl"):
        ts = c.get("type_signature")                          # a formal row carries a non-empty type signature
        if ts and not (isinstance(ts, str) and not ts.strip()):
            for lab in c.get("labels") or []:
                if lab in pop:
                    formal.add(lab)
    out = {}
    for lab in pop:
        rows = bi.get(lab, {}).get("rows", [])
        srcs = sorted({x["source_id"] for x in rows})
        out[lab] = {"row": band_row(bi.get(lab, {}).get("distinct_rows", 0)), "pair": band_pair(pc.get(lab, 0)),
                    "prov": band_prov([fm[s].get("provenance") for s in srcs if s in fm]),
                    "span": band_span([fm[s].get("best_historical_date") for s in srcs if s in fm]),
                    "degree": band_degree(max([deg.get(s, 0) for s in srcs] or [0])),
                    "has_formal_rows": lab in formal}
    return out


BAND_VARS = ("row", "pair", "prov", "span", "degree")          # relaxation order: reverse (degree first)


def set_band(members, bands, variables=BAND_VARS):
    """Plan §H.1: a set's band profile per variable = the sorted multiset of its members' bands."""
    return {v: tuple(sorted(bands[m][v] for m in members)) for v in variables}


# ---------------------------------------------------------------- seeds (plan §G.3)
def int_seed(root, n, i):
    import numpy as np
    return int(np.random.SeedSequence(root).spawn(n)[i].generate_state(1)[0])


# ---------------------------------------------------------------- §19.5 header
def header(script_file, inputs, parameters, body_text, extra=None):
    h = {"script_name": rel(script_file), "script_version": git("hash-object", os.path.abspath(script_file)),
         "script_committed_unmodified": git("status", "--porcelain", "--", os.path.abspath(script_file)) == "",
         "parameters": parameters, "input_hashes": {i: sha256_file(i) for i in sorted(inputs)},
         "output_sha256": sha256_bytes(body_text.encode("utf-8")), "python_version": platform.python_version(),
         "platform": platform.platform(), "protocol_sha256": PROTOCOL_SHA256, "plan_sha256": PLAN_SHA256,
         "population_basis": POPULATION_BASIS,
         "run_timestamp_utc": datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")}
    try:
        import numpy
        h["numpy_version"] = numpy.__version__
    except ImportError:
        pass
    if extra:
        h.update(extra)
    return h


def verify_frozen():
    """Frozen inputs every S5 tool depends on."""
    if sha256_file(PROTOCOL) != PROTOCOL_SHA256:
        raise S5Error("frozen protocol v1.7 hash mismatch")
    if sha256_file(PLAN) != PLAN_SHA256:
        raise S5Error("approved S5 plan v2.3.2 hash mismatch")
    hubs()
    return True


if __name__ == "__main__":
    print("library module; import it", file=sys.stderr)
    sys.exit(2)
