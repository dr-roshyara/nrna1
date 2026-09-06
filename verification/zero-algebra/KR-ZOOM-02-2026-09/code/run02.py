#!/usr/bin/env python3
r"""KR-ZOOM-02 executor. PAIRED design: both operators see the identical case and the
identical exploration RNG, so any difference is the operator, not the sample."""
import os, sys, json, statistics, collections
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from nexus import generate, investigate, zero_for_inquiry, DIMENSIONS

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SEEDS = {"train": 20260904, "test": 88020260904}
N, BUDGET = 4000, 40

def run(split):
    cases = generate(SEEDS[split], N)
    rows = []
    for i, c in enumerate(cases):
        os_ = 7000 + i                              # SAME exploration seed for both operators
        R = investigate(c, "RESTRICTION", BUDGET, os_)
        I = investigate(c, "INQUIRY",     BUDGET, os_)
        cross = c.root_dim != c.anchor_dim
        # H3, now well-posed: Q FIXED, dimension identity PRESERVED across the investigation
        z_before = zero_for_inquiry(c, c.root_dim, {c.anchor_dim})
        z_after  = zero_for_inquiry(c, c.root_dim, set(I["working_set"]))
        rows.append({"split": split, "case": i,
                     "chain_len": len(c.chain), "root_dim": c.root_dim,
                     "cause_outside_anchor_dim": cross,
                     "restriction_found": R["found"], "inquiry_found": I["found"],
                     "restriction_probes": R["probes"], "inquiry_probes": I["probes"],
                     "expansions": I["expansions"],
                     "working_set_size": len(I["working_set"]),
                     "zero_before_evidence": z_before, "zero_after_evidence": z_after,
                     "zero_flip": z_before and not z_after})
    return rows

def order_sensitivity(split, n=1500, budget=40, k=5):
    """Z5: same case, SAME operator, DIFFERENT exploration order."""
    cases = generate(SEEDS[split], n); out = []
    for i, c in enumerate(cases):
        res = [investigate(c, "INQUIRY", budget, 90000 + i * 17 + j) for j in range(k)]
        found = [r["found"] for r in res]
        out.append({"case": i, "all_same": len(set(found)) == 1, "n_found": sum(found), "k": k,
                    "probe_spread": max(r["probes"] for r in res) - min(r["probes"] for r in res)})
    return out

def frac(n, d): return {"n": n, "d": d, "rate": round(n / d, 5) if d else None}

if __name__ == "__main__":
    allrows, summary = [], {}
    for split in ("train", "test"):
        R = run(split); allrows += R
        cross = [r for r in R if r["cause_outside_anchor_dim"]]
        same  = [r for r in R if not r["cause_outside_anchor_dim"]]
        summary[split] = {
          "n": len(R), "budget": BUDGET,
          "GENERATOR_cause_outside_anchor_dim": frac(len(cross), len(R)),
          "Z3_restriction_found": frac(sum(r["restriction_found"] for r in R), len(R)),
          "Z3_inquiry_found":     frac(sum(r["inquiry_found"] for r in R), len(R)),
          "Z3_paired_inquiry_wins": frac(sum(1 for r in R if r["inquiry_found"] and not r["restriction_found"]), len(R)),
          "Z3_paired_restriction_wins": frac(sum(1 for r in R if r["restriction_found"] and not r["inquiry_found"]), len(R)),
          "Z1_restriction_on_CROSS_dimension_cases": frac(sum(r["restriction_found"] for r in cross), len(cross)),
          "Z1_restriction_on_SAME_dimension_cases":  frac(sum(r["restriction_found"] for r in same), len(same)),
          "Z1_inquiry_on_CROSS_dimension_cases":     frac(sum(r["inquiry_found"] for r in cross), len(cross)),
          "Z2_expansions_needed_median": statistics.median(r["expansions"] for r in R),
          "Z2_determined_with_zero_expansions": frac(
              sum(1 for r in R if r["inquiry_found"] and r["expansions"] == 0), len(R)),
          "Z2_working_set_size_median": statistics.median(r["working_set_size"] for r in R),
          "Z4_zero_before_evidence": frac(sum(r["zero_before_evidence"] for r in R), len(R)),
          "Z4_zero_after_evidence":  frac(sum(r["zero_after_evidence"] for r in R), len(R)),
          "Z4_ZERO_FLIP_root_cause": frac(sum(r["zero_flip"] for r in R), len(R)),
          "probes_median": {"restriction": statistics.median(r["restriction_probes"] for r in R),
                            "inquiry":     statistics.median(r["inquiry_probes"] for r in R)},
        }
        OS = order_sensitivity(split)
        summary[split]["Z5_order_changes_outcome"] = frac(sum(1 for o in OS if not o["all_same"]), len(OS))
        summary[split]["Z5_probe_spread_median"] = statistics.median(o["probe_spread"] for o in OS)
    os.makedirs(f"{ROOT}/data", exist_ok=True)
    with open(f"{ROOT}/data/ledger02.jsonl", "w") as f:
        for r in allrows: f.write(json.dumps(r, separators=(",", ":")) + "\n")
    json.dump(summary, open(f"{ROOT}/data/summary02.json", "w"), indent=1)
    for s in ("train", "test"):
        print(f"\n{'='*74}\n{s.upper()}  (n={summary[s]['n']}, probe budget={BUDGET})\n{'='*74}")
        for k, v in summary[s].items():
            if isinstance(v, dict) and "rate" in v: print(f"  {k:46s} {v['n']:>6}/{v['d']:<6} = {v['rate']}")
            elif isinstance(v, dict): print(f"  {k:46s} {v}")
            elif k not in ("n","budget"): print(f"  {k:46s} {v}")
