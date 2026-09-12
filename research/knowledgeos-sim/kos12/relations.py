"""v1.2 §4 — FIVE state-level relations that may never substitute for one another.

    Identity  ≠  Structural equality (=)  ≠  Semantic equivalence (≡)
              ≠  Observational equivalence (≈)  ≠  Provenance-sensitive equivalence (≅_λ)

The experiment TESTS non-substitutability rather than assuming it: for each ordered
pair it searches for a witness where the two relations disagree.
"""
import itertools, json

def identity(a, b):
    """id(K) = H(P,e,c,t,Π) — the recorded identifier, not the content."""
    return a.get("id") == b.get("id")

def struct_eq(a, b):
    """= : structural equality of the whole record."""
    return _norm(a) == _norm(b)

def sem_equiv(a, b):
    """≡ : agreement on semantic content only."""
    k = lambda x: (x.get("status"), tuple(sorted(map(str, x.get("A", ())))), x.get("value"))
    return k(a) == k(b)

def obs_equiv(a, b, lam=("status", "value")):
    """≈ : agreement on an OBSERVATION WINDOW λ — parameterizable by construction."""
    return tuple(a.get(f) for f in lam) == tuple(b.get(f) for f in lam)

def prov_equiv(a, b):
    """≅_λ : semantic equivalence PLUS provenance agreement."""
    return sem_equiv(a, b) and tuple(a.get("provenance", ())) == tuple(b.get("provenance", ()))

def _norm(x):
    return json.dumps({k: (sorted(map(str, v)) if isinstance(v, (list, tuple)) else str(v))
                       for k, v in sorted(x.items()) if not k.startswith("_")}, sort_keys=True)

RELATIONS = dict(identity=identity, struct_eq=struct_eq, sem_equiv=sem_equiv,
                 obs_equiv=obs_equiv, prov_equiv=prov_equiv)

# ------------------------------------------------------- witnesses of separation
def _states():
    base = dict(id="k1", status="unique", A=["RHEL9.8"], value="RHEL9.8",
                provenance=("a1",), weight=1.0)
    return {
      "base":        dict(base),
      "same_id_diff_content": dict(base, A=["RHEL8.6"], value="RHEL8.6"),
      "diff_id_same_content": dict(base, id="k2"),
      "same_sem_diff_prov":   dict(base, id="k3", provenance=("a2",)),
      "same_obs_diff_sem":    dict(base, id="k4", A=["RHEL9.8", "RHEL8.6"],
                                   status="unique", value="RHEL9.8"),
      "diff_weight_only":     dict(base, id="k5", weight=0.4),
    }

def substitutability_matrix():
    """For each ordered pair (R1,R2): is there a witness with R1 true and R2 false?
    A witness proves R1 may NOT substitute for R2."""
    S = _states(); names = list(RELATIONS)
    out = {}
    for r1, r2 in itertools.permutations(names, 2):
        wit = None
        for n1, n2 in itertools.combinations(S, 2):
            a, b = S[n1], S[n2]
            if RELATIONS[r1](a, b) and not RELATIONS[r2](a, b):
                wit = (n1, n2); break
        out[f"{r1}->{r2}"] = dict(separable=wit is not None, witness=wit)
    return out
