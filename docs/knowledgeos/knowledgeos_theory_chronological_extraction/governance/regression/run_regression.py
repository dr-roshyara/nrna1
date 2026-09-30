#!/usr/bin/env python3
"""
run_regression.py — gate-integrity regression battery (extended Increment 1a).

Governance-owned (L0-DEC-15). Each case copies a snapshot of the research
workspace, applies ONE mutation, runs the runner (--json) and the door, and
compares the outcome with the EXPECTED POST-CORRECTION verdict recorded below.
The expectations were written BEFORE the 1a runner changes (1a.2); running
this file against the unchanged runner must show mismatches (RED).

    python3 governance/regression/run_regression.py                 # control plane from the working tree
    python3 governance/regression/run_regression.py --control rev   # control plane from --rev (e.g. the unchanged runner)
    python3 governance/regression/run_regression.py --rev <commit> --only D09,G02 --json

Research data always comes from `git archive --rev` (default HEAD), so the
battery never reads or writes the live workspace.

Pins: the harness activates the five first-group gates WITH a pin computed
from the unmutated gate definitions (simulating the human act before a
tamper), then applies the mutation. Its pin algorithm is written here
independently of the runner; a disagreement shows up as INOPERATIVE on A0.

Exit 0 when every case matches; 1 otherwise. The battery reports what it
measured; it accepts nothing (1a.6 review and 1a.7 L0 acceptance do that).
"""

import argparse, hashlib, json, os, shutil, subprocess, sys, tempfile

HERE = os.path.dirname(os.path.abspath(__file__))
WS = os.path.dirname(os.path.dirname(HERE))            # the research workspace
J = os.path.join
ACT = ["KOS-G-001", "KOS-G-002", "KOS-G-003", "KOS-G-010", "KOS-G-022"]
INOP, BLOCK, CLEAR, NOACT = ("GOVERNANCE_INOPERATIVE", "BLOCK", "CLEAR",
                             "NO_ACTIVE_GOVERNANCE_CONTROLS")
DOOR = {CLEAR: 0, NOACT: 0, BLOCK: 2, INOP: 3}


# ── helpers ──────────────────────────────────────────────────────────────

def jl(p): return [json.loads(l) for l in open(p, encoding="utf-8") if l.strip()]
def wl(p, L): open(p, "w", encoding="utf-8").write("".join(json.dumps(x, ensure_ascii=False) + "\n" for x in L))
def G(t): return J(t, "governance", "gates.yaml")
def S(t): return J(t, "governance", "governance-state.yaml")


def pin_of(gate):
    """Independent statement of the GIA-4 pin: sha256 over canonical JSON of
    the parsed gate mapping, first 16 hex."""
    blob = json.dumps(gate, sort_keys=True, ensure_ascii=False,
                      separators=(",", ":"), default=str)
    return hashlib.sha256(blob.encode("utf-8")).hexdigest()[:16]


def gate_defs(t):
    import yaml
    return {g["id"]: g for g in yaml.safe_load(open(G(t), encoding="utf-8"))["gates"]}


def write_state(t, entries):
    """Replace the live `activated:` key (kept LAST in the file) with entries.
    entries: list of str (bare id) or (id, pin)."""
    s = open(S(t), encoding="utf-8").read()
    head = s[: s.index("\nactivated:") + 1]
    lines = ["activated:"]
    for e in entries:
        if isinstance(e, str):
            lines.append(f"  - {e}")
        else:
            lines.append(f'  - {{id: {e[0]}, pin: "{e[1]}", by: regression-harness, date: 2026-09-23}}')
    if not entries:
        lines = ["activated: []"]
    open(S(t), "w", encoding="utf-8").write(head + "\n".join(lines) + "\n")


def pinned(t, ids):
    d = gate_defs(t)
    return [(i, pin_of(d[i])) for i in ids]


def add_activation(gid, pin=True):
    def m(t):
        cur = pinned(t, ACT)
        cur.append((gid, pin_of(gate_defs(t)[gid])) if pin else gid)
        write_state(t, cur)
    return m


def edit(path_fn, old, new):
    def m(t):
        p = path_fn(t); s = open(p, encoding="utf-8").read()
        assert old in s, (old, p)
        open(p, "w", encoding="utf-8").write(s.replace(old, new, 1))
    return m


def gate_field(gid, field, old, new):
    """Text edit of one field inside one gate block (a human-style edit)."""
    def m(t):
        p = G(t); s = open(p, encoding="utf-8").read()
        i = s.index(f"id: {gid}\n"); j = s.index(f"{field}: {old}", i)
        open(p, "w", encoding="utf-8").write(s[:j] + f"{field}: {new}" + s[j + len(f'{field}: {old}'):])
    return m


def seq(*ms):
    def m(t):
        for f in ms: f(t)
    return m


def rm(*fs):
    def m(t):
        for f in fs: os.remove(J(t, f))
    return m


def empty(*fs):
    def m(t):
        for f in fs: open(J(t, f), "w").close()
    return m


# ── cases: (id, mutation, expectation) ───────────────────────────────────
# expectation keys: result · door · verdicts{gid: verdict} · reason (substring
# of the runner's error) · same_as ("A0": result and door equal A0's) · note.

def _malformed_reg(t):
    p = J(t, "FILE-REGISTRY.jsonl"); L = open(p).read().splitlines(); L[5] = L[5][:40]; open(p, "w").write("\n".join(L) + "\n")
def _trunc_partial(t):
    p = J(t, "THEORY-OBJECTS.jsonl"); s = open(p).read(); open(p, "w").write(s[: len(s) - 200])
def _trunc_both(t):
    for f in ("FILE-REGISTRY.jsonl", "THEORY-DISCOVERY-INDEX.jsonl"):
        p = J(t, f); wl(p, jl(p)[:30])
def _trunc_reg(t):
    p = J(t, "FILE-REGISTRY.jsonl"); wl(p, jl(p)[:30])
def _dup_reg(t):
    p = J(t, "FILE-REGISTRY.jsonl"); L = jl(p); x = dict(L[3]); x["path"] = "docs/knowledgeos/README.md"; L.append(x); wl(p, L)
def _dup_obj(t):
    p = J(t, "THEORY-OBJECTS.jsonl"); L = jl(p); L.append(dict(L[0])); wl(p, L)
def _dangling(t):
    p = J(t, "phase2_extraction/THEORY-OBJECT-RELATIONS.jsonl"); L = jl(p); L[0]["relations"]["probe"] = ["T-9999"]; wl(p, L)
def _dangling_md(t):
    open(J(t, "phase2_extraction/CANDIDATE-KNOWLEDGEOS-THEORY.md"), "a").write("\nsee T-9999 and F9999\n")
def _other_prefix(t):
    p = J(t, "phase2_extraction/THEORY-OBJECT-RELATIONS.jsonl"); L = jl(p); L[0]["relations"]["probe"] = ["HA-9999", "SI-9999", "C-9999", "TH-9999"]; wl(p, L)
def _idx_space(t):
    p = J(t, "THEORY-DISCOVERY-INDEX.jsonl"); L = jl(p); L[4]["file_id"] += " "; wl(p, L)
def _idx_extra(t):
    p = J(t, "THEORY-DISCOVERY-INDEX.jsonl"); L = jl(p); x = dict(L[0]); x["file_id"] = "F9998"; L.append(x); wl(p, L)
def _seed_3digit(t):
    open(J(t, "phase2_extraction/THEORY-SEED.md"), "a").write("\n| SI-044 | a new seed item with a malformed id |\n")
def _seed_body_only(t):
    open(J(t, "phase2_extraction/THEORY-SEED.md"), "a").write("\n| SI-0999 | new seed item |\n")
    q = J(t, "phase2_extraction/CANDIDATE-KNOWLEDGEOS-THEORY.md"); s = open(q).read()
    body, app = s.split("# Appendix · Trace", 1); open(q, "w").write(body + "\nSI-0999 mentioned in body only\n# Appendix · Trace" + app)
def _marker(t):
    q = J(t, "phase2_extraction/CANDIDATE-KNOWLEDGEOS-THEORY.md"); s = open(q).read(); open(q, "w").write(s.replace("# Appendix · Trace", "# Appendix - Trace"))
def _nonobject(t):
    open(J(t, "GAPS.jsonl"), "a").write("42\n")
def _binary(t):
    open(J(t, "FILE-REGISTRY.jsonl"), "wb").write(b"\xff\xfe\x00garbage\n")
def _swap(t):
    p = J(t, "FILE-REGISTRY.jsonl"); L = jl(p)
    a = next(x for x in L if x["file_id"] == "F0005"); b = next(x for x in L if x["file_id"] == "F0006")
    a["path"], b["path"] = b["path"], a["path"]; wl(p, L)
def _f0035(t):
    p = J(t, "FILE-REGISTRY.jsonl"); L = jl(p)
    next(x for x in L if x["file_id"] == "F0035")["path"] = "docs/knowledgeos/KnowledgeOS_Ontology_Discovery.md"; wl(p, L)
def _dup_gate(t):
    p = G(t); s = open(p).read()
    s = s.replace("\n  - id: KOS-G-010\n", "\n  - id: KOS-G-010\n    name: DUPLICATE-shadow\n    stage: PHASE_1\n    timing: POST\n    class: AUT\n    tier: B\n    status: active\n    purpose: x\n    source: {rule: x}\n    pass_condition: {type: deterministic, check: theory_doc_exists}\n    failure: {effect: WARN}\n    human_decision_required: false\n\n  - id: KOS-G-010\n", 1)
    open(p, "w").write(s)
def _no_fixtures(t): shutil.rmtree(J(t, "governance", "fixtures"))
def _all_proposed(t):
    for gid in ACT: gate_field(gid, "status", "active", "proposed")(t)
def _nested_malformed(t):
    open(J(t, "phase2_extraction/FORMALIZATION/structures/STRUCTURES.jsonl"), "a").write('{"id": "broken\n')
def _canonical_cite(t):
    p = J(t, "GAPS.jsonl"); L = jl(p); L[0]["notes"] = "see canonical F2837 (Conceptual_Foundation), not yet registered"; wl(p, L)
def _live_state_malformed(t):
    p = S(t); s = open(p).read(); open(p, "w").write(s.replace("\nactivated:\n", "\nactivated: [\n", 1))
def _e1(t):
    p = J(t, "THEORY-OBJECTS.jsonl"); L = jl(p)
    L[0]["statement"] = "OVERWRITTEN: " + str(L[0].get("statement", ""))[:40]; L[0]["status"] = "VALIDATED"; wl(p, L)
def _e2(t):
    p = J(t, "THEORY-OBJECTS.jsonl"); L = jl(p)
    L.append({"theory_object_id": "T-0099", "status": "RECOVERED", "object_type": "Proposition", "historical_sources": ["F0001"], "statement": "refines T-0001", "supersedes": "T-0001"}); wl(p, L)
    q = J(t, "phase2_extraction/THEORY-OBJECT-RELATIONS.jsonl"); R = jl(q)
    R.append({"object": "T-0099", "type": "Proposition", "relations": {"refines": ["T-0001"]}, "origin": "[C]"}); wl(q, R)
def _e3(t):
    p = J(t, "phase2_extraction/THEORY-EVOLUTION.jsonl"); L = jl(p); L[0]["old_formulation"] = "rewritten history"; wl(p, L)
def _e4(t):
    p = J(t, "phase2_extraction/THEORY-OBJECT-RELATIONS.jsonl"); L = jl(p); L.pop(3); wl(p, L)
def _f1(t):
    q = J(t, "phase2_extraction/CANDIDATE-KNOWLEDGEOS-THEORY.md"); s = open(q).read()
    open(q, "w").write(s.replace("not yet demonstrated", "demonstrated").replace("candidate", "established"))
def _f2(t):
    q = J(t, "phase2_extraction/THEORY-SEED.md"); s = open(q).read(); open(q, "w").write(s.replace("| **L2** |", "| **L4** |"))
def _runner_missing(t): os.remove(J(t, "governance", "gate-runner.py"))
def _bare_state(t): write_state(t, list(ACT))
def _wrong_pin(t):
    cur = pinned(t, ACT); cur[1] = (cur[1][0], "0000000000000000"); write_state(t, cur)
def _corrupt_expectation(t):
    d = J(t, "governance", "fixtures", "cases", "index_coverage", "missing_index")
    os.makedirs(d, exist_ok=True)
    open(J(d, "EXPECT.json"), "w").write('{"expect": "PASS", "canonical": "shared"}\n')
def _no_canonical(t): os.remove(J(os.path.dirname(t), "list_of_files_to_read.log"))
def _pin_roundtrip(t):
    write_state(t, list(ACT))
    r = subprocess.run([sys.executable, "governance/gate-runner.py", "--pin-lines"], cwd=t, capture_output=True, text=True)
    s = open(S(t)).read(); head = s[: s.index("\nactivated:") + 1]
    open(S(t), "w").write(head + "activated:\n" + r.stdout)


INC = "INCONCLUSIVE"
CASES = [
    ("A0",  None, dict(result=CLEAR, verdicts={g: "PASS" for g in ACT}, note="HEAD; G-003 known F-set = canonical list (L0-DEC-17)")),
    ("B01", rm("FILE-REGISTRY.jsonl"), dict(result=BLOCK, verdicts={"KOS-G-010": INC})),
    ("B02", rm("THEORY-DISCOVERY-INDEX.jsonl"), dict(result=BLOCK, verdicts={"KOS-G-010": INC})),
    ("B03", rm("FILE-REGISTRY.jsonl", "THEORY-DISCOVERY-INDEX.jsonl"), dict(result=BLOCK, verdicts={"KOS-G-010": INC})),
    ("B04", rm("phase2_extraction/THEORY-SEED.md"), dict(result=BLOCK, verdicts={"KOS-G-022": INC})),
    ("B05", rm("phase2_extraction/CANDIDATE-KNOWLEDGEOS-THEORY.md"), dict(result=BLOCK, verdicts={"KOS-G-022": INC})),
    ("B06", rm("THEORY-OBJECTS.jsonl"), dict(result=BLOCK, verdicts={"KOS-G-002": INC, "KOS-G-003": INC})),
    ("B07", empty("FILE-REGISTRY.jsonl"), dict(result=BLOCK, verdicts={"KOS-G-010": INC})),
    ("B08", empty("FILE-REGISTRY.jsonl", "THEORY-DISCOVERY-INDEX.jsonl"), dict(result=BLOCK, verdicts={"KOS-G-010": INC})),
    ("B09", empty("phase2_extraction/THEORY-SEED.md"), dict(result=BLOCK, verdicts={"KOS-G-022": INC})),
    ("B10", _malformed_reg, dict(result=BLOCK, verdicts={"KOS-G-001": "FAIL"})),
    ("B11", _trunc_partial, dict(result=BLOCK, verdicts={"KOS-G-001": "FAIL"})),
    ("B12", _trunc_both, dict(same_as="A0", note="consistent truncation is undetectable by any activated gate (known limit; 1b)")),
    ("B13", _trunc_reg, dict(same_as="A0", note="registry truncation leaves index a superset; not a G-010 violation (known limit)")),
    ("B14", _dup_reg, dict(same_as="A0", note="duplicate file_id in the registry is outside G-002's declared scope (known limit)")),
    ("B15", _dup_obj, dict(result=BLOCK, verdicts={"KOS-G-002": "FAIL"})),
    ("B16", _dangling, dict(result=BLOCK, verdicts={"KOS-G-003": "FAIL"})),
    ("B17", _dangling_md, dict(same_as="A0", note="IC-5: G-003 declared scope narrowed to .jsonl artifacts; markdown is out of scope and says so")),
    ("B18", _other_prefix, dict(same_as="A0", note="HA-/SI-/C-/TH- prefixes are outside G-003's declared T-/G-/F- scope")),
    ("B19", _idx_space, dict(result=BLOCK, verdicts={"KOS-G-010": "FAIL"})),
    ("B20", _idx_extra, dict(result=BLOCK, verdicts={"KOS-G-003": "FAIL"}, note="F9998 is not in the canonical list")),
    ("B21", _seed_3digit, dict(same_as="A0", note="malformed seed id silently excluded (known limit, outside 1a)")),
    ("B22", _seed_body_only, dict(result=BLOCK, verdicts={"KOS-G-022": "FAIL"})),
    ("B23", _marker, dict(result=BLOCK, verdicts={"KOS-G-022": "FAIL"})),
    ("B24", _nonobject, dict(result=BLOCK, verdicts={"KOS-G-003": "ERROR"})),
    ("B25", _binary, dict(result=BLOCK, verdicts={"KOS-G-001": "ERROR", "KOS-G-010": "ERROR"})),
    ("C1",  _swap, dict(same_as="A0", note="registry/canonical binding is 1b's job; 1a does not detect it (stated per 1a.5)")),
    ("C2",  _f0035, dict(same_as="A0")),
    ("D01", edit(G, "gates:\n", "gates: [\n"), dict(result=INOP, reason="gates.yaml")),
    ("D02b", _live_state_malformed, dict(result=INOP, reason="governance-state.yaml")),
    ("D03", lambda t: write_state(t, pinned(t, ACT) + ["KOS-G-099"]), dict(result=INOP, reason="KOS-G-099")),
    ("D04", _dup_gate, dict(result=INOP, reason="duplicate")),
    ("D05", gate_field("KOS-G-010", "check", "index_coverage", "index_coverage_v2"), dict(result=INOP, reason="KOS-G-010: pin mismatch")),
    ("D06", _no_fixtures, dict(result=INOP, reason="self-test")),
    ("D07", edit(lambda t: J(t, "governance", "gate-schema.yaml"), "schema:", "schema: [[[ corrupted"), dict(result=INOP, reason="gate-schema.yaml")),
    ("D08", gate_field("KOS-G-010", "status", "active", "proposed"), dict(result=INOP, reason="KOS-G-010")),
    ("D09", _all_proposed, dict(result=INOP, reason="status")),
    ("D10", seq(gate_field("KOS-G-002", "tier", "A", "B"), _dup_obj), dict(result=INOP, reason="KOS-G-002: pin mismatch")),
    ("D11", gate_field("KOS-G-002", "class", "AUT", "REVIEW"), dict(result=INOP, reason="KOS-G-002")),
    ("D12", lambda t: write_state(t, pinned(t, ACT) + ["KOS-G-027"]), dict(result=INOP, reason="unpinned")),
    ("D13", lambda t: write_state(t, []), dict(result=NOACT)),
    ("D14", add_activation("KOS-G-040"), dict(result=INOP, reason="KOS-G-040")),
    ("D15", add_activation("KOS-G-060"), dict(result=INOP, reason="KOS-G-060")),
    ("D16", add_activation("KOS-G-041"), dict(result=INOP, reason="KOS-G-041")),
    ("D17", add_activation("KOS-G-047"), dict(result=INOP, reason="KOS-G-047")),
    ("D18", _runner_missing, dict(result="RUNNER_ABSENT", door=3)),
    ("E1",  _e1, dict(same_as="A0", note="RCI-015 append-only: no activated gate covers it (outside 1a)")),
    ("E2",  _e2, dict(same_as="A0")),
    ("E3",  _e3, dict(same_as="A0", note="outside 1a")),
    ("E4",  _e4, dict(same_as="A0", note="relation-row deletion of a non-last object is outside activated coverage")),
    ("F1",  _f1, dict(same_as="A0", note="epistemic-status inflation: no gate (outside 1a)")),
    ("F2",  _f2, dict(same_as="A0", note="outside 1a")),
    ("G01", _nested_malformed, dict(result=BLOCK, verdicts={"KOS-G-001": "FAIL"}, note="IC-5: G-001 scans every .jsonl recursively")),
    ("G02", _canonical_cite, dict(same_as="A0", verdicts={"KOS-G-003": "PASS"}, note="GIA-5: a correct canonical id is known")),
    # ── new in 1a (pinning, canonical list) ──
    ("U1",  _bare_state, dict(result=INOP, reason="unpinned", note="the committed bare activations: INOPERATIVE until the human re-activates with pins (L0-DEC-16 note)")),
    ("U2",  _wrong_pin, dict(result=INOP, reason="KOS-G-002: pin mismatch")),
    ("U3",  _no_canonical, dict(result=BLOCK, verdicts={"KOS-G-003": INC}, note="unreadable canonical list => INCONCLUSIVE (L0-DEC-17 note)")),
    ("U4",  _pin_roundtrip, dict(same_as="A0", note="--pin-lines output pasted by the human re-activates cleanly")),
    ("U5",  _corrupt_expectation, dict(result=INOP, reason="self-test", note="a self-test failure makes governance inoperative (IC-4)")),
    ("U6",  gate_field("KOS-G-003", "status", "active", "proposed"), dict(result=INOP, reason="KOS-G-003", note="D09 reduced to the live failing gate")),
    # ── L0-DEC-21 correction slice (written before the fix) ──
    ("R1",  add_activation("KOS-G-025"), dict(result=INOP, reason="KOS-G-025: tier B",
             note="IR-G1: a pinned tier-B activation is refused; an advisory gate cannot block, so its failure would read as CLEAR")),
    ("R2",  seq(gate_field("KOS-G-010", "check", "index_coverage", "index_coverage_v2"), lambda t: write_state(t, pinned(t, ACT))),
             dict(result=INOP, reason="KOS-G-010: check index_coverage_v2 not implemented",
             note="IR-G4: re-pinned after the edit, so only the missing implementation remains; INOPERATIVE, not BLOCK")),
    ("R3",  None, dict(result=INOP, stage="PHASE_9", reason="stage", note="IR-G2: a stage outside the schema enum is refused")),
    ("R4",  None, dict(result=INOP, stage="PHASE_1", timing="LATER", reason="timing", note="IR-G2: a timing outside the schema enum is refused")),
    ("R5",  None, dict(result=NOACT, stage="EXPERIMENT", door_forbid=["NO BLOCKING CONTROLS HAVE BEEN ACTIVATED"],
             note="IR-G2: valid stage, no activated gate applies; the door must not claim nothing is activated")),
    ("R6",  None, dict(result=CLEAR, stage="PHASE_1", verdicts={"KOS-G-010": "PASS"}, note="control: a valid stage that selects an activated gate")),
]


# ── execution ────────────────────────────────────────────────────────────

def snapshot(rev, control, dest):
    top = subprocess.run(["git", "rev-parse", "--show-toplevel"], cwd=WS, capture_output=True, text=True, check=True).stdout.strip()
    rel = os.path.relpath(WS, top); canon = J(os.path.dirname(rel), "list_of_files_to_read.log")
    tar = J(dest, "s.tar")
    subprocess.run(["git", "archive", "-o", tar, rev, "--", rel, canon], cwd=top, check=True)
    subprocess.run(["tar", "-xf", tar, "-C", dest], check=True); os.remove(tar)
    base = J(dest, rel)
    if control == "worktree":
        for sub in ("governance", J(".claude", "hooks")):
            src, dst = J(WS, sub), J(base, sub)
            if os.path.isdir(dst): shutil.rmtree(dst)
            shutil.copytree(src, dst, ignore=shutil.ignore_patterns("__pycache__"))
    return base


def run_case(base, cid, mutate, work, exp=None):
    exp = exp or {}
    d = tempfile.mkdtemp(prefix=cid + ".", dir=work)
    t = J(d, "ws"); shutil.copytree(base, t)
    shutil.copy(J(os.path.dirname(base), "list_of_files_to_read.log"), J(d, "list_of_files_to_read.log"))
    write_state(t, pinned(t, ACT))
    if mutate: mutate(t)
    # optional stage/timing, passed identically to the runner and the door
    door_args = [x for x in (exp.get("stage"), exp.get("timing")) if x is not None]
    runner_args = (["--stage", exp["stage"]] if exp.get("stage") is not None else []) + \
                  (["--timing", exp["timing"]] if exp.get("timing") is not None else [])
    out = {"case": cid}
    if os.path.exists(J(t, "governance", "gate-runner.py")):
        r = subprocess.run([sys.executable, "governance/gate-runner.py", "--json"] + runner_args, cwd=t, capture_output=True, text=True)
        out["runner_exit"] = r.returncode
        try:
            j = json.loads(r.stdout)
            out["result"] = j.get("result"); out["error"] = j.get("error", "")
            out["verdicts"] = {x["id"]: x["verdict"] for x in j.get("rows", []) if x["id"] in ACT}
        except Exception:
            out["result"] = "UNPARSEABLE"; out["error"] = (r.stdout + r.stderr)[-300:]; out["verdicts"] = {}
    else:
        out.update(result="RUNNER_ABSENT", error="", verdicts={})
    p = subprocess.run(["bash", ".claude/hooks/governance-preflight.sh"] + door_args, cwd=t, capture_output=True, text=True)
    out["door_exit"] = p.returncode
    out["door_text"] = p.stdout + p.stderr
    shutil.rmtree(d, ignore_errors=True)
    return out


def judge(out, exp, a0):
    want_res = a0["result"] if exp.get("same_as") else exp["result"]
    want_door = a0["door_exit"] if exp.get("same_as") else exp.get("door", DOOR.get(want_res))
    miss = []
    if out["result"] != want_res: miss.append(f"result {out['result']} != {want_res}")
    if out["door_exit"] != want_door: miss.append(f"door {out['door_exit']} != {want_door}")
    for g, v in (exp.get("verdicts") or {}).items():
        if out["verdicts"].get(g) != v: miss.append(f"{g} {out['verdicts'].get(g)} != {v}")
    if exp.get("reason") and exp["reason"].lower() not in (out.get("error") or "").lower():
        miss.append(f"reason lacks '{exp['reason']}'")
    for s in exp.get("door_forbid") or []:
        if s in out.get("door_text", ""):
            miss.append(f"door says {s!r}")
    return miss


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--rev", default="HEAD")
    ap.add_argument("--control", choices=("worktree", "rev"), default="worktree")
    ap.add_argument("--only")
    ap.add_argument("--json", action="store_true")
    a = ap.parse_args()
    work = tempfile.mkdtemp(prefix="kos-regression.")
    try:
        base = snapshot(a.rev, a.control, work)
        todo = [c for c in CASES if not a.only or c[0] in a.only.split(",") or c[0] == "A0"]
        a0, bad = None, 0
        for cid, mut, exp in todo:
            out = run_case(base, cid, mut, work, exp)
            if cid == "A0": a0 = out
            miss = judge(out, exp, a0)
            bad += bool(miss)
            if a.json:
                print(json.dumps({**out, "expected": {k: v for k, v in exp.items() if k != 'note'}, "match": not miss, "mismatch": miss}, ensure_ascii=False))
            else:
                print(f"  [{'OK ' if not miss else 'RED'}] {cid:<5} result={out['result']:<30} door={out['door_exit']}  {'; '.join(miss)}")
        print(f"\nregression: {len(todo) - bad}/{len(todo)} match  (rev={a.rev}, control={a.control})")
        return 1 if bad else 0
    finally:
        shutil.rmtree(work, ignore_errors=True)


if __name__ == "__main__":
    sys.exit(main())
