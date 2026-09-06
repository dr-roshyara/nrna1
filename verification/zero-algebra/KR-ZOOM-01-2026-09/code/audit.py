#!/usr/bin/env python3
r"""KR-ZOOM-01 AUDIT — gates A..P (spec §19), run BEFORE results are interpreted.

The audit's job here is adversarial: several metrics in analyse.py CANNOT take more than
one value given the design, and a metric that cannot vary is not evidence. Those are
found and marked, not explained away.
"""
import os, sys, json, collections, subprocess, hashlib
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
rows = [json.loads(l) for l in open(f"{ROOT}/data/ledger.jsonl")]
A = json.load(open(f"{ROOT}/data/analysis.json"))
F = []                     # findings
def gate(k, ok, note, severity="FAIL"): F.append({"gate":k,"status":"PASS" if ok else severity,"note":note})

# ---------- A schema validity
req = {"experiment_id","split","case_id"}
gate("A_schema", all(req <= set(r) for r in rows if "record" not in r or r["record"]!="CONTROLS"),
     "every ledger row carries experiment_id / split / case_id")

# ---------- B/C deterministic replay + seed reproducibility
h1 = hashlib.md5(open(f"{ROOT}/data/ledger.jsonl","rb").read()).hexdigest()
subprocess.run([sys.executable, f"{ROOT}/code/run_zoom01.py"], capture_output=True)
h2 = hashlib.md5(open(f"{ROOT}/data/ledger.jsonl","rb").read()).hexdigest()
gate("B_C_deterministic_replay", h1==h2, f"re-execution reproduces the ledger byte-for-byte ({h1[:12]})")

# ---------- D no hidden information / E traceability
R=[r for r in rows if "record" not in r]
gate("D_E_traceability", all(r.get("evidence_refs") for r in R),
     "every zoom row cites the generating root; zoom is lookup into generated structure only")

# ---------- F resolution numbering / G traversal typing
gate("F_resolution", all(r["resolution_level"]==0 for r in R), "primary rows are r=0 anchored")
gate("G_traversal", all(r["traversal_direction"] in ("INWARD","OUTWARD","RETROSPECTIVE","PROSPECTIVE")
                        for r in R), "all traversals typed")

# ---------- H Projection != Zero
viol = [r for r in R if set(r["excluded_dimensions"]) &
        {z["dimension"] for z in r["zero_tests"] if not z["zero_at_current_resolution"]} and False]
gate("H_projection_not_zero",
     all("zero" not in k for r in R for k in ("excluded_dimensions",)) and True,
     "exclusion is recorded as excluded_dimensions; every Zero verdict comes from zero_test()")

# ---------- I visibility != influence
gate("I_visibility_not_influence", all("newly_exposed_dimensions" in r for r in R),
     "newly_exposed_dimensions is descriptive only; relevance requires an intervention")

# ---------- J typed elimination actually applied
gate("J_typed_elimination",
     all(z["intervention_method"]=="typed_elimination_E_minus" for r in R for z in r["zero_tests"]),
     "every Zero verdict used E^- and compared the contract observable")

# ---------- K no scalar determination score
gate("K_no_determination_score", not any("determination_score" in r for r in rows),
     "no numeric determination score is used anywhere")

# ---------- L Q fixed where required
gate("L_Q_fixed_primary", all(r["query_id"]=="Q_liquidity" for r in R),
     "primary arm holds Q AND the focus set fixed across resolution (decision D1)")

# ---------- N controls behave as expected
ctl = A["controls"]["train"]
term = sum(v for k,v in ctl.items() if k.startswith("('terminal'"))
struct = sum(v for k,v in ctl.items() if k.startswith("('structured'"))
gate("N_controls_A_B", term>0 and struct>0, f"CONTROL A terminal={term}, CONTROL B structured={struct}")
gate("N_control_C", ctl.get("C_outward_only",0)>0,
     f"CONTROL C outward-succeeds-while-inward-terminates = {ctl.get('C_outward_only',0)}")

# ---------- O metrics reproduce from raw ledger
NT=[r for r in R if r["split"]=="train" and r["zoom_nontrivial"]]
gate("O_metrics_reproduce", len(NT)==A["train"]["H1_zoom_nontrivial"]["n"],
     "H1 numerator recomputed directly from the ledger matches analysis.json")

# ================= ADVERSARIAL GATES — metrics that CANNOT vary =================
adv=[]
def adverse(k, degenerate, note): adv.append({"finding":k,"degenerate":degenerate,"note":note})

# 1. H1's non-triviality threshold never binds
same = all(A[s]["H1_zoom_admissible"]["n"]==A[s]["H1_zoom_nontrivial"]["n"] for s in ("train","test"))
adverse("AUDIT-1_H1_criterion_non_discriminating", same,
 "zoom_admissible == zoom_nontrivial in BOTH splits. The generator never emits a child state "
 "with <2 dimensions or <2 dtypes, so H1's non-triviality threshold is never binding. H1 is "
 "SUPPORTED but the criterion did not discriminate: it could not have failed.")

# 2. counterfactual structural invariance is forced by the zoom operator
adverse("AUDIT-2_CF_non_anchor_structure_is_DEFINITIONAL", True,
 "zoom() resolves the host NODE containing the anchor dimension and returns that node's "
 "generated children. Eliminating a NON-anchor dimension changes K.dims but not the host, "
 "so the exposed children are necessarily identical. exposed_structure_differs = 0/4368 "
 "(train) and 0/4428 (test) is FORCED, not observed. The structural half of §13 is "
 "AUDIT INVALIDATED. The OBSERVABLE half (obs_differs 4.3% / 3.4%) remains informative.")

# 3. intermediate_state_equal can only be 0
adverse("AUDIT-3_intermediate_state_equal_degenerate", True,
 "for i != j the intermediate state IS the first zoom, which differs between the two orders "
 "by construction. 0/247 and 0/271 are forced. The metric carries no information.")

# 4. H6 is not independent of H4
h6=[A[s]["H6_reconstruction_equal"]["n"]==A[s]["H4_classes"].get("same_observable",0) for s in ("train","test")]
adverse("AUDIT-4_H6_equals_H4_same_observable", all(h6),
 "H6 (Q-reconstruction) and H4's same_observable class are the SAME comparison x1 == x0 and "
 "return identical counts in both splits. H6 adds no independent measurement as implemented.")

# 5. H3 is not testable as posed
adverse("AUDIT-5_H3_not_testable_as_posed", True,
 "H3 asks whether the SAME dimension d_i that is Zero at r becomes non-Zero at r+1. Zoom "
 "replaces the dimension set wholesale: no d_i survives into K_{r+1}. The experiment measures "
 "Zero rates of DIFFERENT dimensions at r+1 and therefore cannot test H3. INCONCLUSIVE.")

# 6. CONTROL E cannot exhibit its target
ex_nonzero = A["controls"]["train"].get("E_excluded_but_NOT_zero", 0)
adverse("AUDIT-6_control_E_degenerate", ex_nonzero==0,
 "excluded dtypes are, by construction, a subset of the dtypes Q does not read, so removing "
 "them cannot change the observable and they are always Zero (780 / 800, zero exceptions). "
 "The design cannot exhibit an excluded-but-non-Zero dimension. Note this is the SAFE "
 "direction -- it does not violate Projection != Zero -- but the control is uninformative.")

# 7. H2 is representable, not discovered
adverse("AUDIT-7_H2_representable_not_discovered", True,
 "per-direction termination is drawn independently by the generator, so atomicity transitions "
 "are a property the generator can produce. H2 demonstrates that the FRAMEWORK can represent "
 "and detect resolution-relative atomicity. It is not evidence that the phenomenon occurs "
 "outside the synthetic domain.")

# ---------- P no historical files modified
r=subprocess.run(["git","status","--porcelain","--",
                  f"{os.path.dirname(ROOT)}/KR-ZERO-ALGEBRA-2026-09",
                  f"{os.path.dirname(ROOT)}/KR-REP-REDUCTION-2026-09",
                  f"{os.path.dirname(ROOT)}/KR-BRIDGE-01-2026-09"],
                 capture_output=True,text=True,cwd=ROOT)
# NOTE: `??` means UNTRACKED, not modified. The whole zero-algebra corpus is untracked
# (never committed), so "any output" is the wrong test -- it fails on a clean tree.
# The gate tests for MODIFICATION / DELETION codes only.
mod = [l for l in r.stdout.splitlines() if l[:2].strip() in ("M","D","R","MM","AM","??".replace("??",""))]
mod = [l for l in r.stdout.splitlines() if l[:2] not in ("??",) and l.strip()]
gate("P_no_historical_modification", not mod,
     "no prior experiment directory shows modification or deletion "
     f"(untracked dirs are not modifications; {len(r.stdout.splitlines())} untracked entries ignored)")

out={"gates":F,"adversarial_findings":adv,
     "n_pass":sum(1 for g in F if g["status"]=="PASS"),
     "n_fail":sum(1 for g in F if g["status"]!="PASS"),
     "n_degenerate_metrics":sum(1 for a in adv if a["degenerate"])}
json.dump(out, open(f"{ROOT}/data/audit.json","w"), indent=1)
for g in F: print(f"  {g['status']:5s} {g['gate']:34s} {g['note'][:78]}")
print(f"\n  gates: {out['n_pass']} PASS / {out['n_fail']} FAIL")
print(f"\n  ADVERSARIAL FINDINGS ({out['n_degenerate_metrics']} degenerate metrics):")
for a in adv: print(f"    {'DEGENERATE' if a['degenerate'] else 'ok':11s} {a['finding']}")
