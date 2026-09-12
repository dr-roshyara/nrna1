"""PHASE C — Zero Closure Decision, run ONLY now that U's meanings are known.

The review's order:  define what U means → test Sat_c → choose Zero semantics.

Phase B established the U-reason vocabulary actually used.  Partitioning it by
WHO CAN ACT yields a fourth Zero reading the review did not list.
"""
from .sat3 import TOP, BOT, U
from .phaseB import CASES, Just
from .satc_spec import CLASSES

# ------------------------------------------------------- the partition Phase B enables
AGENT_REMEDIABLE = {"UNOBSERVED", "UNINTERPRETED", "UNDERDETERMINED",
                    "INSUFFICIENT_PROVENANCE"}          # more epistemic work would help
THEORY_BLOCKED   = {"NO_EVALUATOR", "NO_ORDERING",
                    "NO_TEMPORAL_SEMANTICS", "DELTA_UNDEFINED"}   # the THEORY is missing
WORLD_BLOCKED    = {"UNOBSERVABLE"}                      # nothing helps

def classify(reason):
    if reason in AGENT_REMEDIABLE: return "agent-remediable"
    if reason in THEORY_BLOCKED:   return "theory-blocked"
    if reason in WORLD_BLOCKED:    return "world-blocked"
    return "unclassified"

# ------------------------------------------------------- the four readings
def zero_strict(vals, reasons):   return all(v == TOP for v in vals.values())
def zero_weak(vals, reasons):     return not any(v == BOT for v in vals.values())
def zero_kleene(vals, reasons):
    if any(v == BOT for v in vals.values()): return BOT
    if any(v == U   for v in vals.values()): return U
    return TOP
def zero_reasoned(vals, reasons):
    """NEW — the reading the two-level Sat makes possible:
    no violation, and no U that the AGENT could still act on.
    'The agent has done everything it can; what remains is the theory's or the world's.'"""
    if any(v == BOT for v in vals.values()): return False
    return not any(classify(reasons[c]) == "agent-remediable"
                   for c, v in vals.items() if v == U)

READINGS = dict(strict=zero_strict, weak=zero_weak, kleene=zero_kleene, reasoned=zero_reasoned)

def run():
    out = {}
    for name, case in CASES.items():
        vals = case["observed"]
        reasons = {c: Just(c, v, name) for c, v in vals.items()}
        buckets = {}
        for c, v in vals.items():
            if v == U: buckets.setdefault(classify(reasons[c]), []).append(c)
        out[name] = dict(desc=case["desc"],
                         values=vals, reasons=reasons, U_buckets=buckets,
                         zero_strict=zero_strict(vals, reasons),
                         zero_weak=zero_weak(vals, reasons),
                         zero_kleene=zero_kleene(vals, reasons),
                         zero_reasoned=zero_reasoned(vals, reasons))
    # do the readings order?  strict ⊆ reasoned ⊆ weak is the conjecture
    order_ok = all((not c["zero_strict"] or c["zero_reasoned"]) and
                   (not c["zero_reasoned"] or c["zero_weak"]) for c in out.values())
    disagreements = {n: dict(strict=c["zero_strict"], reasoned=c["zero_reasoned"],
                             weak=c["zero_weak"], kleene=c["zero_kleene"])
                     for n, c in out.items()
                     if len({c["zero_strict"], c["zero_reasoned"], c["zero_weak"]}) > 1}
    return dict(cases=out, implication_order_strict_reasoned_weak=order_ok,
                disagreements=disagreements)
