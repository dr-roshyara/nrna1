"""§2.3-2.7 Inquiry, Ideal State, Requirement, Adequacy, Gap, Zero."""
from dataclasses import dataclass, field

@dataclass(frozen=True)
class Requirement:
    id: str
    prop: str
    kind: str = "determined"    # determined | unique | corroborated | causal | provenanced
    note: str = ""

@dataclass
class EpistemicStandard:
    """§2.10 S^epi — NOT governance, NOT authorization, NOT truth."""
    id: str
    min_weight: float = 1.0
    require_independent_sources: int = 1
    require_corroboration: bool = False
    allow_ambiguous_interpretation: bool = True

@dataclass
class EpistemicContract:
    id: str
    standard: EpistemicStandard
    requirements: tuple = ()
    attribution_policy: str = "justified-unique"   # what Γ will attribute

@dataclass
class Inquiry:
    """Q = (Target, Purpose, Context, Requirements, Constraints)"""
    id: str
    target: tuple
    purpose: str
    context: str
    requirements: tuple
    constraints: tuple = ()

def ideal_state(Q, C, S, EC):
    """§2.4 I_t = I(Q,C,S,EC) — the epistemically SUFFICIENT target, not truth."""
    return dict(inquiry=Q.id, context=C, standard=S.id, contract=EC.id,
                requirements=tuple(r.id for r in Req(Q, EC)))

def Req(Q, EC):
    return tuple(Q.requirements) + tuple(EC.requirements)

def Sat(K, r, E=None):
    """Requirement satisfaction against the KNOWLEDGE state (never the world)."""
    a = K.get(r.prop)
    if r.kind == "determined":
        return a is not None and a["status"] in ("unique", "negative", "cannot-determine")
    if r.kind == "unique":
        return a is not None and a["status"] == "unique"
    if r.kind == "corroborated":
        return a is not None and a.get("independent_sources", 0) >= 2
    if r.kind == "causal":
        return a is not None and a.get("causal_status") == "identified"
    if r.kind == "provenanced":
        return a is not None and bool(a.get("provenance"))
    return False

def Gap(K, Q, C, EC, E=None):
    """DEF-21  Δ_t = { r ∈ Req : ¬Sat(K,r) }"""
    return tuple(r for r in Req(Q, EC) if not Sat(K, r, E))

def Zero(K, Q, C, EC, E=None):
    """DEF-22  Zero ⟺ Δ_t = ∅.  A CLOSURE predicate, never certainty."""
    return len(Gap(K, Q, C, EC, E)) == 0

def Adequate(K, Q, C, EC, E=None):
    """DEF-20 — adequacy, NOT completeness."""
    return Zero(K, Q, C, EC, E)
