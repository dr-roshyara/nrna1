"""CANDIDATE EVALUATORS for the three theory-blocked classes.

    Eval_Gov   governance
    Eval_Time  temporal
    delta / Sat_op   operational

**These are EXPERIMENTER-SUPPLIED candidates, not theory-derived.**  The theory defines none of
them; the point of supplying them is to test PB-4's prediction — whether `Zero_strict` becomes
reachable in principle, or is unreachable by construction.

Each evaluator is deliberately PARTIAL.  An evaluator that always returned ⊤ would rig the result.
"""
from .sat3 import TOP, BOT, U

# ---- new U-reasons these evaluators can produce (the vocabulary must grow) -----
AUTHORITY_SILENT   = "AUTHORITY_SILENT"
AUTHORITY_EXPIRED  = "AUTHORITY_EXPIRED"
AUTHORITY_CONFLICT = "AUTHORITY_CONFLICT"
COVERAGE_INCOMPLETE= "COVERAGE_INCOMPLETE"
NO_INTERVAL        = "NO_INTERVAL"
PRECONDITION_UNMET = "PRECONDITION_UNMET"
KAPPA_OPERATIONAL  = "KAPPA_OPERATIONAL"     # the PB-5 regress, now reachable

NEW_REASONS = [AUTHORITY_SILENT, AUTHORITY_EXPIRED, AUTHORITY_CONFLICT,
               COVERAGE_INCOMPLETE, NO_INTERVAL, PRECONDITION_UNMET, KAPPA_OPERATIONAL]

# =============================================================== governance
def Eval_Gov(K, g, authorities, now=0):
    """Authorities are (id, grants:dict, valid_until).  Governance may NOT be inferred
    from semantic content, so K is accepted but never consulted for the grant itself."""
    live = [a for a in authorities if a["valid_until"] is None or a["valid_until"] >= now]
    expired = [a for a in authorities if a not in live]
    speaking = [a for a in live if g in a["grants"]]
    if not live and expired:            return U, AUTHORITY_EXPIRED
    if not speaking:                    return U, AUTHORITY_SILENT
    verdicts = {a["grants"][g] for a in speaking}
    if len(verdicts) > 1:               return U, AUTHORITY_CONFLICT   # resolution is NORMATIVE
    return (TOP, None) if verdicts == {True} else (BOT, None)

# =============================================================== temporal
def Eval_Time(K, p, I, intervals, counter_intervals=()):
    """`p established over I` iff the evidence intervals COVER I.
    true@t1 and false@t2 is NOT a contradiction — it is a temporal fact."""
    if I is None:                       return U, NO_INTERVAL
    lo, hi = I
    for (a, b) in counter_intervals:    # established counter-evidence inside I
        if a <= lo and b >= hi:         return BOT, None
    covered, cursor = [], lo
    for (a, b) in sorted(intervals):
        if a <= cursor <= b: cursor = max(cursor, b)
    if cursor >= hi:                    return TOP, None
    return U, COVERAGE_INCOMPLETE

# =============================================================== operational
def delta(K, o, preconditions):
    """δ(K,o) is defined iff o's preconditions hold in K."""
    unmet = [c for c in preconditions if not c(K)]
    return (None, unmet) if unmet else (dict(K, _applied=o), [])

def Sat_op(K, o, kappa, preconditions, kappa_class, restrict_kappa=True, depth=0):
    """Sat_op(K,r) = Sat_κ(δ(K,o)).  PB-5: well-founded only if κ is non-operational."""
    if kappa_class == "operational":
        if restrict_kappa:
            return U, KAPPA_OPERATIONAL          # refused: keeps the family well-founded
        if depth > 3:
            return U, KAPPA_OPERATIONAL          # regress cut off by fiat — the PB-5 defect, live
        return Sat_op(K, o, kappa, preconditions, kappa_class, restrict_kappa, depth+1)
    K2, unmet = delta(K, o, preconditions)
    if K2 is None:                      return U, PRECONDITION_UNMET
    return (TOP, None) if kappa(K2) else (BOT, None)

# =============================================================== situation matrix
AUTH = dict(
  grants     =[dict(id="A", grants={"publish": True},  valid_until=None)],
  denies     =[dict(id="A", grants={"publish": False}, valid_until=None)],
  silent     =[dict(id="A", grants={"archive": True},  valid_until=None)],
  expired    =[dict(id="A", grants={"publish": True},  valid_until=-1)],
  conflicting=[dict(id="A", grants={"publish": True},  valid_until=None),
               dict(id="B", grants={"publish": False}, valid_until=None)],
)
TIME = dict(
  full      =dict(I=(0, 10), intervals=[(0, 10)],          counter=()),
  partial   =dict(I=(0, 10), intervals=[(0, 4)],           counter=()),
  counter   =dict(I=(0, 10), intervals=[(0, 10)],          counter=((0, 10),)),
  no_interval=dict(I=None,   intervals=[],                 counter=()),
)
OPER = dict(
  met       =dict(pre=[lambda K: True],  kappa=lambda K: True,  kclass="content"),
  unmet_post=dict(pre=[lambda K: True],  kappa=lambda K: False, kclass="content"),
  pre_unmet =dict(pre=[lambda K: False], kappa=lambda K: True,  kclass="content"),
  kappa_op  =dict(pre=[lambda K: True],  kappa=lambda K: True,  kclass="operational"),
)

def evaluate_three(auth_key, time_key, oper_key, restrict_kappa=True):
    g, gj = Eval_Gov({}, "publish", AUTH[auth_key])
    t = TIME[time_key]
    tv, tj = Eval_Time({}, "p", t["I"], t["intervals"], t["counter"])
    o = OPER[oper_key]
    ov, oj = Sat_op({}, "op1", o["kappa"], o["pre"], o["kclass"], restrict_kappa)
    return dict(governance=(g, gj), temporal=(tv, tj), operational=(ov, oj))
