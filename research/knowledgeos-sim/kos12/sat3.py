"""Three-valued, class-indexed satisfaction  Sat : 𝒦 × ℛ → {⊤, ⊥, U}.

The pivotal v1.2 change: **absence is not negation**.
`p ∉ Content(K)` does NOT mean `¬p ∈ Content(K)`.
"""
TOP, BOT, U = "T", "F", "U"

# Kleene (strong) conjunction, as specified in §10 of the Sat document
def conj(vals):
    vals = list(vals)
    if any(v == BOT for v in vals): return BOT
    if all(v == TOP for v in vals): return TOP
    return U

CLASSES = ["content", "evidence", "provenance", "status", "consistency",
           "governance", "temporal", "operational"]

def sat_content(K, r):
    a = K.get(r["prop"])
    if a is None: return U                       # absent, NOT negated
    if a["status"] == "unique" and a["value"] is not None: return TOP
    if a["status"] == "negative": return BOT
    return U

def sat_evidence(K, r):
    a = K.get(r["prop"])
    if a is None: return U
    if a.get("weight", 0) >= r.get("e_min", 1.0): return TOP
    if a.get("weight", 0) == 0 and a.get("evidence_seen", False): return BOT
    return U                                     # existence ≠ sufficiency

def sat_provenance(K, r):
    a = K.get(r["prop"])
    if a is None: return U
    if a.get("provenance"): return TOP
    if a.get("provenance_refuted"): return BOT
    return U

def sat_status(K, r):
    """§5 — depends on an ordering ⪰ that the theory has NOT defined."""
    a = K.get(r["prop"])
    if a is None: return U
    order = {"cannot-determine": 0, "underdetermined": 1, "unique": 2}
    need = order.get(r.get("s_min", "unique"), 2)
    have = order.get(a["status"], 0)
    if a["status"] not in order: return U
    return TOP if have >= need else BOT

def sat_consistency(K, r):
    a = K.get(r["prop"])
    if a is None: return U                       # unknown contradiction ≠ no contradiction
    if a["status"] == "underdetermined" and len(a["A"]) > 1:
        return U                                 # cannot evaluate: rivals still live
    return TOP

def sat_governance(K, r):
    """§7 — cannot be inferred from semantic content; needs an authority."""
    auth = r.get("authority")
    if auth is None: return U
    return TOP if auth.get(r.get("g"), None) is True else \
           BOT if auth.get(r.get("g"), None) is False else U

def sat_temporal(K, r):
    a = K.get(r["prop"])
    if a is None: return U
    if a.get("interval") is None: return U       # time is not metadata
    return TOP if a["interval"] == r.get("I") else U

def sat_operational(K, r):
    """§9 — if δ is undefined/inapplicable the answer is U, NEVER automatically ⊥."""
    if not r.get("delta_defined", False): return U
    return TOP if r.get("postcondition_met", False) else BOT

DISPATCH = dict(content=sat_content, evidence=sat_evidence, provenance=sat_provenance,
                status=sat_status, consistency=sat_consistency, governance=sat_governance,
                temporal=sat_temporal, operational=sat_operational)

def Sat(K, r):
    return DISPATCH[r["class"]](K, r)

# ---------------------------------------------------------------- Gap partition
def GapPartition(K, reqs):
    """v1.1 had Δ = {r : ¬Sat}.  With three values that set SPLITS."""
    vals = {r["id"]: Sat(K, r) for r in reqs}
    return dict(violated=tuple(i for i, v in vals.items() if v == BOT),
                undetermined=tuple(i for i, v in vals.items() if v == U),
                satisfied=tuple(i for i, v in vals.items() if v == TOP),
                per_req=vals)

# ---------------------------------------------------------------- Zero candidates
def Zero_strict(g):   return len(g["violated"]) == 0 and len(g["undetermined"]) == 0
def Zero_weak(g):     return len(g["violated"]) == 0
def Zero_threeval(g):
    """Zero is itself three-valued — the reading the Kleene semantics forces."""
    if g["violated"]:     return BOT
    if g["undetermined"]: return U
    return TOP

ZERO_READINGS = dict(strict=Zero_strict, weak=Zero_weak, three_valued=Zero_threeval)
