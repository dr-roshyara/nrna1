#!/usr/bin/env python3
"""
gate-runner.py - measuring instrument for TEMPORARY KnowledgeOS research gates.

R-26 CONTRACT: run -> capture -> verdict -> stop.
No explanations, no recommendations, no summaries. The runner reports what it
measured and nothing about what it means. Interpretation belongs to the
reviewer and to the research record, never inside the instrument.

    python3 gate-runner.py                 # evaluate; exit 1 if any ACTIVATED tier-A gate fails
    python3 gate-runner.py --json
    python3 gate-runner.py --self-test     # verify the instrument against fixtures
    python3 gate-runner.py --list-eligible # ids eligible for activation
    python3 gate-runner.py --stage PHASE_2 --timing POST
    python3 gate-runner.py --pin-lines     # activation lines with pins, for the HUMAN to paste

Verdicts: PASS FAIL INCONCLUSIVE REVIEW_REQUIRED NOT_ACTIVATED NOT_APPLICABLE ERROR
A REVIEW or HUMAN class gate is never PASSed by this runner.
INCONCLUSIVE = a required input is missing or empty; it blocks like FAIL.

Exit: 0 CLEAR / NO_ACTIVE_GOVERNANCE_CONTROLS · 1 BLOCK · 3 GOVERNANCE_INOPERATIVE.
GOVERNANCE_INOPERATIVE (extended 1a) whenever no verdict can be trusted:
unreadable control surface, duplicate or schema-invalid gate, self-test not
100%, an activated gate that is not `active` or is REVIEW/HUMAN, or an
activation whose pin does not match the current gate definition.
"""

import argparse
import datetime
import hashlib
import json
import os
import re
import sys

try:
    import yaml
except ImportError:
    sys.stderr.write("gate-runner: pyyaml required\n")
    sys.exit(3)

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.dirname(HERE)          # the research workspace
P2 = os.path.join(ROOT, "phase2_extraction")

PASS, FAIL = "PASS", "FAIL"
REVIEW, NOT_ACTIVATED = "REVIEW_REQUIRED", "NOT_ACTIVATED"
NOT_APPLICABLE, ERROR = "NOT_APPLICABLE", "ERROR"
INCONCLUSIVE = "INCONCLUSIVE"
EVALUATED = (PASS, FAIL, ERROR, INCONCLUSIVE)

# L0-DEC-17: KOS-G-003's known file-id set is the canonical corpus list,
# read directly. Derived from the runner's location, never from --root.
CANONICAL_LIST = os.path.join(os.path.dirname(ROOT), "list_of_files_to_read.log")


# ── artifact access ──────────────────────────────────────────────────────

class Inconclusive(Exception):
    """A required input is missing, or present with nothing to examine (IC-1).

    Absence is not evidence of conformance: a check that examined nothing
    reports INCONCLUSIVE, never PASS. On an activated tier-A gate it blocks.
    """


def _required(root, rel, allow_empty=False):
    """Records of a required .jsonl input. Missing ⇒ Inconclusive; zero
    records ⇒ Inconclusive unless an empty file is a meaningful input."""
    path = os.path.join(root, rel)
    if not os.path.exists(path):
        raise Inconclusive(f"{rel}: absent")
    recs, _ = _jsonl(path)
    if not recs and not allow_empty:
        raise Inconclusive(f"{rel}: 0 records")
    return recs


def _jsonl(path):
    out, bad = [], []
    if not os.path.exists(path):
        return out, bad
    with open(path, encoding="utf-8") as fh:
        for i, line in enumerate(fh, 1):
            if not line.strip():
                continue
            try:
                out.append(json.loads(line))
            except json.JSONDecodeError:
                bad.append(i)
    return out, bad


def _jsonl_paths(root):
    """Every .jsonl artifact under root, recursively (IC-5: implementation
    scope = declared scope). Excluded, and declared as excluded in gates.yaml:
    the top-level `governance/` tree (governance-owned; its fixtures are
    deliberately defective) and hidden directories."""
    paths = []
    for d, dirs, files in os.walk(root):
        dirs[:] = sorted(x for x in dirs if not x.startswith(".")
                         and not (d == root and x == "governance"))
        paths += [os.path.join(d, f) for f in sorted(files) if f.endswith(".jsonl")]
    return paths


def _theory_split(root):
    path = os.path.join(root, "phase2_extraction",
                        "CANDIDATE-KNOWLEDGEOS-THEORY.md")
    if not os.path.exists(path):
        return None, None
    text = open(path, encoding="utf-8").read()
    marker = "# Appendix · Trace"
    if marker not in text:
        return text, ""
    body, appendix = text.split(marker, 1)
    return body, appendix


# ── check implementations ────────────────────────────────────────────────
# Each returns (bool_passed, measurement_string). The measurement is a FACT:
# counts and identifiers. It carries no judgement about significance.

def c_jsonl_wellformed(root):
    paths = _jsonl_paths(root)
    if not paths:
        raise Inconclusive("0 .jsonl files")
    bad, records = [], 0
    for p in paths:
        recs, lines = _jsonl(p)
        records += len(recs) + len(lines)
        if lines:
            bad.append(f"{os.path.relpath(p, root)}:{lines}")
    if not records:
        raise Inconclusive(f"{len(paths)} files; 0 non-blank lines")
    return not bad, f"{len(paths)} files; malformed={bad or 0}"


def c_no_duplicate_ids(root):
    objs = _required(root, "THEORY-OBJECTS.jsonl")
    rels = _required(root, "phase2_extraction/THEORY-OBJECT-RELATIONS.jsonl",
                     allow_empty=True)
    oid = [o.get("theory_object_id") for o in objs]
    rid = [r.get("object") for r in rels]
    dup_o = sorted({x for x in oid if oid.count(x) > 1})
    dup_r = sorted({x for x in rid if rid.count(x) > 1})
    return (not dup_o and not dup_r,
            f"objects={len(oid)} relations={len(rid)} "
            f"dup_objects={dup_o or 0} dup_relations={dup_r or 0}")


def _canonical_file_ids(path):
    """File ids of the canonical corpus list (L0-DEC-17). Lines are
    `F0001<TAB>timestamp path`. Unreadable or id-less ⇒ Inconclusive."""
    if not path or not os.path.exists(path):
        raise Inconclusive("canonical list absent")
    ids = set()
    with open(path, encoding="utf-8") as fh:
        for line in fh:
            m = re.match(r"^(F\d{4})\t", line)
            if m:
                ids.add(m.group(1))
    if not ids:
        raise Inconclusive("canonical list: 0 file ids")
    return ids


def c_no_dangling_refs(root, canonical_list=CANONICAL_LIST):
    objs = _required(root, "THEORY-OBJECTS.jsonl")
    gaps = _required(root, "GAPS.jsonl", allow_empty=True)
    canon = _canonical_file_ids(canonical_list)
    known = ({o.get("theory_object_id") for o in objs}
             | {g.get("gap_id") for g in gaps}
             | canon)
    dangling = {}
    for p in _jsonl_paths(root):
        text = open(p, encoding="utf-8").read()
        for m in set(re.findall(r"\b(?:T-\d{4}|G-\d{4}|F\d{4})\b", text)):
            if m not in known:
                dangling.setdefault(os.path.relpath(p, root), set()).add(m)
    return (not dangling,
            f"known={len(known)} (canonical={len(canon)}) dangling="
            + (json.dumps({k: sorted(v) for k, v in dangling.items()}) if dangling else "0"))


def c_index_coverage(root):
    reg = _required(root, "FILE-REGISTRY.jsonl")
    idx = _required(root, "THEORY-DISCOVERY-INDEX.jsonl", allow_empty=True)
    r = {x.get("file_id") for x in reg}
    i = {x.get("file_id") for x in idx}
    missing = sorted(r - i)
    return not missing, f"{len(i)}/{len(r)} missing={missing or 0}"


def c_index_false_has_reason(root):
    idx = _required(root, "THEORY-DISCOVERY-INDEX.jsonl")
    false_entries = [e for e in idx if e.get("candidate_theory_bearing") is False]
    no_reason = [e.get("file_id") for e in false_entries
                 if not (e.get("why") or "").strip()]
    return (not no_reason,
            f"false_entries={len(false_entries)} without_reason={no_reason or 0}")


def c_index_reason_not_document_kind(root):
    idx = _required(root, "THEORY-DISCOVERY-INDEX.jsonl")
    markers = ("governance document", "process document", "administrative",
               "document kind", "document type", "it is a governance",
               "it is a process")
    hits = [e.get("file_id") for e in idx
            if e.get("candidate_theory_bearing") is False
            and any(m in (e.get("why") or "").lower() for m in markers)]
    return not hits, f"kind_based_reasons={hits or 0}"


def c_relations_coverage(root):
    objs = _required(root, "THEORY-OBJECTS.jsonl")
    rels = _required(root, "phase2_extraction/THEORY-OBJECT-RELATIONS.jsonl",
                     allow_empty=True)
    o ={x.get("theory_object_id") for x in objs}
    r = {x.get("object") for x in rels}
    missing = sorted(o - r)
    return not missing, f"{len(r & o)}/{len(o)} missing={missing or 0}"


def c_relations_none_found_has_reason(root):
    rels = _required(root, "phase2_extraction/THEORY-OBJECT-RELATIONS.jsonl")
    bare = []
    for rec in rels:
        blob = json.dumps(rec.get("relations", {}))
        if "NONE_FOUND" in blob and not re.search(r'"[^"]*reason[^"]*"\s*:', blob):
            bare.append(rec.get("object"))
    return not bare, f"bare_none_found={bare or 0}"


def _seed_and_traced(root):
    seed_path = os.path.join(root, "phase2_extraction", "THEORY-SEED.md")
    if not os.path.exists(seed_path):
        raise Inconclusive("phase2_extraction/THEORY-SEED.md: absent")
    seed = set(re.findall(r"SI-\d{4}", open(seed_path, encoding="utf-8").read()))
    if not seed:
        raise Inconclusive("THEORY-SEED.md: 0 seed items")
    body, appendix = _theory_split(root)
    if body is None:
        raise Inconclusive("CANDIDATE-KNOWLEDGEOS-THEORY.md: absent")
    traced = set(re.findall(r"SI-\d{4}", appendix or ""))
    return seed, traced


def c_seed_trace_complete(root):
    seed, traced = _seed_and_traced(root)
    missing = sorted(seed - traced)
    return not missing, f"{len(traced & seed)}/{len(seed)} untraced={missing or 0}"


def c_seed_trace_no_phantom(root):
    seed, traced = _seed_and_traced(root)
    phantom = sorted(traced - seed)
    return not phantom, f"phantom={phantom or 0}"


def c_theory_doc_exists(root):
    body, _ = _theory_split(root)
    if body is None:
        return False, "absent"
    return bool(body.strip()), f"lines_before_appendix={len(body.splitlines())}"


def c_theory_body_no_identifiers(root):
    body, _ = _theory_split(root)
    if body is None:
        raise Inconclusive("CANDIDATE-KNOWLEDGEOS-THEORY.md: absent")
    hits = re.findall(r"SI-\d{4}", body)
    return not hits, f"identifiers_in_body={len(hits)}"


def c_theory_body_no_json_refs(root):
    body, _ = _theory_split(root)
    if body is None:
        raise Inconclusive("CANDIDATE-KNOWLEDGEOS-THEORY.md: absent")
    hits = sorted(set(re.findall(r"[\w-]+\.jsonl", body)))
    return not hits, f"json_refs_in_body={hits or 0}"


def c_gaps_dispositioned(root):
    gaps = _required(root, "GAPS.jsonl")
    valid = ("FILLED", "REFUSED", "POINTER", "WAITING", "BLOCKED", "RESOLVED")
    undisp = []
    for g in gaps:
        s = (g.get("resolution_status") or "").upper()
        if not s or s in ("UNRESOLVED", "OPEN") or not any(v in s for v in valid):
            undisp.append(g.get("gap_id"))
    return (not undisp,
            f"{len(gaps) - len(undisp)}/{len(gaps)} undispositioned={undisp or 0}")


CHECKS = {
    "jsonl_wellformed": c_jsonl_wellformed,
    "no_duplicate_ids": c_no_duplicate_ids,
    "no_dangling_refs": c_no_dangling_refs,
    "index_coverage": c_index_coverage,
    "index_false_has_reason": c_index_false_has_reason,
    "index_reason_not_document_kind": c_index_reason_not_document_kind,
    "relations_coverage": c_relations_coverage,
    "relations_none_found_has_reason": c_relations_none_found_has_reason,
    "seed_trace_complete": c_seed_trace_complete,
    "seed_trace_no_phantom": c_seed_trace_no_phantom,
    "theory_doc_exists": c_theory_doc_exists,
    "theory_body_no_identifiers": c_theory_body_no_identifiers,
    "theory_body_no_json_refs": c_theory_body_no_json_refs,
    "gaps_dispositioned": c_gaps_dispositioned,
}


# ── rule book ────────────────────────────────────────────────────────────

class RuleBookError(Exception):
    """The rule book or the activation list could not be read.

    This is NOT a gate failure and must never be reported as one. It means the
    instrument cannot say anything at all. An unreadable control surface is
    treated as inoperative, never as permission: the caller exits 3, which the
    door reports as 'the door itself is broken', not as CLEAR and not as BLOCK.
    """


def _load_yaml(name):
    path = os.path.join(HERE, name)
    if not os.path.exists(path):
        raise RuleBookError(f"{name}: absent")
    try:
        return yaml.safe_load(open(path, encoding="utf-8")) or {}
    except yaml.YAMLError as exc:
        where = ""
        mark = getattr(exc, "problem_mark", None)
        if mark is not None:
            where = f" at line {mark.line + 1}, column {mark.column + 1}"
        raise RuleBookError(
            f"{name}: not parseable{where} — {getattr(exc, 'problem', exc)}"
        ) from exc


def load_gates():
    doc = _load_yaml("gates.yaml")
    gates = doc.get("gates")
    if not gates:
        raise RuleBookError("gates.yaml: no gates defined")
    return gates


def load_activated(known_ids=None):
    """Read the activation list.

    If `known_ids` is given, every activated id MUST name a gate that exists.
    An unknown id is a RuleBookError, not a silently ignored entry: the human
    activated something, so the alternative is to report CLEAR while quietly
    enforcing less than was asked for. A typo would then remove a gate from
    enforcement and say nothing. Fail loud instead.
    """
    doc = _load_yaml("governance-state.yaml")
    entries = doc.get("activated") or []
    if not isinstance(entries, list):
        raise RuleBookError("governance-state.yaml: `activated` is not a list")
    ids = {}                               # id -> pin (None when unpinned)
    for e in entries:
        if isinstance(e, str):
            ids[e] = None
        elif isinstance(e, dict) and e.get("id"):
            ids[e["id"]] = e.get("pin")
        else:
            raise RuleBookError(
                f"governance-state.yaml: unusable activation entry {e!r}")
    if known_ids is not None:
        validate_activation(ids, known_ids)
    return ids


def validate_activation(ids, known_ids):
    """Every activated id must name a gate that exists. Pure; raises."""
    unknown = sorted(set(ids) - set(known_ids))
    if unknown:
        raise RuleBookError(
            "governance-state.yaml: activated id(s) name no gate in "
            f"gates.yaml: {unknown}. Nothing was evaluated — an unknown "
            "id cannot be honoured, and ignoring it would enforce less "
            "than was activated while reporting success.")
    return True


def pin_of(gate):
    """GIA-4 / L0-DEC-16: the pin of a gate definition. sha256 over the
    canonical JSON of the parsed gate mapping (sorted keys, compact
    separators), first 16 hex. Any change to any field changes the pin."""
    blob = json.dumps(gate, sort_keys=True, ensure_ascii=False,
                      separators=(",", ":"), default=str)
    return hashlib.sha256(blob.encode("utf-8")).hexdigest()[:16]


def schema_violations(gates, schema):
    """Every gate against gate-schema.yaml. Returns a list of violations."""
    out = []
    rules = schema.get("field_rules") or {}
    required = schema.get("required_fields") or []
    if not required or not rules:
        return ["gate-schema.yaml: no required_fields/field_rules"]
    pattern = (rules.get("id") or {}).get("pattern")
    for g in gates:
        if not isinstance(g, dict):
            out.append(f"gate entry is not a mapping: {g!r}")
            continue
        gid = g.get("id")
        for f in required:
            if f not in g:
                out.append(f"{gid}: missing field `{f}`")
        if pattern and not re.match(pattern, str(gid)):
            out.append(f"{gid}: id does not match {pattern}")
        for f in ("stage", "timing", "class", "tier", "status"):
            enum = (rules.get(f) or {}).get("enum")
            if enum and g.get(f) not in enum:
                out.append(f"{gid}: {f}={g.get(f)!r} not in {enum}")
        pc = g.get("pass_condition") or {}
        pc_enum = ((rules.get("pass_condition") or {}).get("type") or {}).get("enum")
        if pc_enum and pc.get("type") not in pc_enum:
            out.append(f"{gid}: pass_condition.type={pc.get('type')!r}")
        if pc.get("type") == "deterministic" and not pc.get("check"):
            out.append(f"{gid}: deterministic without pass_condition.check")
        eff_enum = ((rules.get("failure") or {}).get("effect") or {}).get("enum")
        if eff_enum and (g.get("failure") or {}).get("effect") not in eff_enum:
            out.append(f"{gid}: failure.effect not in {eff_enum}")
        if "human_decision_required" in g and not isinstance(g["human_decision_required"], bool):
            out.append(f"{gid}: human_decision_required is not boolean")
    return out


def operability(gates, activated):
    """Conditions under which NO verdict of this run can be trusted.

    Each one returns GOVERNANCE_INOPERATIVE (exit 3) — never CLEAR, never a
    gate verdict (IC-2, IC-3, IC-4, IC-8). Returns a list of reasons; empty
    means the instrument may evaluate.
    """
    reasons = []
    ids = [g.get("id") for g in gates if isinstance(g, dict)]
    dups = sorted({i for i in ids if ids.count(i) > 1})
    if dups:
        reasons.append(f"gates.yaml: duplicate gate id(s) {dups}")
    try:
        reasons += schema_violations(gates, _load_yaml("gate-schema.yaml"))
    except RuleBookError as exc:
        reasons.append(str(exc))
    bad = [r for r in self_test() if r[2] != "OK"]
    if bad:
        reasons.append("self-test not 100%: "
                       + ", ".join(f"{k}:{n}={v}" for k, n, v, _ in bad[:6]))
    by_id = {g.get("id"): g for g in gates if isinstance(g, dict)}
    for gid, pin in sorted(activated.items()):
        g = by_id.get(gid)
        if g is None:
            continue                          # validate_activation owns this
        if g.get("status") != "active":
            reasons.append(f"{gid}: activated but status={g.get('status')} "
                           "(an activated gate that is not active is never "
                           "counted as applicable)")
        if g.get("class") in ("REVIEW", "HUMAN"):
            reasons.append(f"{gid}: class {g.get('class')} may not be "
                           "activated (L0-DEC-14: no completion-record "
                           "mechanism exists)")
        if g.get("tier") != "A":
            reasons.append(f"{gid}: tier {g.get('tier')} may not be activated "
                           "(L0-DEC-21, IR-G1): an advisory gate never blocks, "
                           "so its failure would be reported as CLEAR")
        check = (g.get("pass_condition") or {}).get("check")
        if g.get("class") == "AUT" and check not in CHECKS:
            reasons.append(f"{gid}: check {check} not implemented "
                           "(L0-DEC-21, IR-G4): an instrument defect, not a "
                           "research BLOCK")
        if pin is None:
            reasons.append(f"{gid}: unpinned activation (L0-DEC-16); "
                           "print pins with --pin-lines")
        elif not isinstance(pin, str) or pin != pin_of(g):
            reasons.append(f"{gid}: pin mismatch — the gate definition "
                           "changed since activation, or the pin is "
                           "malformed; re-activation by the human is required")
    return reasons


def integrity():
    """sha256 of each governance file. Tamper-evident, not tamper-proof."""
    out = {}
    for name in ("gates.yaml", "governance-state.yaml", "gate-schema.yaml",
                 "gate-runner.py"):
        p = os.path.join(HERE, name)
        if os.path.exists(p):
            out[name] = hashlib.sha256(open(p, "rb").read()).hexdigest()[:16]
    return out


def evaluate(gates, activated, root, stage=None, timing=None):
    rows = []
    for g in gates:
        gid, cls = g.get("id"), g.get("class")
        status, tier = g.get("status"), g.get("tier")
        if stage and g.get("stage") != stage:
            continue
        if timing and g.get("timing") != timing:
            continue

        row = {"id": gid, "name": g.get("name"), "class": cls, "tier": tier,
               "status": status, "verdict": None, "measurement": "",
               "blocking": False}

        if status != "active":
            row["verdict"] = NOT_APPLICABLE
            row["measurement"] = f"status={status}"
            rows.append(row)
            continue

        if cls in ("REVIEW", "HUMAN"):
            # Never PASSed by an instrument, whatever the artifacts say.
            row["verdict"] = REVIEW
            row["measurement"] = f"class={cls}"
            row["blocking"] = (gid in activated and tier == "A")
            rows.append(row)
            continue

        check = (g.get("pass_condition") or {}).get("check")
        fn = CHECKS.get(check)
        if fn is None:
            row["verdict"] = ERROR
            row["measurement"] = f"check={check} not implemented"
        else:
            try:
                ok, measurement = fn(root)
                row["verdict"] = PASS if ok else FAIL
                row["measurement"] = measurement
            except Inconclusive as exc:
                row["verdict"] = INCONCLUSIVE
                row["measurement"] = str(exc)
            except Exception as exc:                      # noqa: BLE001
                row["verdict"] = ERROR
                row["measurement"] = f"{type(exc).__name__}: {exc}"

        if gid not in activated:
            row["measurement"] += " | not activated"
            if row["verdict"] == PASS:
                row["verdict"] = PASS
            row["blocking"] = False
        else:
            row["blocking"] = (tier == "A"
                               and row["verdict"] in (FAIL, ERROR, INCONCLUSIVE))
        rows.append(row)
    return rows


# ── self-test ────────────────────────────────────────────────────────────

def self_test():
    """Verify each implemented check returns PASS on a passing fixture and
    FAIL on a failing one. Proves the instrument measures; claims nothing
    about the research."""
    fx = os.path.join(HERE, "fixtures")
    results = []
    # ---- activation-handling cases (pure, no fixture tree needed) ------
    for name, ids, known, should_raise in (
            ("activation:all-known", {"KOS-G-001"}, {"KOS-G-001"}, False),
            ("activation:unknown-id", {"KOS-G-999"}, {"KOS-G-001"}, True),
            ("activation:partial-typo",
             {"KOS-G-001", "KOS-G-O22"}, {"KOS-G-001", "KOS-G-022"}, True)):
        try:
            validate_activation(ids, known)
            raised = False
        except RuleBookError:
            raised = True
        results.append(("logic", name,
                        "OK" if raised is should_raise else "MISMEASURED",
                        f"raised={raised} expected={should_raise}"))

    shared_list = os.path.join(fx, "CANONICAL-LIST.log")

    def measure(fn, base, canonical_list):
        """-> (PASS|FAIL|INCONCLUSIVE|ERROR, measurement)"""
        try:
            if fn is c_no_dangling_refs:
                ok, m = fn(base, canonical_list=canonical_list)
            else:
                ok, m = fn(base)
            return (PASS if ok else FAIL), m
        except Inconclusive as exc:
            return INCONCLUSIVE, str(exc)
        except Exception as exc:                          # noqa: BLE001
            return ERROR, f"{type(exc).__name__}: {exc}"

    for kind, expect in (("pass", PASS), ("fail", FAIL)):
        shared = os.path.join(fx, kind)
        if not os.path.isdir(shared):
            results.append((kind, "-", "ERROR", "fixture dir absent"))
            continue
        for name, fn in sorted(CHECKS.items()):
            # A check whose failure cannot coexist with the others in one
            # tree gets its own fixture directory. Absent -> use the shared.
            own = os.path.join(fx, kind, "_per_check", name)
            base = own if os.path.isdir(own) else shared
            got, measurement = measure(fn, base, shared_list)
            verdict = "OK" if got == expect else ("ERROR" if got == ERROR else "MISMEASURED")
            where = "own" if base is own else "shared"
            results.append((kind, name, verdict, f"[{where}] {got}: {measurement}"))

    # IC-7: missing / empty / near-miss cases, one directory each, with the
    # expected verdict in EXPECT.json. Absent case tree ⇒ ERROR, never skipped.
    cases = os.path.join(fx, "cases")
    if not os.path.isdir(cases):
        results.append(("case", "-", "ERROR", "fixtures/cases absent"))
        return results
    for name in sorted(os.listdir(cases)):
        fn = CHECKS.get(name)
        for case in sorted(os.listdir(os.path.join(cases, name))):
            base = os.path.join(cases, name, case)
            label = f"{name}/{case}"
            try:
                spec = json.load(open(os.path.join(base, "EXPECT.json"), encoding="utf-8"))
            except Exception as exc:                      # noqa: BLE001
                results.append(("case", label, "ERROR", f"EXPECT.json: {exc}"))
                continue
            if fn is None:
                results.append(("case", label, "ERROR", "no such check"))
                continue
            canonical_list = {"shared": shared_list, "none": None,
                              "local": os.path.join(base, "CANONICAL-LIST.log")
                              }.get(spec.get("canonical", "shared"))
            got, measurement = measure(fn, base, canonical_list)
            verdict = "OK" if got == spec.get("expect") else "MISMEASURED"
            results.append(("case", label, verdict,
                            f"{got} (expected {spec.get('expect')}): {measurement}"))
    return results


# ── output ───────────────────────────────────────────────────────────────

def _rulebook_failure(exc, as_json):
    """Report an unreadable control surface. Never CLEAR, never BLOCK."""
    if as_json:
        print(json.dumps({"result": "GOVERNANCE_INOPERATIVE",
                          "error": str(exc),
                          "reading": "The control surface could not be read "
                                     "or is not operable. This is not a gate "
                                     "verdict and grants no permission to "
                                     "proceed."}, indent=2))
    else:
        print("STATUS: GOVERNANCE_INOPERATIVE")
        print(f"  {exc}")
        print("  The control surface could not be read or is not operable. This")
        print("  is NOT a gate verdict, NOT a pass, and grants no permission to proceed.")
    return 3


def main():
    ap = argparse.ArgumentParser(add_help=True)
    ap.add_argument("--json", action="store_true")
    ap.add_argument("--self-test", action="store_true")
    ap.add_argument("--list-eligible", action="store_true")
    ap.add_argument("--stage")
    ap.add_argument("--timing")
    ap.add_argument("--root", default=ROOT)
    ap.add_argument("--pin-lines", nargs="?", const="ACTIVATED", default=None,
                    help="print activation lines with pins for the activated "
                         "ids (or a comma-separated id list) for the human to paste")
    a = ap.parse_args()

    try:
        gates = load_gates()
    except RuleBookError as exc:
        _rulebook_failure(exc, a.json)
        return 3

    if a.list_eligible:
        for g in gates:
            if g.get("status") == "active":
                print(f"{g['id']}  {g['name']}  class={g['class']} tier={g['tier']}")
        return 0

    if a.self_test:
        rows = self_test()
        bad = [r for r in rows if r[2] != "OK"]
        if a.json:
            print(json.dumps({"rows": rows, "ok": not bad}, indent=2))
        else:
            for kind, name, verdict, measurement in rows:
                print(f"  [{verdict:<12}] {kind:<4} {name:<34} {measurement}")
            print(f"\nself-test: {len(rows) - len(bad)}/{len(rows)}")
        return 1 if bad else 0

    try:
        activated = load_activated(known_ids={g.get("id") for g in gates})
    except RuleBookError as exc:
        _rulebook_failure(exc, a.json)
        return 3

    if a.pin_lines:
        # The runner PRINTS pins; the human pastes them under `activated:`.
        # Printing is not activation (L0-DEC-16 implementation note).
        by_id = {g.get("id"): g for g in gates}
        today = datetime.date.today().isoformat()
        for gid in (a.pin_lines.split(",") if a.pin_lines != "ACTIVATED" else sorted(activated)):
            g = by_id.get(gid)
            if g is None:
                print(f"  # {gid}: no such gate")
            elif (g.get("status") != "active" or g.get("class") in ("REVIEW", "HUMAN")
                  or g.get("tier") != "A"):
                print(f"  # {gid}: not pinnable (status={g.get('status')}, "
                      f"class={g.get('class')}, tier={g.get('tier')})")
            else:
                print(f'  - {{id: {gid}, pin: "{pin_of(g)}", by: <human>, date: {today}}}')
        return 0

    reasons = operability(gates, activated)
    # IR-G2 (L0-DEC-21): a stage/timing filter outside the schema enums is a
    # usage error, never a filter that silently selects nothing.
    try:
        rules = _load_yaml("gate-schema.yaml").get("field_rules") or {}
        for flag, value in (("stage", a.stage), ("timing", a.timing)):
            enum = (rules.get(flag) or {}).get("enum") or []
            if value is not None and value not in enum:
                reasons.append(f"--{flag} {value!r} is not in the schema enum {enum}")
    except RuleBookError as exc:
        reasons.append(str(exc))
    if reasons:
        _rulebook_failure(RuleBookError("; ".join(reasons)), a.json)
        return 3

    rows = evaluate(gates, activated, a.root, a.stage, a.timing)
    blocking = [r for r in rows if r["blocking"]]

    eligible = sum(1 for g in gates if g.get("status") == "active")
    failing = [r for r in rows if r["verdict"] in (FAIL, ERROR, INCONCLUSIVE)]
    # Applicable = activated AND actually evaluated. A row that was not
    # evaluated is never counted (D09: re-statused gates produced CLEAR).
    applicable = [r for r in rows if r["id"] in activated and r["verdict"] in EVALUATED]
    # With nothing activated there is no control to clear. The result token is
    # NOT a variant of CLEAR: a word that reads as approval must not appear
    # where no control was exercised.
    if blocking:
        result = "BLOCK"
    elif not applicable:
        # No activated gate was APPLICABLE here, so nothing was checked. That
        # is true when nothing is activated, and equally true when everything
        # activated was filtered out by stage/timing. CLEAR must never name a
        # run in which no control was exercised.
        result = "NO_ACTIVE_GOVERNANCE_CONTROLS"
    else:
        result = "CLEAR"

    if a.json:
        print(json.dumps({"rows": rows, "activated": sorted(activated),
                          "eligible": eligible,
                          "applicable_activated": len(applicable),
                          "activated_blocking": len(blocking),
                          "failing": [r["id"] for r in failing],
                          "integrity": integrity(), "result": result}, indent=2))
        return 1 if blocking else 0

    for r in rows:
        act = "*" if r["id"] in activated else " "
        print(f" {act}[{r['verdict']:<15}] {r['id']}  {r['name']:<38} {r['measurement']}")
    print()
    print(f"Activated blocking gates: {len(blocking)}")
    print(f"Applicable activated gates: {len(applicable)}")
    print(f"Eligible but not activated: {eligible - len(activated)}   "
          f"Measurements not passing: {len(failing)}")
    print()
    print(f"STATUS: {result}")
    return 1 if blocking else 0


if __name__ == "__main__":
    try:
        sys.exit(main())
    except Exception as exc:                              # noqa: BLE001
        # A crash is not a gate verdict. Uncaught, it would exit 1 and the
        # door would read it as BLOCK (IC-6).
        print(f"STATUS: GOVERNANCE_INOPERATIVE\n  runner crashed: {type(exc).__name__}: {exc}")
        sys.exit(3)
