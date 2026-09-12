"""KR-CLOSURE-2026-09-02 — Epistemic Closure Event.  [EXP]

Protocol authored by ChatGPT (`20260902-122934_…`); this module is the executor.
"Orgasm" is a research label only; the formal candidate is EpistemicClosureEvent.
Nothing here is canonical. Metaphor is NOT converted into ontology.
"""
from dataclasses import dataclass, field
import itertools

# ---------------------------------------------------------------- §5.3 ArgumentField
REL = ["support","oppose","contradict","corroborate","undermine","qualify",
       "irrelevant","unresolved"]

@dataclass(frozen=True)
class Arg:
    id: str
    rel: str            # typed relation to H — NOT a signed scalar
    weight: float       # a scalar the metaphor would use
    source: str = "s1"
    derived_from: str = None    # dependence
    about: str = "H1"

@dataclass(frozen=True)
class State:
    """A structured epistemic situation. Deliberately richer than +n/-n."""
    args: tuple = ()
    hypotheses: tuple = ("H1",)
    admissible: tuple = ("H1",)      # survivors after elimination
    truth: str = "H1"                # world truth — ORACLE ONLY
    attributed: str = None           # what the agent attributes
    requirements: tuple = ("r_determined",)
    unmet: tuple = ()                # applicable requirements not satisfied
    note: str = ""

# ---------------------------------------------------------------- the five conditions
def balanced(s, eps=1e-9):
    """§6 the metaphor's condition: signed scalar sum ≈ 0."""
    tot = sum((a.weight if a.rel in ("support","corroborate") else
              -a.weight if a.rel in ("oppose","contradict","undermine") else 0.0)
              for a in s.args)
    return abs(tot) < 1e-9

def reconciled(s):
    """No argument relation is left unresolved, and no contradiction stands."""
    return not any(a.rel in ("unresolved","contradict") for a in s.args)

def determined(s):
    """|A_t| = 1."""
    return len(s.admissible) == 1

def known(s):
    """Attributed AND factive. The oracle reads s.truth; the agent never does."""
    return s.attributed is not None and s.attributed == s.truth

def closed(s):
    """Inquiry-relative: no applicable requirement outstanding."""
    return len(s.unmet) == 0

CONDITIONS = dict(Balanced=balanced, Reconciled=reconciled, Determined=determined,
                  Known=known, Closed=closed)

# ---------------------------------------------------------------- §6 negative control
def negative_control():
    """Does +0.8 + (-0.8) = 0 correspond to materially different epistemic conditions?"""
    A = lambda r,w,**k: Arg(f"a{abs(hash((r,w,str(k))))%9999}", r, w, **k)
    cases = {
     "1 genuine reconciliation":  State(args=(A("support",.8), A("qualify",.8)),
        admissible=("H1",), unmet=(), attributed="H1", note="the qualifier is absorbed"),
     "2 unresolved contradiction":State(args=(A("support",.8), A("contradict",.8)),
        admissible=("H1","H2"), unmet=("r_determined",), note="both stand"),
     "3 insufficient evidence":   State(args=(A("support",.4), A("oppose",.4)),
        admissible=("H1","H2"), unmet=("r_determined",), note="neither meets threshold"),
     "4 incomparable evidence":   State(args=(A("support",.8,about="H1"),
                                              A("oppose",.8,about="H2")),
        admissible=("H1","H2"), unmet=("r_determined",), note="different targets"),
     "5 dependent evidence":      State(args=(A("support",.8),
                                              A("oppose",.8,derived_from="a1")),
        admissible=("H1",), unmet=("r_corroborated",), note="the negative is a copy"),
     "6 wrong model":             State(args=(A("support",.8), A("oppose",.8)),
        admissible=("H1","H2"), unmet=("r_causal",), note="fits, causally invalid"),
     "7 multiple surviving H":    State(args=(A("support",.8), A("oppose",.8)),
        hypotheses=("H1","H2","H3"), admissible=("H1","H2","H3"),
        unmet=("r_determined",), note="three live"),
     "8 temporal mismatch":       State(args=(A("support",.8), A("oppose",.8)),
        admissible=("H1",), unmet=("r_temporal",), note="true@t1, false@t2"),
     "9 irrelevant evidence":     State(args=(A("irrelevant",.8), A("irrelevant",.8)),
        admissible=("H1","H2"), unmet=("r_determined",), note="neither bears on H"),
    "10 accidental cancellation": State(args=(A("support",.8,source="s1"),
                                              A("oppose",.8,source="s2")),
        admissible=("H1","H2"), unmet=("r_determined",), note="coincidence"),
    }
    rows={}
    for k,s in cases.items():
        rows[k]=dict(scalar_sum=round(sum((a.weight if a.rel in ("support","corroborate")
                     else -a.weight if a.rel in ("oppose","contradict","undermine") else 0.0)
                     for a in s.args),6),
                     balanced=balanced(s), reconciled=reconciled(s),
                     determined=determined(s), closed=closed(s),
                     relations=sorted({a.rel for a in s.args}), note=s.note)
    return rows

# ---------------------------------------------------------------- §9 witness search
def witness_states():
    """A small deterministic catalogue used to search for pairwise witnesses."""
    A = lambda r,w: Arg(f"x{r}{w}", r, w)
    W = {}
    W["w_bal_only"]      = State(args=(A("support",.5),A("oppose",.5)),
        admissible=("H1","H2"), unmet=("r_determined",), attributed=None)
    W["w_rec_not_bal"]   = State(args=(A("support",.9),A("qualify",.1)),
        admissible=("H1",), unmet=(), attributed="H1")
    W["w_det_not_rec"]   = State(args=(A("support",.9),A("unresolved",.2)),
        admissible=("H1",), unmet=(), attributed="H1")
    W["w_det_not_known"] = State(args=(A("support",.9),), admissible=("H1",),
        truth="H2", attributed="H1", unmet=())          # attributed falsehood
    W["w_known_not_closed"]=State(args=(A("support",.9),), admissible=("H1",),
        truth="H1", attributed="H1", unmet=("r_provenance",))
    W["w_closed_not_known"]=State(args=(A("support",.9),), admissible=("H1",),
        truth="H1", attributed=None, unmet=())
    W["w_closed_not_det"] = State(args=(A("support",.5),A("oppose",.5)),
        admissible=("H1","H2"), unmet=(), attributed=None,
        note="the inquiry asked only 'is it undetermined?'")
    W["w_bal_and_all"]   = State(args=(A("support",.5),A("oppose",.5)),
        admissible=("H1",), truth="H1", attributed="H1", unmet=())
    W["w_rec_not_det"]   = State(args=(A("support",.5),), admissible=("H1","H2"),
        unmet=("r_determined",), attributed=None)
    W["w_none"]          = State(args=(A("contradict",.5),), admissible=("H1","H2"),
        unmet=("r_determined",), attributed=None)
    return W

def distinction_matrix():
    W = witness_states()
    out = {}
    for a, b in itertools.permutations(CONDITIONS, 2):
        wit = None
        for n, s in W.items():
            if CONDITIONS[a](s) and not CONDITIONS[b](s):
                wit = n; break
        out[f"{a} ∧ ¬{b}"] = dict(separable=wit is not None, witness=wit)
    # the symmetric question the protocol asks
    pairs = {}
    for a, b in itertools.combinations(CONDITIONS, 2):
        f = out[f"{a} ∧ ¬{b}"]; g = out[f"{b} ∧ ¬{a}"]
        pairs[f"{a} | {b}"] = dict(
            distinguishable=f["separable"] or g["separable"],
            both_directions=f["separable"] and g["separable"],
            witness_a_not_b=f["witness"], witness_b_not_a=g["witness"])
    return out, pairs
