"""d_SNF(x, y) — mechanism-independent distance over KOS-SNF-IR v0.1.

Purpose (HPA commission):
  A function that measures how far apart two candidate meanings are, so that
  independent mechanisms can be compared WITHOUT any mechanism's internals.
  It is part of the EVALUATION layer. It never grants identity authority.

Design — two tiers:

  TIER 1 (decisive, categorical): predicate identity, negation, argument
  inversion, entity mismatch in an EXPRESSED role. A difference here is a
  *category boundary*, not a degree. Each carries a large weight so that a
  single decisive difference alone yields a high distance:

      predicate differs              -> +0.65
      negation differs               -> +0.65
      EXPRESSED role entity differs  -> +0.65 per slot (max/mean blend)
      argument inversion detected    -> +0.20 (on top of the entity swaps)

  TIER 2 (graded, epistemic): modality, presence/absence, unknown,
  ambiguous, structural presence. These are degrees, not boundaries:

      modality differs                -> +0.10
      EXPRESSED vs NOT_EXPRESSED      -> +0.40 (absence is not presence)
      EXPRESSED vs UNKNOWN            -> +0.50 (ignorance is not presence)
      NOT_EXPRESSED vs UNKNOWN        -> +0.30 (ignorance is not absence)
      UNKNOWN vs UNKNOWN              -> +0.15 (two unknowns may differ)
      one slot AMBIGUOUS, other not   -> +0.20 (underdetermined vs determined)
      role in one IR, absent in other -> +0.30 (structural difference)
      one IR AMBIGUOUS, other RESOLVED-> +0.20 (global epistemic difference)

  Role slots are blended 0.65*max + 0.35*mean over the union of roles so that a
  single decisive slot is not diluted by averaging, while cumulative structure
  still registers.

  d_snf is a *pre-metric*: non-negative, symmetric, d(x,x)=0, and decisive
  dimensions are strongly separated, but the triangle inequality is NOT
  guaranteed across category boundaries (the max/mean role blend is nonlinear).
  The pilot thresholds do not rely on the triangle inequality; the report notes
  this honestly.

  Total is clamped to [0, 1].

Sanity test (``d_snf_sanity_test``) MUST pass before mechanisms run:
    A identical          == 0
    B paraphrase          <  0.30
    C active/passive      <  0.30
    D argument inversion >= 0.60
    E negation           >= 0.60
    F different predicate>= 0.60
    G ambiguous vs clear  0.30 .. 0.85
    H incomplete vs full  0.15 .. 0.85
"""

from __future__ import annotations

import _bootstrap  # noqa: F401  (sys.path setup; see _bootstrap.py)

from ir import (IR, ROLE_AGENT, ROLE_PATIENT, ARG_EXPRESSED, ARG_UNKNOWN,
                ARG_NOT_EXPRESSED, ARG_AMBIGUOUS, ARG_NOT_LICENSED,
                UNC_RESOLVED, UNC_AMBIGUOUS)

# Tier-1 weights (decisive / categorical).
W_PREDICATE = 0.65
W_NEGATION = 0.65
W_MODALITY = 0.10
W_ENTITY_MISMATCH = 0.65
W_INVERSION = 0.20

# Tier-2 weights (graded / epistemic), per role slot.
W_PRESENT_ABSENT = 0.40      # EXPRESSED vs NOT_EXPRESSED
W_PRESENT_UNKNOWN = 0.50     # EXPRESSED vs UNKNOWN
W_ABSENT_UNKNOWN = 0.30      # NOT_EXPRESSED vs UNKNOWN
W_UNKNOWN_UNKNOWN = 0.15     # UNKNOWN vs UNKNOWN
W_AMBIGUOUS_SLOT = 0.20      # one slot AMBIGUOUS, other not
W_STRUCT_MISMATCH = 0.30     # role present in one IR, absent in the other
W_NOT_LICENSED = 0.05

# Global epistemic difference: one IR declares AMBIGUOUS, the other RESOLVED.
W_UNCERTAINTY_GLOBAL = 0.20

# ---------------------------------------------------------------------------
# Versions (v0.1 = pilot baseline, retained for A/B; v0.2 = P5 research metric)
# ---------------------------------------------------------------------------
#
# v0.2 repairs two measured defects of v0.1 (P5 plan D-2, D-3):
#
#   D-2  an optional role's PRESENCE could be diluted below the acceptance
#        threshold by role averaging, so "engineer stored the file" and
#        "engineer stored the file in the server" read as the same meaning.
#   D-3  NOT_EXPRESSED vs UNKNOWN carried 0.30 and, after the blend, landed
#        under tau — epistemic absence collapsing into semantic absence.
#
# The repair is a CATEGORY FLOOR, not a bigger weight: a status-category
# mismatch is a boundary, so the total may not fall below the floor for the
# most decisive slot. This is the same reasoning Tier 1 already applies to
# predicate/negation/entity; v0.1 simply failed to apply it to presence and
# epistemic status. Floors are declared research hyper-parameters, NOT
# architectural thresholds, and v0.1 remains callable as the A/B baseline.

V01 = "v0.1"
V02 = "v0.2"
VERSIONS = (V01, V02)
DEFAULT_VERSION = V01   # keeps the Phase-1 pilot byte-reproducible

# v0.2 category floors, per role slot (status-pair -> minimum total distance).
FLOOR_PRESENT_ABSENT = 0.50    # EXPRESSED vs NOT_EXPRESSED
FLOOR_PRESENT_UNKNOWN = 0.55   # EXPRESSED vs UNKNOWN   (ignorance != presence)
FLOOR_ABSENT_UNKNOWN = 0.45    # NOT_EXPRESSED vs UNKNOWN (ignorance != absence)
FLOOR_STRUCT_MISMATCH = 0.50   # role in one IR, absent from the other
FLOOR_AMBIG_ABSENT = 0.45      # AMBIGUOUS vs NOT_EXPRESSED
FLOOR_AMBIG_UNKNOWN = 0.45     # AMBIGUOUS vs UNKNOWN

# Role-slot blend: max-dominant so a single decisive slot is never diluted away.
ROLE_BLEND_MAX = 0.65
ROLE_BLEND_MEAN = 0.35


def _norm_entity(e: str | None) -> str | None:
    if e is None:
        return None
    s = e.strip().lower()
    if s.endswith("ies") and len(s) > 4:
        return s[:-3] + "y"
    if s.endswith("s") and not s.endswith("ss") and len(s) > 3:
        return s[:-1]
    return s


def _same_meaning(x: IR, y: IR) -> bool:
    """Are these two IRs the same meaning-bearing content?

    Compares only the semantic fields. `confidence` and `provenance` are
    experiment bookkeeping — no distance term reads them, so they cannot make
    two IRs different meanings.
    """
    def key(m: IR):
        return (m.predicate, m.negation, m.modality, m.temporal,
                m.quantification, m.uncertainty,
                tuple(sorted((a.role, _norm_entity(a.entity), a.status)
                             for a in m.arguments)))
    return key(x) == key(y)


def _predicate_term(x: IR, y: IR) -> float:
    return W_PREDICATE if x.predicate != y.predicate else 0.0


def _negation_term(x: IR, y: IR) -> float:
    return W_NEGATION if x.negation != y.negation else 0.0


def _modality_term(x: IR, y: IR) -> float:
    return W_MODALITY if x.modality != y.modality else 0.0


def _global_uncertainty_term(x: IR, y: IR) -> float:
    ax = x.uncertainty == UNC_AMBIGUOUS
    ay = y.uncertainty == UNC_AMBIGUOUS
    return W_UNCERTAINTY_GLOBAL if ax != ay else 0.0


def _slot_term(x: IR, y: IR, role: str) -> float:
    """Per-role-slot distance with EXPLICIT status-pair handling.

    Every (status, status) pair is decided by design, so that two IRs that
    agree on the same ambiguous slot are NOT separated, while underdetermined
    vs determined vs absent each remain distinguishable:
      (EX, EX) same entity  -> 0          |  different -> W_ENTITY_MISMATCH
      (NE, NE)              -> 0          |  (UK, UK)  -> W_UNKNOWN_UNKNOWN
      (AM, AM) same entity  -> 0          |  different -> 0.20
      EX vs NE              -> 0.40       |  EX vs UK  -> 0.50
      NE vs UK              -> 0.30       |  AM vs EX (same entity) -> 0.35
      AM vs EX (diff entity)-> 0.65       |  AM vs NE  -> 0.45
      AM vs UK              -> 0.45
    """
    ax = x.role_map().get(role)
    ay = y.role_map().get(role)
    if ax is None and ay is None:
        return 0.0
    if ax is None or ay is None:
        return W_STRUCT_MISMATCH

    sx, sy = ax.status, ay.status
    ex, ey = _norm_entity(ax.entity), _norm_entity(ay.entity)
    same_entity = ex is not None and ey is not None and ex == ey

    if ARG_NOT_LICENSED in (sx, sy):
        return W_NOT_LICENSED

    if sx == ARG_EXPRESSED and sy == ARG_EXPRESSED:
        return 0.0 if same_entity else W_ENTITY_MISMATCH
    if sx == ARG_NOT_EXPRESSED and sy == ARG_NOT_EXPRESSED:
        return 0.0
    if sx == ARG_UNKNOWN and sy == ARG_UNKNOWN:
        return W_UNKNOWN_UNKNOWN
    if sx == ARG_AMBIGUOUS and sy == ARG_AMBIGUOUS:
        return 0.0 if same_entity else 0.20

    pair = {sx, sy}
    if pair == {ARG_EXPRESSED, ARG_NOT_EXPRESSED}:
        return W_PRESENT_ABSENT
    if pair == {ARG_EXPRESSED, ARG_UNKNOWN}:
        return W_PRESENT_UNKNOWN
    if pair == {ARG_NOT_EXPRESSED, ARG_UNKNOWN}:
        return W_ABSENT_UNKNOWN
    if pair == {ARG_AMBIGUOUS, ARG_EXPRESSED}:
        return 0.35 if same_entity else W_ENTITY_MISMATCH
    if pair == {ARG_AMBIGUOUS, ARG_NOT_EXPRESSED}:
        return 0.45
    if pair == {ARG_AMBIGUOUS, ARG_UNKNOWN}:
        return 0.45
    return W_ENTITY_MISMATCH


def _slot_floor(x: IR, y: IR, role: str) -> float:
    """v0.2 category floor for one role slot.

    Returns the minimum total distance this slot's status pair must produce.
    0.0 when the pair is not a category boundary (same status, or a purely
    graded difference such as entity mismatch, which Tier 1 already handles).
    """
    ax = x.role_map().get(role)
    ay = y.role_map().get(role)
    if ax is None and ay is None:
        return 0.0
    if ax is None or ay is None:
        return FLOOR_STRUCT_MISMATCH

    sx, sy = ax.status, ay.status
    if sx == sy:
        return 0.0
    if ARG_NOT_LICENSED in (sx, sy):
        return 0.0          # frame difference, deliberately mild
    pair = {sx, sy}
    if pair == {ARG_EXPRESSED, ARG_NOT_EXPRESSED}:
        return FLOOR_PRESENT_ABSENT
    if pair == {ARG_EXPRESSED, ARG_UNKNOWN}:
        return FLOOR_PRESENT_UNKNOWN
    if pair == {ARG_NOT_EXPRESSED, ARG_UNKNOWN}:
        return FLOOR_ABSENT_UNKNOWN
    if pair == {ARG_AMBIGUOUS, ARG_NOT_EXPRESSED}:
        return FLOOR_AMBIG_ABSENT
    if pair == {ARG_AMBIGUOUS, ARG_UNKNOWN}:
        return FLOOR_AMBIG_UNKNOWN
    return 0.0


def _category_floor(x: IR, y: IR) -> float:
    """The strongest category boundary present across all role slots."""
    roles = {a.role for m in (x, y) for a in m.arguments}
    if not roles:
        return 0.0
    return max(_slot_floor(x, y, r) for r in roles)


def _roles_term(x: IR, y: IR) -> float:
    all_roles = set()
    for m in (x, y):
        for a in m.arguments:
            all_roles.add(a.role)
    if not all_roles:
        return 0.0
    slot_terms = [_slot_term(x, y, r) for r in all_roles]
    mx = max(slot_terms)
    mn = sum(slot_terms) / len(slot_terms)
    return ROLE_BLEND_MAX * mx + ROLE_BLEND_MEAN * mn


def _inversion_term(x: IR, y: IR) -> float:
    """Detect swapped AGENT/PATIENT over the same entity pair.

    This is a *structure* signal on top of the two entity-mismatch terms it
    already produces: the same lexical items with opposite argument direction
    are far apart.
    """
    rx = x.role_map()
    ry = y.role_map()
    ax, ay = rx.get(ROLE_AGENT), ry.get(ROLE_AGENT)
    px, py = rx.get(ROLE_PATIENT), ry.get(ROLE_PATIENT)
    if not all([ax, ay, px, py]):
        return 0.0
    if ax.status != ARG_EXPRESSED or ay.status != ARG_EXPRESSED:
        return 0.0
    if px.status != ARG_EXPRESSED or py.status != ARG_EXPRESSED:
        return 0.0
    exa, exy = _norm_entity(ax.entity), _norm_entity(ay.entity)
    exp, eyp = _norm_entity(px.entity), _norm_entity(py.entity)
    if None in (exa, exy, exp, eyp):
        return 0.0
    if exa == eyp and exp == exy:
        return W_INVERSION
    return 0.0


def d_snf(x: IR, y: IR, version: str = DEFAULT_VERSION) -> float:
    """Semantic distance in [0, 1]. Higher = more semantically distant.

    version=V01 — the Phase-1 pilot metric, unchanged (A/B baseline).
    version=V02 — the P5 research metric: identical terms, plus the category
                  floor that keeps a presence/epistemic-status boundary from
                  being averaged away (D-2, D-3).

    Still a PRE-METRIC: non-negative, symmetric, d(x,x)=0. The triangle
    inequality is NOT claimed for either version.
    """
    if version not in VERSIONS:
        raise ValueError(f"unknown d_snf version: {version}")
    if version == V02 and _same_meaning(x, y):
        # Reflexivity. v0.1 violated d(x,x)=0 whenever an IR carried an UNKNOWN
        # slot: W_UNKNOWN_UNKNOWN (0.15) separated an IR from ITSELF, because
        # "two unknowns may differ" was applied without first checking whether
        # the two IRs are the same IR. The pilot report claimed d(x,x)=0 as a
        # pre-metric property; for UNKNOWN-bearing IRs that claim was false.
        # v0.2 restores reflexivity and keeps the UNKNOWN-vs-UNKNOWN separation
        # for genuinely DISTINCT IRs. (v0.1 is left as-is: it is the A/B
        # baseline and reproducing its defects is the point.)
        return 0.0
    terms = [
        _predicate_term(x, y),
        _negation_term(x, y),
        _modality_term(x, y),
        _roles_term(x, y),
        _inversion_term(x, y),
        _global_uncertainty_term(x, y),
    ]
    total = sum(terms)
    if version == V02:
        total = max(total, _category_floor(x, y))
    return max(0.0, min(1.0, total))


def d_snf_acceptance(x: IR, y: IR, tau: float = 0.40,
                     version: str = DEFAULT_VERSION) -> bool:
    """Binarised interpretation: are these "the same meaning"?
    tau is a research hyper-parameter, NOT an architectural threshold."""
    return d_snf(x, y, version=version) <= tau


# ---------------------------------------------------------------------------
# Sanity test (must pass BEFORE any mechanism runs)
# ---------------------------------------------------------------------------

from ir import (make_ir, ROLE_INSTRUMENT, MODALITY_ASSERTED, UNC_AMBIGUOUS,
                ARG_AMBIGUOUS)

TAU_LOW = 0.30
TAU_HIGH = 0.60


def _gold_ir(predicate, agent=None, patient=None, instrument=None,
             negation=False, modality=MODALITY_ASSERTED):
    args = []
    if agent is not None:
        args.append((ROLE_AGENT, agent))
    if patient is not None:
        args.append((ROLE_PATIENT, patient))
    if instrument is not None:
        args.append((ROLE_INSTRUMENT, instrument))
    return make_ir(predicate, *args, negation=negation, modality=modality)


def _build_sanity_pairs():
    """Eight reference IR pairs, hand-written as GOLD, with expected bounds."""
    approve = _gold_ir("APPROVE", agent="committee", patient="order")
    chase_cd = _gold_ir("CHASE", agent="cat", patient="dog")
    chase_dc = _gold_ir("CHASE", agent="dog", patient="cat")
    approve_neg = _gold_ir("APPROVE", agent="committee", patient="order",
                           negation=True)
    reject = _gold_ir("REJECT", agent="committee", patient="order")

    approve_ambig = _gold_ir("APPROVE", agent="committee", patient="order",
                             instrument="manager")
    approve_ambig.arguments[-1].status = ARG_AMBIGUOUS
    approve_ambig.uncertainty = UNC_AMBIGUOUS

    incomplete = make_ir("APPROVE", (ROLE_PATIENT, "order"), (ROLE_AGENT, None))

    pairs = [
        ("A", approve, approve, 0.001, "identical"),
        ("B", approve, _gold_ir("APPROVE", agent="committee", patient="order"),
         TAU_LOW, "paraphrase (same gold IR)"),
        ("C", approve, _gold_ir("APPROVE", agent="committee", patient="order"),
         TAU_LOW, "active/passive (same gold IR)"),
        ("D", chase_cd, chase_dc, TAU_HIGH, "argument inversion"),
        ("E", approve, approve_neg, TAU_HIGH, "negation"),
        ("F", approve, reject, TAU_HIGH, "different predicate"),
        ("G", approve, approve_ambig, 0.85, "ambiguous vs clear"),
        ("H", approve, incomplete, 0.85, "incomplete vs complete"),
    ]
    return pairs


def _build_v02_pairs():
    """Six additional GOLD pairs for the v0.2 gate — the distinctions the HPA
    named as must-not-collapse (2026-08-22 amendment):

        EXPRESSED  !=  EXPRESSED+NEGATED  !=  NOT_EXPRESSED  !=  UNKNOWN

    plus the two presence cases (D-2). Every one of these must land strictly
    above the evaluator's acceptance threshold (0.40), otherwise the metric
    would report "same meaning" for a real semantic or epistemic difference.
    """
    from ir import Argument

    def store(loc_entity, loc_status):
        return IR(predicate="STORE",
                  arguments=[Argument(ROLE_AGENT, "engineer", ARG_EXPRESSED),
                             Argument(ROLE_PATIENT, "file", ARG_EXPRESSED),
                             Argument("LOCATION", loc_entity, loc_status)])

    def eat(entity, status, negation=False):
        return IR(predicate="EAT",
                  arguments=[Argument(ROLE_AGENT, "rama", ARG_EXPRESSED),
                             Argument(ROLE_PATIENT, entity, status)],
                  negation=negation)

    expressed = eat("rice", ARG_EXPRESSED)
    negated = eat("rice", ARG_EXPRESSED, negation=True)
    not_expressed = eat(None, ARG_NOT_EXPRESSED)
    unknown = eat(None, ARG_UNKNOWN)

    return [
        ("I", expressed, not_expressed, "EXPRESSED vs NOT_EXPRESSED"),
        ("J", expressed, unknown, "EXPRESSED vs UNKNOWN"),
        ("K", not_expressed, unknown, "NOT_EXPRESSED vs UNKNOWN"),
        ("L", expressed, negated, "EXPRESSED vs NEGATED"),
        ("M", store("server", ARG_EXPRESSED), store(None, ARG_NOT_EXPRESSED),
         "optional-role presence vs absence"),
        ("N", store("server", ARG_EXPRESSED), store(None, ARG_UNKNOWN),
         "optional-role presence vs unknown"),
    ]


TAU_SEPARATE = 0.40   # must exceed the evaluator's acceptance threshold


def d_snf_sanity_test() -> list[dict]:
    """Run the metric sanity test. Returns per-pair results.

    The pilot MUST NOT proceed to mechanisms until every bound holds:
      A: d == 0            (identity)
      B, C: d < 0.30       (paraphrase / active-passive low)
      D, E, F: d >= 0.60   (inversion / negation / predicate strongly separated)
      G: 0.30 <= d <= 0.85 (ambiguous is a real, moderate difference)
      H: 0.15 <= d <= 0.85 (incomplete is a real, partial difference)
    """
    return d_snf_gate(DEFAULT_VERSION)


def d_snf_gate(version: str = V02) -> list[dict]:
    """The metric gate for `version`. MUST pass before mechanisms run.

    v0.1: pairs A..H (the Phase-1 gate, unchanged).
    v0.2: pairs A..H plus I..N (the non-collapse gate) — I..N must each be
          strictly above TAU_SEPARATE.
    """
    results = []
    for pid, x, y, bound, label in _build_sanity_pairs():
        d = d_snf(x, y, version=version)
        if pid == "A":
            ok = d <= 0.001
        elif pid in ("B", "C"):
            ok = d < TAU_LOW
        elif pid in ("D", "E", "F"):
            ok = d >= TAU_HIGH
        elif pid == "G":
            ok = 0.30 <= d <= 0.85
        else:
            ok = 0.15 <= d <= 0.85
        results.append({"pair": pid, "label": label, "d_snf": round(d, 4),
                        "bound": bound, "pass": ok, "version": version})
    if version == V02:
        for pid, x, y, label in _build_v02_pairs():
            d = d_snf(x, y, version=version)
            results.append({"pair": pid, "label": label, "d_snf": round(d, 4),
                            "bound": f"> {TAU_SEPARATE}",
                            "pass": d > TAU_SEPARATE, "version": version})
    return results


if __name__ == "__main__":
    import sys
    _v = sys.argv[1] if len(sys.argv) > 1 else V02
    for r in d_snf_gate(_v):
        print(f"{r['pair']} {r['label']:<26} d={r['d_snf']:.3f} "
              f"bound<={r['bound']}  {'PASS' if r['pass'] else 'FAIL'}")
    if all(r["pass"] for r in d_snf_gate(_v)):
        print("\nSANITY TEST PASSES — mechanisms may run.")
    else:
        print("\nSANITY TEST FAILS — investigate the metric before running mechanisms.")
