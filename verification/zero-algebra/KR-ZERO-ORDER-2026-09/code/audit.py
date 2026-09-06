"""Denominator reconciliation + full irreducible-witness dump.  No new claims."""
import itertools, random, collections, json, os
from zero_algebra import *
from generators import corpus
from interaction_order import minimal_k, canonical_sig
from experiments import SEED, N, MAXN, TSET, PSET, NONCANCEL

def pipeline(off, contracts, label):
    """Every filter stage, counted."""
    rng = random.Random(SEED+off); cs = contracts
    raw = corpus(N, SEED+off)
    st = {"label": label, "seed": SEED+off, "generated": len(raw)}
    size_ok = [c for c in raw if 2 <= len(c["rep"]) <= MAXN]
    st["dropped_size_out_of_range"] = len(raw) - len(size_ok)
    st["size_ok"] = len(size_ok)
    # NOTE: the rng is consumed in the SAME order as _contexts(), so the (t,p) draws match
    ctxs, vac = [], 0
    for c in raw:
        D = c["rep"]
        if not (2 <= len(D) <= MAXN): continue
        t, p = rng.choice(TSET), rng.choice(cs)
        if contract_is_vacuous(D, t, p): vac += 1; continue
        ctxs.append({"id": c["id"], "D": D, "T": t, "Pi": p, "n": len(D), "cls": c["cls"]})
    st["dropped_vacuous"] = vac; st["contexts"] = len(ctxs)
    # size-test stage
    tests = 0; skipped_incomparable = 0; per_m = collections.Counter()
    for c in ctxs:
        for m in range(2, c["n"]+1):
            n_sub = len(list(itertools.combinations(range(c["n"]), m)))
            if n_sub < 2: skipped_incomparable += 1; continue
            tests += 1; per_m[m] += 1
    st["skipped_incomparable_fewer_than_2_subsets"] = skipped_incomparable
    st["size_tests"] = tests
    st["size_tests_by_m"] = dict(sorted(per_m.items()))
    return st, ctxs

def irreducible_dump(ctxs, label):
    """EVERY irreducible witness, fully specified."""
    out = []
    for c in ctxs:
        D, t, p = c["D"], c["T"], c["Pi"]
        for m in range(2, c["n"]+1):
            k, w = minimal_k(D, t, p, m)
            if k is None and w is not None:
                coll = [frozenset(s) for s in w["colliding_subsets"]]
                out.append({
                  "sample": label, "case_id": c["id"], "n": c["n"],
                  "representation_class": c["cls"], "transformation": t, "contract": p,
                  "subset_size_m": m, "k_exhausted": w["k_exhausted"],
                  "D_tokens": [i.token for i in D.items],
                  "D_polarities": [i.polarity for i in D.items],
                  "D_sources": [i.source for i in D.items],
                  "colliding_subsets": [sorted(s) for s in coll],
                  "colliding_zero_status": [zero(D,t,p,s) for s in coll],
                  "singleton_zero": {str(i): zero(D,t,p,{i}) for i in range(c["n"])},
                  "shared_signature_k": w["k_exhausted"]})
    return out

if __name__ == "__main__":
    ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    report = {"note": ("O1, O2 and O6 use DIFFERENT SEED OFFSETS and are therefore "
                       "DIFFERENT SAMPLES, not different filters on one sample. This is "
                       "the primary source of the differing denominators."),
              "constants": {"N_generated_per_sample": N, "powerset_bound_MAXN": MAXN,
                            "base_seed": SEED}}
    samples = [(1, PSET, "O1 (all contracts, off=1)"),
               (2, PSET, "O2-A (all contracts, off=2)"),
               (2, NONCANCEL, "O2-B (non-cancelling, off=2)"),
               (6, NONCANCEL, "O6 (non-cancelling, off=6)")]
    report["pipelines"] = []; allw = []
    for off, cs, lab in samples:
        st, ctxs = pipeline(off, cs, lab)
        w = irreducible_dump(ctxs, lab)
        st["irreducible_found"] = len(w)
        st["irreducible_rate"] = round(len(w)/st["size_tests"], 4) if st["size_tests"] else None
        report["pipelines"].append(st); allw += w
    json.dump(report, open(os.path.join(ROOT,"filter-accounting.json"),"w"), indent=1)
    json.dump(allw, open(os.path.join(ROOT,"witnesses","ALL_irreducible_witnesses.json"),"w"),
              indent=1, default=str)
    for st in report["pipelines"]:
        print(f"{st['label']:32s} gen {st['generated']} -> size_ok {st['size_ok']} "
              f"(-{st['dropped_size_out_of_range']}) -> ctx {st['contexts']} (-{st['dropped_vacuous']} vac) "
              f"-> tests {st['size_tests']} (-{st['skipped_incomparable_fewer_than_2_subsets']} incomparable) "
              f"| irreducible {st['irreducible_found']} ({st['irreducible_rate']})")
    print(f"\ntotal irreducible witnesses dumped: {len(allw)}")
