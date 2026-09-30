#!/usr/bin/env python3
"""EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH.

Master Protocol v3.5 implementation-conformance audit, section 13: synthetic
information-loss test fixtures. Constructs 8 minimal, entirely synthetic (fake)
label/row/group records IN MEMORY ONLY, and traces each through faithful,
line-for-line reimplementations of the actual matching logic used by
derive_reconciliation.py (candidate generation) and row_brief() (evidence
serialization) -- never touches any real file, never writes to any production
path, never runs the real script against real data. Output goes only to stdout
and this audit's own report.
"""
from collections import defaultdict

# ---- faithful copies of the actual logic under test (see derive_reconciliation.py) ----

def row_brief(c):
    """EXACT COPY of derive_reconciliation.py's row_brief() -- the function that
    builds a_rows_sample/b_rows_sample. If this drifts from the real function,
    the audit is invalid; verified identical by diff against the source at
    audit time (2026-09-21)."""
    return {
        "source_id": c["source_id"],
        "anchor": c.get("anchor"),
        "types": c.get("types"),
        "statement": c.get("statement"),
        "type_signature": c.get("type_signature"),
        "explicit_date": c.get("explicit_date"),
    }


def find_candidate_pairs(families, nodes, groups):
    """EXACT REPLICATION of derive_reconciliation.py's WITHIN-GROUP and
    CROSS-GROUP pair-detection logic, operating on synthetic data."""
    within_pairs = {}
    for g in groups:
        members = [m for m in g["members"] if m in nodes]
        if len(members) < 2:
            continue
        for i in range(len(members)):
            for j in range(i + 1, len(members)):
                a, b = sorted([members[i], members[j]])
                within_pairs.setdefault((a, b), []).append(g)

    string_to_label = defaultdict(set)
    for label, node in nodes.items():
        string_to_label[label].add(label)
        for n in node.get("notations") or []:
            string_to_label[n].add(label)
        for al in node.get("aliases") or []:
            string_to_label[al].add(label)

    cross_pairs = {}
    for label, fam in families.items():
        for c in fam["rows"]:
            for lc in (c.get("lineage_claims") or []):
                target = lc.get("target")
                if not target:
                    continue
                for tl in string_to_label.get(target, set()):
                    if tl == label:
                        continue
                    a, b = sorted([label, tl])
                    if (a, b) in within_pairs:
                        continue
                    cross_pairs.setdefault((a, b), []).append(lc)

    return within_pairs, cross_pairs


def dependencies_are_read_by_candidate_generation():
    """derive_reconciliation.py never reads the `dependencies` field anywhere in
    its pair-generation logic (verified by inspection: the field name
    'dependencies' does not appear in the WITHIN-GROUP or CROSS-GROUP detection
    code at all). This function exists only to document that fact in one place
    a test can assert against."""
    return False


CASES = []


def case(name, families, nodes, groups, expect_pair, expect_evidence_visible, note):
    within, cross = find_candidate_pairs(families, nodes, groups)
    all_pairs = set(within.keys()) | set(cross.keys())
    pair_key = tuple(sorted(nodes.keys())[:2]) if len(nodes) >= 2 else None
    found = pair_key in all_pairs if pair_key else False
    CASES.append({
        "case": name, "expected_pair_generated": expect_pair,
        "actual_pair_generated": found,
        "conforms_to_expectation": found == expect_pair,
        "evidence_visible_in_row_brief": expect_evidence_visible,
        "note": note,
    })
    return found


def main():
    # Case 1: A dependency relationship only (A's row has dependencies=["B"])
    fams1 = {"obj-a": {"rows": [{"source_id": "S0001", "labels": ["obj-a"],
                                  "dependencies": ["obj-b"], "lineage_claims": [],
                                  "statement": "A depends on B"}]},
             "obj-b": {"rows": [{"source_id": "S0002", "labels": ["obj-b"],
                                  "dependencies": [], "lineage_claims": [],
                                  "statement": "B is defined here"}]}}
    nodes1 = {"obj-a": {"notations": [], "aliases": []}, "obj-b": {"notations": [], "aliases": []}}
    case("1_dependency_only", fams1, nodes1, [], expect_pair=False, expect_evidence_visible=False,
         note="dependencies[] is never consulted by candidate generation at all -- "
              "confirmed structurally (dependencies_are_read_by_candidate_generation() "
              "returns False by inspection of the real script). No pair is generated "
              "regardless of P2a grouping, unless the two labels ALSO happen to be "
              "WITHIN-GROUP or share an exact-match lineage_claim by other means.")

    # Case 2: lineage claim using exact source ID target (not a label string)
    fams2 = {"obj-a": {"rows": [{"source_id": "S0003", "labels": ["obj-a"],
                                  "dependencies": [], "statement": "extends S0004",
                                  "lineage_claims": [{"kind": "SOURCE-CLAIMED-EXTENSION",
                                                       "target": "S0004", "quote": "..."}]}]},
             "obj-b": {"rows": [{"source_id": "S0004", "labels": ["obj-b"],
                                  "dependencies": [], "lineage_claims": [],
                                  "statement": "original B content"}]}}
    nodes2 = {"obj-a": {"notations": [], "aliases": []}, "obj-b": {"notations": [], "aliases": []}}
    case("2_lineage_claim_bare_source_id", fams2, nodes2, [], expect_pair=False,
         expect_evidence_visible=False,
         note="target='S0004' is a source_id, not in string_to_label (which only "
              "indexes label/notation/alias strings) -- exact match fails, no "
              "CROSS-GROUP pair generated. This exactly reproduces the RP0526 defect "
              "found in production (target='S1345').")

    # Case 3: lineage claim using a paraphrase containing the label as substring
    fams3 = {"obj-a": {"rows": [{"source_id": "S0005", "labels": ["obj-a"],
                                  "dependencies": [], "statement": "extends B's core idea",
                                  "lineage_claims": [{"kind": "SOURCE-CLAIMED-EXTENSION",
                                                       "target": "obj-b's core mechanism (Step 9)",
                                                       "quote": "..."}]}]},
             "obj-b": {"rows": [{"source_id": "S0006", "labels": ["obj-b"],
                                  "dependencies": [], "lineage_claims": [],
                                  "statement": "original B content"}]}}
    nodes3 = {"obj-a": {"notations": [], "aliases": []}, "obj-b": {"notations": [], "aliases": []}}
    case("3_lineage_claim_paraphrase_substring", fams3, nodes3, [], expect_pair=False,
         expect_evidence_visible=False,
         note="target=\"obj-b's core mechanism (Step 9)\" is not an exact match to "
              "'obj-b' (string equality, not substring/contains) -- no CROSS-GROUP "
              "pair. Reproduces the RP0288 defect found in production.")

    # Case 4: relationship across candidate groups, exact label match both ways
    fams4 = {"obj-a": {"rows": [{"source_id": "S0007", "labels": ["obj-a"],
                                  "dependencies": [], "statement": "extends obj-b directly",
                                  "lineage_claims": [{"kind": "SOURCE-CLAIMED-EXTENSION",
                                                       "target": "obj-b", "quote": "..."}]}]},
             "obj-b": {"rows": [{"source_id": "S0008", "labels": ["obj-b"],
                                  "dependencies": [], "lineage_claims": [],
                                  "statement": "original B content"}]}}
    nodes4 = {"obj-a": {"notations": [], "aliases": []}, "obj-b": {"notations": [], "aliases": []}}
    case("4_cross_group_exact_label_match", fams4, nodes4, [], expect_pair=True,
         expect_evidence_visible=True,
         note="target='obj-b' IS an exact match to the real label string -- this is "
              "the ONE mechanism that correctly works: a CROSS-GROUP pair IS generated. "
              "This confirms the mechanism specified in A11 ('a lineage_claim whose "
              "target string matches label Y's own working_label') is implemented "
              "correctly for the one narrow case where the source author happened to "
              "type the exact canonical label string as the claim's target.")

    # Case 5: source explicitly says "extends" in prose but the structured
    # lineage_claims field target does not resolve (simulates a real author writing
    # natural-language cross-reference without a matching structured tag)
    fams5 = {"obj-a": {"rows": [{"source_id": "S0009", "labels": ["obj-a"],
                                  "dependencies": [], "lineage_claims": [],
                                  "statement": "This extends the earlier obj-b concept "
                                               "with a new formal signature."}]},
             "obj-b": {"rows": [{"source_id": "S0010", "labels": ["obj-b"],
                                  "dependencies": [], "lineage_claims": [],
                                  "statement": "original B content"}]}}
    nodes5 = {"obj-a": {"notations": [], "aliases": []}, "obj-b": {"notations": [], "aliases": []}}
    case("5_prose_extends_no_structured_claim", fams5, nodes5, [], expect_pair=False,
         expect_evidence_visible=False,
         note="'This extends the earlier obj-b concept' is real English prose stating "
              "a relationship, but P1 recorded no structured lineage_claims entry for "
              "it (perhaps because the extraction agent judged it too informal, or "
              "simply missed it) -- with no P2a group membership either, this pair is "
              "invisible by every mechanism the pipeline has. R7 says a source lineage "
              "statement must become SOURCE-CLAIMED-* to be usable by P3 at all; if P1 "
              "never tags it, P3 structurally cannot recover it later.")

    # Case 6: relationship expressed only through prose, but P2a's fuzzy clustering
    # WOULD catch it via label string similarity (simulating STRING-SIMILARITY grouping)
    fams6 = {"obj-alpha-model": {"rows": [{"source_id": "S0011", "labels": ["obj-alpha-model"],
                                            "dependencies": [], "lineage_claims": [],
                                            "statement": "Discusses the alpha model in "
                                                         "the context of the beta model."}]},
             "obj-beta-model": {"rows": [{"source_id": "S0012", "labels": ["obj-beta-model"],
                                           "dependencies": [], "lineage_claims": [],
                                           "statement": "original beta content"}]}}
    nodes6 = {"obj-alpha-model": {"notations": [], "aliases": []},
              "obj-beta-model": {"notations": [], "aliases": []}}
    groups6 = [{"group_id": "G-SIM-TEST", "kind": "STRING-SIMILARITY",
                "members": ["obj-alpha-model", "obj-beta-model"]}]
    case("6_prose_only_but_p2a_string_similarity_catches_it", fams6, nodes6, groups6,
         expect_pair=True, expect_evidence_visible=True,
         note="If, and only if, P2a's separate STRING-SIMILARITY/Jaccard clustering "
              "(normalize_labels.py) happens to group these two labels for an "
              "unrelated reason (shared tokens 'model'), a WITHIN-GROUP pair IS "
              "generated as a byproduct -- but this is incidental, not a mechanism "
              "designed to recover prose-only relationships, and would not fire for "
              "two dissimilarly-named labels making the identical prose claim.")

    # Case 7: duplicate source (R9 -- duplicate != independent evidence)
    # Simulated by two rows sharing identical statement text under the same label,
    # from what P1c's near-dup scan should have flagged as EXACT-DUPLICATE at the
    # FILE level (not modeled here at the row level -- see note).
    case("7_duplicate_source_R9", {}, {}, [], expect_pair=None, expect_evidence_visible=None,
         note="R9 is enforced upstream of P3 entirely, at P0/P1c: validate_roadmap.py "
              "assigns dup='EXACT-DUPLICATE' (vs 'UNIQUE') per source file's sha256, "
              "and plan_batches.py excludes non-UNIQUE files from ANY batch -- an "
              "exact-duplicate file is never independently re-extracted, so it can "
              "never surface as independent corroborating evidence downstream. "
              "Verified by inspection of validate_roadmap.py/plan_batches.py; not "
              "modeled as a P3-level fixture because the enforcement point is P0, "
              "not P3.")

    # Case 8: a SECONDARY-SYNTHESIS source referencing a PRIMARY source
    fams8 = {"obj-syn": {"rows": [{"source_id": "S0013", "labels": ["obj-syn"],
                                    "dependencies": [], "statement": "Synthesizes obj-prim",
                                    "lineage_claims": [{"kind": "SOURCE-CLAIMED-EXTENSION",
                                                         "target": "obj-prim", "quote": "..."}]}]},
             "obj-prim": {"rows": [{"source_id": "S0014", "labels": ["obj-prim"],
                                     "dependencies": [], "lineage_claims": [],
                                     "statement": "original primary content"}]}}
    nodes8 = {"obj-syn": {"notations": [], "aliases": []}, "obj-prim": {"notations": [], "aliases": []}}
    found8 = case("8_secondary_synthesis_cites_primary", fams8, nodes8, [], expect_pair=True,
                   expect_evidence_visible=True,
                   note="The CROSS-GROUP pair IS generated (exact label match, same "
                        "mechanism as case 4). BUT: row_brief() -- the function that "
                        "builds what a P3a reviewer actually sees -- has NO field for "
                        "file-level `provenance` (PRIMARY/SECONDARY-SYNTHESIS) at all. "
                        "The protocol's own citation discipline ('EVERY statement "
                        "carries [S-id §anchor] † if SECONDARY-SYNTHESIS', prompt3 "
                        "§B4) is therefore invisible to a P3a reviewer working from "
                        "a_rows_sample/b_rows_sample -- confirmed by direct inspection "
                        "of row_brief()'s field list below.")
    rb_fields = set(row_brief({"source_id": "x"}).keys())
    provenance_visible_in_row_brief = "provenance" in rb_fields or "corpus_role" in rb_fields
    assert provenance_visible_in_row_brief is False, "unexpected: row_brief now carries provenance"

    print("=== Synthetic Conformance Fixture Results (EXPERIMENTAL, no production impact) ===\n")
    for c in CASES:
        print(f"Case: {c['case']}")
        print(f"  expected_pair_generated={c['expected_pair_generated']}  "
              f"actual_pair_generated={c['actual_pair_generated']}  "
              f"conforms={c['conforms_to_expectation']}")
        print(f"  note: {c['note']}\n")
    print(f"row_brief() fields: {sorted(rb_fields)}")
    print(f"provenance/corpus_role field present in row_brief output: {provenance_visible_in_row_brief}")


if __name__ == "__main__":
    main()
