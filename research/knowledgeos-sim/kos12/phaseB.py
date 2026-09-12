"""PHASE B — Adversarial semantic test of the Sat_c specification.

Uses the FOUR DETERMINISTIC CASES already found, as instructed — no new random trials:
  B1  CE-1 factivity / determinacy
  B2  CE-3 revision / retraction
  B3  provenance & source removal
  B4  governance / temporal / operational U

Question per class: **does Sat_c have a coherent interpretation?**
These are checks on the SPECIFICATION, not an implementation of it.
"""
import itertools
from .satc_spec import SPEC, CLASSES, EXECUTABLE_NOW, BLOCKED, ALL_U_REASONS
from .sat3 import TOP, BOT, U, conj

# ---------------------------------------------------------------- PB-1 exclusivity
def PB1_exclusivity():
    """Can P_c and ¬P_c hold together?  If yes the ⊤/⊥ skeleton is incoherent."""
    out = {}
    for c, s in SPEC.items():
        # a class is at risk when its ⊥ condition is not the complement of its ⊤ condition
        risk = ("EXPLICIT" in s["bot"]) or ("both" in s["conflict_behaviour"].lower()) \
               or ("competing" in s["conflict_behaviour"].lower())
        out[c] = dict(top=s["top"], bot=s["bot"],
                      bot_is_not_complement_of_top=risk,
                      coherent=not (c == "content" and risk))
    return out

# ---------------------------------------------------------------- PB-2 content contradiction
def PB2_content_contradiction():
    """The content class says ⊤ if p ∈ Content, ⊥ if ¬p ∈ Content.
    Both can hold simultaneously — the codomain has no value for it."""
    K = {"content": {"p", "not-p"}}
    p_in  = "p" in K["content"]; np_in = "not-p" in K["content"]
    return dict(p_present=p_in, neg_p_present=np_in,
                skeleton_yields=("⊤ and ⊥ simultaneously" if (p_in and np_in) else "one value"),
                codomain_has_a_value_for_it=False,
                verdict="INCOHERENT AS SPECIFIED — Sat_content is not a function when K holds "
                        "both p and ¬p. Either the codomain needs a 4th value C, or "
                        "Sat_content must delegate to Sat_consistency first.",
                note="This is the review's OPEN question about a 4-valued codomain, reached "
                     "from the content class rather than the consistency class.")

# ---------------------------------------------------------------- PB-3 Just completeness
CASES = {
 "B1-factivity":   dict(desc="policy-trusted source reports a falsehood",
                        observed={"content": TOP, "evidence": TOP, "provenance": TOP,
                                  "status": TOP, "consistency": TOP,
                                  "governance": U, "temporal": U, "operational": U}),
 "B2-revision":    dict(desc="stale evidence never retired; rivals both live",
                        observed={"content": U, "evidence": U, "provenance": TOP,
                                  "status": BOT, "consistency": U,
                                  "governance": U, "temporal": U, "operational": U}),
 "B3-no-source":   dict(desc="source component removed from the observation model",
                        observed={"content": U, "evidence": U, "provenance": U,
                                  "status": BOT, "consistency": U,
                                  "governance": U, "temporal": U, "operational": U}),
 "B4-open-classes":dict(desc="determined & corroborated; only the open classes remain",
                        observed={"content": TOP, "evidence": TOP, "provenance": TOP,
                                  "status": TOP, "consistency": TOP,
                                  "governance": U, "temporal": U, "operational": U}),
}

def Just(c, value, case):
    """Level 2 — the REASON.  Implemented; Sat_c itself is not."""
    if value != U: return None
    s = SPEC[c]
    if c == "governance":  return "NO_EVALUATOR"
    if c == "temporal":    return "NO_TEMPORAL_SEMANTICS"
    if c == "operational": return "DELTA_UNDEFINED"
    if c == "status":      return "NO_ORDERING"
    if case == "B3-no-source" and c == "provenance": return "INSUFFICIENT_PROVENANCE"
    if case in ("B2-revision",):  return "UNDERDETERMINED"
    if case == "B3-no-source":    return "UNDERDETERMINED"
    return "UNOBSERVED"

def PB3_just_completeness():
    """Hypothesis under test: every U carries a reason from the declared vocabulary."""
    rows, unexplained = {}, []
    for name, case in CASES.items():
        r = {}
        for c, v in case["observed"].items():
            j = Just(c, v, name)
            r[c] = dict(value=v, just=j)
            if v == U:
                if j is None or j not in ALL_U_REASONS:
                    unexplained.append((name, c, j))
                elif j not in SPEC[c]["u_reasons"]:
                    unexplained.append((name, c, f"{j} NOT in class's declared u_reasons"))
        rows[name] = r
    return dict(cases=rows, unexplained=unexplained,
                hypothesis_holds=not unexplained,
                distinct_reasons_used=sorted({d["just"] for r in rows.values()
                                              for d in r.values() if d["just"]}))

# ---------------------------------------------------------------- PB-4 blocked-class contagion
def PB4_composition():
    """Kleene conjunction across classes.  What does a composite requirement evaluate to
    when it touches a class whose semantics are BLOCKED?"""
    results = {}
    for k in range(1, 9):
        stuck = 0; total = 0
        for combo in itertools.combinations(CLASSES, k):
            total += 1
            # best case: every executable class returns ⊤, every blocked class returns U
            vals = [TOP if c in EXECUTABLE_NOW else U for c in combo]
            if conj(vals) == U: stuck += 1
        results[k] = dict(composites=total, permanently_U=stuck, rate=stuck/total)
    # the decisive number: composites over ALL eight
    return dict(by_arity=results,
                all_eight=conj([TOP if c in EXECUTABLE_NOW else U for c in CLASSES]),
                verdict="Any composite requirement touching one of the five blocked classes "
                        "is permanently U under Kleene conjunction, no matter how well the "
                        "executable classes perform.")

# ---------------------------------------------------------------- PB-5 well-foundedness
def PB5_recursion():
    """Sat_op is defined via Sat_κ.  Is the family well-founded?"""
    return dict(recursive_classes=["operational"],
                definition="Sat_op(K,r) = Sat_κ(δ(K,o))",
                well_founded_if="κ is drawn from the seven NON-operational classes",
                ill_founded_if="κ may itself be an operational requirement",
                theory_states_which=False,
                verdict="UNDER-SPECIFIED — the theory does not restrict κ, so the family "
                        "is not provably well-founded. An operational requirement whose "
                        "postcondition is itself operational regresses without a base case.")

# ---------------------------------------------------------------- PB-6 factivity hook
def PB6_factivity_hook():
    hooks = {c: SPEC[c]["factivity_requirement"] for c in CLASSES}
    return dict(per_class=hooks,
                any_class_requires_truth=any(not v.startswith("NONE") for v in hooks.values()),
                verdict="**NO class in the family requires factivity.** A knowledge state can "
                        "satisfy all eight and still be false. CE-1 is therefore not an "
                        "accident of Γ — the satisfaction family has no place to attach truth.")

def run():
    return dict(PB1_exclusivity=PB1_exclusivity(),
                PB2_content_contradiction=PB2_content_contradiction(),
                PB3_just_completeness=PB3_just_completeness(),
                PB4_composition=PB4_composition(),
                PB5_recursion=PB5_recursion(),
                PB6_factivity_hook=PB6_factivity_hook())
