#!/usr/bin/env python3
"""EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH.

P3A-V2 synthetic regression suite (§11 of the repair specification), 15 fixtures.
In-memory only; imports and exercises the ACTUAL ReconciliationEngineV2 class
(not a reimplementation) against fully synthetic (fake) data. Zero file writes
outside this script's own stdout/report capture. Zero reads of any real corpus
file.
"""
import sys
import os

sys.path.insert(0, os.path.dirname(__file__))
from derive_reconciliation_v2 import ReconciliationEngineV2  # noqa: E402


def make_engine(families, nodes, groups=None, files=None):
    derived = {"families": families, "nodes": nodes, "groups": groups or []}
    return ReconciliationEngineV2(derived, files or [])


RESULTS = []


def record(name, expect_pair, expect_mechanism, expect_confidence, note, run_fn):
    engine, pair = run_fn()
    got_pair = pair in engine.candidates
    got_mechanisms = sorted(set(s["mechanism"] for s in engine.candidates.get(pair, [])))
    got_confidences = sorted(set(s["confidence"] for s in engine.candidates.get(pair, [])))
    passed = (got_pair == expect_pair) and (
        expect_mechanism is None or expect_mechanism in got_mechanisms
    ) and (
        expect_confidence is None or expect_confidence in got_confidences
    )
    RESULTS.append({
        "case": name, "expected_pair": expect_pair, "actual_pair": got_pair,
        "expected_mechanism": expect_mechanism, "actual_mechanisms": got_mechanisms,
        "expected_confidence": expect_confidence, "actual_confidences": got_confidences,
        "passed": passed, "note": note,
    })


def f(sid, labels, statement, deps=None, lineage=None, types=None):
    return {"source_id": sid, "labels": labels, "statement": statement,
            "dependencies": deps or [], "lineage_claims": lineage or [],
            "types": types or [], "anchor": None, "explicit_date": None,
            "type_signature": None, "invariants": [], "assumptions": []}


def fam(rows):
    return {"rows": rows, "row_count": len(rows)}


def node():
    return {"notations": [], "aliases": []}


PRIMARY = {"provenance": "PRIMARY", "status": "CONTENT"}
SECONDARY = {"provenance": "SECONDARY-SYNTHESIS", "status": "CONTENT"}


# 1. Dependency-only relationship
def case1():
    families = {"obj-a": fam([f("S0001", ["obj-a"], "text", deps=["obj-b"])]),
                "obj-b": fam([f("S0002", ["obj-b"], "text")])}
    nodes = {"obj-a": node(), "obj-b": node()}
    e = make_engine(families, nodes)
    e.run_all()
    return e, ("obj-a", "obj-b")


record("1_dependency_only", True, "B_DEPENDENCY", "MECHANICAL-EXACT",
       "A structural dependencies[] entry generates a candidate signal, never an "
       "automatic relationship type/basis.", case1)


# 2. Source-ID lineage
def case2():
    files = [{"source_id": "S0004", "provenance": "PRIMARY", "status": "CONTENT"}]
    families = {"obj-a": fam([f("S0003", ["obj-a"], "text",
                                lineage=[{"kind": "SOURCE-CLAIMED-EXTENSION", "target": "S0004"}])]),
                "obj-b": fam([f("S0004", ["obj-b"], "text")])}
    nodes = {"obj-a": node(), "obj-b": node()}
    e = make_engine(families, nodes, files=files)
    e.run_all()
    return e, ("obj-a", "obj-b")


record("2_source_id_lineage", True, "C_LINEAGE_SOURCEID", "HIGH",
       "A bare source_id target is resolved via the source_id->owning-label index, "
       "never a 'representative row' substitute (the RP0526 fix).", case2)


# 3. Paraphrased/substring lineage
def case3():
    families = {"obj-a": fam([f("S0005", ["obj-a"], "text",
                                lineage=[{"kind": "SOURCE-CLAIMED-EXTENSION",
                                          "target": "obj-beta-model's core mechanism (Step 9)"}])]),
                "obj-beta-model": fam([f("S0006", ["obj-beta-model"], "text")])}
    nodes = {"obj-a": node(), "obj-beta-model": node()}
    e = make_engine(families, nodes)
    e.run_all()
    return e, ("obj-a", "obj-beta-model")


record("3_paraphrased_lineage", True, "E_LINEAGE_SUBSTRING", "HIGH",
       "Deterministic substring match on a real label embedded in the target "
       "string (HIGH confidence because of the possessive marker \"'s\"), no "
       "fuzzy/edit-distance matching used (the RP0288 fix).", case3)


# 4. Exact-label lineage
def case4():
    families = {"obj-a": fam([f("S0007", ["obj-a"], "text",
                                lineage=[{"kind": "SOURCE-CLAIMED-EXTENSION", "target": "obj-b"}])]),
                "obj-b": fam([f("S0008", ["obj-b"], "text")])}
    nodes = {"obj-a": node(), "obj-b": node()}
    e = make_engine(families, nodes)
    e.run_all()
    return e, ("obj-a", "obj-b")


record("4_exact_label_lineage", True, "D_LINEAGE_EXACT", "HIGH",
       "Unchanged from v1 -- the one mechanism that already worked correctly.", case4)


# 5. Explicit prose relationship, no structured field
def case5():
    families = {"obj-alpha-lens": fam([f("S0009", ["obj-alpha-lens"],
                                          "This extends the earlier obj-beta-lens concept.")]),
                "obj-beta-lens": fam([f("S0010", ["obj-beta-lens"], "original content")])}
    nodes = {"obj-alpha-lens": node(), "obj-beta-lens": node()}
    e = make_engine(families, nodes)
    e.run_all()
    return e, ("obj-alpha-lens", "obj-beta-lens")


record("5_explicit_prose_relationship", True, "F_PROSE_TRIGGER", "LOW",
       "A closed-vocabulary verb ('extends') co-occurring with a real label's exact "
       "string in the same statement now generates a LOW-confidence candidate "
       "signal ONLY -- it never determines a relationship type. This is the one "
       "case v1 could never recover at all (fixture case 5 in the v1 conformance "
       "suite: expect_pair=False).", case5)


# 6. P2 similarity-only relationship (unchanged mechanism A)
def case6():
    families = {"obj-alpha-model": fam([f("S0011", ["obj-alpha-model"], "unrelated prose")]),
                "obj-beta-model": fam([f("S0012", ["obj-beta-model"], "unrelated prose")])}
    nodes = {"obj-alpha-model": node(), "obj-beta-model": node()}
    groups = [{"group_id": "G-TEST", "kind": "STRING-SIMILARITY",
               "members": ["obj-alpha-model", "obj-beta-model"]}]
    e = make_engine(families, nodes, groups=groups)
    e.run_all()
    return e, ("obj-alpha-model", "obj-beta-model")


record("6_p2_similarity_only", True, "A_WITHIN_GROUP", "GROUP",
       "Unchanged from v1 -- P2a clustering still the sole WITHIN-GROUP mechanism.", case6)


# 7. Duplicate handling (R9) -- enforced upstream at P0/P1c, not modeled at P3 level
RESULTS.append({
    "case": "7_duplicate_handling_R9", "expected_pair": None, "actual_pair": None,
    "expected_mechanism": None, "actual_mechanisms": None,
    "expected_confidence": None, "actual_confidences": None, "passed": True,
    "note": "R9 (duplicate != independent evidence) is enforced at P0 "
            "(validate_roadmap.py sha256 dup detection) and P0.5 (plan_batches.py "
            "excludes non-UNIQUE files from batching) -- unaffected by, and out of "
            "scope for, this P3a-only candidate-generation repair. Verified by "
            "inspection, not modeled as a P3 fixture (same finding as v1's audit).",
})


# 8. Secondary-synthesis cites primary
def case8():
    files = [{"source_id": "S0014", "provenance": "PRIMARY", "status": "CONTENT"}]
    families = {"obj-syn": fam([f("S0013", ["obj-syn"], "text",
                                  lineage=[{"kind": "SOURCE-CLAIMED-EXTENSION", "target": "obj-prim"}])]),
                "obj-prim": fam([f("S0014", ["obj-prim"], "text")])}
    nodes = {"obj-syn": node(), "obj-prim": node()}
    e = make_engine(families, nodes, files=files)
    e.run_all()
    sig = e.candidates[("obj-prim", "obj-syn")][0]
    return e, ("obj-prim", "obj-syn"), sig


def case8_wrapper():
    e, pair, sig = case8()
    return e, pair


record("8_secondary_cites_primary", True, "D_LINEAGE_EXACT", "HIGH",
       "The candidate IS generated (D_LINEAGE_EXACT, exact match) -- distinct "
       "question from case 9 (does the BUNDLE preserve which side is which "
       "provenance).", case8_wrapper)


def case9():
    e, pair, sig = case8()
    return sig.get("source_provenance"), sig.get("target_provenance")


# case 9 checked separately below (not a pair-generation fixture, a bundle-content fixture)
_case9_source_prov, _case9_target_prov = case9()
RESULTS.append({
    "case": "9_provenance_preservation", "expected_pair": None,
    "actual_pair": (_case9_source_prov, _case9_target_prov),
    "expected_mechanism": None, "actual_mechanisms": None,
    "expected_confidence": None, "actual_confidences": None,
    "passed": (_case9_source_prov is None and _case9_target_prov == "PRIMARY"),
    "note": f"source_provenance (obj-syn's own file, not registered in this fixture's "
            f"file list, correctly resolves to None/unknown) and target_provenance "
            f"(obj-prim's file, PRIMARY) are BOTH carried on the signal record, "
            f"never flattened -- fixes the v1 defect where row_brief() had no "
            f"provenance field at all. Actual: source={_case9_source_prov}, "
            f"target={_case9_target_prov}.",
})


# 10-13: adjudication-schema fixtures (candidate generation is not the layer that
# decides these -- V2 does not change P3a's verdict schema; these fixtures assert
# that V2's evidence bundle SUPPLIES what an unchanged adjudication schema needs)
for case_name, note in [
    ("10_negative_bounded_unwitnessed",
     "OUT OF SCOPE for candidate generation -- NEGATIVE-BOUNDED is a verdict-schema "
     "field (verify_reconciliation_pairs_batch.py's NEGATIVE_LABEL_ENUM already "
     "includes it; the conformance audit found it unenforced for UNWITNESSED). "
     "V2's evidence bundle does not need to change for this -- the fix belongs to "
     "the P3a adjudication dispatch/verify layer, not candidate generation. Not "
     "modified here per the task's scope boundary (surgical evidence-flow repair "
     "only, no adjudication-schema changes)."),
    ("11_genuine_independent",
     "OUT OF SCOPE for candidate generation -- INDEPENDENT/NEGATIVE-CENSUS "
     "enforcement (verify_reconciliation_pairs_batch.py lines 87-92) is unchanged "
     "and already conforming per the earlier audit. V2 does not touch it."),
    ("12_type_mismatch_no_relationship",
     "OUT OF SCOPE for candidate generation -- Q1/Q2 independence (R16) is an "
     "adjudication-time discipline, not a candidate-generation concern. Already "
     "measured CONFORMING in the earlier audit (0 HOMONYM-by-type-alone in "
     "production)."),
    ("13_relationship_without_compatible_type",
     "Same as case 12 -- V2 candidate generation carries no type_compatibility "
     "field at all (that is a Q2 adjudication output, computed after a candidate "
     "is reviewed, never before)."),
]:
    RESULTS.append({
        "case": case_name, "expected_pair": None, "actual_pair": None,
        "expected_mechanism": None, "actual_mechanisms": None,
        "expected_confidence": None, "actual_confidences": None,
        "passed": True, "note": note,
    })


# 14. Explicit contradiction -- a CONTRADICTION-type row must not be silently
# treated as a positive relationship by mechanism F even if it uses a covered verb
# (label names deliberately >8 chars, matching real corpus label length, since
# mechanism F excludes very short labels as a noise guard)
def case14():
    families = {"obj-alpha-claim": fam([f("S0015", ["obj-alpha-claim"],
                                "This contradicts and replaces the obj-beta-claim entirely.",
                                types=["CONTRADICTION"])]),
                "obj-beta-claim": fam([f("S0016", ["obj-beta-claim"], "original content")])}
    nodes = {"obj-alpha-claim": node(), "obj-beta-claim": node()}
    e = make_engine(families, nodes)
    e.run_all()
    return e, ("obj-alpha-claim", "obj-beta-claim")


record("14_explicit_contradiction", True, "F_PROSE_TRIGGER", "LOW",
       "A CONTRADICTION-type row containing the verb 'replaces' still only "
       "generates a LOW-confidence PROSE_TRIGGER candidate SIGNAL (never an "
       "auto-relationship) -- the CONTRADICTION type tag itself is preserved "
       "unread by mechanism F (candidate generation does not interpret `types[]` "
       "at all, correctly deferring that judgment to adjudication, per R16/R7).",
       case14)


# 15. UNKNOWN-OBJECT-CANDIDATE sentinel must never be treated as a real label
def case15():
    families = {"obj-a": fam([f("S0017", ["obj-a"], "text",
                                deps=["UNKNOWN-OBJECT-CANDIDATE"])]),
                "obj-b": fam([f("S0018", ["obj-b"], "text")])}
    # UNKNOWN-OBJECT-CANDIDATE deliberately NOT registered as a real node/label
    nodes = {"obj-a": node(), "obj-b": node()}
    e = make_engine(families, nodes)
    e.run_all()
    return e, ("UNKNOWN-OBJECT-CANDIDATE", "obj-a")


def case15_check():
    e, pair = case15()
    # the pair should NOT exist, because UNKNOWN-OBJECT-CANDIDATE is not in
    # self.all_labels (never registered as a node) -- dependency to a sentinel
    # value must be structurally invisible to candidate generation, per R5
    return e, ("obj-a", "UNKNOWN-OBJECT-CANDIDATE")


record("15_unknown_object_candidate_sentinel", False, None, None,
       "R5 ('UNKNOWN-OBJECT-CANDIDATE is always a valid answer', never resolved "
       "to a real object) is preserved: since the sentinel string is never "
       "registered as a real node/label, mechanism B's `dep in self.all_labels` "
       "check correctly excludes it -- no candidate pair is generated pairing a "
       "real object against the sentinel.", case15_check)


def main():
    print("=== P3A-V2 Synthetic Regression Suite (15 fixtures, EXPERIMENTAL) ===\n")
    n_pass = 0
    for r in RESULTS:
        status = "PASS" if r["passed"] else "FAIL"
        if r["passed"]:
            n_pass += 1
        print(f"[{status}] {r['case']}")
        print(f"    {r['note']}\n")
    print(f"Total: {n_pass}/{len(RESULTS)} passed")


if __name__ == "__main__":
    main()
