#!/usr/bin/env python3
"""KR-SIM-2026-09-02 — full run.  [EXP] simulation OF THE THEORY, not the product."""
import json, os, sys, random, platform, datetime
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from kos import EXPERIMENT_ID, MODEL, THEORY, STATUS
from kos.scenarios import FAMILIES
from kos.adversarial import CASES, ORDER
from kos.properties import evaluate, CATALOG
from kos.factivity import witness, sweep
from kos.audits import (smuggling_audit, helper_audit, CIRCULARITY, type_audit,
                        information_audit)
from kos.randomized import run as run_random
from kos.types import TYPE_TABLE, COLLISIONS

SEED = 20260902
R = "results"; os.makedirs(R, exist_ok=True)
META = dict(experiment_id=EXPERIMENT_ID, model=MODEL, theory=THEORY, status=STATUS,
            date=datetime.date.today().isoformat(), python=platform.python_version(),
            seed=SEED, seeds_random=[1,7,13,101,2718], trials_per_seed=2000)

def dump(name, obj):
    json.dump(dict(_meta=META, **obj), open(f"{R}/{name}.json","w"), indent=1, default=str)
    print("wrote", f"{R}/{name}.json")

def main():
    rng = random.Random(SEED)
    # --- D scenario catalog + F test results
    scen, props = {}, {}
    for f in FAMILIES:
        s = f(rng); r = evaluate(s)
        scen[s["id"]] = dict(expect=s["expect"],
                             determinations={p: dict(status=d["status"], A=list(d["A"]),
                                                     standard_met=d["standard_met"])
                                             for p, d in (s.get("E").determinations.items()
                                                          if s.get("E") else [])},
                             K={p: dict(status=v["status"], attributed=v["attributed"],
                                        value=v["value"]) for p, v in (s.get("K") or {}).items()},
                             unknown=s.get("unknown"))
        for pid, v in r.items():
            props.setdefault(pid, {})[s["id"]] = v
    dump("scenarios", dict(scenarios=scen))
    dump("property_results", dict(catalog={k: dict(statement=v["statement"],
                                                   definitional=v["definitional"])
                                           for k, v in CATALOG.items()},
                                  per_scenario=props))
    # --- G adversarial + counterexamples
    rng2 = random.Random(SEED)
    adv, ces = {}, []
    for cid in ORDER:
        s = CASES[cid]["fn"](rng2); r = evaluate(s)
        fails = {p: v for p, v in r.items() if v["verdict"] == "FAIL"}
        adv[cid] = dict(name=CASES[cid]["name"], probe=s.get("probe"),
                        attributed={p: v["value"] for p, v in (s.get("K") or {}).items()
                                    if v["attributed"]},
                        failures=list(fails))
        for p, v in fails.items():
            ces.append(dict(id=f"CE-{len(ces)+1}", scenario=cid, property=p,
                            detail=v["detail"]))
    dump("adversarial", dict(cases=adv, counterexamples=ces))
    # --- factivity theorem witness
    dump("factivity", dict(witness=witness(), policy_sweep=sweep()))
    # --- audits
    dump("audits", dict(smuggling=smuggling_audit(), helper_writes=helper_audit(),
                        circularity=CIRCULARITY, types=type_audit(),
                        type_table={k: dict(inputs=v[0], carrier=v[1], forbidden=v[2])
                                    for k, v in TYPE_TABLE.items()},
                        notation_collisions=COLLISIONS))
    # --- randomized
    dump("randomized", run_random())
    print("\nDONE.")

if __name__ == "__main__":
    main()
