#!/usr/bin/env python3
"""E-2 anchor check (trial M-04-T1). Every citation 'KEY Lx', 'KEY Lx–y' (and following ', Ly') in the
chapters must point inside the cited source's line count. Unmapped keys are listed, not judged.
    python3 -I anchor_check.py <repo_root> <chapter.md> [...]"""
import re, sys
root, chapters = sys.argv[1], sys.argv[2:]
K = "docs/knowledge_tranfer/20260816_"
SRC = {"V3": K+"0841_target_architecture_v3.md", "CF": K+"0905_architecture_conformance.md",
       "RM": K+"0914_rule_model_and_conflict_analysis.md", "AB": K+"0922_aggregate_boundaries.md",
       "IM": K+"1023_invariant_aggregate_matrix.md", "IA": K+"1029_invariant_allocation.md",
       "GA": K+"1108_architecture_gap_analysis_knowledgeos.md", "EP01": K+"1129_arb_evidence_package_01_v3.md",
       "TR": K+"1150_arch_inv_1_trace.md", "DR-AB": K+"1156_arb_decision_record_A_B.md",
       "RS": K+"1211_rule_model_business_semantics.md", "AMS": K+"1332_authority_model_business_semantics.md",
       "SC": K+"1351_scope_applicability_business_semantics.md", "SD": K+"1513_sc1_dimension_preparation.md",
       "EV": K+"2043_environment_vocabulary_preparation.md", "PC": K+"1034_policy_catalogue.md",
       "DR": K+"1040_decision_register.md", "DE": K+"1044_domain_events.md", "AG": K+"1057_artifact_gap_analysis.md",
       "SI": K+"1144_script_capability_evidence_inventory_v1.md"}
n = {k: len(open(f"{root}/{p}", encoding="utf-8").read().split("\n")) for k, p in SRC.items()}
cite = re.compile(r"\b(DR-AB|EP01|[A-Z][A-Z0-9]{1,4})\s+(L\d+(?:[-–]\d+)?(?:,\s*L\d+(?:[-–]\d+)?)*)")
bad = checked = 0; unmapped = set()
for ch in chapters:
    for no, line in enumerate(open(ch, encoding="utf-8"), 1):
        for key, refs in cite.findall(line):
            if key not in n: unmapped.add(key); continue
            for a, b in re.findall(r"L(\d+)(?:[-–](\d+))?", refs):
                checked += 1
                if int(b or a) > n[key] or int(a) < 1 or (b and int(b) < int(a)):
                    bad += 1; print(f"{ch.split('/')[-1]}:{no}\t{key} L{a}{'-'+b if b else ''}\tsource has {n[key]} lines")
print(f"# {checked} anchors checked, {bad} out of range; unmapped keys: {sorted(unmapped)}", file=sys.stderr)
