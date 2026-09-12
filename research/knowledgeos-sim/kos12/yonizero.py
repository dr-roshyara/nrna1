"""KR-YZ-2026-09-02 — Yoni-Zero Lens: empirical test of the complete-cycle claim. [EXP]

The specification (`…130334_experiment-yoni-zero-lens-…`) marks its own empirical testing
[EXP] PENDING and lists 'testing the complete cycle' as open. This module tests it.

DISCIPLINE (per the corpus's own critique `…125443_…four-conflations`):
  Yoni is [EXT] — a philosophical lens.  `Kernel = Yoni` is NOT accepted.
  Zero is a LENS, not a state producer (Zero Concept v1.2 §3: Zero ≠ K_t).
"""
import copy, json
from kos12.zeroI import VOCAB

# theory-blocked conditions established by KR-SIM-…-D/G: no amount of cycling removes them
THEORY_BLOCKED = {("G_model","no-evaluator"), ("G_model","no-ordering"),
                  ("G_model","delta-undefined"), ("G_temporal","no-temporal-semantics")}
AGENT_REMEDIABLE = {("G_evidence","not-yet-gathered"), ("G_assessment","not-assessed"),
                    ("G_assessment","insufficient-magnitude"),
                    ("G_assessment","insufficient-discrimination"),
                    ("G_value","none")}

def yoni(state):
    """§6.2 Yoni phase: generate hypotheses and proposals from (K,Q,E)."""
    s = copy.deepcopy(state)
    s["hypotheses"] = s["hypotheses"] + [f"H{len(s['hypotheses'])+1}"]
    s["proposals"]  = s["proposals"] + [f"P{len(s['proposals'])+1}"]
    s["generated"]  = s.get("generated", 0) + 1
    return s

def kernel_phase(state):
    """Apply transitions. Agent-remediable boundary conditions can be discharged here."""
    s = copy.deepcopy(state)
    disch = [b for b in s["boundary"] if tuple(b) in AGENT_REMEDIABLE]
    s["boundary"] = [b for b in s["boundary"] if tuple(b) not in AGENT_REMEDIABLE]
    s["discharged"] = s.get("discharged", 0) + len(disch)
    return s

def zero(state):
    """§6.2 Zero phase: EXAMINE. Produces a boundary report — it does NOT produce K."""
    s = copy.deepcopy(state)
    B = list(THEORY_BLOCKED)                       # always present: the theory is incomplete
    if s["hypotheses"] and len(s["hypotheses"]) > 1:
        B.append(("G_value","rivals"))             # generation itself creates a boundary
    if s.get("unassessed"):
        B.append(("G_assessment","not-assessed"))
    s["boundary"] = sorted(set(map(tuple, B)))
    s["gaps"] = [b for b in s["boundary"] if b[0] != "G_model"]
    return s

def reconcile(state):
    s = copy.deepcopy(state); s["unassessed"] = False; return s

def YZ(state):
    """§6.1 YZ(K,Q,E,A,S,C) → (K_{t+1}, B_t, G_t, Δ_t)"""
    s = yoni(state); s = kernel_phase(s); s = zero(s); s = reconcile(s)
    s["delta"] = len(s["gaps"])
    return s

def run_cycle(n=12):
    s = dict(hypotheses=["H1"], proposals=[], boundary=[], gaps=[],
             unassessed=True, generated=0, discharged=0, delta=0)
    trace=[]
    for i in range(n):
        s = YZ(s)
        trace.append(dict(iter=i+1, n_hyp=len(s["hypotheses"]),
                          boundary=sorted("%s:%s"%b for b in s["boundary"]),
                          n_boundary=len(s["boundary"]), n_gaps=len(s["gaps"]),
                          delta=s["delta"],
                          theory_blocked=sum(1 for b in s["boundary"] if tuple(b) in THEORY_BLOCKED)))
    return trace
