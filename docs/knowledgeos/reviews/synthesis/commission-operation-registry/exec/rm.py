#!/usr/bin/env python3
r"""
rm.py — REFERENCE MODEL for the operation-minimality test.

COMMISSION: GN-79 / GN-80 (HPA, 2026-08-31), ARCHITECTURE/THEORY lane.

NOTHING IN THIS FILE IS RATIFIED. It is an executable witness, not architecture.

WHAT THIS IS
------------
A deliberately small, total, deterministic state machine over the RATIFIED
canonical state (the 8 primitives, the 3-rung admission ladder, the A6
boundary, the FA-1 non-admission states) plus a POOL of candidate operations
drawn from the research corpus.

Its only job: make the necessity criterion

    o is primitive  <=>  exists r in R_mandatory : r not in Closure(T minus {o})

MECHANICALLY EXECUTABLE. Closure is computed as bounded reachability under
finite composition of the operations in a subset.

DECLARED MODELLING COMMITMENTS (each is a CHOICE, not a derivation; the
sensitivity of the result to each is reported in MINIMALITY-RESULT.md):

  M1. State equality is STRUCTURAL (full field equality over frozensets).
      The canon defines NO equality rule. This is model-local PROPOSED.
  M2. Rejection is TYPED with five constructors (see Rej). The canon defines
      NO rejection vocabulary. This is model-local PROPOSED.
  M3. An operation is modelled at the granularity of its corpus-stated
      signature. Where the corpus states both a fine and a coarse operation
      (e.g. Determine vs Promote vs Transform), BOTH are in the pool. No
      granularity is preferred.
  M4. Evidence strength is a SET of independent source-classes, never a
      number. This satisfies I-5/I-6 structurally WITHOUT selecting an
      aggregation operator (OQ-3 stays open by ruling).
  M5. Sigma (the epistemic direction/strength structure) is NOT used in the
      state core. Only the ratified statuses appear. Operations that require
      Sigma are marked SIGMA-DEPENDENT and their capability contribution is
      reported separately.
  M6. Guards are derived from RATIFIED constraints only (I-12, A6/I-4, I-11,
      I-5/I-6, Art.7/8/9 via FA-1 D-FA-1). Where a guard would need an
      unratified premise, the operation is left ungated and flagged.
"""
from __future__ import annotations
from dataclasses import dataclass, replace
from typing import FrozenSet, Tuple, Optional, Dict, Callable, Any

# ============================================================ RATIFIED STATUSES
# v0.2 R-3/R-4: the admission ladder is exactly three, a covering relation.
LADDER = ("Candidate", "Supported", "Accepted")
# FA-1 D-FA-1 (RATIFIED GN-31): non-admission states beside the ladder.
NON_ADMISSION = ("Rejected", "Conflicted")
ALL_STATUS = LADDER + NON_ADMISSION

def next_rung(s: str) -> Optional[str]:
    """I-12: reachable only from the immediate predecessor. No skipping."""
    if s in LADDER and LADDER.index(s) + 1 < len(LADDER):
        return LADDER[LADDER.index(s) + 1]
    return None

# ==================================================== M2: TYPED REJECTION (PROPOSED)
class Rej:
    STRUCTURAL = "REJECTED(structural)"   # referent absent / malformed / dangling
    LEGALITY   = "REJECTED(legality)"     # would violate I-12 covering relation
    POLICY     = "REJECTED(policy)"       # no in-force policy / policy refuses
    AUTHORITY  = "REJECTED(authority)"    # no authority act / not governed route
    EVIDENCE   = "REJECTED(evidence)"     # dependency unresolved (I-6)
OK = "ok"

# ==================================================================== STATE
@dataclass(frozen=True, order=True)
class Obs:
    """SourceObservation. v0.1: SourceObs != SemanticObs."""
    id: str
    dim: str
    reading: str
    source: str
    caused_by: str = ""        # id of the authorized decision that produced it, or ""

@dataclass(frozen=True, order=True)
class Ev:
    """Qualified evidence. Carries dependency structure and role (I-5/I-6)."""
    id: str
    source: str                # source CLASS -> duplicates share it (M4)
    polarity: str              # 'supports' | 'refutes'
    depends_on: str = ""       # id of another Ev this one depends on, or ""

@dataclass(frozen=True, order=True)
class Item:
    """An element of K_t over the 8 primitives (kind selects the primitive)."""
    id: str
    kind: str                  # proposition | policy | action | relation | ...
    dim: str
    value: Optional[str]       # None => recognized dimension, value UNKNOWN
    status: str
    committed: bool
    ev: FrozenSet[str]         # linked evidence ids
    grade: Optional[FrozenSet[str]]  # M4: computed independent source-class set
    withdrawn: bool = False
    version: int = 1

@dataclass(frozen=True, order=True)
class Pol:
    id: str
    version: int
    in_force: bool

@dataclass(frozen=True)
class K:
    items: FrozenSet[Item] = frozenset()
    rels: FrozenSet[Tuple[str, str, str]] = frozenset()
    obs: FrozenSet[Obs] = frozenset()
    ev: FrozenSet[Ev] = frozenset()
    dims: FrozenSet[str] = frozenset()          # D_t: recognized dimensions
    pols: FrozenSet[Pol] = frozenset()
    approvals: FrozenSet[Tuple[str, int]] = frozenset()   # governed approval decisions
    auth_acts: FrozenSet[Tuple[str, str]] = frozenset()   # (actor, target)
    zero: FrozenSet[Tuple[str, str]] = frozenset()        # (target, gap TYPE) — I-9
    proposals: FrozenSet[Tuple[str, Optional[str]]] = frozenset()  # (action, authority|None)
    decisions: FrozenSet[Tuple[str, ...]] = frozenset()   # admissible DC decisions
    validations: FrozenSet[Tuple[str, bool]] = frozenset()
    replays: FrozenSet[Tuple[str, bool]] = frozenset()
    qualified: FrozenSet[Tuple[str, str]] = frozenset()   # (obs_id, ev_id) qualification acts
    hist: Tuple[Tuple[str, str], ...] = ()      # append-only op log (State != History)

    def item(self, i: str) -> Optional[Item]:
        return next((a for a in self.items if a.id == i), None)
    def evu(self, i: str) -> Optional[Ev]:
        return next((e for e in self.ev if e.id == i), None)
    def in_force(self) -> Tuple[Pol, ...]:
        return tuple(sorted(p for p in self.pols if p.in_force))

def _put(k: K, it: Item) -> K:
    return replace(k, items=frozenset(a for a in k.items if a.id != it.id) | {it})

def log(k: K, name: str, arg: str) -> K:
    return replace(k, hist=k.hist + ((name, arg),))

# M1: structural equality. dataclass(frozen=True) eq over frozensets gives it.
def eq_structural(a: K, b: K) -> bool:
    return (a.items, a.rels, a.obs, a.ev, a.dims, a.pols, a.approvals,
            a.auth_acts, a.zero, a.proposals, a.decisions, a.qualified) == \
           (b.items, b.rels, b.obs, b.ev, b.dims, b.pols, b.approvals,
            b.auth_acts, b.zero, b.proposals, b.decisions, b.qualified)

# ============================================================ ARG ALPHABET
# Deliberately tiny and fixed, so the reachable state space is finite.
ITEMS   = ("i1", "i2")
OBSS    = ("o1", "o2")
EVS     = ("e1", "e1dup", "e3", "e4dep")
DIMS    = ("d1",)
POLS    = ("p1",)
ACTORS  = ("knower",)

# Evidence catalogue: e1 and e1dup share source class s1 (a DUPLICATE, I-5);
# e3 is an independent source class s3 (CORROBORATION, I-5);
# e4dep depends on an evidence unit that is never qualified (I-6 dependency-first).
EV_CATALOGUE = {
    "e1":    Ev("e1",    "s1", "supports", ""),
    "e1dup": Ev("e1dup", "s1", "supports", ""),
    "e3":    Ev("e3",    "s3", "supports", ""),
    "e4dep": Ev("e4dep", "s4", "supports", "eMISSING"),
}
OBS_CATALOGUE = {
    "o1": Obs("o1", "d1", "v1", "s1"),
    "o2": Obs("o2", "d1", "v1", "s3"),
}

# =================================================================== OPERATIONS
# Each op: (K, arg) -> (K, outcome). TOTAL: never raises. Deterministic.
# Provenance for every op is recorded in PROV (see bottom) — C-7 obligation.

# ---- observation / intake ----------------------------------------------------
def op_Observe(k: K, arg: str):
    """Record a SourceObservation. Forced by the flow's 'partial observation'."""
    if arg not in OBS_CATALOGUE: return k, Rej.STRUCTURAL
    o = OBS_CATALOGUE[arg]
    return log(replace(k, obs=k.obs | {o}, dims=k.dims | {o.dim}), "Observe", arg), OK

def op_RecognizeDimension(k: K, arg: str):
    """Add a dimension to D_t WITHOUT asserting a value.
    Forced by UNKNOWN != ABSENT (Art.9 / I-9, ratified via FA-1 evidence layer)."""
    if arg not in DIMS: return k, Rej.STRUCTURAL
    return log(replace(k, dims=k.dims | {arg}), "RecognizeDimension", arg), OK

def op_Qualify(k: K, arg: str):
    """Observation -> Evidence. Forced by Observation != Evidence.
    NOTE: the qualification PREDICATE is undefined in canon (OPERATION-CONTRACT-GAP C-7).
    Model-local: an observation qualifies to the evidence unit sharing its source."""
    if arg not in OBSS: return k, Rej.STRUCTURAL
    o = next((x for x in k.obs if x.id == arg), None)
    if o is None: return k, Rej.STRUCTURAL
    cand = [e for e in EV_CATALOGUE.values() if e.source == o.source]
    if not cand: return k, Rej.STRUCTURAL
    return log(replace(k, ev=k.ev | set(cand),
                       qualified=k.qualified | {(o.id, e.id) for e in cand}),
               "Qualify", arg), OK

def op_AdmitEvidence(k: K, arg: str):
    """Admit an evidence unit directly (bypassing observation).
    Model-local convenience so that evidence-composition capability can be
    tested independently of the Qualify route. Flagged NON-CORPUS."""
    if arg not in EVS: return k, Rej.STRUCTURAL
    return log(replace(k, ev=k.ev | {EV_CATALOGUE[arg]}), "AdmitEvidence", arg), OK

# ---- semantic state ----------------------------------------------------------
def op_Assert(k: K, arg: str):
    """Add an item at the FIRST rung. I-12 forbids entering above Candidate.
    Corpus: 256.2 'Add'; 272A 'Assert'."""
    if arg not in ITEMS: return k, Rej.STRUCTURAL
    if k.item(arg): return k, Rej.STRUCTURAL
    if "d1" not in k.dims: return k, Rej.STRUCTURAL   # cannot assert into an unrecognized dimension
    it = Item(arg, "proposition", "d1", "v1", "Candidate", False,
              frozenset(), None)
    return log(_put(k, it), "Assert", arg), OK

def op_AssertPolicy(k: K, arg: str):
    """Policy-as-content enters K_t like any claim (v0.2 R-1 stratification)."""
    if arg not in POLS: return k, Rej.STRUCTURAL
    if any(p.id == arg for p in k.pols): return k, Rej.STRUCTURAL
    return log(replace(k, pols=k.pols | {Pol(arg, 1, False)}), "AssertPolicy", arg), OK

def op_Retract(k: K, arg: str):
    """Remove a member. Corpus: 256.5 'Remove'; 272A 'Retract'. Remove != Withdraw."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    return log(replace(k, items=frozenset(a for a in k.items if a.id != arg),
                       rels=frozenset(r for r in k.rels if arg not in (r[0], r[1]))),
               "Retract", arg), OK

def op_Withdraw(k: K, arg: str):
    """Retraction that is NOT deletion. Corpus: 256.12 (Accepted -> Withdrawn)."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    return log(_put(k, replace(it, withdrawn=True)), "Withdraw", arg), OK

def op_Reject(k: K, arg: str):
    """Inadmissibility recorded WITHOUT deletion. Ratified: FA-1 D-FA-1, Art.7 —
    REJECTED is terminal-preserved, never a deletion."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    if it.committed: return k, Rej.AUTHORITY
    return log(_put(k, replace(it, status="Rejected")), "Reject", arg), OK

def op_Revise(k: K, arg: str):
    """Content changes, identity persists (Identity != State). Corpus 256.6.
    I-11 GUARD: an in-force policy may NOT be revised outside the governed route."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    if it.kind == "policy": return k, Rej.AUTHORITY     # I-11
    return log(_put(k, replace(it, value="v2", version=it.version + 1)), "Revise", arg), OK

def op_RevisePolicyDirect(k: K, arg: str):
    """ADVERSARIAL PROBE (not a proposed member): mutate an in-force policy with
    no governed approval. Must be refused, or I-11 is violated."""
    ps = [p for p in k.pols if p.id == arg and p.in_force]
    if not ps: return k, Rej.STRUCTURAL
    return k, Rej.AUTHORITY                             # I-11 enforced

def op_Supersede(k: K, arg: str):
    """Identity changes, content persists; records a relation (256.8, 272A)."""
    a = k.item("i1"); b = k.item("i2")
    if a is None or b is None: return k, Rej.STRUCTURAL
    return log(replace(k, rels=k.rels | {("i2", "i1", "supersedes")}), "Supersede", arg), OK

def op_Relate(k: K, arg: str):
    """Add an R edge of any type (executed algebra: 'relate'; handoff/02)."""
    a = k.item("i1"); b = k.item("i2")
    if a is None or b is None: return k, Rej.STRUCTURAL
    return log(replace(k, rels=k.rels | {("i1", "i2", arg or "refers")}), "Relate", arg), OK

def op_Derive(k: K, arg: str):
    """Record a derivation edge (lineage arises from derivation). Provenance != Lineage."""
    a = k.item("i1"); b = k.item("i2")
    if a is None or b is None: return k, Rej.STRUCTURAL
    return log(replace(k, rels=k.rels | {("i2", "i1", "derived_from")}), "Derive", arg), OK

def op_Merge(k: K, arg: str):
    """256.9 / 272A. Forced by NO non-collapse law (02-OPERATION-UNIVERSE 4.2)."""
    a = k.item("i1"); b = k.item("i2")
    if a is None or b is None: return k, Rej.STRUCTURAL
    m = replace(a, ev=a.ev | b.ev, grade=None)
    k2 = replace(k, items=frozenset(x for x in k.items if x.id not in ("i1", "i2")) | {m})
    return log(k2, "Merge", arg), OK

def op_Split(k: K, arg: str):
    """256.10 / 277. Recorded LOSSY on R by the executed algebra (handoff/02).
    Modelled AS LOSSY, faithfully: relations on the split item are dropped."""
    a = k.item("i1")
    if a is None: return k, Rej.STRUCTURAL
    p1 = replace(a, id="i1", ev=frozenset(), grade=None)
    p2 = replace(a, id="i2", ev=frozenset(), grade=None)
    k2 = replace(k, items=frozenset(x for x in k.items if x.id not in ("i1", "i2")) | {p1, p2},
                 rels=frozenset(r for r in k.rels if "i1" not in (r[0], r[1])))
    return log(k2, "Split", arg), OK

def op_Infer(k: K, arg: str):
    """272A O_S. Produce a new item from an existing one; enters at Candidate (I-12)."""
    a = k.item("i1")
    if a is None or k.item("i2"): return k, Rej.STRUCTURAL
    it = Item("i2", "proposition", a.dim, a.value, "Candidate", False, frozenset(), None)
    return log(replace(_put(k, it), rels=k.rels | {("i2", "i1", "derived_from")}), "Infer", arg), OK

# ---- evidence linkage / assessment ------------------------------------------
def op_LinkEvidence(k: K, arg: str):
    """277's T_candidate member. UNRECONCILED (handoff/02): it MUTATES an assertion,
    against the executed algebra's immutability invariant. Modelled here as a
    mutation of the item's evidence field, WITH the conflict recorded."""
    if ":" not in arg: return k, Rej.STRUCTURAL
    iid, eid = arg.split(":", 1)
    it = k.item(iid); e = k.evu(eid)
    if it is None or e is None: return k, Rej.STRUCTURAL
    return log(_put(k, replace(it, ev=it.ev | {eid}, grade=None)), "LinkEvidence", arg), OK

def op_Support(k: K, arg: str):
    """272A O_E. Attach a SUPPORTING evidence unit."""
    if ":" not in arg: return k, Rej.STRUCTURAL
    iid, eid = arg.split(":", 1)
    it = k.item(iid); e = k.evu(eid)
    if it is None or e is None or e.polarity != "supports": return k, Rej.STRUCTURAL
    return log(_put(k, replace(it, ev=it.ev | {eid}, grade=None)), "Support", arg), OK

def op_Assess(k: K, arg: str):
    """Recompute the epistemic grade from the linked evidence.
    RATIFIED OBLIGATIONS ENFORCED:
      I-6  dependency resolution precedes aggregation -> units with an
           unresolved dependency are EXCLUDED, not merely down-weighted;
      I-5  duplicates must not amplify -> the grade is the SET of independent
           source CLASSES (M4), so a duplicate is idempotent;
      I-5  corroboration must amplify -> an independent source ADDS an element.
    NO aggregation operator is selected (OQ-3 untouched)."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    units = [k.evu(e) for e in it.ev]
    if any(u is None for u in units): return k, Rej.STRUCTURAL
    resolved = [u for u in units if not u.depends_on or k.evu(u.depends_on) is not None]
    grade = frozenset(u.source for u in resolved if u.polarity == "supports")
    return log(_put(k, replace(it, grade=grade)), "Assess", arg), OK

def op_DetectContradiction(k: K, arg: str):
    """272A O_E. Ratified target state: CONFLICTED (FA-1 D-FA-1, Art.8)."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    if it.status not in LADDER: return k, Rej.LEGALITY
    units = [k.evu(e) for e in it.ev if k.evu(e)]
    if not (any(u.polarity == "supports" for u in units)
            and any(u.polarity == "refutes" for u in units)):
        # No contradiction present. Model-local: also allow an explicit
        # contradicts-relation to trigger it.
        if not any(r[2] == "contradicts" and arg in (r[0], r[1]) for r in k.rels):
            return k, Rej.EVIDENCE
    return log(_put(k, replace(it, status="Conflicted")), "DetectContradiction", arg), OK

def op_MarkConflict(k: K, arg: str):
    """Direct route to CONFLICTED via a governed act (Art.8 'governed suspension').
    Distinct from DetectContradiction, which is evidence-triggered."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    if it.status not in LADDER: return k, Rej.LEGALITY
    if not any(a[1] == arg for a in k.auth_acts): return k, Rej.AUTHORITY
    return log(_put(k, replace(it, status="Conflicted")), "MarkConflict", arg), OK

def op_Resolve(k: K, arg: str):
    """272A O_E. Art.8: CONFLICTED holds UNTIL A GOVERNED RESOLUTION.
    Guard: requires an authority act on the item."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    if it.status != "Conflicted": return k, Rej.LEGALITY
    if not any(a[1] == arg for a in k.auth_acts): return k, Rej.AUTHORITY
    return log(_put(k, replace(it, status="Supported")), "Resolve", arg), OK

def op_Reintroduce(k: K, arg: str):
    """259.7. Bring a withdrawn item back. Forced by NO non-collapse law."""
    it = k.item(arg)
    if it is None or not it.withdrawn: return k, Rej.STRUCTURAL
    return log(_put(k, replace(it, withdrawn=False)), "Reintroduce", arg), OK

# ---- admission-axis movement (THE GRANULARITY BATTLEGROUND) ------------------
def op_Promote(k: K, arg: str):
    """259.7/259.8: advances one rung. I-12 GUARD: immediate successor only.
    The Accepted rung is a DETERMINATION and is gated on an IN-FORCE
    AcceptancePolicy (v0.1 Determination concept row)."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    if it.status not in LADDER: return k, Rej.LEGALITY
    nxt = next_rung(it.status)
    if nxt is None: return k, Rej.LEGALITY
    if nxt == "Supported" and not it.grade: return k, Rej.EVIDENCE
    if nxt == "Accepted" and not k.in_force(): return k, Rej.POLICY
    return log(_put(k, replace(it, status=nxt)), "Promote", arg), OK

def op_Determine(k: K, arg: str):
    """The Supported -> Accepted transition under AcceptancePolicy.
    FORCED by Determination != Decision (oderive) yet ABSENT from 256.2 and 259.7."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    if it.status != "Supported": return k, Rej.LEGALITY
    if not k.in_force(): return k, Rej.POLICY
    return log(_put(k, replace(it, status="Accepted")), "Determine", arg), OK

def op_Transform(k: K, arg: str):
    """256.2/256.7 — the corpus's strongest operation by usage, forced by NO law.
    Modelled as the GENERIC guarded rung-advance (its corpus signature
    K x Rule -> K' admits this reading). Same ratified guards as Promote:
    I-12 covering relation, policy gate on the Accepted rung, and NO route to
    Committed (A6: only an authority act crosses that boundary)."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    if it.status not in LADDER: return k, Rej.LEGALITY
    nxt = next_rung(it.status)
    if nxt is None: return k, Rej.LEGALITY
    if nxt == "Supported" and not it.grade: return k, Rej.EVIDENCE
    if nxt == "Accepted" and not k.in_force(): return k, Rej.POLICY
    return log(_put(k, replace(it, status=nxt)), "Transform", arg), OK

# ---- governance / authority --------------------------------------------------
def op_Authorize(k: K, arg: str):
    """An AUTHORITY ACT. A6/I-4: authority determines commitment, not evidence.
    Records the act; does NOT itself set Committed (that is Commit's job)."""
    return log(replace(k, auth_acts=k.auth_acts | {("knower", arg)}), "Authorize", arg), OK

def op_Commit(k: K, arg: str):
    """Attach Committed to an ACCEPTED item BY AN AUTHORITY ACT.
    A6 GUARD (ratified): requires status == Accepted AND a recorded authority
    act. Evidence volume can never satisfy this guard."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    if it.status != "Accepted": return k, Rej.LEGALITY
    if not any(a[1] == arg for a in k.auth_acts): return k, Rej.AUTHORITY
    return log(_put(k, replace(it, committed=True)), "Commit", arg), OK

def op_CommitByEvidence(k: K, arg: str):
    """ADVERSARIAL PROBE (not a proposed member): cross A6 on evidence volume.
    Must ALWAYS be refused, or A6 is violated."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    return k, Rej.AUTHORITY                             # A6 enforced

def op_CommitBySufficientSupport(k: K, arg: str):
    """ADVERSARIAL PROBE — the ONE candidate rule in the corpus whose stated
    conditions would admit an EVIDENCE-ONLY commit. Step 025a-2 §36:

        Commit(A) only if Relevant AND TemporallyValid AND SufficientSupport
                     AND NoBlockingConflict AND ProvenanceAvailable

    Five conjuncts, NO AUTHORITY CONJUNCT. `SufficientSupport` is
    evidence-derived. Implemented VERBATIM to the rule as stated, so that the
    A6 check reports what the rule actually does rather than what its author
    presumably intended."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    relevant   = it.dim in k.dims
    temporal   = True
    sufficient = bool(it.grade) and len(it.grade) >= 2      # evidence-derived
    noconflict = it.status != "Conflicted"
    provenance = bool(it.ev)
    if relevant and temporal and sufficient and noconflict and provenance:
        return log(_put(k, replace(it, committed=True)),
                   "CommitBySufficientSupport", arg), OK
    return k, Rej.EVIDENCE

def op_Approve(k: K, arg: str):
    """256.19 `Approve: Candidate x Authority -> ApprovedCandidate`.
    Modelled as the GOVERNED, VERSIONED approval decision required by I-11."""
    ps = [p for p in k.pols if p.id == arg]
    if not ps: return k, Rej.STRUCTURAL
    p = ps[0]
    if not any(a[1] == arg for a in k.auth_acts): return k, Rej.AUTHORITY
    return log(replace(k, approvals=k.approvals | {(arg, p.version)}), "Approve", arg), OK

def op_EnactPolicyVersion(k: K, arg: str):
    """I-11 / R-1: a new in-force policy version, ONLY on a recorded governed
    approval decision. This is the ONLY route to in-force policy change."""
    ps = [p for p in k.pols if p.id == arg]
    if not ps: return k, Rej.STRUCTURAL
    p = ps[0]
    if (arg, p.version) not in k.approvals: return k, Rej.AUTHORITY   # I-11
    newp = Pol(arg, p.version + 1, True)
    return log(replace(k, pols=frozenset(x for x in k.pols if x.id != arg) | {newp}),
               "EnactPolicyVersion", arg), OK

def op_Validate(k: K, arg: str):
    """256.20 `Validate: K x Evidence -> ValidationResult`, and 256.32:
    Validate !~ Transform. Modelled as a NON-state-transforming check whose
    RESULT is recorded (recording a result is not transforming the carrier)."""
    ok = all(k.item(r[0]) and k.item(r[1]) for r in k.rels)
    return log(replace(k, validations=k.validations | {("K", ok)}), "Validate", arg), OK

# ---- gap / proposal / decision / action -------------------------------------
def op_ComputeZero(k: K, arg: str):
    """Zero(K, EC): TYPED discrepancy, four-way, must not collapse to Boolean (I-9).
    EC is fixed model-locally as 'd1 must have an ACCEPTED value'."""
    it = k.item("i1")
    if "d1" not in k.dims:
        t = "missing"
    elif it is None:
        t = "unknown"
    elif it.status == "Conflicted":
        t = "conflicting"
    elif it.status == "Rejected":
        t = "invalid"
    elif it.status == "Accepted":
        t = "satisfied"
    else:
        t = "unknown"
    return log(replace(k, zero=k.zero | {("EC", t)}), "ComputeZero", arg), OK

def op_Propose(k: K, arg: str):
    """025g / I-2: a selector that holds NO authority. Records (action, None)."""
    if not k.zero: return k, Rej.STRUCTURAL
    return log(replace(k, proposals=k.proposals | {(arg or "next", None)}), "Propose", arg), OK

def op_Decide(k: K, arg: str):
    """A decision under DC(d) = (Pre, Inv, Auth, Post, Temporal, Evidence) — 042.
    ADMISSIBLE ONLY IF ALL SIX SLOTS ARE FILLED. Auth comes via governance.
    NOTE: no ratified conjunction over exactly these six exists
    (OPERATION-CONTRACT-GAP C-8) -> the conjunction below is PROPOSED."""
    pre  = bool(k.proposals)                                  # Pre
    inv  = any(v for (_, v) in k.validations)                 # Inv
    auth = bool(k.auth_acts)                                  # Auth
    post = bool(k.zero)                                       # Post (a target gap state)
    temp = bool(k.hist)                                       # Temporal
    evid = any(a.grade for a in k.items if a.grade)           # Evidence
    if not (pre and inv and auth and post and temp and evid):
        return k, Rej.POLICY
    return log(replace(k, decisions=k.decisions | {("d1", "admissible")}), "Decide", arg), OK

def op_Act(k: K, arg: str):
    """Authorized action -> new observation. The far side of the interlock is
    OPEN BY RULING (OQ-4). Modelled minimally: an authorized decision yields an
    observation tagged with its cause."""
    if not k.decisions: return k, Rej.AUTHORITY
    o = Obs("oACT", "d1", "v1", "s1", caused_by="d1")
    return log(replace(k, obs=k.obs | {o}), "Act", arg), OK

# ---- history ----------------------------------------------------------------
def op_Replay(k: K, arg: str):
    """State != History: history must be foldable back to state.
    DEPENDS ON A STATE EQUALITY RULE, which the canon does not supply (M1)."""
    ok = len(k.hist) > 0
    return log(replace(k, replays=k.replays | {("H", ok)}), "Replay", arg), OK

def op_Trace(k: K, arg: str):
    """272A O_H. Read the lineage of an item. Non-state-transforming."""
    it = k.item(arg)
    if it is None: return k, Rej.STRUCTURAL
    return log(k, "Trace", arg), OK

def op_Query(k: K, arg: str):
    """272A O_O. A read. Non-state-transforming."""
    return log(k, "Query", arg), OK

def op_Compare(k: K, arg: str):
    """272A O_O. Requires an equality rule (M1)."""
    return log(k, "Compare", arg), OK

def op_ExplainRevision(k: K, arg: str):
    """257.4: hypothesized MANDATORY; appears in no enumeration. A read over H."""
    return log(k, "ExplainRevision", arg), OK

# ---- remaining corpus names (added so the pool is NAME-COMPLETE; several are
# ---- expected to collapse under the mechanical effect-equivalence test) ------
def op_Refute(k: K, arg: str):
    """272A O_E / 277 §277.14. Attach a REFUTING evidence unit.
    Model-local: the catalogue has no refuting unit, so a refuting view of an
    existing unit is admitted under a distinct id."""
    if ":" not in arg: return k, Rej.STRUCTURAL
    iid, eid = arg.split(":", 1)
    it = k.item(iid)
    if it is None or eid not in EV_CATALOGUE: return k, Rej.STRUCTURAL
    ru = Ev("r_" + eid, EV_CATALOGUE[eid].source + "R", "refutes", "")
    return log(_put(replace(k, ev=k.ev | {ru}), replace(it, ev=it.ev | {ru.id}, grade=None)),
               "Refute", arg), OK

def op_Add(k: K, arg: str):          return op_Assert(k, arg)            # 256.2/232.3/249/250
def op_Remove(k: K, arg: str):       return op_Retract(k, arg)           # 256.2/232.7/249/250
def op_Create(k: K, arg: str):       return op_Assert(k, arg)            # 250 §250.4
def op_Accept(k: K, arg: str):       return op_Determine(k, arg)         # 250 §250.8
def op_ChangePolicy(k: K, arg: str): return op_EnactPolicyVersion(k, arg)# 277 §277.2
def op_Refine(k: K, arg: str):                                          # D §2 / C ℛ_ref
    a = k.item("i1"); b = k.item("i2")
    if a is None or b is None: return k, Rej.STRUCTURAL
    return log(replace(k, rels=k.rels | {("i2", "i1", "refines")}), "Refine", arg), OK
def op_noop(k: K, arg: str):         return k, OK                        # E identity element
def op_Equal(k: K, arg: str):        return log(k, "Equal", arg), OK     # 272A O_O
def op_Identity(k: K, arg: str):     return log(k, "Identity", arg), OK  # 272A O_O
def op_Explain(k: K, arg: str):      return log(k, "Explain", arg), OK   # 277 §277.19
def op_Evaluate(k: K, arg: str):     return log(k, "Evaluate", arg), OK  # 277 §277.2
def op_LineageQuery(k: K, arg: str): return log(k, "LineageQuery", arg), OK
def op_ProvenanceQuery(k: K, arg: str): return log(k, "ProvenanceQuery", arg), OK
def op_SupersessionHistory(k: K, arg: str): return log(k, "SupersessionHistory", arg), OK

# ================================================================= THE POOL
# name -> (fn, tuple of admissible args)
POOL: Dict[str, Tuple[Callable[[K, str], Any], Tuple[str, ...]]] = {
    "Observe":            (op_Observe,            OBSS),
    "RecognizeDimension": (op_RecognizeDimension, DIMS),
    "Qualify":            (op_Qualify,            OBSS),
    "AdmitEvidence":      (op_AdmitEvidence,      EVS),
    "Assert":             (op_Assert,             ITEMS),
    "AssertPolicy":       (op_AssertPolicy,       POLS),
    "Retract":            (op_Retract,            ITEMS),
    "Withdraw":           (op_Withdraw,           ITEMS),
    "Reject":             (op_Reject,             ITEMS),
    "Revise":             (op_Revise,             ITEMS),
    "Supersede":          (op_Supersede,          ("-",)),
    "Relate":             (op_Relate,             ("refers", "contradicts")),
    "Derive":             (op_Derive,             ("-",)),
    "Merge":              (op_Merge,              ("-",)),
    "Split":              (op_Split,              ("-",)),
    "Infer":              (op_Infer,              ("-",)),
    "LinkEvidence":       (op_LinkEvidence,       tuple(f"i1:{e}" for e in EVS)),
    "Support":            (op_Support,            tuple(f"i1:{e}" for e in EVS)),
    "Assess":             (op_Assess,             ITEMS),
    "DetectContradiction":(op_DetectContradiction,ITEMS),
    "MarkConflict":       (op_MarkConflict,       ITEMS),
    "Resolve":            (op_Resolve,            ITEMS),
    "Reintroduce":        (op_Reintroduce,        ITEMS),
    "Promote":            (op_Promote,            ITEMS),
    "Determine":          (op_Determine,          ITEMS),
    "Transform":          (op_Transform,          ITEMS),
    "Authorize":          (op_Authorize,          ITEMS + POLS),
    "Commit":             (op_Commit,             ITEMS),
    "Approve":            (op_Approve,            POLS),
    "EnactPolicyVersion": (op_EnactPolicyVersion, POLS),
    "Validate":           (op_Validate,           ("-",)),
    "ComputeZero":        (op_ComputeZero,        ("-",)),
    "Propose":            (op_Propose,            ("next",)),
    "Decide":             (op_Decide,             ("-",)),
    "Act":                (op_Act,                ("-",)),
    "Replay":             (op_Replay,             ("-",)),
    "Trace":              (op_Trace,              ITEMS),
    "Query":              (op_Query,              ("-",)),
    "Compare":            (op_Compare,            ("-",)),
    "ExplainRevision":    (op_ExplainRevision,    ITEMS),
    "Refute":             (op_Refute,             tuple(f"i1:{e}" for e in EVS)),
    "Add":                (op_Add,                ITEMS),
    "Remove":             (op_Remove,             ITEMS),
    "Create":             (op_Create,             ITEMS),
    "Accept":             (op_Accept,             ITEMS),
    "ChangePolicy":       (op_ChangePolicy,       POLS),
    "Refine":             (op_Refine,             ("-",)),
    "noop":               (op_noop,               ("-",)),
    "Equal":              (op_Equal,              ("-",)),
    "Identity":           (op_Identity,           ITEMS),
    "Explain":            (op_Explain,            ITEMS),
    "Evaluate":           (op_Evaluate,           ITEMS),
    "LineageQuery":       (op_LineageQuery,       ITEMS),
    "ProvenanceQuery":    (op_ProvenanceQuery,    ITEMS),
    "SupersessionHistory":(op_SupersessionHistory,ITEMS),
}


# Adversarial probes: NEVER in the pool. Used only by the consistency check.
PROBES = {
    "CommitByEvidence":            (op_CommitByEvidence,            ITEMS),
    "RevisePolicyDirect":          (op_RevisePolicyDirect,          POLS),
    "CommitBySufficientSupport":   (op_CommitBySufficientSupport,   ITEMS),
}

# ---- ops whose semantics require something UNRATIFIED (surfaced, not relied on)
UNRATIFIED_DEPENDENCE = {
    "Assess":          "SIGMA — an epistemic grading axis; Sigma is UNRATIFIED "
                       "(272B |D|=4 vs DECISION-SIGMA |Sigma|=3 disagree). "
                       "Model avoids Sigma by using a SET of source classes (M4).",
    "DetectContradiction": "SIGMA — 'Conflict' as D=(1,1) is a 272B construct, UNRATIFIED.",
    "Replay":          "STATE EQUALITY — undefined in canon (M1 is model-local).",
    "Compare":         "STATE EQUALITY — undefined in canon (M1 is model-local).",
    "Query":           "Q_t — no question/query structure is ratified.",
    "ExplainRevision": "Q_t + HISTORY OBSERVABILITY — neither is ratified.",
    "Decide":          "DC CONJUNCTION — no ratified conjunction over exactly the "
                       "six DC slots exists (OPERATION-CONTRACT-GAP C-8).",
    "Act":             "OQ-4 — action/execution semantics OPEN BY RULING.",
    "Qualify":         "The qualification predicate is UNDEFINED in canon.",
    "LinkEvidence":    "UNRECONCILED against the executed algebra's immutability "
                       "invariant (handoff/02).",
    "Split":           "UNRECONCILED — executed LOSSY on R; needs a declared R policy.",
}

# ---- provenance per pool member (C-7)
PROV = {
    "Observe":            ("250 §250.5 Observe: World x Context -> Observation (verdict "
                           "'upstream epistemic operation', PLAUSIBLE); + v0.1/v0.2 flow "
                           "'partial observation'",
                           "CORPUS-NAMED (n=1 in the live universe) + RC (ratified-flow-forced)"),
    "RecognizeDimension": ("oderive <D_t> structure", "PROPOSED — forced STRUCTURE, not a corpus-named op"),
    "Qualify":            ("oderive forced (Observation != Evidence); 272A O_E", "DERIVED-by-forcing-table"),
    "AdmitEvidence":      ("none", "NON-CORPUS — model instrument only"),
    "Assert":             ("256.2 Add; 272A O_S Assert", "CORPUS-NAMED"),
    "AssertPolicy":       ("v0.2 R-1 policy-as-content", "RC — ratified-flow-forced"),
    "Retract":            ("256.2 Remove; 272A O_S Retract", "CORPUS-NAMED"),
    "Withdraw":           ("256.2/256.12 Withdraw", "CORPUS-NAMED"),
    "Reject":             ("256.2/256.11 Reject; FA-1 D-FA-1 Art.7", "CORPUS-NAMED + RATIFIED target state"),
    "Revise":             ("256.2/256.6; 257 discriminating op", "CORPUS-NAMED"),
    "Supersede":          ("256.2/256.8; 272A O_S; 257", "CORPUS-NAMED"),
    "Relate":             ("executed algebra 'relate' (handoff/02)", "EXECUTED-LANE"),
    "Derive":             ("oderive forced (Provenance != Lineage)", "DERIVED-by-forcing-table"),
    "Merge":              ("256.2/256.9; 272A O_S; 257", "CORPUS-NAMED, forced by NO law"),
    "Split":              ("256.2/256.10; 277 T_candidate", "CORPUS-NAMED, forced by NO law, UNRECONCILED"),
    "Infer":              ("272A O_S", "CORPUS-NAMED"),
    "LinkEvidence":       ("277 T_candidate", "CORPUS-NAMED, UNRECONCILED"),
    "Support":            ("272A O_E", "CORPUS-NAMED"),
    "Assess":             ("259.7 Assess:KxX->Assessment; 272A O_E", "CORPUS-NAMED + SIGNED"),
    "DetectContradiction":("272A O_E", "CORPUS-NAMED"),
    "MarkConflict":       ("FA-1 D-FA-1 Art.8 governed suspension", "RC — ratified-state-forced"),
    "Resolve":            ("272A O_E; FA-1 Art.8 'governed resolution'", "CORPUS-NAMED + RATIFIED guard"),
    "Reintroduce":        ("259.7", "CORPUS-NAMED, forced by NO law"),
    "Promote":            ("259.7/259.8", "CORPUS-NAMED (no signature anywhere)"),
    "Determine":          ("oderive forced (Determination != Decision); v0.1 Determination row",
                           "DERIVED-by-forcing-table; ABSENT from 256.2 and 259.7"),
    "Transform":          ("256.2/256.7/256.20; 257", "CORPUS-NAMED + SIGNED, forced by NO law"),
    "Authorize":          ("259.7 Authorize:ActorxActionxPolicy->Decision; 272A O_G", "CORPUS-NAMED + SIGNED"),
    "Commit":             ("v0.2 R-3 Committed boundary status; A6/I-4", "RC — ratified-boundary-forced"),
    "Approve":            ("256.19 Approve:CandidatexAuthority->ApprovedCandidate", "CORPUS-NAMED + SIGNED"),
    "EnactPolicyVersion": ("v0.2 R-1/I-11 stratification loop", "RC — ratified-flow-forced"),
    "Validate":           ("256.19/256.20 Validate:KxE->Result", "CORPUS-NAMED + SIGNED"),
    "ComputeZero":        ("v0.2 Zero(K,EC); I-9 four-way typology", "RC — ratified-object-forced"),
    "Propose":            ("025g Proposal selector; I-2", "CORPUS-NAMED (as an object)"),
    "Decide":             ("042 DC 6-tuple", "RC — ratified-object-forced; conjunction PROPOSED"),
    "Act":                ("025z/056 action loop", "CORPUS-NAMED; far side OQ-4 OPEN"),
    "Replay":             ("259.7; 272A O_H", "CORPUS-NAMED (no signature)"),
    "Trace":              ("272A O_H", "CORPUS-NAMED"),
    "Query":              ("272A O_O", "CORPUS-NAMED"),
    "Compare":            ("272A O_O", "CORPUS-NAMED"),
    "ExplainRevision":    ("257.4", "CORPUS-NAMED, hypothesized mandatory, in NO enumeration"),
}

# ================================================================== SEED STATE
def seed() -> K:
    """The canonical start: nothing known, one AcceptancePolicy already in force.
    (v0.2 requires an in-force policy to exist for Determination to be possible;
    its ARRIVAL is a separate matter, exercised by the R-POLICY capability which
    starts from a seed with the policy NOT in force.)"""
    return K(pols=frozenset({Pol("p1", 1, True)}))

def seed_nopolicy() -> K:
    return K(pols=frozenset({Pol("p1", 1, False)}))

def apply(k: K, name: str, arg: str):
    fn, _ = POOL[name]
    return fn(k, arg)

PROV.update({
    "Refute":              ("272A O_E; 277 §277.14", "CORPUS-NAMED"),
    "Add":                 ("256.2; 232.3; 249.19; 250.12", "CORPUS-NAMED (249: ESTABLISHED)"),
    "Remove":              ("256.2; 232.7; 249.12; 250.11", "CORPUS-NAMED (249: UNDEFINED at signature level)"),
    "Create":              ("250 §250.4", "CORPUS-NAMED (250: PLAUSIBLE, not established)"),
    "Accept":              ("250 §250.8", "CORPUS-NAMED (250: UNRESOLVED)"),
    "ChangePolicy":        ("277 §277.2", "CORPUS-NAMED (277: governance-EXTERNAL)"),
    "Refine":              ("D §2 'refine'; C ℛ_ref", "EXECUTED-LANE"),
    "noop":                ("E §4 identity element", "EXECUTED-LANE"),
    "Equal":               ("272A O_O", "CORPUS-NAMED"),
    "Identity":            ("272A O_O", "CORPUS-NAMED"),
    "Explain":             ("277 §277.19", "CORPUS-NAMED (277: derived)"),
    "Evaluate":            ("277 §277.2", "CORPUS-NAMED (277: supporting)"),
    "LineageQuery":        ("277 §277.2", "CORPUS-NAMED (277: supporting)"),
    "ProvenanceQuery":     ("277 §277.2", "CORPUS-NAMED (277: supporting)"),
    "SupersessionHistory": ("257 §257.17", "CORPUS-NAMED, in NO enumeration"),
})

# ============================================================ FIELD SIGNATURES
# Per-operation READ and WRITE sets over the state's fields. DECLARED, not
# inferred. Two uses:
#   (1) they are the "input state" / "state transition" content of the contracts;
#   (2) they make a SOUND relevance filter possible for the witness search:
#       an operation whose WRITES never intersect the influence closure of a
#       goal's fields cannot change whether that goal becomes true, so it can
#       appear in no witness of that goal.
#
# HIST is DELIBERATELY OMITTED from every read/write set. It is append-only and
# is read by exactly two operations (Decide, Replay), each of which needs only
# "non-empty" — a condition any single successful operation satisfies. Including
# it would place every operation in every closure and the filter would do nothing.
IT_P, IT_S, IT_C, IT_V, IT_E, IT_G, IT_W, IT_N = (
    "item.present","item.status","item.committed","item.value","item.ev",
    "item.grade","item.withdrawn","item.version")
# NB: prefixed F_ deliberately — the unprefixed names DIMS/POLS/OBS/EVS are the
# ARGUMENT ALPHABETS above, and shadowing them would silently break the guards.
F_RELS, F_OBS, F_EV, F_DIMS, F_POLS = "rels","obs","ev","dims","pols"
APPR, AUTH, ZERO, PROP, DEC, VAL, REPL, QUAL = (
    "approvals","auth_acts","zero","proposals","decisions","validations",
    "replays","qualified")

FIELDS = {
 "Observe":            (set(),                          {F_OBS, F_DIMS}),
 "RecognizeDimension": (set(),                          {F_DIMS}),
 "Qualify":            ({F_OBS},                          {F_EV, QUAL}),
 "AdmitEvidence":      (set(),                          {F_EV}),
 "Assert":             ({F_DIMS, IT_P},                   {IT_P, IT_S, IT_V}),
 "Add":                ({F_DIMS, IT_P},                   {IT_P, IT_S, IT_V}),
 "Create":             ({F_DIMS, IT_P},                   {IT_P, IT_S, IT_V}),
 "AssertPolicy":       ({F_POLS},                         {F_POLS}),
 "Retract":            ({IT_P},                         {IT_P, F_RELS}),
 "Remove":             ({IT_P},                         {IT_P, F_RELS}),
 "Withdraw":           ({IT_P},                         {IT_W}),
 "Reject":             ({IT_P, IT_C},                   {IT_S}),
 "Revise":             ({IT_P},                         {IT_V, IT_N}),
 "Supersede":          ({IT_P},                         {F_RELS}),
 "Relate":             ({IT_P},                         {F_RELS}),
 "Refine":             ({IT_P},                         {F_RELS}),
 "Derive":             ({IT_P},                         {F_RELS}),
 "Merge":              ({IT_P, IT_E},                   {IT_P, IT_E, IT_G}),
 "Split":              ({IT_P},                         {IT_P, IT_E, IT_G, F_RELS, IT_S}),
 "Infer":              ({IT_P, IT_V},                   {IT_P, IT_S, IT_V, F_RELS}),
 "LinkEvidence":       ({IT_P, F_EV},                     {IT_E, IT_G}),
 "Support":            ({IT_P, F_EV},                     {IT_E, IT_G}),
 "Refute":             ({IT_P},                         {F_EV, IT_E, IT_G}),
 "Assess":             ({IT_P, IT_E, F_EV},               {IT_G}),
 "DetectContradiction":({IT_P, IT_S, IT_E, F_EV, F_RELS},   {IT_S}),
 "MarkConflict":       ({IT_P, IT_S, AUTH},             {IT_S}),
 "Resolve":            ({IT_P, IT_S, AUTH},             {IT_S}),
 "Reintroduce":        ({IT_P, IT_W},                   {IT_W}),
 "Promote":            ({IT_P, IT_S, IT_G, F_POLS},       {IT_S}),
 "Transform":          ({IT_P, IT_S, IT_G, F_POLS},       {IT_S}),
 "Determine":          ({IT_P, IT_S, F_POLS},             {IT_S}),
 "Accept":             ({IT_P, IT_S, F_POLS},             {IT_S}),
 "Authorize":          (set(),                          {AUTH}),
 "Commit":             ({IT_P, IT_S, AUTH},             {IT_C}),
 "Approve":            ({F_POLS, AUTH},                   {APPR}),
 "EnactPolicyVersion": ({F_POLS, APPR},                   {F_POLS}),
 "ChangePolicy":       ({F_POLS, APPR},                   {F_POLS}),
 "Validate":           ({F_RELS, IT_P},                   {VAL}),
 "ComputeZero":        ({F_DIMS, IT_P, IT_S},             {ZERO}),
 "Propose":            ({ZERO},                         {PROP}),
 "Decide":             ({PROP, VAL, AUTH, ZERO, IT_G},  {DEC}),
 "Act":                ({DEC},                          {F_OBS}),
 "Replay":             (set(),                          {REPL}),
 "Trace":              ({IT_P},                         set()),
 "Query":              (set(),                          set()),
 "Compare":            (set(),                          set()),
 "Equal":              (set(),                          set()),
 "Identity":           (set(),                          set()),
 "Explain":            (set(),                          set()),
 "Evaluate":           (set(),                          set()),
 "LineageQuery":       (set(),                          set()),
 "ProvenanceQuery":    (set(),                          set()),
 "ExplainRevision":    (set(),                          set()),
 "SupersessionHistory":(set(),                          set()),
 "noop":               (set(),                          set()),
}
assert set(FIELDS) == set(POOL), (set(POOL) - set(FIELDS), set(FIELDS) - set(POOL))

def influence_closure(goal_fields):
    """Fields that can matter for a goal over `goal_fields`. Iterate to a fixed
    point: any operation writing into the set contributes its READS to the set,
    because those reads are its guard and another operation may enable it."""
    C = set(goal_fields)
    changed = True
    while changed:
        changed = False
        for n, (r, w) in FIELDS.items():
            if (w & C) and not (r <= C):
                C |= r; changed = True
    return C

def relevant_ops(goal_fields, names=None):
    C = influence_closure(goal_fields)
    pool = list(POOL) if names is None else list(names)
    return [n for n in pool if FIELDS[n][1] & C], C
