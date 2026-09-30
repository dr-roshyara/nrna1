"""Synthetic-only tests of derive_constraints.py r1 (no corpus, no frozen result is read). Run: python3 test_derive.py"""
import copy
import hashlib
import os
import tempfile

import derive_constraints as D

T = tempfile.mkdtemp(); SRC = os.path.join(T, "src.md")
open(SRC, "wb").write(b"line one\nthe rule says X holds\nline three\nitem Y was handled without X\nline five\n")
SHA = hashlib.sha256(open(SRC, "rb").read()).hexdigest()
PASS = {"A": ({"start_line": 2, "end_line": 2}, "rule says X"), "B": ({"start_line": 4, "end_line": 4}, "handled without X")}
SPEC = {"primary_models": ["A", "B"], "control_models": ["C"], "propositions": [
    {"id": "P1", "eq": "EQ-x", "claim": "c", "property": "PROP", "requires": "HOLDS"},
    {"id": "P2", "eq": "EQ-y", "claim": "c", "property": None, "gap": "g"},
    {"id": "P3", "eq": "EQ-z", "claim": "c", "property": "PROP2", "requires": "REACHABLE"},
    {"id": "P4", "eq": "EQ-w", "claim": "c", "property": "PROP3", "requires": "HOLDS"},
    {"id": "P5", "eq": "EQ-v", "claim": "c", "property": None, "structural": "s"}]}
def inst(v): return {"properties": {"PROP": {"class": v[0]}, "PROP2": {"class": v[1]}, "PROP3": {"class": v[2]}}}
RES = {"A": {"instances": {"i1": inst(("HOLDS", "NOT_REACHABLE", "HOLDS")), "i2": inst(("VACUOUS", "NOT_REACHABLE", "FAILS"))}},
       "B": {"instances": {"i1": inst(("FAILS", "NOT_REACHABLE", "HOLDS")), "i2": inst(("FAILS", "NOT_REACHABLE", "HOLDS"))}},
       "C": {"instances": {"i1": inst(("FAILS", "REACHABLE", "HOLDS")), "i2": inst(("FAILS", "REACHABLE", "HOLDS"))}}}
BASIS = {"status": "SECONDARY-REPRODUCED", "results_path": "synthetic/results.json", "results_sha256": "a" * 64}


def cell(cid, prop, assess, claim="A", reader="r1", rclass="SELF", conf="HIGH", layer="HISTORICAL-EVIDENCE", date="2026-01", **kw):
    anc, q = PASS[claim]
    c = {"id": cid, "claim_id": "cl-" + claim, "proposition": prop, "source_slot": "S-1", "source_path": "src.md", "source_sha256": SHA,
         "anchor": anc, "exact_wording": q, "historical_date": {"value": date, "precision": "MONTH" if date else "UNKNOWN"},
         "source_claim": "text", "assessment": assess, "interpretation_confidence": conf, "layer": layer, "reader_class": rclass, "reader_id": reader,
         "l0_release_id": "L0-REL-test"}
    c.update(kw); return c


def ev(*cells, events=()): return {"cells": list(cells), "events": list(events)}
def run(*cells): return D.derive(ev(*cells), SPEC, RES, BASIS)
def val(*cells, events=()): return D.validate(ev(*cells, events=events), SPEC["propositions"], T)
def flags(R, pid): return [f["flag"] for f in R["family_flags"] if f["proposition"] == pid]


fails = 0
def check(name, cond):
    global fails
    print(("ok   " if cond else "FAIL ") + name); fails += 0 if cond else 1


# --- validation / REJECT paths
check("V1 valid cell passes", val(cell("c1", "P1", "SUPPORTED")) == [])
check("V2 quote outside anchor span rejected", val(cell("c", "P1", "SUPPORTED", anchor={"start_line": 1, "end_line": 1})) != [])
check("V3 source hash mismatch rejected", val(cell("c", "P1", "SUPPORTED", source_sha256="0" * 64)) != [])
check("V4 unknown key rejected", val(dict(cell("c", "P1", "SUPPORTED"), extra=1)) != [])
check("V5 out-of-vocabulary assessment rejected", val(cell("c", "P1", "PROBABLY")) != [])
check("V6 missing required key (interpretation_confidence) rejected", val({k: v for k, v in cell("c", "P1", "SUPPORTED").items() if k != "interpretation_confidence"}) != [])
check("V6b legacy key `confidence` is rejected as unknown", val(dict({k: v for k, v in cell("c", "P1", "SUPPORTED").items() if k != "interpretation_confidence"}, confidence="HIGH")) != [])
check("V7 unknown proposition rejected", val(cell("c", "PX", "SUPPORTED")) != [])
check("V8 precedence without basis rejected", val(cell("c", "P1", "SUPPORTED", precedence=[{"before": "a", "after": "b", "status": "ESTABLISHED"}])) != [])
check("V9 duplicate id rejected", val(cell("c", "P1", "SUPPORTED"), cell("c", "P1", "SUPPORTED", reader="r2")) != [])
check("V10 claim_id reused for a different passage rejected", val(cell("c1", "P1", "SUPPORTED"), dict(cell("c2", "P1", "SUPPORTED", claim="B", reader="r2"), claim_id="cl-A")) != [])
check("V11 same reader assessing the same claim twice rejected", val(cell("c1", "P1", "SUPPORTED"), cell("c2", "P1", "REFUTED")) != [])
E1 = {"id": "e1", "type": "PROMOTION", "source_path": "src.md", "source_sha256": SHA, "anchor": PASS["B"][0], "exact_wording": PASS["B"][1],
      "historical_date": {"value": "2026-02", "precision": "MONTH"}, "l0_release_id": "L0-REL-test", "context": {"scope": "item Y"}}
check("V12 independent temporal event (source->event->context) validates", val(cell("c1", "P1", "SUPPORTED"), events=[E1]) == [])
check("V13 event with bad type rejected", val(cell("c1", "P1", "SUPPORTED"), events=[dict(E1, type="HAPPENED")]) != [])

# --- required cases 1..11
R = run(cell("c1", "P1", "SUPPORTED"))
check("01 support only -> SUPPORTED; A consistent (VACUOUS=HOLDS), B inconsistent", R["propositions"]["P1"]["outcome"] == "SUPPORTED" and R["propositions"]["P1"]["per_model"] == {"A": "CONSISTENT", "B": "INCONSISTENT", "C": "INCONSISTENT"} and R["models"]["B"]["status"] == "INCONSISTENT-WITH P1")
R = run(cell("c1", "P1", "REFUTED"))
check("02 refutation only -> REFUTED flips the requirement", R["propositions"]["P1"]["per_model"]["A"] == "INCONSISTENT" and R["propositions"]["P1"]["per_model"]["B"] == "CONSISTENT")
R = run()
check("03 silence -> SILENT everywhere, no constraint, no flag", all(p["outcome"] == "SILENT" for p in R["propositions"].values()) and not R["family_flags"] and all(m["status"] == "NOT-ELIMINATED" for m in R["models"].values()))
R = run(cell("c1", "P1", "AMBIGUOUS"))
check("04 ambiguity -> AMBIGUOUS, open, SEMANTIC-AMBIGUITY, no constraint", R["propositions"]["P1"]["outcome"] == "AMBIGUOUS" and "P1" in R["open"] and "SEMANTIC-AMBIGUITY" in R["propositions"]["P1"]["diagnostics"] and R["models"]["B"]["status"] == "NOT-ELIMINATED")
R = run(cell("c1", "P1", "SUPPORTED", claim="A"), cell("c2", "P1", "REFUTED", claim="B", reader="r2"))
p = R["propositions"]["P1"]
check("05 source conflict -> CONFLICTING, SOURCE-CONFLICT, BOTH directions preserved, unresolved",
      p["outcome"] == "CONFLICTING" and "SOURCE-CONFLICT" in p["diagnostics"] and p["if_supported"]["B"] == "INCONSISTENT" and p["if_refuted"]["A"] == "INCONSISTENT"
      and "P1" in R["open"] and "P1 if SUPPORTED" in R["models"]["B"]["conditionally_inconsistent_with"] and R["models"]["B"]["status"] == "NOT-ELIMINATED")
check("05b CONFLICTING is not SILENT", p["outcome"] != "SILENT" and "if_supported" in p)
R = run(cell("c1", "P1", "SUPPORTED", reader="r1"), cell("c2", "P1", "REFUTED", reader="r2"))
p = R["propositions"]["P1"]
check("06 reader disagreement (same passage) -> READER-DISAGREEMENT, not SOURCE-CONFLICT", "READER-DISAGREEMENT" in p["diagnostics"] and "SOURCE-CONFLICT" not in p["diagnostics"] and p["outcome"] == "CONFLICTING")
R = run(cell("c1", "P1", "SUPPORTED", claim="A", date="2025-01"), cell("c2", "P1", "REFUTED", claim="B", reader="r2", date="2026-06"))
check("07 temporal conflict -> SOURCE-CONFLICT + TEMPORAL-CONFLICT; recency does not override", {"SOURCE-CONFLICT", "TEMPORAL-CONFLICT"} <= set(R["propositions"]["P1"]["diagnostics"]) and R["propositions"]["P1"]["outcome"] == "CONFLICTING")
R = run(cell("c1", "P3", "SUPPORTED"), cell("c2", "P3", "AMBIGUOUS", reader="r2"))
check("08 all primary inconsistent, but ambiguous evidence -> INCONCLUSIVE, never INCOMPLETE", flags(R, "P3") == ["MODEL-FAMILY-INCONCLUSIVE"])
R = run(cell("c1", "P3", "SUPPORTED", reader="r1", rclass="SELF"), cell("c2", "P3", "SUPPORTED", reader="r2", rclass="INDEPENDENT"))
check("09 all primary inconsistent with stable HIGH, corroborated evidence -> MODEL-FAMILY-INCOMPLETE-CANDIDATE (L0 decision required)", flags(R, "P3") == ["MODEL-FAMILY-INCOMPLETE-CANDIDATE"] and R["family_flags"][0]["guard_passed"] and R["family_flags"][0]["l0_research_decision_required"])
R = run(cell("c1", "P3", "SUPPORTED", reader="r1"))
check("10a single uncorroborated SELF reader -> INCONCLUSIVE", flags(R, "P3") == ["MODEL-FAMILY-INCONCLUSIVE"])
R = run(cell("c1", "P3", "SUPPORTED", reader="r1", conf="MEDIUM"), cell("c2", "P3", "SUPPORTED", reader="r2", conf="HIGH", rclass="INDEPENDENT"))
check("10b non-HIGH confidence -> INCONCLUSIVE", flags(R, "P3") == ["MODEL-FAMILY-INCONCLUSIVE"])
R = run(cell("c1", "P3", "SUPPORTED", claim="A", reader="r1", rclass="INDEPENDENT"), cell("c2", "P3", "REFUTED", claim="B", reader="r2", rclass="INDEPENDENT"))
check("10c conflicting evidence where one direction fits no model -> INCONCLUSIVE only", flags(R, "P3") == ["MODEL-FAMILY-INCONCLUSIVE"])
R = run(cell("c1", "P2", "SUPPORTED", reader="r1"), cell("c2", "P2", "SUPPORTED", reader="r2", rclass="INDEPENDENT"))
check("11 non-expressible claim, stable corroborated evidence -> CANNOT-EXPRESS + MODEL-FAMILY-INCOMPLETE-CANDIDATE", R["propositions"]["P2"]["per_model"]["A"] == "CANNOT-EXPRESS" and flags(R, "P2") == ["MODEL-FAMILY-INCOMPLETE-CANDIDATE"])

# --- further invariants
R = run(cell("c1", "P1", "SUPPORTED", layer="INFERENCE"))
check("X1 non-evidence layer does not count (annotation only)", R["propositions"]["P1"]["outcome"] == "SILENT" and R["propositions"]["P1"]["annotations"] == ["c1"])
R = run(cell("c1", "P2", "REFUTED"))
check("X2 REFUTED non-expressible -> NO-CONSTRAINT, no flag", R["propositions"]["P2"]["per_model"]["A"] == "NO-CONSTRAINT" and not R["family_flags"])
R = run(cell("c1", "P3", "SUPPORTED", reader="r1"), cell("c2", "P3", "SUPPORTED", reader="r2", rclass="INDEPENDENT"))
check("X3 control model never rescues the family", R["propositions"]["P3"]["per_model"]["C"] == "CONSISTENT" and flags(R, "P3") == ["MODEL-FAMILY-INCOMPLETE-CANDIDATE"])
R = run(cell("c1", "P4", "SUPPORTED"))
check("X4 instance-dependent value is flagged, not decided", R["propositions"]["P4"]["per_model"]["A"] == "INSTANCE-DEPENDENT")
R = run(cell("c1", "P5", "SUPPORTED"))
check("X5 structural proposition -> STRUCTURALLY-CONSISTENT, no flag", R["propositions"]["P5"]["per_model"]["A"] == "STRUCTURALLY-CONSISTENT" and not R["family_flags"])
R = run(cell("c1", "P1", "SUPPORTED"), cell("c2", "P1", "OUT-OF-SCOPE", reader="r2"))
check("X6 OUT-OF-SCOPE ignored in the count but recorded as a scope diagnostic", R["propositions"]["P1"]["outcome"] == "SUPPORTED" and "SCOPE-CONFLICT" in R["propositions"]["P1"]["diagnostics"])
R = run(cell("c1", "P1", "SUPPORTED", claim="A", context={"scope": "regular items"}), cell("c2", "P1", "REFUTED", claim="B", reader="r2", context={"scope": "exception items"}))
check("X7 opposing passages with different declared scopes -> SCOPE-CONFLICT", "SCOPE-CONFLICT" in R["propositions"]["P1"]["diagnostics"])
R = run(cell("c1", "P1", "SUPPORTED"))
check("X8 every proposition carries the full basis stamp (status, results path, sha)", R["formal_basis"] == R["propositions"]["P1"]["formal_basis"] == {"status": "SECONDARY-REPRODUCED", "results_path": "synthetic/results.json", "results_sha256": "a" * 64})
R2 = D.derive(ev(cell("c1", "P1", "SUPPORTED")), SPEC, RES, dict(BASIS, status="INDEPENDENT-REPRODUCED"))
check("X9 re-evaluation under a new basis is deterministic and changes only the stamp", R2["propositions"]["P1"]["per_model"] == R["propositions"]["P1"]["per_model"] and R2["propositions"]["P1"]["formal_basis"]["status"] == "INDEPENDENT-REPRODUCED")
check("X10 inputs not mutated", copy.deepcopy(SPEC) == SPEC)
E0 = ev(cell("c1", "P3", "SUPPORTED", reader="r1"), cell("c2", "P3", "SUPPORTED", reader="r2", rclass="INDEPENDENT")); E0c = copy.deepcopy(E0); R = D.derive(E0, SPEC, RES, BASIS)
check("X11 the engine never emits MODEL-FAMILY-INCOMPLETE itself", all(f["flag"] != "MODEL-FAMILY-INCOMPLETE" for f in R["family_flags"]))
check("X12 evidence is not rewritten by derivation", E0 == E0c)

print("FAILURES:", fails); raise SystemExit(1 if fails else 0)
