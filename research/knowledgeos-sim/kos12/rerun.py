"""PHASES B′ and C′ — re-run with the three candidate evaluators supplied."""
import itertools
from .sat3 import TOP, BOT, U, conj
from .satc_spec import SPEC, CLASSES
from .evaluators import (evaluate_three, AUTH, TIME, OPER, NEW_REASONS,
                         AUTHORITY_SILENT, AUTHORITY_EXPIRED, AUTHORITY_CONFLICT,
                         COVERAGE_INCOMPLETE, NO_INTERVAL, PRECONDITION_UNMET,
                         KAPPA_OPERATIONAL)

# with evaluators supplied, three classes leave the blocked set
NOW_EXECUTABLE = ["content", "evidence", "provenance", "governance", "temporal", "operational"]
STILL_BLOCKED  = ["status", "consistency"]      # ⪰ undefined · Contr undefined

# ---------------------------------------------------------------- PB-4′ contagion
def PB4_prime():
    out = {}
    for k in range(1, 9):
        stuck = tot = 0
        for combo in itertools.combinations(CLASSES, k):
            tot += 1
            vals = [TOP if c in NOW_EXECUTABLE else U for c in combo]
            if conj(vals) == U: stuck += 1
        out[k] = dict(composites=tot, permanently_U=stuck, rate=stuck/tot)
    return dict(by_arity=out,
                all_eight=conj([TOP if c in NOW_EXECUTABLE else U for c in CLASSES]),
                still_blocked=STILL_BLOCKED)

# ---------------------------------------------------------------- reason partition v2
AGENT_REMEDIABLE = {"UNOBSERVED", "UNINTERPRETED", "UNDERDETERMINED",
                    "INSUFFICIENT_PROVENANCE", COVERAGE_INCOMPLETE,
                    NO_INTERVAL, PRECONDITION_UNMET}
THEORY_BLOCKED   = {"NO_ORDERING", "NO_TEMPORAL_SEMANTICS", "DELTA_UNDEFINED",
                    "NO_EVALUATOR", KAPPA_OPERATIONAL}
WORLD_BLOCKED    = {"UNOBSERVABLE"}
# --- TWO BUCKETS THAT ONLY APPEAR ONCE EVALUATORS EXIST ---
GOVERNANCE_REMEDIABLE = {AUTHORITY_SILENT, AUTHORITY_EXPIRED}   # a third party can act
NORMATIVE_DECISION    = {AUTHORITY_CONFLICT}                    # nobody ACTS; someone DECIDES

def classify2(reason):
    if reason in AGENT_REMEDIABLE:      return "agent-remediable"
    if reason in GOVERNANCE_REMEDIABLE: return "governance-remediable"
    if reason in NORMATIVE_DECISION:    return "normative-decision"
    if reason in THEORY_BLOCKED:        return "theory-blocked"
    if reason in WORLD_BLOCKED:         return "world-blocked"
    return "unclassified"

# ---------------------------------------------------------------- PB-3′ reason coverage
def PB3_prime():
    seen, unclassified = set(), []
    for a in AUTH:
        for t in TIME:
            for o in OPER:
                r = evaluate_three(a, t, o)
                for cls, (v, j) in r.items():
                    if v == U:
                        seen.add(j)
                        if classify2(j) == "unclassified": unclassified.append((cls, j))
    return dict(reasons_produced=sorted(seen), n_situations=len(AUTH)*len(TIME)*len(OPER),
                unclassified=unclassified,
                new_reasons_declared=NEW_REASONS,
                all_new_reasons_seen=set(seen) >= {AUTHORITY_SILENT, AUTHORITY_EXPIRED,
                    AUTHORITY_CONFLICT, COVERAGE_INCOMPLETE, NO_INTERVAL,
                    PRECONDITION_UNMET, KAPPA_OPERATIONAL})

# ---------------------------------------------------------------- PB-5′ well-foundedness
def PB5_prime():
    ok  = evaluate_three("grants", "full", "kappa_op", restrict_kappa=True)
    bad = evaluate_three("grants", "full", "kappa_op", restrict_kappa=False)
    return dict(restricted=ok["operational"], unrestricted=bad["operational"],
                verdict="Restricting κ to the seven non-operational classes keeps the family "
                        "well-founded and returns U(KAPPA_OPERATIONAL). Unrestricted, the "
                        "recursion must be cut off by an arbitrary depth bound — the PB-5 defect "
                        "is now REACHABLE, not merely conjectured.")

# ---------------------------------------------------------------- PHASE C′
def zero_readings(vals, reasons, buckets_closing):
    strict = all(v == TOP for v in vals.values())
    weak   = not any(v == BOT for v in vals.values())
    kleene = BOT if any(v == BOT for v in vals.values()) else (
             U if any(v == U for v in vals.values()) else TOP)
    reasoned = weak and not any(classify2(reasons[c]) not in buckets_closing
                                for c, v in vals.items() if v == U)
    return dict(strict=strict, reasoned=reasoned, weak=weak, kleene=kleene)

def PhaseC_prime(invent_missing=False):
    """invent_missing=True lets the experimenter supply ⪰ and Contr as well."""
    closing = {"theory-blocked", "world-blocked", "normative-decision", "governance-remediable"}
    rows = {}
    for a in AUTH:
        for t in TIME:
            for o in OPER:
                r = evaluate_three(a, t, o)
                vals = {"content": TOP, "evidence": TOP, "provenance": TOP,
                        "status": TOP if invent_missing else U,
                        "consistency": TOP if invent_missing else U,
                        "governance": r["governance"][0], "temporal": r["temporal"][0],
                        "operational": r["operational"][0]}
                reasons = {"status": None if invent_missing else "NO_ORDERING",
                           "consistency": None if invent_missing else "NO_ORDERING",
                           "governance": r["governance"][1], "temporal": r["temporal"][1],
                           "operational": r["operational"][1],
                           "content": None, "evidence": None, "provenance": None}
                rows[f"{a}/{t}/{o}"] = dict(values=vals, reasons=reasons,
                                            **zero_readings(vals, reasons, closing))
    n = len(rows)
    return dict(n_situations=n,
                strict_reachable=any(r["strict"] for r in rows.values()),
                n_strict=sum(r["strict"] for r in rows.values()),
                n_reasoned=sum(r["reasoned"] for r in rows.values()),
                n_weak=sum(r["weak"] for r in rows.values()),
                chain_holds=all((not r["strict"] or r["reasoned"]) and
                                (not r["reasoned"] or r["weak"]) for r in rows.values()),
                rows=rows)
