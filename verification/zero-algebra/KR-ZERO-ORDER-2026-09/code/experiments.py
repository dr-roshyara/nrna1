"""O1-O6 — the interaction order of eliminability."""
import itertools, random, collections
from zero_algebra import *
from generators import corpus
from interaction_order import canonical_sig, minimal_k, context_profile

SEED = 20260902; N = 1500; MAXN = 6
TSET = list(TRANSFORMS); PSET = list(CONTRACTS)
NONCANCEL = [p for p in PSET if p != "P9_balance"]

def _rep(D): return [dict(token=i.token, polarity=i.polarity, source=i.source) for i in D.items]

def _contexts(off, contracts=None):
    rng = random.Random(SEED+off); cs = contracts or PSET; out = []
    for c in corpus(N, SEED+off):
        D = c["rep"]
        if not (2 <= len(D) <= MAXN): continue
        t, p = rng.choice(TSET), rng.choice(cs)
        if contract_is_vacuous(D, t, p): continue
        out.append({"id": c["id"], "D": D, "T": t, "Pi": p, "n": len(D), "cls": c["cls"]})
    return out

# ---------------------------------------------------------------- O1 minimal k
def O1_minimal_k(contracts=None, off=1):
    ctxs = _contexts(off, contracts)
    dist = collections.Counter(); by_m = collections.defaultdict(collections.Counter)
    irre = 0; tot = 0; wit = []
    for c in ctxs:
        for m in range(2, c["n"] + 1):
            k, w = minimal_k(c["D"], c["T"], c["Pi"], m)
            if k is None and w is None: continue      # not comparable
            tot += 1
            key = k if k is not None else "irreducible"
            dist[key] += 1; by_m[m][key] += 1
            if k is None:
                irre += 1
                if len(wit) < 10:
                    wit.append({"case": c["id"], "T": c["T"], "Pi": c["Pi"],
                                "n": c["n"], "D": _rep(c["D"]), **w})
    return {"question": "O1 -- smallest k such that Zero(S) IS determined by proper "
                        "subsets of size <= k, within a fixed context",
            "n_contexts": len(ctxs), "size_tests": tot,
            "minimal_k_distribution": {str(k): v for k, v in sorted(dist.items(), key=str)},
            "by_subset_size": {str(m): {str(k): v for k, v in sorted(cc.items(), key=str)}
                               for m, cc in sorted(by_m.items())},
            "irreducible": irre,
            "irreducible_rate": round(irre/tot, 4) if tot else None,
            "witnesses": wit}

# ---------------------------------------------------------------- O2 robustness
def O2_robustness():
    """The methodological invariant, applied: does the phenomenon survive removal of the
    executor-added cancelling contract?"""
    a = O1_minimal_k(PSET, 2); b = O1_minimal_k(NONCANCEL, 2)
    for x in (a, b): x.pop("witnesses", None)
    return {"question": "O2 -- does the interaction-order result survive contract-provenance "
                        "analysis?",
            "all_contracts": {k: a[k] for k in
                ("size_tests","minimal_k_distribution","irreducible","irreducible_rate")},
            "excluding_cancelling_contract": {k: b[k] for k in
                ("size_tests","minimal_k_distribution","irreducible","irreducible_rate")},
            "robust": (b["irreducible"] > 0),
            "note": ("required by the methodological invariant: a structural finding must be "
                     "tested against the provenance of the contracts that make it observable")}

# ---------------------------------------------------------------- O3 what drives k
def O3_drivers():
    ctxs = _contexts(3)
    by = {"Pi": collections.defaultdict(collections.Counter),
          "T":  collections.defaultdict(collections.Counter),
          "cls":collections.defaultdict(collections.Counter)}
    for c in ctxs:
        for m in range(2, c["n"] + 1):
            k, w = minimal_k(c["D"], c["T"], c["Pi"], m)
            if k is None and w is None: continue
            key = k if k is not None else "irreducible"
            for dim, val in (("Pi", c["Pi"]), ("T", c["T"]), ("cls", c["cls"])):
                by[dim][val][key] += 1
    def summarize(d):
        return {kk: {"n": sum(v.values()),
                     "irreducible": v.get("irreducible", 0),
                     "rate": round(v.get("irreducible",0)/sum(v.values()), 4)}
                for kk, v in sorted(d.items())}
    return {"question": "O3 -- which factor drives interaction order?",
            "by_contract": summarize(by["Pi"]), "by_transformation": summarize(by["T"]),
            "by_representation_class": summarize(by["cls"])}

# ---------------------------------------------------------------- O4 cross-context
def O4_cross_context():
    """The STRICTLY STRONGER requirement: is Zero(S) determined by its <=k signature
    ACROSS contexts?  KR-ZERO-GROUP already established context-dependence, so a failure
    here is EXPECTED and is reported to keep the two questions apart."""
    ctxs = _contexts(4)
    for k in (1, 2, 3):
        table = collections.defaultdict(set)
        for c in ctxs:
            for m in range(max(2, k+1), c["n"] + 1):
                for S in itertools.combinations(range(c["n"]), m):
                    S = frozenset(S)
                    sig = (m, canonical_sig(c["D"], c["T"], c["Pi"], S, k))
                    table[sig].add(zero(c["D"], c["T"], c["Pi"], S))
        amb = sum(1 for v in table.values() if len(v) > 1)
        if k == 1: out = {}
        out[f"k={k}"] = {"signatures": len(table), "ambiguous": amb,
                         "determined": amb == 0}
    return {"question": "O4 -- cross-context determination (strictly stronger)",
            "results": out,
            "expected": ("failure is EXPECTED -- context-dependence is already established "
                         "(KR-ZERO-GROUP case I, robust). Reported so the within-context "
                         "result in O1 is not confused with this one")}

# ---------------------------------------------------------------- O5 monotonicity of k
def O5_k_monotone_in_m():
    ctxs = _contexts(5); mono = non = 0; wit = []
    for c in ctxs:
        prof = context_profile(c["D"], c["T"], c["Pi"], MAXN)
        if not prof: continue
        ks = [(m, v) for m, v in sorted(prof["profile"].items()) if v != "irreducible"]
        if len(ks) < 2: continue
        vals = [v for _, v in ks]
        if all(vals[i] <= vals[i+1] for i in range(len(vals)-1)): mono += 1
        else:
            non += 1
            if len(wit) < 8:
                wit.append({"case": c["id"], "T": c["T"], "Pi": c["Pi"],
                            "profile": {str(m): v for m, v in prof["profile"].items()},
                            "D": _rep(c["D"])})
    return {"question": "O5 -- does required order k grow with subset size m?",
            "monotone": mono, "non_monotone": non,
            "verdict": "CONFIRMED monotone" if non == 0 else "REFUTED -- k is not monotone in m",
            "witnesses": wit}

# ---------------------------------------------------------------- O6 the ladder
def O6_ladder():
    """Where on the element -> pair -> triple -> ... ladder does the phenomenon sit?"""
    o1 = O1_minimal_k(NONCANCEL, 6); o1.pop("witnesses", None)
    d = o1["minimal_k_distribution"]
    tot = sum(d.values())
    return {"question": "O6 -- the interaction-order ladder, cancelling contract EXCLUDED",
            "distribution": d, "total": tot,
            "k_equals_1_share": round(d.get("1",0)/tot, 4) if tot else None,
            "k_ge_2_share": round(sum(v for k,v in d.items() if k.isdigit() and int(k)>=2)/tot,4) if tot else None,
            "irreducible_share": round(d.get("irreducible",0)/tot, 4) if tot else None,
            "reading": ("k=1 means singleton data suffices; k>=2 means genuinely higher-order "
                        "interaction; 'irreducible' means NO proper-subset information "
                        "determines the group's eliminability")}
