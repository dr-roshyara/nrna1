#!/usr/bin/env python3
"""KnowledgeOS Kernel Reduction experiment - full run.

Writes results/*.json with reproducibility metadata (Part XXVI).
NOTHING produced here is canonical KnowledgeOS architecture.  [EXP]
"""
import json, os, sys, math, platform, datetime, itertools
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from kr import EXPERIMENT_ID, MODEL_VERSION
from kr.operators import C0, C0_PLUS
from kr.capabilities import evaluate, REQUIRED, CAP
from kr.scenarios import run_scenarios, SCENARIOS
from kr.ablate import loo, pairwise, triples, atom_loo, smuggling_probe
from kr.properties import run_with_vacuity
from kr.variants import VARIANTS, use, ops_for
from kr.infotheory import context_experiment, confounding_experiment
from kr.reach import exclusive_atoms

SEEDS = [1, 7, 13, 101, 2718]
TRIALS = 2000
R = "results"
os.makedirs(R, exist_ok=True)

def wilson(k, n, z=1.96):
    if n == 0: return [0.0, 0.0]
    p = k/n; d = 1+z*z/n
    c = (p+z*z/(2*n))/d; h = z*math.sqrt(p*(1-p)/n+z*z/(4*n*n))/d
    return [max(0.0, c-h), min(1.0, c+h)]

META = dict(experiment_id=EXPERIMENT_ID, model_version=MODEL_VERSION,
            date=datetime.date.today().isoformat(), python=platform.python_version(),
            seeds=SEEDS, trials_per_seed=TRIALS,
            capability_model="C1..C24 + C25(added)", operator_sets=["C0(13)", "C0+(14)"])

def dump(name, obj):
    obj = dict(_meta=META, **obj)
    json.dump(obj, open(f"{R}/{name}.json", "w"), indent=1, default=str)
    print("wrote", f"{R}/{name}.json")

def main():
    # 1 baselines
    b0, bp = evaluate(C0.values()), evaluate(C0_PLUS.values())
    dump("baseline", dict(C0=dict(score=b0["score"], total=b0["total"], lost=b0["lost"],
                                  kinds=b0["kinds"], pool=b0["pool"]),
                          C0_plus=dict(score=bp["score"], total=bp["total"], lost=bp["lost"],
                                       kinds=bp["kinds"], pool=bp["pool"]),
                          scenarios_C0=run_scenarios(C0.values()),
                          scenarios_C0_plus=run_scenarios(C0_PLUS.values())))
    # 2 ablations
    full, l = loo(C0_PLUS)
    scen = {}
    for n in C0_PLUS:
        r = run_scenarios([o for k, o in C0_PLUS.items() if k != n])
        scen[n] = dict(passed=sum(x["passed"] for x in r),
                       failed=[x["id"] for x in r if not x["passed"]])
    dump("ablation_loo", dict(leave_one_out=l, scenario_ablation=scen,
                              exclusive_atoms=exclusive_atoms(list(C0_PLUS.values()))))
    pw = pairwise(C0_PLUS)
    dump("ablation_pairwise", dict(pairs=pw,
         interacting={k: v for k, v in pw.items() if v["interaction"]},
         triples={**triples(C0_PLUS, ("DetectGap", "Discriminate", "Determine")),
                  **triples(C0_PLUS, ("Hypothesize", "Infer", "Represent"))}))
    dump("ablation_atoms", dict(atom_leave_one_out=atom_loo(C0_PLUS)))

    # 3 randomized properties + vacuity
    rand = {}
    for n in [None]+list(C0_PLUS):
        ops = [o for k, o in C0_PLUS.items() if k != n]
        f = {f"P{i}": 0 for i in range(1, 11)}; a = dict(f)
        for s in SEEDS:
            ff, aa = run_with_vacuity(ops, s, TRIALS)
            for k in f: f[k] += ff[k]; a[k] += aa[k]
        N = len(SEEDS)*TRIALS
        rand[n or "_baseline"] = {k: dict(failures=f[k], rate=f[k]/N, ci95=wilson(f[k], N),
                                          guard_active_rate=a[k]/N,
                                          vacuous=(a[k] == 0)) for k in f}
    dump("randomized", dict(n_per_arm=len(SEEDS)*TRIALS, results=rand))

    # 4 minimal kernels
    names = list(C0_PLUS); covers = []
    for r in range(1, len(names)+1):
        for c in itertools.combinations(names, r):
            if not evaluate([C0_PLUS[x] for x in c])["lost"]: covers.append(set(c))
    minimal = [sorted(c) for c in covers if not any(d < c for d in covers)]
    dump("minimal_kernels", dict(n_covering_subsets=len(covers), minimal_kernels=minimal))

    # 5 variants
    var = {}
    for vid, v in VARIANTS.items():
        with use(v):
            ops = ops_for(v); fl, rr = loo(ops)
            var[vid] = dict(desc=v["desc"], baseline_lost=fl["lost"],
                            results={n: r["result"] for n, r in rr.items()},
                            derivable=[n for n, r in rr.items() if r["result"] != "A-irreducible"])
    dump("variants", dict(variants=var))

    # 6 smuggling
    probes = [("DetectGap", "Validate"), ("Challenge", "Validate"), ("Determine", "Infer"),
              ("Select", "Determine"), ("Interpret", "Represent"), ("Qualify", "Observe"),
              ("Hypothesize", "Infer")]
    dump("smuggling", dict(probes=[smuggling_probe(C0_PLUS, a, b) for a, b in probes]))

    # 7 information theory + causal
    dump("information_causal", dict(context_dpi=context_experiment(),
                                    confounding=confounding_experiment()))
    print("\nDONE.")

if __name__ == "__main__":
    main()
