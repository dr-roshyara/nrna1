#!/usr/bin/env python3
"""
KSME-17 -- Executable Semantic Seed (ESS).

CONSTRUCTION, not recovery. Built on top of the historical framework found
in Question 15 -> Question 17 -> Step 016 (2026-08-26/27, top-level
phase_measure_theory/), which establishes:
    delta: K x E -~-> K   (partial function)
    Pre(K,e), Post(K,e,K')
    event/command/transition separation
    H_{t+1} = H_t || e_t (append-only)
but leaves 3 gaps unresolved (KSME-16's own finding):
    G1 -- "epistemic state MAY be updated" (EvidenceAdded, ConflictResolved)
    G2 -- implicit Assertion/retire object construction
    G3 -- Rollback's "different provenance" claim, unconstructed

Every construction decision below is tagged SOURCE / DERIVED / CONSTRUCTION
/ HYPOTHESIS. Per the standing discipline: CONSTRUCTION and HYPOTHESIS are
NEVER promoted to SOURCE. This module does not claim the historical corpus
specifies any of the candidate variants below -- it constructs them, labels
them, and hands them to BSE for a materiality verdict.
"""
from __future__ import annotations
from dataclasses import dataclass, field
from typing import FrozenSet, Tuple


# ============================================================ G2: minimal state carrier
# CONSTRUCTION: the historical framework never gives Assertion a closed field
# list. This carrier keeps only fields directly named in Question 15's own
# event definitions (SOURCE: P, Sigma, E, tau, Pi) plus an explicit identity
# and retirement flag (CONSTRUCTION: needed to make "retires old assertion"
# and set-membership well-defined at all -- Question 15 never says how).
@dataclass(frozen=True)
class Assertion:
    id: int                       # CONSTRUCTION -- identity, not in source
    P: str                        # SOURCE -- proposition label
    Sigma: str                    # SOURCE -- epistemic status
    E: FrozenSet[str]             # SOURCE -- evidence set
    tau: int                      # SOURCE -- validity marker (simplified to an int clock)
    Pi: str                       # SOURCE -- provenance label
    retired: bool = False         # CONSTRUCTION -- needed for "retires old assertion"


@dataclass(frozen=True)
class K:
    """Minimal state carrier. CONSTRUCTION: (A,R) only -- Z_t/L_t explicitly
    excluded per Question 15's OWN revised model (Option A: Zero/Lord belong
    to the broader system state S_t, not K). Evidence is folded into each
    Assertion's E field rather than a separate global set (CONSTRUCTION,
    simplification -- the source never specifies a global evidence carrier
    distinct from per-assertion evidence)."""
    A: FrozenSet[Assertion] = field(default_factory=frozenset)
    R: FrozenSet[Tuple[int, int, str]] = field(default_factory=frozenset)  # (from_id, to_id, type)
    rollback_marker: Tuple[str, ...] = field(default_factory=tuple)  # CONSTRUCTION, see G3

    def get(self, aid: int) -> Assertion | None:
        for a in self.A:
            if a.id == aid:
                return a
        return None


def fresh_id(P: str, Sigma: str, E: frozenset, tau: int, Pi: str) -> int:
    """CONSTRUCTION FIX (KSME-17 closure bug): the original global-counter
    fresh_id() made identity depend on CALL ORDER, not on content -- two BFS
    explorations reaching "the same" conceptual assertion via different
    paths would get different ids, so the reachable state space was never
    actually well-defined or finite-closable. This is a disclosed
    CONSTRUCTION decision, not a source fact: identity is now a deterministic
    function of an assertion's own content (P,Sigma,E,tau,Pi), so BFS closure
    from any seed is well-defined and reproducible regardless of exploration
    order. This does NOT claim the historical corpus specifies content-
    addressed identity -- G2 (object construction) remains UNDERIVED; this
    is one disclosed way to make it computable at all."""
    return hash((P, Sigma, tuple(sorted(E)), tau, Pi)) & 0xFFFFFFFF


# ============================================================ AssertionCreated -- SOURCE, low ambiguity
def assertion_created(k: K, P: str, Sigma: str, E: frozenset, tau: int, Pi: str) -> K:
    """SOURCE-grounded shape (Question 15 sec 6.1/2.2): Pre requires P/Sigma/E
    all in their respective spaces (DERIVED: trivially true for any well-typed
    call here). CONSTRUCTION: identity assignment (fresh_id)."""
    a = Assertion(id=fresh_id(P, Sigma, E, tau, Pi), P=P, Sigma=Sigma, E=E, tau=tau, Pi=Pi)
    return K(A=k.A | {a}, R=k.R, rollback_marker=k.rollback_marker)


# ============================================================ EvidenceAdded -- G1 ambiguity lives here
def evidence_added_C1(k: K, aid: int, evidence: str) -> K:
    """CONSTRUCTION candidate 1: 'add evidence only' -- the minimal reading of
    the SOURCE text ('Adds Evidence to A'), treating the 'may trigger
    Re-evaluate' clause as NOT automatically firing."""
    a = k.get(aid)
    if a is None or a.retired:
        return k  # Pre fails, per Question 15's own partiality -- CONSTRUCTION: no-op on failure
    new_a = Assertion(a.id, a.P, a.Sigma, a.E | {evidence}, a.tau, a.Pi, a.retired)
    return K(A=(k.A - {a}) | {new_a}, R=k.R, rollback_marker=k.rollback_marker)


def evidence_added_C2(k: K, aid: int, evidence: str) -> K:
    """CONSTRUCTION candidate 2: 'add evidence + deterministic epistemic
    update' -- resolves the SOURCE's 'may be updated' as ALWAYS updating,
    via an explicitly disclosed CONSTRUCTION rule (more evidence => stronger
    Sigma). This rule itself is not source-stated; it is one plausible
    completion among many."""
    a = k.get(aid)
    if a is None or a.retired:
        return k
    new_E = a.E | {evidence}
    new_Sigma = "Strong" if len(new_E) >= 2 else a.Sigma  # CONSTRUCTION rule
    new_a = Assertion(a.id, a.P, new_Sigma, new_E, a.tau, a.Pi, a.retired)
    return K(A=(k.A - {a}) | {new_a}, R=k.R, rollback_marker=k.rollback_marker)


# ============================================================ ValueRevised -- G2 object-construction ambiguity
def value_revised(k: K, aid: int, V_new: str, tau: int) -> K:
    """SOURCE-grounded shape: 'creates new assertion, retires old, creates
    relationship' (Question 15 sec 3.4.3 / sec 6.3). CONSTRUCTION: 'retired'
    field and 'supersedes' relation type are both inventions needed to make
    this concrete -- the source names the effects, never their representation."""
    old = k.get(aid)
    if old is None or old.retired:
        return k
    retired_old = Assertion(old.id, old.P, old.Sigma, old.E, old.tau, old.Pi, retired=True)
    new_a = Assertion(fresh_id(V_new, old.Sigma, old.E, tau, old.Pi), V_new, old.Sigma, old.E, tau, old.Pi, retired=False)
    return K(
        A=(k.A - {old}) | {retired_old, new_a},
        R=k.R | {(old.id, new_a.id, "supersedes")},
        rollback_marker=k.rollback_marker,
    )


# ============================================================ ConflictResolved -- G1 ambiguity, second instance
def conflict_resolved_C1(k: K, aid1: int, aid2: int, strategy: str) -> K:
    """CONSTRUCTION candidate 1: 'record only' -- resolves the conflict
    relation, assertion Sigma untouched (the minimal reading; treats 'may be
    updated' as not firing)."""
    return K(A=k.A, R=k.R | {(aid1, aid2, f"conflict-resolved:{strategy}")},
              rollback_marker=k.rollback_marker)


def conflict_resolved_C2(k: K, aid1: int, aid2: int, strategy: str) -> K:
    """CONSTRUCTION candidate 2: 'evidence-based strategy retracts the loser'
    -- a disclosed, specific completion of the SOURCE's own named strategy
    list (sec 3.6.1: 'Evidence-Based: Determine correct assertion'). Only
    fires for strategy=='evidence-based'; otherwise behaves like C1."""
    if strategy != "evidence-based":
        return conflict_resolved_C1(k, aid1, aid2, strategy)
    a1, a2 = k.get(aid1), k.get(aid2)
    if a1 is None or a2 is None:
        return k
    loser = a1 if len(a1.E) < len(a2.E) else a2  # CONSTRUCTION rule: fewer evidence items loses
    new_loser = Assertion(loser.id, loser.P, "Retracted", loser.E, loser.tau, loser.Pi, loser.retired)
    return K(A=(k.A - {loser}) | {new_loser},
              R=k.R | {(aid1, aid2, f"conflict-resolved:{strategy}")},
              rollback_marker=k.rollback_marker)


# ============================================================ RollbackPerformed -- G3 provenance ambiguity
def rollback_C1_no_marker(k_target: K, reason: str) -> K:
    """CONSTRUCTION candidate 1: rollback returns a state STRUCTURALLY
    IDENTICAL to the historical target -- tests the SOURCE's own claim
    ('different provenance') against a carrier that cannot represent it.
    If this candidate is behaviorally indistinguishable from literally
    reusing k_target, the source's 'different provenance' claim is
    UNWITNESSED by this carrier, not merely unimplemented."""
    return k_target


def rollback_C2_with_marker(k_target: K, reason: str) -> K:
    """CONSTRUCTION candidate 2: adds an explicit rollback_marker field
    (CONSTRUCTION -- not in any source-defined tuple) recording that this
    state was reached via rollback, distinguishing it from k_target
    structurally, per the source's own claim."""
    return K(A=k_target.A, R=k_target.R, rollback_marker=k_target.rollback_marker + (f"rollback:{reason}",))
