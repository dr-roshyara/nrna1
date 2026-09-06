"""G1-G9 — the structure of Z(D,T,Pi)."""
import itertools, random, collections
from zero_algebra import *
from generators import corpus
from group_zero import *

SEED = 20260902
N    = 1200
MAXN = 7                       # powerset bound: 2^7 = 128 subsets per case
TSET = list(TRANSFORMS); PSET = list(CONTRACTS)

def _rep(D): return [dict(token=i.token, source=i.source, polarity=i.polarity,
                          scope=i.scope, uncertainty=i.uncertainty) for i in D.items]
def _s(S):   return sorted(S)

def _cases(off):
    """(D,T,Pi) triples that are non-vacuous, non-trivial and within the powerset bound."""
    rng = random.Random(SEED + off); out = []
    for c in corpus(N, SEED + off):
        D = c["rep"]
        if not (2 <= len(D) <= MAXN): continue
        t, p = rng.choice(TSET), rng.choice(PSET)
        if contract_is_vacuous(D, t, p): continue
        Z = zero_family(D, t, p, MAXN)
        if Z is None: continue
        out.append({"id": c["id"], "D": D, "T": t, "Pi": p, "Z": Z, "n": len(D)})
    return out

# ---------------------------------------------------------------- G1 closure properties
def G1_closure_properties():
    cs = _cases(1)
    tally = {k: {"holds":0, "fails":0} for k in
             ("downward_closed","upward_closed","union_closed","intersection_closed")}
    wit = collections.defaultdict(list)
    for c in cs:
        if not c["Z"]: continue
        for name, fn in (("downward_closed", lambda: downward_closed(c["Z"])),
                         ("upward_closed",   lambda: upward_closed_within(c["Z"], c["n"])),
                         ("union_closed",    lambda: union_closed(c["Z"])),
                         ("intersection_closed", lambda: intersection_closed(c["Z"]))):
            ok, w = fn()
            tally[name]["holds" if ok else "fails"] += 1
            if not ok and len(wit[name]) < 5:
                wit[name].append({"case": c["id"], "T": c["T"], "Pi": c["Pi"],
                                  "D": _rep(c["D"]), "witness": [_s(x) for x in w]})
    return {"question": "G1 -- which closure properties does Z satisfy?",
            "n_cases": len(cs), "tally": tally,
            "verdict": {k: ("CONFIRMED" if v["fails"] == 0 else "REFUTED")
                        for k, v in tally.items()},
            "witnesses": dict(wit)}

# ---------------------------------------------------------------- G2 minimal structure
def G2_minimal_structure():
    cs = _cases(2)
    sizes = collections.Counter(); emergent = 0; tot_min = 0; wit = []
    max_sizes = collections.Counter()
    for c in cs:
        if not c["Z"]: continue
        M = minimal(c["Z"]); X = maximal(c["Z"])
        for S in M:
            sizes[len(S)] += 1; tot_min += 1
            if len(S) >= 2:
                emergent += 1
                if len(wit) < 10:
                    wit.append({"case": c["id"], "T": c["T"], "Pi": c["Pi"],
                                "minimal_zero_subset": _s(S), "size": len(S),
                                "D": _rep(c["D"])})
        for S in X: max_sizes[len(S)] += 1
    return {"question": "G2 -- what do MINIMAL Zero subsets look like?",
            "n_cases": len(cs), "total_minimal_subsets": tot_min,
            "minimal_size_distribution": dict(sorted(sizes.items())),
            "maximal_size_distribution": dict(sorted(max_sizes.items())),
            "emergent_minimal_subsets_size_ge_2": emergent,
            "emergent_fraction": round(emergent/tot_min, 4) if tot_min else None,
            "interpretation": ("a minimal Zero subset of size >= 2 is EMERGENT "
                               "eliminability: no proper part of it is eliminable"),
            "witnesses": wit}

# ---------------------------------------------------------------- G3 generation
def G3_generated_by_minimal():
    cs = _cases(3); ok = bad = 0; wit = []
    for c in cs:
        if not c["Z"]: continue
        g, (extra, missing) = generated_by_minimal(c["Z"], c["n"])
        if g: ok += 1
        else:
            bad += 1
            if len(wit) < 8:
                wit.append({"case": c["id"], "T": c["T"], "Pi": c["Pi"], "D": _rep(c["D"]),
                            "predicted_but_not_zero": [_s(x) for x in list(extra)[:4]],
                            "zero_but_not_predicted": [_s(x) for x in list(missing)[:4]],
                            "minimal": [_s(x) for x in minimal(c["Z"])]})
    return {"question": "G3 -- is Z generated upward by its minimal elements?",
            "n_cases": ok+bad, "generated": ok, "not_generated": bad,
            "verdict": "CONFIRMED" if bad == 0 else "REFUTED",
            "consequence": ("if REFUTED, the hypergraph of minimal Zero subsets does NOT "
                            "determine Z -- so Z has no compact generator-based encoding"),
            "witnesses": wit}

# ---------------------------------------------------------------- G4 matroid
def G4_matroid_circuits():
    cs = _cases(4); ok = bad = skipped = 0; wit = []
    for c in cs:
        M = minimal(c["Z"]) if c["Z"] else set()
        if len(M) < 2: skipped += 1; continue
        e, w = matroid_circuit_exchange(M)
        if e: ok += 1
        else:
            bad += 1
            if len(wit) < 8:
                C1, C2, el, U = w
                wit.append({"case": c["id"], "T": c["T"], "Pi": c["Pi"], "D": _rep(c["D"]),
                            "C1": _s(C1), "C2": _s(C2), "shared_element": el,
                            "union_minus_e": _s(U),
                            "contains_no_circuit": True,
                            "all_minimal": [_s(x) for x in M]})
    return {"question": "G4 -- do minimal Zero subsets satisfy the matroid circuit "
                        "exchange axiom (C3)?",
            "testable_cases": ok+bad, "skipped_fewer_than_2_circuits": skipped,
            "exchange_holds": ok, "exchange_fails": bad,
            "verdict": "CONFIRMED" if bad == 0 else "REFUTED -- not a matroid circuit family",
            "witnesses": wit}

# ---------------------------------------------------------------- G5 pairwise determination
def G5_pairwise_determination():
    """Can Zero(S) be predicted from the Zero-status of the SINGLETONS and PAIRS in S?
    Two subsets with an identical pairwise signature but different Zero-status refute it.
    This is the same SHAPE of question as FR-001 -- tested here independently."""
    cs = _cases(5)
    table = collections.defaultdict(set); wit = []; examined = 0
    for c in cs:
        D, t, p = c["D"], c["T"], c["Pi"]
        for S in subsets(c["n"], 3, min(4, c["n"])):
            sig = (c["n"], pairwise_signature(D, t, p, S))
            table[sig].add(zero(D, t, p, S)); examined += 1
            if len(table[sig]) > 1 and len(wit) < 8:
                wit.append({"case": c["id"], "T": t, "Pi": p, "subset": _s(S),
                            "D": _rep(D),
                            "note": "same singleton+pair signature, different group Zero"})
    ambiguous = sum(1 for v in table.values() if len(v) > 1)
    return {"question": "G5 -- is group Zero determined by its singleton and pair Zeros?",
            "subsets_examined": examined, "distinct_signatures": len(table),
            "ambiguous_signatures": ambiguous,
            "verdict": "CONFIRMED" if ambiguous == 0
                       else "REFUTED -- pairwise data does NOT determine group Zero",
            "relation_to_FR_001": ("FR-001 froze that PAIRWISE distinguishability cannot "
                                   "carry family-level complexity. This is an independent "
                                   "instance of the same shape, in a different domain."),
            "witnesses": wit}

# ---------------------------------------------------------------- G6 subset order
def G6_subset_vs_sequential():
    """Removing S at once vs removing its elements one at a time."""
    cs = _cases(6); same = diff = 0; wit = []
    for c in cs:
        D, t, p = c["D"], c["T"], c["Pi"]
        for S in c["Z"]:
            if len(S) < 2: continue
            atonce = D.without(S)
            seq = D
            for i in sorted(S, reverse=True):     # remove high indices first
                seq = seq.without({i})
            same_out = (CONTRACTS[p](TRANSFORMS[t](atonce)) ==
                        CONTRACTS[p](TRANSFORMS[t](seq)))
            if same_out: same += 1
            else:
                diff += 1
                if len(wit) < 6:
                    wit.append({"case": c["id"], "T": t, "Pi": p, "S": _s(S),
                                "D": _rep(D)})
    return {"question": "G6 -- is removing S at once the same as removing its elements "
                        "one at a time?",
            "identical": same, "divergent": diff,
            "verdict": "CONFIRMED identical" if diff == 0 else "REFUTED",
            "note": ("index-stable removal makes these agree by construction; a "
                     "divergence would indicate an implementation defect, so this "
                     "doubles as a self-check"),
            "witnesses": wit}

# ---------------------------------------------------------------- G7 overlap
def G7_overlapping_zero_subsets():
    cs = _cases(7)
    tal = {k: {"in_Z":0, "not_in_Z":0} for k in ("union","intersection","difference","sym")}
    wit = []
    for c in cs:
        Z = c["Z"]
        for A, B in itertools.combinations(sorted(Z, key=sorted), 2):
            if not (A & B): continue
            for k, S in (("union", A|B), ("intersection", A&B),
                         ("difference", A-B), ("sym", (A|B)-(A&B))):
                if not S: continue
                tal[k]["in_Z" if S in Z else "not_in_Z"] += 1
            if (A|B) not in Z and len(wit) < 6:
                wit.append({"case": c["id"], "T": c["T"], "Pi": c["Pi"],
                            "A": _s(A), "B": _s(B), "union_not_zero": _s(A|B),
                            "D": _rep(c["D"])})
    return {"question": "G7 -- how do OVERLAPPING Zero subsets combine?",
            "tally": tal,
            "closed_under": [k for k, v in tal.items() if v["not_in_Z"] == 0 and v["in_Z"] > 0],
            "witnesses": wit}

# ---------------------------------------------------------------- G8 classification
def G8_redundancy_vs_cancellation():
    cs = _cases(8)
    kinds = collections.Counter(); ex = collections.defaultdict(list)
    for c in cs:
        D, t, p, Z = c["D"], c["T"], c["Pi"], c["Z"]
        for S in minimal(Z):
            if len(S) == 1:
                k = "redundancy (singleton eliminable)"
            else:
                singles = [zero(D,t,p,{i}) for i in S]
                k = ("cancellation (no part eliminable)" if not any(singles)
                     else "mixed (some parts eliminable)")
            kinds[k] += 1
            if len(ex[k]) < 3:
                ex[k].append({"case": c["id"], "T": t, "Pi": p, "S": _s(S),
                              "tokens": [D.items[i].token for i in sorted(S)],
                              "polarities": [D.items[i].polarity for i in sorted(S)]})
    return {"question": "G8 -- are minimal Zero subsets redundancy or cancellation?",
            "classification": dict(kinds), "examples": dict(ex)}

# ---------------------------------------------------------------- G9 regime adjudication
def G9_regime_adjudication():
    g1 = G1_closure_properties(); g3 = G3_generated_by_minimal(); g4 = G4_matroid_circuits()
    g5 = G5_pairwise_determination()
    return {"question": "G9 -- which candidate mathematical regime survives?",
            "candidates": {
              "simplicial complex / independence system (downward-closed)":
                  g1["verdict"]["downward_closed"],
              "filter (upward-closed)":            g1["verdict"]["upward_closed"],
              "union-closed family":               g1["verdict"]["union_closed"],
              "closure system / lattice (intersection-closed)":
                  g1["verdict"]["intersection_closed"],
              "matroid (circuit exchange)":        g4["verdict"],
              "hypergraph GENERATED by minimal edges": g3["verdict"],
              "pairwise-determined family":        g5["verdict"]},
            "note": ("REFUTED here means the tested corpus contains counterexamples. It "
                     "does NOT mean the regime is unusable elsewhere, and NO regime is "
                     "concluded to be *the* correct framework.")}

# ---------------------------------------------------------------- G10 robustness
def G10_contract_class_robustness():
    """SELF-CHECK, and it changes the headline.

    `P9_balance` is a CANCELLING contract and was added by the executor for the previous
    experiment.  If the interesting group structure exists only under it, then the
    finding is about cancelling contracts, not about Zero in general.  So: re-run the
    structural tests with P9 EXCLUDED and report both.
    """
    out = {}
    for label, keep in (("all_contracts", lambda p: True),
                        ("excluding_P9_balance", lambda p: p != "P9_balance")):
        cs = [c for c in _cases(1) if keep(c["Pi"])]
        sizes = collections.Counter(); emergent = 0
        dc = ic = ng = 0; mx = 0; mtot = 0
        for c in cs:
            M = minimal(c["Z"])
            for S in M:
                sizes[len(S)] += 1
                if len(S) >= 2: emergent += 1
            if not downward_closed(c["Z"])[0]: dc += 1
            if not intersection_closed(c["Z"])[0]: ic += 1
            g, (extra, missing) = generated_by_minimal(c["Z"], c["n"])
            if not g:
                ng += 1
                if missing: mx += 1      # case-J direction: Zero but not predicted
                if extra:   mtot += 1    # case-I direction: predicted but not Zero
            if len(M) >= 2 and not matroid_circuit_exchange(M)[0]:
                out.setdefault("_matroid_fail_" + label, 0)
                out["_matroid_fail_" + label] = out.get("_matroid_fail_" + label, 0) + 1
        out[label] = {
          "n_cases": len(cs),
          "minimal_size_distribution": dict(sorted(sizes.items())),
          "emergent_minimal_subsets": emergent,
          "downward_closure_failures": dc,
          "intersection_closure_failures": ic,
          "not_generated_by_minimal": ng,
          "of_which_case_I_direction (superset of a Zero set is not Zero)": mtot,
          "of_which_case_J_direction (Zero set contains no Zero minimal)": mx,
          "matroid_exchange_failures": out.get("_matroid_fail_" + label, 0)}
    for k in [k for k in out if k.startswith("_")]: del out[k]
    a, b = out["all_contracts"], out["excluding_P9_balance"]
    return {"question": "G10 -- does the group structure survive removal of the CANCELLING "
                        "contract the executor added?",
            "comparison": out,
            "case_I_robust": b["not_generated_by_minimal"] > 0,
            "case_J_contract_conditional": b["emergent_minimal_subsets"] == 0,
            "verdict": ("Case I is GENERAL. Case J and the intersection/matroid failures "
                        "are CONDITIONAL on a cancelling contract."
                        if b["emergent_minimal_subsets"] == 0 and
                           b["not_generated_by_minimal"] > 0 else "see comparison"),
            "consequence": ("the previous experiment's symmetric claim -- 'Zero is not "
                            "element-wise in EITHER direction' -- must be split: the two "
                            "directions do not have the same empirical status")}
