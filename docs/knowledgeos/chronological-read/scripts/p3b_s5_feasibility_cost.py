#!/usr/bin/env python3
"""P3b S5 feasibility cost model (stop-the-line, G-LOG-0031). Reads committed artifacts only, never corpus content and
never a sealed artifact: P3B-DISCOVERY-SEARCH.jsonl (stage-1 hits), P3B-SAMPLE-PLAN.jsonl (population, strata,
checklist), P3B-IDENTITY-MANIFEST.jsonl (git blob sizes via `git cat-file --batch-check`), and the S4 R2.2 objects
(calibration only). Unit: dispositions = distinct hit keys x the label's absence dimensions (R2.2 unit).

  python3 p3b_s5_feasibility_cost.py            (prints the JSON summary used by the S5 feasibility decision record)
"""
import collections
import json
import os
import random
import statistics
import subprocess
import sys

CR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SEED, DRAWS = 1, 2000


def jl(name):
    with open(os.path.join(CR, name), encoding="utf-8") as f:
        return [json.loads(line) for line in f if line.strip()]


def main():
    man = {r["source_id"]: r for r in jl("P3B-IDENTITY-MANIFEST.jsonl") if "source_id" in r}
    specs = [(s, r["blob_spec"]) for s, r in man.items() if r.get("blob_spec_type") == "GIT-OBJECT"]
    out = subprocess.run(["git", "cat-file", "--batch-check=%(objectsize)"], cwd=CR, capture_output=True, text=True,
                         input="\n".join(b for _, b in specs) + "\n").stdout.split("\n")
    size = {s: int(o) for (s, _), o in zip(specs, out) if o.strip().isdigit()}
    recs = jl("P3B-DISCOVERY-SEARCH.jsonl")[1:]
    hits = {r["label"]: r for r in recs if r["record"] == "LABEL-HITS"}
    dims = collections.Counter(r["label"] for r in recs if r["record"] == "DIMENSION")

    def cost(lab):
        keys, files = set(), set()
        for kind in ("ledger_hits", "raw_hits"):
            for s, occ in ((hits.get(lab) or {}).get(kind) or {}).items():
                files.add(s)
                keys.update((s, kind, json.dumps(x)) for x in occ)
        return {"keys": len(keys), "files": len(files), "bytes": sum(size.get(s, 0) for s in files),
                "disp": len(keys) * dims[lab]}

    plan = jl("P3B-SAMPLE-PLAN.jsonl")[1:]
    pop = [r["working_label"] for r in plan]
    C = {lab: cost(lab) for lab in pop}

    def agg(labs):
        return {"n": len(labs), "stage2_bytes": sum(C[l]["bytes"] for l in labs),
                "stage2_file_reads": sum(C[l]["files"] for l in labs), "dispositions": sum(C[l]["disp"] for l in labs)}

    pilot = [json.loads(line)["working_label"] for b in ("PB02", "PB03", "PB04", "PB05")
             for line in open(os.path.join(CR, "ledger-p3b-r2", "S4-R22", b, "objects.jsonl"), encoding="utf-8")]
    res = {"calibration_r22_20_labels": {"dispositions": sum(cost(l)["disp"] for l in pilot),
                                         "stage2_bytes": sum(cost(l)["bytes"] for l in pilot)},
           "O1_full": agg(pop),
           "O4_checklist_567": agg([r["working_label"] for r in plan if r["in_checklist"]]),
           "O4_random_200": agg([r["working_label"] for r in plan if r["in_checklist"] and not r["purposive_criteria"]]),
           "O4_purposive_367": agg([r["working_label"] for r in plan if r["in_checklist"] and r["purposive_criteria"]])}
    ranked = sorted(pop, key=lambda l: -C[l]["disp"])
    tot_d, tot_b = sum(C[l]["disp"] for l in pop), sum(C[l]["bytes"] for l in pop)
    res["concentration"] = {f"top{k}": {"disp_share": round(sum(C[l]["disp"] for l in ranked[:k]) / tot_d, 3),
                                        "bytes_share": round(sum(C[l]["bytes"] for l in ranked[:k]) / tot_b, 3)}
                            for k in (10, 50, 100, 200)}
    q = statistics.quantiles([C[l]["disp"] for l in pop], n=100)
    res["disp_per_label"] = {"p50": q[49], "p90": q[89], "p99": q[98], "max": max(C[l]["disp"] for l in pop),
                             "zero_hit_labels": sum(1 for l in pop if C[l]["disp"] == 0)}
    res["O5_threshold"] = {f"top{k}_excluded": {"threshold_disp_gt": C[ranked[k - 1]]["disp"], "census": agg(ranked[k:]),
                                                "hub": agg(ranked[:k])} for k in (50, 100, 200)}
    strata = collections.defaultdict(list)
    for r in plan:
        strata[r["stratum"]].append(r["working_label"])
    rng = random.Random(SEED)
    res["O2_stratified_proportional"] = {}
    for n in (100, 200, 400):
        sims = []
        for _ in range(DRAWS):
            s = []
            for labs in strata.values():
                s += rng.sample(labs, max(1, round(n * len(labs) / len(pop))))
            sims.append(agg(s))
        res["O2_stratified_proportional"][str(n)] = {
            m: {"median": sorted(x[m] for x in sims)[DRAWS // 2], "p90": sorted(x[m] for x in sims)[int(DRAWS * 0.9)]}
            for m in ("stage2_bytes", "stage2_file_reads", "dispositions")}
    # O5 threshold sensitivity (G-LOG-0032): candidate rules from S3 data and the S4 R2.2 audited capacity only.
    # Strata = the H-19 addendum §7 baseline strata without the O-analog (tier x row_count band x source-count band),
    # discovery labels only; the sealed hold-out is never read.
    bi = {r["working_label"]: r for r in jl("_batch_input_r2/bundle_index_discovery.jsonl")[1:]}
    tier = {r["working_label"]: r["tier"] for r in plan}

    def cell(lab):
        rows = bi[lab].get("distinct_rows", 0)
        src = len({x["source_id"] for x in bi[lab].get("rows", [])})
        return (tier[lab], "1" if rows <= 1 else "2-3" if rows <= 3 else "4-9" if rows <= 9 else ">=10",
                "1" if src <= 1 else "2-3" if src <= 3 else ">=4")

    full_cells = collections.Counter(cell(l) for l in pop)
    pilot_max = max(C[l]["disp"] for l in pilot)
    rules = {"T1_gt_p99": q[98], "T2_gt_p95": q[94], "T3_gt_r22_run_load": res["calibration_r22_20_labels"]["dispositions"],
             "T4_gt_max_s4_audited_label": pilot_max, "T5_gt_mean_r22_batch_load": res["calibration_r22_20_labels"]["dispositions"] / 4}
    res["O5_sensitivity"] = {}
    for name, t in rules.items():
        hub = [l for l in pop if C[l]["disp"] > t]
        rest = [l for l in pop if C[l]["disp"] <= t]
        rc = collections.Counter(cell(l) for l in rest)
        res["O5_sensitivity"][name] = {
            "threshold_disp_gt": t, "hubs": len(hub), "processed": agg(rest),
            "excluded_disp_share": round(1 - agg(rest)["dispositions"] / tot_d, 3),
            "excluded_bytes_share": round(1 - agg(rest)["stage2_bytes"] / tot_b, 3),
            "baseline_cells_n_ge_10": sum(1 for v in rc.values() if v >= 10),
            "baseline_cells_lost": sum(1 for c, v in full_cells.items() if v >= 10 and rc.get(c, 0) < 10),
            "hub_tiers": dict(collections.Counter(tier[l] for l in hub)),
            "hub_in_checklist": sum(1 for r in plan if r["working_label"] in set(hub) and r["in_checklist"]),
            "hub_labels": sorted(hub)}
    res["parameters"] = {"seed": SEED, "draws": DRAWS, "python_version": sys.version.split()[0]}
    print(json.dumps(res, indent=1, sort_keys=True))
    return 0


if __name__ == "__main__":
    sys.exit(main())
