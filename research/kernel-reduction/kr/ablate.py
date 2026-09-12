"""Ablation engine: leave-one-out, pairwise, and selected higher-order."""
import itertools
from .capabilities import evaluate, REQUIRED, CAP
from .reach import exclusive_atoms, atom_pool

def _ops(base, drop):
    return [o for n, o in base.items() if n not in drop]

def loo(base):
    full = evaluate(_ops(base, set()))
    out = {}
    for n in base:
        a = evaluate(_ops(base, {n}))
        lost = sorted(set(a["lost"]) - set(full["lost"]))
        out[n] = dict(
            baseline_score=full["score"], ablated_score=a["score"],
            lost_capabilities=lost,
            reconstructed=[c for c in REQUIRED
                           if c not in full["lost"] and a["capabilities"][c]],
            exclusive_atoms=exclusive_atoms(list(base.values()))[n],
            result="A-irreducible" if lost else "B-derivable",
        )
    return full, out

def pairwise(base):
    full = evaluate(_ops(base, set()))
    res = {}
    for a, b in itertools.combinations(sorted(base), 2):
        e = evaluate(_ops(base, {a, b}))
        la = sorted(set(evaluate(_ops(base, {a}))["lost"]) - set(full["lost"]))
        lb = sorted(set(evaluate(_ops(base, {b}))["lost"]) - set(full["lost"]))
        lab = sorted(set(e["lost"]) - set(full["lost"]))
        synergy = sorted(set(lab) - set(la) - set(lb))
        res[f"{a}+{b}"] = dict(lost_a=la, lost_b=lb, lost_pair=lab,
                               synergistic_loss=synergy,
                               interaction=bool(synergy))
    return res

def triples(base, candidates):
    full = evaluate(_ops(base, set()))
    res = {}
    for t in itertools.combinations(candidates, 3):
        e = evaluate(_ops(base, set(t)))
        single = set()
        for x in t:
            single |= set(evaluate(_ops(base, {x}))["lost"])
        lab = sorted(set(e["lost"]) - set(full["lost"]))
        res["+".join(t)] = dict(lost_triple=lab,
                                synergistic_loss=sorted(set(lab) - (single - set(full["lost"]))))
    return res

# ------------------------------------------------------------------ smuggling
def smuggling_probe(base, removed, absorber):
    """Explicitly test the FORBIDDEN move: give `absorber` the removed
    operator's atoms.  Always reported as SMUGGLING=TRUE; never counted as a
    reconstruction."""
    import copy
    from .operators import Op
    ops = []
    for n, o in base.items():
        if n == removed: continue
        if n == absorber:
            ops.append(Op(n + "+" + removed, set(o.atoms) | set(base[removed].atoms),
                          o.corpus, "SMUGGLED"))
        else:
            ops.append(o)
    e = evaluate(ops)
    return dict(removed=removed, absorber=absorber, smuggling=True,
                capabilities_restored=[c for c in REQUIRED if e["capabilities"][c]],
                lost_after_smuggle=e["lost"],
                verdict="REJECTED: absorption modifies the model, it is not a "
                        "composition within it")

# ------------------------------------------------------- atom-level ablation
def atom_loo(base):
    """Remove an ATOM from every operator that holds it.  This tests the
    kernel at the level of semantic powers rather than operator packaging."""
    from .operators import Op
    from .reach import atom_pool
    full = evaluate(list(base.values()))
    out = {}
    for a in sorted(atom_pool(base.values())):
        ops = [Op(n, set(o.atoms) - {a}, o.corpus, o.note) for n, o in base.items()]
        ops = [o for o in ops if o.atoms]
        e = evaluate(ops)
        lost = sorted(set(e["lost"]) - set(full["lost"]))
        out[a] = dict(holders=sorted(n for n, o in base.items() if a in o.atoms),
                      lost_capabilities=lost,
                      result="irreducible-power" if lost else "redundant-power")
    return out
