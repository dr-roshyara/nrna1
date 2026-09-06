"""Emit the case-level corpus that was NEVER PERSISTED, by exact reconstruction.

FINDING (audit): property-results.json is purely AGGREGATE.  The only case-level records
saved were the 481 irreducible witnesses + 14 illustrative ones.  The 1,395 size-tests
were never written out.

They ARE exactly reconstructible: the generator is deterministic, the seeds are recorded,
and audit.py already reproduced every published figure from them.  This script emits the
corpus and re-verifies the aggregates against property-results.json.

UNIT OF ANALYSIS -- important, and it differs from the schema proposed in review:
  a "case" in the 1,395 is a LEVEL TEST  (D, T, Pi, m)  covering ALL subsets of size m,
  not a single subset S.  Both grains are emitted:
      cases.jsonl    1,395 level records   <- the unit that produces the published numbers
      subsets.jsonl  every individual subset with its Zero status
"""
import itertools, random, json, os, collections
from zero_algebra import *
from generators import corpus
from interaction_order import minimal_k, canonical_sig
from experiments import SEED, N, MAXN, TSET, PSET

OFF = 1                                     # O1's offset -- the 1,395 population

def emit():
    rng = random.Random(SEED+OFF)
    raw = corpus(N, SEED+OFF)
    cases, subs = [], []
    cid = 0
    for c in raw:
        D = c["rep"]
        if not (2 <= len(D) <= MAXN): continue
        t, p = rng.choice(TSET), rng.choice(PSET)
        if contract_is_vacuous(D, t, p): continue
        n = len(D)
        base = dict(
            corpus_index=c["id"], seed=SEED+OFF, representation_class=c["cls"],
            generator_shape=c["shape"], n=n,
            D_tokens=[i.token for i in D.items],
            D_sources=[i.source for i in D.items],
            D_polarities=[i.polarity for i in D.items],
            D_scopes=[i.scope for i in D.items],
            D_uncertainties=[i.uncertainty for i in D.items],
            transformation=t, contract=p,
            transformation_class=("relational" if t in
                {"T2_dedup","T4_context","T8_interacting"} else "elementwise"),
            contract_class=("cancelling" if p=="P9_balance" else "noncancelling"))
        # ---- finer grain: every non-empty subset with its Zero status
        for k_ in range(1, n+1):
            for S in itertools.combinations(range(n), k_):
                subs.append({**base, "subset": list(S), "subset_size": k_,
                             "zero": zero(D, t, p, set(S))})
        # ---- the published unit: one record per (context, m) level test
        for m in range(2, n+1):
            n_sub = len(list(itertools.combinations(range(n), m)))
            if n_sub < 2: continue          # incomparable: skipped, as in the experiment
            cid += 1
            mk, w = minimal_k(D, t, p, m)
            rec = {**base, "record_id": f"case_{cid:04d}", "subset_size_m": m,
                   "n_subsets_at_m": n_sub,
                   "minimal_k": mk if mk is not None else "irreducible",
                   "irreducible": mk is None,
                   "zero_count_at_m": sum(1 for S in itertools.combinations(range(n), m)
                                          if zero(D, t, p, set(S)))}
            if mk is None and w is not None:
                rec["k_exhausted"] = w["k_exhausted"]
                rec["colliding_subsets"] = w["colliding_subsets"]
                rec["colliding_zero"] = [zero(D,t,p,set(s)) for s in w["colliding_subsets"]]
            cases.append(rec)
    return cases, subs

if __name__ == "__main__":
    ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    OUT = os.path.join(ROOT, "corpus"); os.makedirs(OUT, exist_ok=True)
    cases, subs = emit()
    with open(os.path.join(OUT,"cases.jsonl"),"w") as f:
        for r in cases: f.write(json.dumps(r, default=str)+"\n")
    with open(os.path.join(OUT,"subsets.jsonl"),"w") as f:
        for r in subs: f.write(json.dumps(r, default=str)+"\n")
    # ---- CSV of the level records, for the fields review asked about
    import csv
    cols=["record_id","corpus_index","representation_class","generator_shape","n",
          "transformation","transformation_class","contract","contract_class",
          "subset_size_m","n_subsets_at_m","zero_count_at_m","minimal_k","irreducible"]
    with open(os.path.join(OUT,"cases.csv"),"w",newline="") as f:
        wcsv=csv.DictWriter(f,fieldnames=cols,extrasaction="ignore"); wcsv.writeheader()
        for r in cases: wcsv.writerow(r)
    # ---- VERIFY against the published aggregates
    pub = json.load(open(os.path.join(ROOT,"property-results.json")))["O1_minimal_k"]
    dist = collections.Counter(str(r["minimal_k"]) for r in cases)
    ok_total = (len(cases) == pub["size_tests"])
    ok_dist  = (dict(dist) == {k:v for k,v in pub["minimal_k_distribution"].items()})
    ok_irre  = (sum(1 for r in cases if r["irreducible"]) == pub["irreducible"])
    print(f"emitted  cases.jsonl {len(cases)} records | subsets.jsonl {len(subs)} records")
    print(f"\nVERIFICATION against published O1:")
    print(f"  size_tests   emitted {len(cases):5d}  published {pub['size_tests']:5d}  MATCH={ok_total}")
    print(f"  distribution emitted {dict(dist)}")
    print(f"               published {pub['minimal_k_distribution']}  MATCH={ok_dist}")
    print(f"  irreducible  emitted {sum(1 for r in cases if r['irreducible']):5d}  published {pub['irreducible']:5d}  MATCH={ok_irre}")
    print(f"\n  ALL AGGREGATES REPRODUCE: {ok_total and ok_dist and ok_irre}")
