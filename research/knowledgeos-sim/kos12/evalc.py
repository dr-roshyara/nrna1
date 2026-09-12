"""EXPERIMENT G — Eval_c as the primary object.  [EXP] CANDIDATE, NOT CANONICAL.

    Eval_c(K_t, r, Γ_t) → EVal_c              primary
    Sat_c := value ∘ Eval_c                   a CANDIDATE PROJECTION, tested not assumed

Rule 1: if the corpus does not define something, mark OPEN — do not infer a definition
because the simulator needs one.
"""
from dataclasses import dataclass, field

# ---------------------------------------------------------------- EVal structure
@dataclass
class EVal:
    """The evaluation RESULT.  Deliberately richer than a truth value; whether the
    three-valued projection is sufficient is Phase A6's question, not an assumption."""
    value: str                    # "T" | "F" | "U" | "C"?  (C is a CANDIDATE, see A1)
    reason: str = None            # why — required whenever value is not T/F
    evaluator: str = "candidate"  # candidate | OPEN | NO_EVALUATOR
    dependencies: tuple = ()      # other evaluators this one consulted
    contradiction: bool = False   # was the input contradictory?
    provenance_state: str = "n/a"
    temporal_state: str = "n/a"
    operational_state: str = "n/a"
    theory_status: str = "[PROP]"
    domain_restricted: bool = False   # was the evaluator partial on a declared restriction?
    trace: tuple = ()             # PB-5′: some defects are invisible in `value`

    def key_full(self):
        return (self.value, self.reason, self.evaluator, self.contradiction,
                self.provenance_state, self.temporal_state, self.operational_state,
                self.domain_restricted, self.trace)
    def key_value(self):
        return (self.value,)

def value(ev):  return ev.value          # the candidate projection Sat_c

# ---------------------------------------------------------------- A1 three content models
def eval_content_3valued(K):
    """three-valued: no member of {T,F,U} for a contradictory state."""
    p, np_ = "p" in K, "np" in K
    if p and np_:
        return EVal(value="UNDEFINED", reason="CONTRADICTORY_INPUT", evaluator="candidate",
                    contradiction=True, theory_status="[NEG]",
                    trace=("no value in {T,F,U} exists for this input",))
    if p:   return EVal("T", None, "candidate")
    if np_: return EVal("F", None, "candidate")
    return EVal("U", "UNOBSERVED", "candidate")

def eval_content_4valued(K):
    """four-valued: adds C.  Changes the codomain of the WHOLE family."""
    p, np_ = "p" in K, "np" in K
    if p and np_: return EVal("C", "CONTRADICTION", "candidate", contradiction=True,
                              theory_status="[HYPOTHESIS]")
    if p:   return EVal("T", None, "candidate")
    if np_: return EVal("F", None, "candidate")
    return EVal("U", "UNOBSERVED", "candidate")

def eval_content_delegated(K, consistency_available=False):
    """delegated: consult Sat_consistency first.  Contr is UNDEFINED in the corpus,
    so the delegate is unavailable and the result is U(NO_EVALUATOR) — theory U,
    not epistemic U."""
    p, np_ = "p" in K, "np" in K
    if p and np_:
        if not consistency_available:
            return EVal("U", "NO_EVALUATOR", "OPEN", dependencies=("consistency",),
                        contradiction=True, theory_status="[OPEN]",
                        trace=("delegated to Sat_consistency; Contr is undefined",))
        return EVal("F", "CONTRADICTION_DETECTED", "candidate", dependencies=("consistency",),
                    contradiction=True)
    if p:   return EVal("T", None, "candidate", dependencies=("consistency",))
    if np_: return EVal("F", None, "candidate", dependencies=("consistency",))
    return EVal("U", "UNOBSERVED", "candidate", dependencies=("consistency",))

CONTENT_MODELS = dict(three_valued=eval_content_3valued,
                      four_valued=eval_content_4valued,
                      delegated=eval_content_delegated)

# ---------------------------------------------------------------- A3 governance
def eval_governance(K, r, authorities=None):
    """Rule 1 + A3.  The corpus states only that governance satisfaction *must be
    authoritative* and *may not be inferred from content*.  It defines NO authority
    artifact, NO evidence of authority, and NO conflict-resolution rule.

    Therefore the honest candidate returns OPEN / NO_EVALUATOR.
    Supplying an authority TABLE (as `KR-SIM-2026-09-02-E` did) invents governance."""
    return EVal("U", "NO_EVALUATOR", "OPEN", theory_status="[OPEN]",
                trace=("corpus defines no governance artifact, no authority evidence, "
                       "no conflict rule; an authority table would be invented semantics",))

# ---------------------------------------------------------------- A4 temporal
def eval_temporal(K, r):
    """A4 requires distinguishing VALID/world time, OBSERVATION time and
    TRANSACTION/record time, and forbids equating timestamps with temporal validity.

    The corpus supplies only a single undifferentiated `t`.  The predecessor
    experiment's interval-coverage evaluator EQUATED coverage with validity — which
    A4 explicitly forbids.  It is therefore RETRACTED here."""
    return EVal("U", "NO_TEMPORAL_SEMANTICS", "OPEN", theory_status="[OPEN]",
                temporal_state="single undifferentiated t; no valid/observation/record split",
                trace=("KR-SIM-2026-09-02-E's Eval_Time is retracted: it equated interval "
                       "coverage with temporal validity, which A4 forbids",))

# ---------------------------------------------------------------- A5 operational
def eval_operational(K, r, kappa_class="content", depth=0, max_depth=8):
    """A5: the evaluator MUST expose its dependency graph, and κ = operational must
    NOT be silently excluded."""
    if not r.get("delta_defined", False):
        return EVal("U", "DELTA_UNDEFINED", "OPEN", theory_status="[OPEN]",
                    operational_state="δ undefined (Step 290)",
                    trace=("δ is not defined by the theory",))
    if kappa_class == "operational":
        if depth >= max_depth:
            return EVal("U", "NON_TERMINATING", "candidate", theory_status="[NEG]",
                        dependencies=("operational",)*depth,
                        trace=tuple(f"Eval_op depth {i}" for i in range(depth+1)) +
                              ("cut off by an ARBITRARY bound — not a semantic result",))
        return eval_operational(K, r, kappa_class, depth+1, max_depth)
    return EVal("T" if r.get("postcondition_met") else "F", None, "candidate",
                dependencies=("delta", f"eval_{kappa_class}"),
                operational_state="δ defined and applicable")

# ---------------------------------------------------------------- the executable three
def eval_evidence(K, r):
    w = K.get("weight", 0.0)
    if w >= r.get("e_min", 1.0): return EVal("T", None, "candidate")
    if K.get("evidence_seen") and w == 0: return EVal("F", None, "candidate")
    return EVal("U", "UNDERDETERMINED" if K.get("evidence_seen") else "UNOBSERVED", "candidate")

def eval_provenance(K, r):
    if K.get("provenance"): return EVal("T", None, "candidate", provenance_state="present")
    if K.get("provenance_refuted"): return EVal("F", None, "candidate", provenance_state="refuted")
    return EVal("U", "INSUFFICIENT_PROVENANCE", "candidate", provenance_state="absent")

def eval_status(K, r):
    """⪰ is undefined by the theory — Rule 1 forbids inventing an order."""
    return EVal("U", "NO_ORDERING", "OPEN", theory_status="[OPEN]",
                trace=("⪰ on epistemic status is not defined by the theory",))

def eval_consistency(K, r):
    """Contr is undefined — and PB-2's only clean repair delegates to THIS."""
    return EVal("U", "NO_EVALUATOR", "OPEN", theory_status="[OPEN]",
                trace=("Contr is not defined; the 4th-value question is OPEN",))

EVALUATORS = dict(content=None,      # supplied per A1 model
                  evidence=eval_evidence, provenance=eval_provenance,
                  status=eval_status, consistency=eval_consistency,
                  governance=eval_governance, temporal=eval_temporal,
                  operational=eval_operational)
