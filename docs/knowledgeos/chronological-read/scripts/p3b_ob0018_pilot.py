#!/usr/bin/env python3
"""OB0018 decomposition-fidelity pilot: mechanical tooling (non-production). G-LOG-0078.

A minimum useful decomposition-fidelity pilot (n_labels = 1, n_units = 3). It compares a single-context baseline with
three synthesis arms over the same three reading units:
  A records only (no re-reads);
  B records plus the deterministic invariant/consistency report (no re-reads);
  C records plus mandatory whole-file re-reads at cross-unit adjacencies and invariant conflicts (budget frozen).
Pre-registration: `audit-p3b/20260926_1800_ob0018-decomposition-fidelity-preregistration.md`. Contract:
`prompts/20260926_1800_p3b-ob0018-pilot-contract.md`. Namespace PX0018 / `pilot-s5-decomp/ob0018/`. Production batch
OB0018 (run OB0018-R2, PREPARED) is never dispatched, modified or written.

It executes no agent. Sub-commands:
  selftest                 synthetic data only; reads no corpus
  plan                     regenerate the control slice in memory, require its sha256 = the frozen production
                           manifest's slice_sha256, write it to the pilot namespace, partition R(L), write the plan
                           (reads no corpus content: slice from research records, sizes from git object sizes)
  prompts                  write the deterministic dispatch prompts (reads no corpus)
  check     --commit C     coverage, budgets and arm-reading discipline from the persistent read logs
  invariants --commit C    deterministic invariant/consistency report over the unit records (arm B/C input)
  blind     --commit C     sealed arm-letter key plus the blinded audit package
  compare   --commit C     field comparison (by letter) plus the four failure-mode metrics
  validate  --commit C     production verifier on relabelled temporary copies (baseline and each arm)
  freeze    --commit C --stage S   appends the stage's file hashes to PROVENANCE.jsonl
  result    --commit C     unblinds and applies the frozen outcome rules; writes OB0018-RESULT.json

Every sub-command except selftest, plan and prompts refuses unless `pilot-s5-decomp/ob0018/OB0018-AUTHORIZATION.json`
names a 40-hex commit at which the pre-registration, the contract, this tool and its evidence-chain dependencies are
committed byte-identical to the working files. Corpus content is read only through the seal-aware resolver.
"""
import collections
import hashlib
import json
import os
import random
import re
import secrets
import shutil
import subprocess
import sys
import tempfile

_HERE = os.path.dirname(os.path.abspath(__file__))
if _HERE not in sys.path:
    sys.path.insert(0, _HERE)

PREREG = "audit-p3b/20260926_1800_ob0018-decomposition-fidelity-preregistration.md"
CONTRACT = "prompts/20260926_1800_p3b-ob0018-pilot-contract.md"
TOOL_REL = "scripts/p3b_ob0018_pilot.py"
DEPENDENCIES = ("scripts/p3b_read_source.py", "scripts/p3b_s5_common.py", "scripts/p3b_discovery_io.py",
                "scripts/p3b_s5_verify.py", "scripts/p3b_s5_quotes.py", "scripts/p3b_s5_prepare.py",
                "scripts/p3b_s5_pilot.py", "scripts/p3b_v1_2_4_instrument.py")
ROOT = "pilot-s5-decomp/ob0018"
PLAN = f"{ROOT}/OB0018-PLAN.json"
SLICE_DIR = f"{ROOT}/slices"
AUTH = f"{ROOT}/OB0018-AUTHORIZATION.json"
S5_BATCH, BATCH = "OB0018", "PX0018"
LABEL = "s1620-removal-test-provenance-base-case-and-user-decisions"
UNIT_BUDGET = 300_000               # brief §D: 3 units at a 300 KB budget
REREAD_BUDGET_C = 300_000           # brief §D: arm C re-read budget (frozen)
AUDIT_BUDGET = 600_000              # auditor's own whole-file re-reads (frozen)
BASELINE = "PX0018-S00"
ARMS = {"A": "PX0018-S01", "B": "PX0018-S02", "C": "PX0018-S03"}
AUDITOR = "PX0018-A01"
LETTERS = ("P", "Q", "R")
JUDGMENT_FIELDS = ("births", "absences.resolution", "semantic_status", "type_status", "mathematical_status",
                   "primary_layer", "stage2_dispositions")
AUDIT_DISPOSITIONS = ("DECOMPOSED-UPHELD", "BASELINE-UPHELD", "JUDGMENT-CALL", "PROTOCOL-VIOLATION", "UNADJUDICATED")
STAGES = (
    ("authorization", ["OB0018-AUTHORIZATION.json", "LEDGER-FINGERPRINT.json"]),
    ("plan", ["OB0018-PLAN.json", f"slices/{LABEL}.json"]),
    ("units", ["CHECK-UNITS.json"]),
    ("invariants", ["INVARIANTS.json"]),
    ("syntheses", ["CHECK-ALL.json", "VALIDATE.json", "SCAN.json", "DISPATCH.json"]),
    ("blind", ["#AUDIT-KEY.json", "AUDIT-PACKAGE.json", "COMPARE.json"]),
    ("audit", ["@PX0018-A01/AUDIT.jsonl", "SCAN-AUDIT.json", "DISPATCH.json"]),
    ("unseal", ["AUDIT-KEY.json"]),
)
SCOPE_STATEMENTS = (
    "OB0018 can provide evidence about decomposition fidelity on the selected few-unit control label. It does not by "
    "itself establish population-wide equivalence of decomposition and single-context analysis.",
    "A successful OB0018 result does not eliminate the need for the S5 reconstruction floor.")
WS = re.compile(r"\s+")
SID = re.compile(r"S\d{4}")


class PilotError(Exception):
    pass


def sha(s):
    return hashlib.sha256(s.encode("utf-8")).hexdigest()


def norm(s):
    return WS.sub(" ", str(s or "")).strip().lower()


# ------------------------------------------------------------------ pure functions (tested; no I/O)
def required_set(sl):
    """R(L) = stage-2 files ∪ every row source (no thinning; pilot §2). Hub labels would have no stage 2."""
    s2 = set() if sl.get("hub") else set(sl.get("stage2_files") or [])
    rows = {json.loads(r)["source_id"] for r in sl["bundle"]["rows_verbatim"]}
    return sorted(s2 | rows), sorted(s2), sorted(rows)


def unit_of(units):
    return {s: u["run_id"] for u in units for s in u["files"]}


def adjacencies(row_sources, units):
    """Cross-unit adjacencies over the label's timeline candidates: row sources in S-id order (the corpus capture
    order; a frozen proxy for historical order). A pair (a, b) of consecutive candidates in different units."""
    uo = unit_of(units)
    seq = [s for s in sorted(row_sources) if s in uo]
    return [(a, b) for a, b in zip(seq, seq[1:]) if uo[a] != uo[b]]


def record_findings(records):
    """{(source_id, dimension): set(findings)} from unit file-reading records' absence_evidence."""
    out = collections.defaultdict(set)
    for r in records:
        for e in r.get("absence_evidence") or []:
            if isinstance(e, dict) and e.get("dimension") and e.get("finding"):
                out[(r.get("source_id"), e["dimension"])].add(e["finding"])
    return out


def disp_key(d, sid, dim):
    """M-2: one key per DISTINCT stage-1 hit (production schema E) and dimension; never collapsed per file."""
    return (d.get("source_id", sid), str(d.get("hit_kind")), json.dumps(d.get("hit_key")), str(d.get("term_index")), dim)


def dispositions_of(entries, sid=None):
    out = {}
    for d in entries or []:
        for dim, v in (d.get("by_dimension") or {}).items():
            out[disp_key(d, sid, dim)] = v
    return out


def record_dispositions(records):
    """{(source_id, hit_kind, hit_key, term_index, dimension): disposition} from the unit records."""
    out = {}
    for r in records:
        out.update(dispositions_of(r.get("stage2_dispositions"), r.get("source_id")))
    return out


def file_level(disp):
    """{(source_id, dimension): True if any hit of that file is FOUND on that dimension}."""
    out = {}
    for (sid, _, _, _, dim), v in disp.items():
        out[(sid, dim)] = out.get((sid, dim), False) or v == "FOUND"
    return out


def disposition_conflicts(disp, findings):
    """§C-2 invariant: FOUND requires a DEFINES finding in the same record; DEFINES forbids FALSE-HIT or
    UNSUPPLIED-DIMENSION. Checked per hit key. Returns sorted [(source_id, hit_key, dimension, disposition, findings)]."""
    out = []
    for key, v in sorted(disp.items()):
        sid, dim = key[0], key[4]
        f = findings.get((sid, dim), set())
        if (v == "FOUND" and "DEFINES" not in f) or (v in ("FALSE-HIT", "UNSUPPLIED-DIMENSION") and "DEFINES" in f):
            out.append((sid, key[2], dim, v, sorted(f)))
    return out


def cross_unit_undefined(absences, findings):
    """§C-2 cross-unit form: no GENUINELY-UNDEFINED-AFTER-CENSUS may survive for a dimension any unit found DEFINES."""
    defined = {dim for (_, dim), f in findings.items() if "DEFINES" in f}
    return sorted(d for d, a in (absences or {}).items()
                  if isinstance(a, dict) and a.get("resolution") == "GENUINELY-UNDEFINED-AFTER-CENSUS" and d in defined)


def calibration_pairs(records, units):
    """§C-3 deterministic pairing: absence_evidence entries in DIFFERENT units with normalized-equal quotes, the same
    dimension, and different findings. No similarity model; exact normalized equality only."""
    uo = unit_of(units)
    idx = collections.defaultdict(list)
    for r in records:
        for e in r.get("absence_evidence") or []:
            if isinstance(e, dict) and e.get("quote") and e.get("dimension"):
                idx[(e["dimension"], norm(e["quote"]))].append((uo.get(r.get("source_id")), r.get("source_id"),
                                                                e.get("finding")))
    out = []
    for (dim, _), xs in sorted(idx.items()):
        for i, a in enumerate(xs):
            for b in xs[i + 1:]:
                if a[0] != b[0] and a[2] != b[2]:
                    out.append({"dimension": dim, "a": a[1], "b": b[1], "findings": [a[2], b[2]]})
    return out


def residual_calibration(pairs, obj_disp):
    """Pairs whose final object dispositions for the two files on that dimension disagree on FOUND vs not-FOUND
    (file level: FOUND if any hit of the file is FOUND on the dimension)."""
    fl = file_level(obj_disp)
    bad = []
    for p in pairs:
        va, vb = fl.get((p["a"], p["dimension"])), fl.get((p["b"], p["dimension"]))
        if va is not None and vb is not None and va != vb:
            bad.append(p)
    return bad


def object_dispositions(obj):
    return dispositions_of(obj.get("stage2_dispositions"))


def edge_metrics(edges, base_edges, labels):
    """(iv) endpoint validity (decision-relevant) and Jaccard vs baseline under both co-label readings (reported only):
    R1 all edges; R2 edges whose quote names the target label (normalized; a deterministic proxy for a quote that
    states the dependency)."""
    def keyset(es, r2):
        return {(e.get("target_label"), e.get("kind")) for e in es
                if isinstance(e, dict) and (not r2 or norm(e.get("target_label")) in norm(e.get("quote")))}
    valid = [e for e in edges if isinstance(e, dict) and e.get("target_label") in labels]
    jac = lambda a, b: 1.0 if not a and not b else len(a & b) / len(a | b)
    return {"n": len(edges), "endpoint_valid": len(valid), "endpoint_validity": (len(valid) / len(edges)) if edges else 1.0,
            "jaccard_R1": jac(keyset(edges, False), keyset(base_edges, False)),
            "jaccard_R2": jac(keyset(edges, True), keyset(base_edges, True))}


def birth_class(v):
    v = str(v or "")
    for k in ("ESTABLISHED", "MOVED", "UNORDERED-BLOCK", "BIRTH-UNRESOLVED", "ESCALATED", "NOT-EVIDENCED-IN-CAPTURE"):
        if v.startswith(k):
            return k
    return v


def compare_fields(base, arm):
    """Pilot §5 fields and agreement classes (exact / coarse / different), plus per-key stage-2 dispositions."""
    rows = []

    def add(field, cls, jf):
        rows.append({"field": field, "class": cls, "judgment": jf})
    for k in ("lexical", "conceptual", "formal", "operational", "governance"):
        a, b = (base.get("births") or {}).get(k), (arm.get("births") or {}).get(k)
        add(f"births.{k}", "exact" if a == b else "coarse" if birth_class(a) == birth_class(b) else "different", True)
    ba, aa = base.get("absences") or {}, arm.get("absences") or {}
    for d in sorted(set(ba) | set(aa)):
        x, y = ba.get(d) or {}, aa.get(d) or {}
        add(f"absences.{d}.resolution", "exact" if x.get("resolution") == y.get("resolution") else "different", True)
        if x.get("resolution") == "FOUND" and y.get("resolution") == "FOUND":
            sx, sy = (x.get("supplied_by") or {}).get("source_id"), (y.get("supplied_by") or {}).get("source_id")
            add(f"absences.{d}.supplied_by", "exact" if sx == sy else "coarse", False)
    for f in ("semantic_status", "type_status", "mathematical_status", "primary_layer"):
        add(f, "exact" if base.get(f) == arm.get(f) else "different", True)
    for f in ("semantic_status_rule", "tier"):
        add(f, "exact" if base.get(f) == arm.get(f) else "different", False)
    sr, ar = set(base.get("secondary_roles") or []), set(arm.get("secondary_roles") or [])
    add("secondary_roles", "exact" if sr == ar else "coarse" if sr & ar else "different", False)
    be = {e.get("target_label") for e in base.get("dependency_edges") or [] if isinstance(e, dict)}
    ae = {e.get("target_label") for e in arm.get("dependency_edges") or [] if isinstance(e, dict)}
    j = 1.0 if not be and not ae else len(be & ae) / len(be | ae)
    add("dependency_edges", "exact" if be == ae else "coarse" if j >= 0.5 else "different", False)
    tb = {p.get("source_id"): p.get("change_vs_previous") for p in base.get("timeline") or [] if isinstance(p, dict)}
    ta = {p.get("source_id"): p.get("change_vs_previous") for p in arm.get("timeline") or [] if isinstance(p, dict)}
    add("timeline", "exact" if tb == ta else "coarse" if set(tb) == set(ta) else "different", False)
    eb = sorted(e.get("reason") for e in base.get("escalations") or [] if isinstance(e, dict))
    ea = sorted(e.get("reason") for e in arm.get("escalations") or [] if isinstance(e, dict))
    add("escalations", "exact" if eb == ea else "coarse" if set(eb) == set(ea) else "different", False)
    db, da = object_dispositions(base), object_dispositions(arm)
    for key in sorted(set(db) | set(da)):
        add(f"stage2_dispositions.{key[0]}.{key[1]}:{key[2]}#{key[3]}.{key[4]}",
            "exact" if db.get(key) == da.get(key) else "different", True)
    return rows


def adjacency_metric(adj, base, arm):
    """(i) change_vs_previous at the later file of each cross-unit adjacency: equal to baseline or not."""
    tb = {p.get("source_id"): p.get("change_vs_previous") for p in base.get("timeline") or [] if isinstance(p, dict)}
    ta = {p.get("source_id"): p.get("change_vs_previous") for p in arm.get("timeline") or [] if isinstance(p, dict)}
    rows = [{"pair": [a, b], "baseline": tb.get(b), "arm": ta.get(b), "equal": tb.get(b) == ta.get(b)}
            for a, b in adj if b in tb or b in ta]
    return rows


def outcome_of(arm, feas, level2, audit_rows, metrics, violations):
    """Frozen outcome rule per arm (pre-registration §H). Returns (outcome, reasons).
    INVALID: an arm-run protocol violation (reading outside the reader, a hold-out access, a re-read in arm A/B, arm C
    over budget). UNDETERMINED: experiment-level feasibility failed, or any judgment-field difference unadjudicated.
    FAITHFUL: levels 1–4 all met. Otherwise NOT-FAITHFUL."""
    if violations:
        return "INVALID", list(violations)
    if not feas["ok"]:
        return "UNDETERMINED", list(feas["reasons"])
    unadj = [r for r in audit_rows if r["judgment"] and r["disposition"] in (None, "UNADJUDICATED")]
    if unadj:
        return "UNDETERMINED", [f"unadjudicated judgment field {r['field']}" for r in unadj]
    reasons = []
    if not level2["verifier_pass"]:
        reasons.append("level 2: production verifier FAIL")
    if level2["quote_misses"]:
        reasons.append(f"level 2: {level2['quote_misses']} quote miss(es)")
    pv = [r for r in audit_rows if r["disposition"] == "PROTOCOL-VIOLATION"]
    bu = [r for r in audit_rows if r["judgment"] and r["disposition"] == "BASELINE-UPHELD"]
    reasons += [f"level 3: PROTOCOL-VIOLATION {r['field']}" for r in pv]
    reasons += [f"level 3: BASELINE-UPHELD on judgment field {r['field']}" for r in bu]
    if metrics["adjacency_baseline_upheld"]:
        reasons.append(f"level 4 (i): {metrics['adjacency_baseline_upheld']} adjacency class(es) BASELINE-UPHELD")
    if metrics["residual_conflicts"]:
        reasons.append(f"level 4 (ii): {metrics['residual_conflicts']} unresolved disposition-vs-finding conflict(s)")
    if metrics["residual_calibration"]:
        reasons.append(f"level 4 (iii): {metrics['residual_calibration']} unresolved calibration pair(s)")
    if metrics["endpoint_validity"] < 1.0:
        reasons.append(f"level 4 (iv): edge endpoint validity {metrics['endpoint_validity']:.3f} < 1.0")
    return ("FAITHFUL", ["levels 1–4 met"]) if not reasons else ("NOT-FAITHFUL", reasons)


def arm_rows(cmp_letter, disp):
    """Audit rows for one letter: every compared field (exact fields need no disposition) plus, M-4, EVERY unequal
    adjacency row with its actual audit disposition (judgment field). Returns (rows, adjacency BASELINE-UPHELD count)."""
    rows = [dict(f, disposition=("EXACT" if f["class"] == "exact" else disp.get(f["field"])))
            for f in cmp_letter["fields"]]
    adj = [{"field": f"adjacency.{a['pair'][1]}", "judgment": True, "class": "different",
            "disposition": disp.get(f"adjacency.{a['pair'][1]}")} for a in cmp_letter["adjacency"] if not a["equal"]]
    return rows + adj, sum(1 for r in adj if r["disposition"] == "BASELINE-UPHELD")


def arm_c_skipped(adjacency_pairs, complete_sources):
    """M-5: adjacency files arm C did not completely re-read (each is a violation of arm C's definition)."""
    return sorted({s for pair in adjacency_pairs for s in pair} - set(complete_sources))


def decision(outcomes):
    """Brief §D mapping, applied only over determinate arms; any UNDETERMINED/INVALID arm that precedes the first
    FAITHFUL arm in the order A, B, C makes the decision UNDETERMINED (the ordering cannot be established)."""
    for arm in ("A", "B", "C"):
        o = outcomes[arm]
        if o == "FAITHFUL":
            return f"EVIDENCE-FOR-ARCHITECTURE-{arm}"
        if o != "NOT-FAITHFUL":
            return "UNDETERMINED"
    return "NO-ARM-SHOWN-FAITHFUL"


READER_CALL = re.compile(r"p3b_read_source\.py(?P<args>[^;&|\n]*)")
AUDIT_BLIND_TOKENS = ("AUDIT-KEY", "ob0018-key", "PX0018-S01", "PX0018-S02", "PX0018-S03", "CHECK-ALL",
                      "VALIDATE.json", "SCAN.json", "SCAN-AUDIT", "DISPATCH.json")      # M-7


def reader_calls(text):
    """[(run, batch)] of every paged-reader invocation named in a command text."""
    out = []
    for m in READER_CALL.finditer(text):
        a = m.group("args")
        r, b = re.search(r"--run\s+(\S+)", a), re.search(r"--batch\s+(\S+)", a)
        out.append((r.group(1) if r else None, b.group(1) if b else None))
    return out


def transcript_scan(tool_calls, corpus_tokens, holdout_tokens, run=None, no_read=False, blind_tokens=()):
    """D-2 bypass detector (not an allowlist). Classes per tool call:
    SEAL-BREACH  the input names a hold-out token;
    PROTOCOL-VIOLATION  the input names a corpus file path or blob spec (content outside the paged reader); or (M-6)
      a reader call whose --run is not the dispatched run or whose --batch is not PX0018 (including any production
      run id); or a reader call in a run that may not read (arms A and B); or (M-7) the auditor naming an
      arm-identifying file. The reader is invoked with S-ids only, so a conforming read never matches a corpus token.
    Returns [(call_id, class, tool, detail)]."""
    out = []
    for c in tool_calls:
        text = json.dumps(c.get("input"), ensure_ascii=False)
        cid, name = c.get("id"), c.get("name")
        if any(t in text for t in holdout_tokens):
            out.append((cid, "SEAL-BREACH", name, "hold-out token"))
            continue
        if any(t in text for t in corpus_tokens):
            out.append((cid, "PROTOCOL-VIOLATION", name, "corpus path or blob spec named"))
            continue
        calls = reader_calls(text) if name == "Bash" else []
        if calls and no_read:
            out.append((cid, "PROTOCOL-VIOLATION", name, "reader call in a no-read arm"))
        elif any((run is not None and r != run) or b != BATCH for r, b in calls):
            out.append((cid, "PROTOCOL-VIOLATION", name, "reader call with a foreign --run or --batch"))
        elif any(t in text for t in blind_tokens):
            out.append((cid, "PROTOCOL-VIOLATION", name, "auditor accessed an arm-identifying file"))
    return out


def blind_key(token):
    """Seeded permutation arm → letter from a runtime secret (sealed until the audit is frozen)."""
    rng = random.Random(token)
    letters = list(LETTERS)
    rng.shuffle(letters)
    return dict(zip(("A", "B", "C"), letters))


def strip_identity(obj, run_id):
    """Blinded copy: run and research identifiers removed; content unchanged."""
    t = json.dumps(obj, ensure_ascii=False).replace(run_id, "ARM")
    o = json.loads(t)
    for k in ("run_id", "batch_id", "model_id", "generation_parameters", "contract_sha256"):
        o.pop(k, None)
    return o


# ------------------------------------------------------------------ I/O, guard and commands
def _c():
    import p3b_s5_common as C
    return C


def _p(name):
    return os.path.join(_c().CR, ROOT, name)


def _jl(path):
    if not os.path.exists(path):
        return []
    with open(path, encoding="utf-8") as f:
        return [json.loads(x) for x in f if x.strip()]


def _dump(name, obj):
    os.makedirs(os.path.dirname(_p(name)), exist_ok=True)
    with open(_p(name), "w", encoding="utf-8") as f:
        json.dump(obj, f, indent=1, sort_keys=True, ensure_ascii=False)
        f.write("\n")


def _load(name):
    with open(_p(name), encoding="utf-8") as f:
        return json.load(f)


def _fsha(path):
    with open(path, "rb") as f:
        return hashlib.sha256(f.read()).hexdigest()


def git_show(commit, rel):
    C = _c()
    cr_rel = os.path.relpath(C.CR, C.REPO_ROOT)
    p = subprocess.run(["git", "-C", C.REPO_ROOT, "show", f"{commit}:{cr_rel}/{rel}"], capture_output=True)
    if p.returncode != 0:
        raise PilotError(f"{rel} is not committed at {commit}")
    return p.stdout


def authorized(commit, show=None):
    show = show or git_show
    C = _c()
    if not re.fullmatch(r"[0-9a-f]{40}", commit or ""):
        raise PilotError("--commit must be the full 40-hex pre-registration commit")
    if not os.path.exists(_p("OB0018-AUTHORIZATION.json")):
        raise PilotError("OB0018 execution is not authorized (no OB0018-AUTHORIZATION.json)")
    a = _load("OB0018-AUTHORIZATION.json")
    if a.get("prereg_commit") != commit:
        raise PilotError("the authorization names a different commit")
    for rel in (PREREG, CONTRACT, TOOL_REL) + DEPENDENCIES:
        with open(os.path.join(C.CR, rel), "rb") as f:
            work = hashlib.sha256(f.read()).hexdigest()
        if hashlib.sha256(show(commit, rel)).hexdigest() != work:
            raise PilotError(f"{rel} differs from its committed version at {commit}")
    if a.get("prereg_sha256") != C.sha256_file(PREREG):
        raise PilotError("the authorization names a different pre-registration hash")
    return a


def read_log(run):
    return _jl(os.path.join(_c().CR, "pilot-s5-decomp", run, "READ-LOG.jsonl"))


def records_of(plan):
    return [r for u in plan["units"] for r in _jl(os.path.join(_c().CR, "pilot-s5-decomp", u["run_id"],
                                                               "file-reading-records.jsonl"))]


def object_of(run):
    rows = _jl(os.path.join(_c().CR, "pilot-s5-decomp", run, "objects.jsonl"))
    rows = [r for r in rows if r.get("working_label") == LABEL]
    return rows[0] if len(rows) == 1 else None


def cmd_plan():
    import p3b_s5_prepare as P
    import p3b_s5_pilot as PL
    C = _c()
    C.assert_sealed()
    if os.path.exists(_p("OB0018-PLAN.json")):
        raise PilotError("the plan exists (the partition is frozen once)")
    ctx = P.Context()
    man = [json.loads(x) for x in open(os.path.join(C.CR, P.MANIFEST), encoding="utf-8") if x.strip()]
    row = next(b for b in man[1:] if b["batch_id"] == S5_BATCH)
    text = ctx.slice(LABEL, S5_BATCH, row["contract_sha256"], row["input_manifest_sha256"], C.holdout_sets())
    if P.sha256_bytes(text.encode("utf-8")) != row["slice_sha256"][LABEL]:
        raise PilotError("regenerated slice differs from the frozen production manifest")
    os.makedirs(_p("slices"), exist_ok=True)
    with open(_p(f"slices/{LABEL}.json"), "w", encoding="utf-8") as f:
        f.write(text + "\n")
    sl = json.loads(text)
    req, s2, rows = required_set(sl)
    sizes = P.blob_sizes()
    parts = PL.partition(req, sizes, budget=UNIT_BUDGET)
    units = [{"run_id": f"{BATCH}-U{n:02d}", "files": u, "bytes": sum(sizes[s] for s in u)}
             for n, u in enumerate(parts, 1)]
    adj = adjacencies(rows, units)
    body = {"status": "NON-PRODUCTION PILOT", "s5_batch": S5_BATCH, "batch": BATCH, "label": LABEL,
            "slice_sha256": row["slice_sha256"][LABEL], "manifest_row": {k: row[k] for k in ("contract_sha256",
                                                                                          "input_manifest_sha256")},
            "required": req, "stage2_files": s2, "row_sources": rows, "required_bytes": sum(sizes[s] for s in req),
            "unit_budget": UNIT_BUDGET, "units": units, "adjacencies": adj, "baseline": BASELINE, "arms": ARMS,
            "auditor": AUDITOR, "reread_budget_C": REREAD_BUDGET_C, "audit_budget": AUDIT_BUDGET,
            "partition_rule": "first-fit decreasing by size, ties by S-id; files never split; unit files sorted by S-id"}
    _dump("OB0018-PLAN.json", {"body": body})
    return {"units": [(u["run_id"], len(u["files"]), u["bytes"]) for u in units], "adjacencies": len(adj),
            "required_files": len(req), "required_bytes": body["required_bytes"]}


PROMPT_T = """You are run {RUN} of the non-production OB0018 decomposition-fidelity pilot (batch {BATCH}), role {ROLE}.
Read completely, then follow exactly:
- the pilot contract docs/knowledgeos/chronological-read/{CONTRACT} (section {SECTION});
- the production batch contract it names;
- the frozen plan docs/knowledgeos/chronological-read/{PLAN} (your entry: {RUN}).
Work from the repository root. Read corpus files ONLY through the paged reader with --run {RUN} --batch {BATCH}.
Write ONLY under docs/knowledgeos/chronological-read/pilot-s5-decomp/{RUN}/. Finally report what you read and wrote.
"""
ROLES = {"U": ("READING UNIT", "1"), "S00": ("SINGLE-CONTEXT BASELINE", "2"), "S01": ("SYNTHESIS ARM A", "3"),
         "S02": ("SYNTHESIS ARM B", "3"), "S03": ("SYNTHESIS ARM C", "3"), "A01": ("BLIND AUDITOR", "4")}


def prompt_for(run):
    tail = run.split("-")[1]
    role, section = ROLES["U"] if tail.startswith("U") else ROLES[tail]
    return PROMPT_T.format(RUN=run, BATCH=BATCH, ROLE=role, CONTRACT=CONTRACT, SECTION=section, PLAN=PLAN)


def cmd_prompts():
    plan = _load("OB0018-PLAN.json")["body"]
    runs = [u["run_id"] for u in plan["units"]] + [BASELINE] + list(ARMS.values()) + [AUDITOR]
    os.makedirs(_p("PROMPTS"), exist_ok=True)
    for r in runs:
        with open(_p(f"PROMPTS/{r}.txt"), "w", encoding="utf-8") as f:
            f.write(prompt_for(r))
    return runs


def coverage(run, files):
    """M-6: every entry of the run's log counts; entries naming another run or batch are counted as foreign (never
    silently dropped). Coverage is computed from the conforming entries only."""
    import p3b_s5_verify as V
    raw = read_log(run)
    log = [e for e in raw if e.get("run_id") == run and e.get("batch_id") == BATCH]
    cov = V.page_coverage(log)
    pages = collections.Counter((e["page"]["source_id"], e["page"]["page"]) for e in raw
                                if e.get("page") and not e.get("refused"))
    done = sorted(s for s in files if cov.get(s, {}).get("complete"))
    return {"files": len(files), "complete": len(done), "missing": sorted(set(files) - set(done)),
            "hash_failures": sum(v["bad"] for v in cov.values()), "refused": sum(1 for e in raw if e.get("refused")),
            "sources_read": sorted({s for s, _ in pages}), "page_reads": sum(pages.values()),
            "foreign_entries": len(raw) - len(log),
            "complete_sources": sorted(s for s, v in cov.items() if v["complete"])}


def ledger_fingerprint():
    """M-6(c): sha256 over the sorted (path, sha256) of every file under the production ledger root and of the
    production state file; any change during the pilot is a production-isolation breach."""
    C = _c()
    h = hashlib.sha256()
    root = os.path.join(C.CR, "ledger-p3b-r2")
    for d, _, fs in sorted(os.walk(root)):
        for f in sorted(fs):
            p = os.path.join(d, f)
            h.update(f"{os.path.relpath(p, C.CR)}\0{_fsha(p)}\n".encode())
    h.update(f"P3B-STATE.json\0{_fsha(os.path.join(C.CR, 'P3B-STATE.json'))}\n".encode())
    return h.hexdigest()


def cmd_fingerprint():
    if os.path.exists(_p("LEDGER-FINGERPRINT.json")):
        raise PilotError("the ledger fingerprint exists (taken once, at authorization)")
    _dump("LEDGER-FINGERPRINT.json", {"ledger_fingerprint": ledger_fingerprint()})
    return _load("LEDGER-FINGERPRINT.json")


def cmd_check(stage_all):
    import p3b_s5_prepare as P
    plan = _load("OB0018-PLAN.json")["body"]
    sizes = P.blob_sizes()
    out = {"units": {}, "arms": {}, "violations": {}}
    for u in plan["units"]:
        cv = coverage(u["run_id"], u["files"])
        cv["files_outside_unit"] = sorted(set(cv["sources_read"]) - set(u["files"]))
        recs = _jl(os.path.join(_c().CR, "pilot-s5-decomp", u["run_id"], "file-reading-records.jsonl"))
        cv["records"] = len(recs)
        cv["not_consumed"] = sorted(r.get("source_id") for r in recs if r.get("status") != "CONSUMED")
        out["units"][u["run_id"]] = cv
    out["ledger_unchanged"] = (os.path.exists(_p("LEDGER-FINGERPRINT.json")) and
                               _load("LEDGER-FINGERPRINT.json")["ledger_fingerprint"] == ledger_fingerprint())
    if stage_all:
        out["baseline"] = coverage(BASELINE, plan["required"])
        for arm, run in ARMS.items():
            cv = coverage(run, plan["required"])
            reread = sum(sizes.get(s, 0) for s in cv["sources_read"])
            v = []
            if arm in ("A", "B") and cv["page_reads"]:
                v.append(f"arm {arm} performed {cv['page_reads']} page read(s); re-reads are not permitted in arm {arm}")
            if cv["foreign_entries"]:
                v.append(f"arm {arm}: {cv['foreign_entries']} read-log entr(ies) under a foreign run or batch")
            if arm == "C" and reread > REREAD_BUDGET_C:
                v.append(f"arm C re-read {reread} bytes > budget {REREAD_BUDGET_C}")
            if arm == "C":                                                   # M-5: mandatory adjacency re-reads
                skipped = arm_c_skipped(plan["adjacencies"], cv["complete_sources"])
                if skipped:
                    v.append(f"arm C did not completely re-read mandatory adjacency file(s) {skipped}")
            cv["reread_bytes"] = reread
            out["arms"][arm] = cv
            out["violations"][arm] = v
        out["auditor"] = coverage(AUDITOR, plan["required"])
        out["auditor"]["reread_bytes"] = sum(sizes.get(s, 0) for s in out["auditor"]["sources_read"])
    _dump("CHECK-ALL.json" if stage_all else "CHECK-UNITS.json", out)
    return out


def cmd_invariants():
    import p3b_s5_common as C
    plan = _load("OB0018-PLAN.json")["body"]
    recs = records_of(plan)
    f, d = record_findings(recs), record_dispositions(recs)
    edges = [dict(e, source_id=r.get("source_id")) for r in recs for e in r.get("dependencies") or [] if isinstance(e, dict)]
    labels = C.discovery_labels()
    body = {"conflicts": [list(x) for x in disposition_conflicts(d, f)],
            "calibration_pairs": calibration_pairs(recs, plan["units"]),
            "edge_endpoints_invalid": [e for e in edges if e.get("target_label") not in labels],
            "edges_without_quote": [e for e in edges if not e.get("quote")],
            "adjacencies": plan["adjacencies"],
            "defines_dimensions": sorted({dim for (_, dim), fs in f.items() if "DEFINES" in fs})}
    _dump("INVARIANTS.json", body)
    return {k: len(v) for k, v in body.items()}


def cmd_blind():
    key = blind_key(secrets.token_hex(16))
    base = object_of(BASELINE)
    if base is None:
        raise PilotError("baseline object missing or not unique")
    pkg = {"baseline": strip_identity(base, BASELINE), "arms": {}}
    for arm, run in ARMS.items():
        o = object_of(run)
        pkg["arms"][key[arm]] = strip_identity(o, run) if o is not None else None
    _dump("AUDIT-KEY.json", {"arm_to_letter": key})
    _dump("AUDIT-PACKAGE.json", pkg)
    return cmd_compare(pkg)


def cmd_compare(pkg=None):
    import p3b_s5_common as C
    plan = _load("OB0018-PLAN.json")["body"]
    pkg = pkg or _load("AUDIT-PACKAGE.json")
    recs = records_of(plan)
    f = record_findings(recs)
    pairs = calibration_pairs(recs, plan["units"])
    labels = C.discovery_labels()
    base = pkg["baseline"]
    out = {}
    for letter, o in sorted(pkg["arms"].items()):
        if o is None:
            out[letter] = {"missing": True}
            continue
        od = object_dispositions(o)
        conf = disposition_conflicts(od, f)
        out[letter] = {"fields": compare_fields(base, o), "adjacency": adjacency_metric(plan["adjacencies"], base, o),
                       "conflicts": [list(x) for x in conf],
                       "cross_unit_undefined": cross_unit_undefined(o.get("absences"), f),
                       "residual_calibration": residual_calibration(pairs, od),
                       "edges": edge_metrics(o.get("dependency_edges") or [], base.get("dependency_edges") or [], labels)}
    _dump("COMPARE.json", out)
    return {k: (len(v.get("fields", [])) if not v.get("missing") else "missing") for k, v in out.items()}


def cmd_validate():
    """Production verifier and quote checker on relabelled temporary copies; pilot artifacts untouched."""
    import p3b_s5_common as C
    import p3b_s5_prepare as P
    import p3b_s5_quotes as Q
    import p3b_s5_verify as V
    plan = _load("OB0018-PLAN.json")["body"]
    sl_text = open(_p(f"slices/{LABEL}.json"), encoding="utf-8").read()
    body_sl = sl_text[:-1] if sl_text.endswith("\n") else sl_text
    man = [json.loads(x) for x in open(os.path.join(C.CR, P.MANIFEST), encoding="utf-8") if x.strip()]
    e0 = next(b for b in man[1:] if b["batch_id"] == S5_BATCH)
    res = {}
    for run, logs_from in [(BASELINE, [BASELINE])] + [(r, [u["run_id"] for u in plan["units"]] + [r]) for r in ARMS.values()]:
        sdir = os.path.join(C.CR, "pilot-s5-decomp", run)
        if not os.path.exists(os.path.join(sdir, "objects.jsonl")):
            res[run] = {"executed": False}
            continue
        tmp = tempfile.mkdtemp(prefix="ob0018-validate-", dir="/tmp")
        try:
            slrel = os.path.join("_batch_input_r2", "s5", "rev3", S5_BATCH)
            os.makedirs(os.path.join(tmp, slrel))
            with open(os.path.join(tmp, slrel, f"{LABEL}.json"), "w", encoding="utf-8") as fh:
                fh.write(sl_text)
            entry = dict(e0, labels=[LABEL], slice_sha256={LABEL: C.sha256_bytes(body_sl.encode("utf-8"))},
                         in_checklist={LABEL: e0["in_checklist"][LABEL]}, weights={LABEL: e0["weights"][LABEL]},
                         checklist=int(bool(e0["in_checklist"][LABEL])))
            body = C.canon(entry) + "\n"
            hdr = dict(man[0]["header"], output_sha256=C.sha256_bytes(body.encode()), pilot_validation_copy=True,
                       contract=dict(man[0]["header"]["contract"], revision=4))
            with open(os.path.join(tmp, P.MANIFEST), "w", encoding="utf-8") as fh:
                fh.write(C.canon({"header": hdr}) + "\n" + body)
            odir = os.path.join(tmp, "ledger-p3b-r2", f"{S5_BATCH}-R2")
            os.makedirs(odir)
            recs_all = []
            for n in ("objects.jsonl", "register.jsonl", "p1-gap-capture.jsonl"):
                recs = _jl(os.path.join(sdir, n))
                rel = [json.loads(json.dumps(r, ensure_ascii=False).replace(f'"{run}:{BATCH}:', f'"{S5_BATCH}-R2:{S5_BATCH}:'))
                       for r in recs]
                for r in rel:
                    r.update(run_id=f"{S5_BATCH}-R2", batch_id=S5_BATCH, contract_sha256=entry["contract_sha256"])
                recs_all += rel
                with open(os.path.join(odir, n), "w", encoding="utf-8") as fh:
                    fh.write("".join(C.canon(r) + "\n" for r in rel))
            logs = [dict(e, run_id=f"{S5_BATCH}-R2", batch_id=S5_BATCH, working_label=LABEL)
                    for r in logs_from for e in read_log(r)]
            with open(os.path.join(odir, "READ-LOG.jsonl"), "w", encoding="utf-8") as fh:
                fh.write("".join(json.dumps(e, sort_keys=True, ensure_ascii=False) + "\n" for e in logs))
            bv, _ = V.verify(S5_BATCH, root=tmp, run=f"{S5_BATCH}-R2")
            q = Q.check(recs_all, C.dio.discovery_resolver())
            res[run] = {"result": bv["result"], "failures": bv["failures"], "quote_misses": len(q["miss"]),
                        "quotes": {"exact": q["exact"], "whitespace": q["whitespace"], "refused": q["refused"],
                                   "no_source_id": q["no_source_id"], "miss": q["miss"][:50]}}
        finally:
            shutil.rmtree(tmp, ignore_errors=True)
    _dump("VALIDATE.json", res)
    return {r: v.get("result", "not executed") for r, v in res.items()}


def scan_tokens():
    """Corpus tokens: every current/historical path and blob spec in the identity manifest; hold-out tokens: those of
    hold-out files plus the seal file name."""
    C = _c()
    _, HF, _ = C.holdout_sets()
    corpus, hold = set(), {"P3B-HOLDOUT-SEAL"}
    for r in _jl(os.path.join(C.CR, "P3B-IDENTITY-MANIFEST.jsonl")):
        if "source_id" not in r:
            continue
        toks = {t for t in (r.get("current_path"), r.get("historical_path"), r.get("blob_spec")) if t and len(t) > 12}
        (hold if r["source_id"] in HF else corpus).update(toks)
    return corpus, hold


def cmd_scan(audit=False):
    """Ingests the dispatched runs' transcripts (paths recorded by the orchestrator in DISPATCH.json): all runs except
    the auditor into SCAN.json (syntheses stage), or the auditor only into SCAN-AUDIT.json (audit stage)."""
    import p3b_v1_2_4_instrument as T
    corpus, hold = scan_tokens()
    disp = _load("DISPATCH.json")
    out = {}
    for run, d in sorted(disp.items()):
        if (run == AUDITOR) != audit:
            continue
        rec = T.parse_transcript(d["transcript"])
        hits = transcript_scan(rec["tool_calls"], corpus, hold, run=run, no_read=run in (ARMS["A"], ARMS["B"]),
                               blind_tokens=AUDIT_BLIND_TOKENS if run == AUDITOR else ())
        out[run] = {"agent_id": d.get("agent_id"), "api_models": rec.get("api_models"), "tool_calls": len(rec["tool_calls"]),
                    "parse_errors": rec.get("parse_errors"), "findings": [list(h) for h in hits],
                    "transcript_sha256": _fsha(d["transcript"])}
    _dump("SCAN-AUDIT.json" if audit else "SCAN.json", out)
    return {r: len(v["findings"]) for r, v in out.items()}


def stage_path(name):
    """'#' = sealed (hash only); '@' = relative to pilot-s5-decomp/ (an agent's own directory); else relative to ROOT."""
    n = name.lstrip("#")
    return os.path.join(_c().CR, "pilot-s5-decomp", n[1:]) if n.startswith("@") else _p(n)


def cmd_freeze(stage):
    files = dict(STAGES).get(stage)
    if files is None:
        raise PilotError("--stage must be one of: " + ", ".join(s for s, _ in STAGES))
    prov = _jl(_p("PROVENANCE.jsonl"))
    if any(p["stage"] == stage for p in prov):
        raise PilotError(f"stage {stage} already frozen")
    rec = {"stage": stage, "files": {}}
    for name in files:
        path = stage_path(name)
        if not os.path.exists(path):                                       # M-1: a listed file must exist
            raise PilotError(f"stage {stage}: {name} is missing")
        rec["files"][name] = _fsha(path)
    import datetime
    rec["utc"] = datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")
    with open(_p("PROVENANCE.jsonl"), "a", encoding="utf-8") as f:
        f.write(json.dumps(rec, sort_keys=True) + "\n")


def _load_ok(name):
    return _load(name) if os.path.exists(_p(name)) else {}


def cmd_result():
    plan = _load("OB0018-PLAN.json")["body"]
    prov = {p["stage"]: p for p in _jl(_p("PROVENANCE.jsonl"))}
    for st in ("authorization", "plan", "units", "invariants", "syntheses", "blind", "audit", "unseal"):
        if st not in prov:
            raise PilotError(f"freeze stage {st} missing")
    if not _load_ok("CHECK-ALL.json").get("ledger_unchanged"):
        raise PilotError("production ledger or state changed during the pilot: isolation breach; stop and escalate")
    key = _load("AUDIT-KEY.json")["arm_to_letter"]
    if _fsha(_p("AUDIT-KEY.json")) != prov["blind"]["files"]["#AUDIT-KEY.json"]:
        raise PilotError("the audit key differs from its blind-stage freeze")
    chk, val, cmp_ = _load("CHECK-ALL.json"), _load("VALIDATE.json"), _load("COMPARE.json")
    scan = _load("SCAN.json")
    if any(f[1] == "SEAL-BREACH" for v in scan.values() for f in v["findings"]):
        raise PilotError("SEAL-BREACH recorded: stop and escalate (P3B-ESC); no outcome is computed")
    scan_a = _load("SCAN-AUDIT.json")
    if any(f[1] == "SEAL-BREACH" for v in scan_a.values() for f in v["findings"]):
        raise PilotError("SEAL-BREACH recorded in the audit: stop and escalate (P3B-ESC); no outcome is computed")
    pv = lambda run: [f"transcript: {f[1]} ({f[2]})" for f in ((scan.get(run) or scan_a.get(run)) or {}).get("findings", [])]
    auditor_pv = pv(AUDITOR)
    audit = _jl(stage_path("@PX0018-A01/AUDIT.jsonl"))                 # M-1: the auditor's own directory
    if _fsha(stage_path("@PX0018-A01/AUDIT.jsonl")) != prov["audit"]["files"]["@PX0018-A01/AUDIT.jsonl"]:
        raise PilotError("the audit file differs from its audit-stage freeze")
    body_a = [r for r in audit if r.get("letter")]
    units_ok = all(not u["missing"] and not u["hash_failures"] and not u["not_consumed"] and not u["foreign_entries"]
                   for u in chk["units"].values()) \
        and not any(pv(u) for u in chk["units"])
    bval = val.get(BASELINE, {})
    base_ok = bval.get("result") == "PASS" and not chk["baseline"]["hash_failures"] and \
        not chk["baseline"]["foreign_entries"] \
        and not pv(BASELINE)
    feas = {"ok": units_ok and base_ok and len(plan["units"]) == 3,
            "reasons": ([] if units_ok else ["level 1: a unit is incomplete, has hash failures or NOT-CONSUMED files"]) +
                       ([] if base_ok else ["level 1: baseline incomplete or baseline verifier not PASS"]) +
                       ([] if len(plan["units"]) == 3 else ["plan does not have 3 units"])}
    outcomes, detail = {}, {}
    for arm, run in ARMS.items():
        letter = key[arm]
        c = cmp_.get(letter, {})
        if c.get("missing"):
            outcomes[arm], detail[arm] = "UNDETERMINED", {"reasons": ["arm object missing"]}
            continue
        disp = {r["field"]: r.get("disposition") for r in body_a if r["letter"] == letter}
        rows, adj_bu = arm_rows(c, disp)
        rereads = set(chk["arms"][arm]["complete_sources"]) if arm == "C" else set()
        conflicts = [x for x in c["conflicts"] if x[0] not in rereads]
        metrics = {"adjacency_baseline_upheld": adj_bu, "residual_conflicts": len(conflicts) + len(c["cross_unit_undefined"]),
                   "residual_calibration": len(c["residual_calibration"]),
                   "endpoint_validity": c["edges"]["endpoint_validity"]}
        v = val.get(run, {})
        level2 = {"verifier_pass": v.get("result") == "PASS", "quote_misses": v.get("quote_misses") or 0}
        o, reasons = outcome_of(arm, feas, level2, rows, metrics, (chk["violations"].get(arm) or []) + pv(run))
        outcomes[arm], detail[arm] = o, {"letter": letter, "reasons": reasons, "metrics": metrics, "level2": level2,
                                         "edges": c["edges"],
                                         "counts": collections.Counter(r["class"] for r in c["fields"])}
    if auditor_pv or any(r.get("disposition") not in AUDIT_DISPOSITIONS for r in body_a):
        outcomes = {k: ("UNDETERMINED" if v in ("FAITHFUL", "NOT-FAITHFUL") else v) for k, v in outcomes.items()}
        feas["reasons"].append("audit invalid: auditor transcript finding or an off-scale disposition")
    res = {"feasibility": feas, "outcomes": outcomes, "detail": detail, "decision": decision(outcomes),
           "scope": "n_labels = 1, n_units = 3: evidence about decomposition fidelity on this few-unit control label only; "
                    "no population claim; the S5 floor is not affected (R19)",
           "scope_statements": SCOPE_STATEMENTS}
    _dump("OB0018-RESULT.json", res)
    return res


def selftest():
    units = [{"run_id": "U1", "files": ["S0001", "S0003"]}, {"run_id": "U2", "files": ["S0002"]}]
    assert adjacencies(["S0001", "S0002", "S0003"], units) == [("S0001", "S0002"), ("S0002", "S0003")]
    recs = [{"source_id": "S0001", "absence_evidence": [{"dimension": "d", "finding": "MENTIONS", "quote": "X  y"}],
             "stage2_dispositions": [{"source_id": "S0001", "by_dimension": {"d": "FOUND"}}]},
            {"source_id": "S0002", "absence_evidence": [{"dimension": "d", "finding": "DEFINES", "quote": "x y"}]}]
    assert [(x[0], x[2], x[3]) for x in disposition_conflicts(record_dispositions(recs), record_findings(recs))] == \
        [("S0001", "d", "FOUND")]
    assert len(calibration_pairs(recs, units)) == 1
    assert decision({"A": "NOT-FAITHFUL", "B": "FAITHFUL", "C": "FAITHFUL"}) == "EVIDENCE-FOR-ARCHITECTURE-B"
    assert decision({"A": "UNDETERMINED", "B": "FAITHFUL", "C": "FAITHFUL"}) == "UNDETERMINED"
    print("selftest PASS")
    return 0


def main(argv=None):
    a = argv if argv is not None else sys.argv[1:]
    if not a:
        print(__doc__, file=sys.stderr)
        return 2
    try:
        if a[0] == "selftest":
            return selftest()
        if a[0] == "plan":
            print(json.dumps(cmd_plan()))
            return 0
        if a[0] == "prompts":
            print(json.dumps(cmd_prompts()))
            return 0
        cmds = {"check": lambda: cmd_check("--all" in a), "invariants": cmd_invariants, "blind": cmd_blind, "scan": lambda: cmd_scan("--audit" in a), "fingerprint": cmd_fingerprint,
                "compare": cmd_compare, "validate": cmd_validate, "result": cmd_result,
                "freeze": lambda: cmd_freeze(a[a.index("--stage") + 1] if "--stage" in a else "")}
        if a[0] not in cmds or "--commit" not in a:
            print(__doc__, file=sys.stderr)
            return 2
        authorized(a[a.index("--commit") + 1])
        _c().assert_sealed()
        out = cmds[a[0]]()
        print(json.dumps(out, indent=1, default=str))
        _c().assert_sealed()
    except PilotError as e:
        print(f"REFUSED: {e}", file=sys.stderr)
        return 1
    return 0


if __name__ == "__main__":
    sys.exit(main())
