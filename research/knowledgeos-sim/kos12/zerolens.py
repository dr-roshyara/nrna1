"""ZERO LENS as a boundary-producing examination (Zero Concept v1.2).

    ZL(K_t, I_t, Γ_t, L_t) → B_t          the boundary, richer than any truth value
    π : 𝓑 → V                              a POTENTIALLY LOSSY projection

Tests whether routing closure through B rather than through value(EVal) repairs D-0
(a contradictory state closing under all four value-based Zero readings).

[EXP] CANDIDATE. Zero is a LENS here, not a state, not a value, not a primitive.
"""
from dataclasses import dataclass
from .expG import CASES, CLASSES, evaluate_case, BUCKET
from .evalc import CONTENT_MODELS

@dataclass(frozen=True)
class Boundary:
    """One boundary condition. Richer than U by construction."""
    kind: str            # NotEstablished | Conflict | Unrepresentable | TheoryIncomplete
    cls: str             # which requirement class exposed it
    reason: str
    remediable_by: str   # agent | theory | world | governance | normative | n/a
    blocking: bool       # does this boundary condition block closure?

def zero_lens(case_name, content_model="delegated"):
    """ZL(K,I,Γ,L) → B.  L here = 'requirement-class examination'."""
    ev = evaluate_case(CASES[case_name], content_model)
    B = []
    for cls, e in ev.items():
        if e.contradiction:
            B.append(Boundary("Conflict", cls, e.reason or "CONTRADICTION",
                              "normative", blocking=True))
        elif e.value == "F":
            B.append(Boundary("NotEstablished", cls, "established violation",
                              "agent", blocking=True))
        elif e.value == "U":
            b = BUCKET.get(e.reason, "n/a")
            B.append(Boundary("TheoryIncomplete" if b == "theory" else "NotEstablished",
                              cls, e.reason, b, blocking=(b == "agent")))
        elif e.value == "UNDEFINED":
            B.append(Boundary("Unrepresentable", cls, e.reason or "NO_VALUE_EXISTS",
                              "theory", blocking=True))
        elif e.value == "C":
            B.append(Boundary("Conflict", cls, "CONTRADICTION", "normative", blocking=True))
    return tuple(B), ev

def pi_value(B):
    """The coarse projection π : 𝓑 → {T,F,U} — what Sat_c did."""
    if any(b.kind == "NotEstablished" and b.reason == "established violation" for b in B):
        return "F"
    return "U" if B else "T"

def zero_boundary(B):
    """Closure over the BOUNDARY: no blocking boundary condition."""
    return not any(b.blocking for b in B)

def run():
    out = {}
    for m in CONTENT_MODELS:
        for c in CASES:
            B, ev = zero_lens(c, m)
            vals = {k: e.value for k, e in ev.items()}
            weak = not any(v == "F" for v in vals.values())
            reasoned = weak and not any(BUCKET.get(ev[k].reason) == "agent"
                                        for k, v in vals.items() if v == "U")
            out[f"{m}/{c}"] = dict(
                n_boundary=len(B),
                kinds=sorted({b.kind for b in B}),
                blocking=[f"{b.cls}:{b.kind}" for b in B if b.blocking],
                pi_value=pi_value(B),
                zero_boundary=zero_boundary(B),
                zero_weak=weak, zero_reasoned=reasoned)
    return out
