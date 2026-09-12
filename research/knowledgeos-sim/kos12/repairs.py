"""PHASE R — repair the SPECIFICATION defects PB-2, PB-3, PB-5.

Per the sequencing condition: repair the specification FIRST, experimentally, and
**do not implement**.  For each defect: enumerate candidate repairs, test each against
the four deterministic cases, report which survive — and CHOOSE NONE.
"""
from .sat3 import TOP, BOT, U, conj
from .satc_spec import SPEC, CLASSES

# =============================================================== PB-2 contradiction
def pb2_candidates():
    """Sat_content is not total when K holds both p and ¬p."""
    states = {"p only": {"p"}, "¬p only": {"np"}, "neither": set(), "BOTH": {"p", "np"}}

    def R_a(s):   # four-valued codomain
        if "p" in s and "np" in s: return "C"
        if "p" in s: return TOP
        if "np" in s: return BOT
        return U
    def R_b(s):   # delegate to consistency first
        if "p" in s and "np" in s: return U      # consistency says ⊥; content declines
        if "p" in s: return TOP
        if "np" in s: return BOT
        return U
    def R_c(s):   # ⊥ requires the ABSENCE of p — makes ⊤/⊥ exclusive by construction
        if "p" in s: return TOP
        if "np" in s: return BOT
        return U
    def R_d(s):   # invariant on K: Content is contradiction-free
        if "p" in s and "np" in s: raise ValueError("K violates the invariant")
        if "p" in s: return TOP
        if "np" in s: return BOT
        return U

    cands = dict(
      R_a=dict(name="four-valued codomain {⊤,⊥,U,C}", fn=R_a),
      R_b=dict(name="delegate to Sat_consistency; content returns U on contradiction", fn=R_b),
      R_c=dict(name="⊥ ≡ (¬p ∈ Content ∧ p ∉ Content) — exclusive by construction", fn=R_c),
      R_d=dict(name="invariant: Content(K) is contradiction-free", fn=R_d),
    )
    out = {}
    for k, c in cands.items():
        vals, err = {}, None
        for sn, s in states.items():
            try: vals[sn] = c["fn"](s)
            except Exception as e: vals[sn] = f"ERROR({e})"; err = True
        total = all(not str(v).startswith("ERROR") for v in vals.values())
        in_codomain = all(v in (TOP, BOT, U) for v in vals.values() if not str(v).startswith("ERROR"))
        # does it preserve "absence ≠ negation"?
        keeps = vals.get("neither") == U and vals.get("¬p only") == BOT
        out[k] = dict(name=c["name"], values=vals, total=total,
                      stays_in_three_valued_codomain=in_codomain,
                      preserves_absence_is_not_negation=keeps,
                      cost=("changes the codomain of the WHOLE family" if k == "R_a" else
                            "makes content depend on consistency — which is itself blocked" if k == "R_b" else
                            "silently loses the contradiction: BOTH is reported as ⊤" if k == "R_c" else
                            "pushes the problem into K's construction; Sat becomes partial on real states"))
    return out

# =============================================================== PB-3 reason vocabulary
def pb3_candidates():
    """The declared per-class reason vocabulary was incomplete (2/32 cells)."""
    observed = {("content", "UNDERDETERMINED"), ("content", "UNOBSERVED"),
                ("evidence", "UNOBSERVED"), ("evidence", "UNDERDETERMINED"),
                ("provenance", "INSUFFICIENT_PROVENANCE"),
                ("governance", "NO_EVALUATOR"), ("temporal", "NO_TEMPORAL_SEMANTICS"),
                ("operational", "DELTA_UNDEFINED"), ("status", "NO_ORDERING")}
    def covered(vocab):
        return [(c, r) for c, r in observed if r not in vocab.get(c, set())]
    per_class = {c: set(SPEC[c]["u_reasons"]) for c in CLASSES}
    R_a = {c: v | ({"UNDERDETERMINED"} if c == "content" else set()) for c, v in per_class.items()}
    allr = set().union(*per_class.values()) | {"UNDERDETERMINED"}
    R_b = {c: allr for c in CLASSES}
    # R_c: derive from the skeleton — U arises from exactly 3 structural sources
    skeleton = {"NO_EVALUATOR_FOR_THE_PREDICATE", "PREDICATE_UNDECIDED_ON_THIS_STATE",
                "INPUT_ABSENT"}
    return dict(
      baseline=dict(name="as declared", uncovered=covered(per_class), exhaustive_provable=False),
      R_a=dict(name="add UNDERDETERMINED to content (one-word repair)",
               uncovered=covered(R_a), exhaustive_provable=False,
               note="closes the two failing cells; proves nothing about the rest"),
      R_b=dict(name="one global vocabulary; any class may return any reason",
               uncovered=covered(R_b), exhaustive_provable=False,
               note="trivially covers everything and therefore tests nothing — "
                    "it destroys the per-class discipline that found the defect"),
      R_c=dict(name="DERIVE the vocabulary from the Sat skeleton: U has exactly three "
                    "structural sources (no evaluator / predicate undecided / input absent)",
               uncovered="n/a — reasons are re-typed, not enumerated",
               exhaustive_provable=True,
               skeleton_sources=sorted(skeleton),
               note="the only candidate that could be EXHAUSTIVE, because it derives the "
                    "vocabulary from the three-case skeleton instead of listing reasons. "
                    "The concrete reasons become REFINEMENTS of the three."),
    )

# =============================================================== PB-5 well-foundedness
def pb5_candidates():
    """Sat_op(K,r) = Sat_κ(δ(K,o)) is recursive; the theory does not restrict κ."""
    def terminates(kind, max_depth=100):
        if kind == "restrict":  return True, "κ ∈ the seven non-operational classes"
        if kind == "measure":   return True, "κ operational allowed if a declared measure strictly decreases"
        if kind == "stratify":  return True, "Sat_op^n only calls Sat_op^{n-1}"
        if kind == "none":      return False, "unbounded; only an arbitrary depth cut stops it"
        return None, ""
    out = {}
    for k, nm in [("restrict", "restrict κ to non-operational classes"),
                  ("measure",  "allow operational κ with a well-founded measure"),
                  ("stratify", "stratify Sat_op into levels"),
                  ("none",     "leave κ unrestricted (status quo)")]:
        t, why = terminates(k)
        out[k] = dict(name=nm, terminates=t, why=why,
                      expressiveness_lost=(k == "restrict"),
                      makes_defect_visible_in_value=(k == "stratify"),
                      note=("refuses composite operations outright" if k == "restrict" else
                            "needs a measure the theory has not defined" if k == "measure" else
                            "the level index is observable, so a level-exhaustion is distinguishable "
                            "from a refusal — the only candidate that fixes PB-5′'s invisibility"
                            if k == "stratify" else
                            "the defect stands"))
    return out

def run():
    return dict(PB2=pb2_candidates(), PB3=pb3_candidates(), PB5=pb5_candidates())
