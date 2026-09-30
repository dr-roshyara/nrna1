"""R-2 comparison: the pre-registered criteria C-1..C-5 (prompts/KNOWLEDGEOS-R-2-VERIFIER-HANDOFF-AND-COMPARISON-RULE.md §3).
C-6 (the exactness argument) is a document check of METHOD.md, done by reading, not here.
Reference: analysis/h_f2_1_verifier/verifier_results.json (all criteria); analysis/h_f2_1/results.json (cross-check C-2/C-3).
Supplementary (NOT pre-registered): every independent countermodel step is replayed through model.py's step predicate.
Read-only; standard library; deterministic. Nothing is edited to obtain agreement.
"""
import json
import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
A = os.path.dirname(HERE)
IND = json.load(open(os.path.join(HERE, "sealed", "results.json")))
REF = json.load(open(os.path.join(A, "h_f2_1_verifier", "verifier_results.json")))["instances"]
MOD = json.load(open(os.path.join(A, "h_f2_1", "results.json")))["posets"]
AX = ("A0", "A1", "A2e", "A3g", "A3s", "A3m", "A4", "A5e", "A5g", "A5s", "A6")
PROPS = ("D1", "D2", "D3", "D3+", "D5", "D6", "NV")
out = {"C-1": [], "C-2": [], "C-3": [], "C-4": [], "C-5": [], "supplementary_replay": []}

sys.path.insert(0, os.path.join(A, "h_f2_1"))
import model  # noqa: E402  (reference step predicate, used read-only)

POS = model.posets()


def ref_len(cm):
    return None if cm is None else len(cm["steps"])


for inst in ("chain3", "V", "diamond", "antichain2"):
    i, r, m = IND[inst], REF[inst], MOD[inst]
    for a, b, lab in ((i["state_count_with_A0"], r["state_count_full_axioms_A0"], "with_A0"),
                      (i["state_count_without_A0"], r["state_count_without_A0"], "without_A0")):
        if a != b: out["C-1"].append([inst, lab, b, a])
    for p in PROPS:
        mp = "NONVACUOUS" if p == "NV" else p
        if i["full"][p] != r["full_axiom_set"][p] or i["full"][p] != m["full"][mp]:
            out["C-2"].append([inst, p, r["full_axiom_set"][p], m["full"][mp], i["full"][p]])
    elems, leq = POS[inst]
    for ax in AX:
        rt = r["single_removal"]["without_" + ax]
        for p in PROPS:
            mp = "NONVACUOUS" if p == "NV" else p
            iv = i["single_removal"][ax][p]
            if iv["holds"] != rt["truth"][p] or iv["holds"] != m["ablation"][ax][mp]["holds"]:
                out["C-3"].append([inst, ax, p, rt["truth"][p], m["ablation"][ax][mp]["holds"], iv["holds"]])
            if p != "NV" and not iv["holds"]:
                il = len(iv["countermodel"]) if iv["countermodel"] else None
                rl = ref_len(rt["shortest_countermodels"].get(p))
                if il != rl: out["C-5"].append([inst, ax, p, rl, il])
                # supplementary: replay each step under Sigma = all axioms minus ax
                mdl = model.Model(elems, leq, [a for a in AX if a != ax])
                fam = set(mdl.family)
                for st in iv["countermodel"] or []:
                    x = (st["from"]["p"], st["from"]["s"], st["from"]["e"], st["from"]["g"], frozenset(st["from"]["u"]))
                    y = (st["to"]["p"], st["to"]["s"], st["to"]["e"], st["to"]["g"], frozenset(st["to"]["u"]))
                    ok = x[4] in fam and y[4] in fam and mdl.allowed(x, st["kind"], y)
                    if not ok: out["supplementary_replay"].append([inst, ax, p, st])
    for p in ("D1", "D2", "D3", "D3+", "D5", "D6"):
        a = {frozenset(s) for s in i["minimal_sets"][p]}
        b = {frozenset(s) for s in r["minimal_axiom_sets"][p]}
        if a != b: out["C-4"].append([inst, p, sorted(map(sorted, b)), sorted(map(sorted, a))])

counts = {k: len(v) for k, v in out.items()}
n = {"C-1": 8, "C-2": 28, "C-3": 308, "C-4": 24}
print(json.dumps({"mismatch_counts": counts, "items_compared": n, "mismatches": out}, indent=1, default=str))
