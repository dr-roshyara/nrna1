"""Experimental capability model.

Started from the protocol's C1..C24.  Justified modifications are recorded
in docs .../03-capability-model.md and marked below.

A capability requirement is expressed as:
    kinds  : carrier kinds that must be in Reach(S)
    atoms  : atoms that must be in the atom pool of S (for capabilities that
             are about a POWER rather than an artifact)
    note   : why
"""
from .carriers import *
from .atoms import *

CAP = {}
def cap(cid, label, kinds=(), atoms=(), note="", kind="artifact"):
    CAP[cid] = dict(id=cid, label=label, kinds=set(kinds), atoms=set(atoms),
                    note=note, kind=kind)

cap("C1",  "acquire an observation",            [OBSERVATION])
cap("C2",  "preserve/construct semantic meaning",[SEMANTIC])
cap("C3",  "construct a representation",        [REPRESENTATION])
cap("C4",  "relate representations/claims",     [RELATION])
cap("C5",  "discriminate alternatives",         [DISCRIMINATION])
cap("C6",  "formulate hypotheses",              [HYPOTHESIS])
cap("C7",  "perform inference",                 [CLAIM],
    note="CLAIM is producible only via entailment: warrant kind DEDUCTIVE")
cap("C8",  "detect epistemic insufficiency",    [GAP])
cap("C9",  "challenge a claim/model",           [DEFEATER])
cap("C10", "validate a claim/model",            [VERDICT])
cap("C11", "revise epistemic state",            [], [A_MUTATION],
    note="commit to K_t preserving history")
cap("C12", "determine inquiry adequately resolved",[DETERMINATION])
cap("C13", "select a next action / answer",     [DECISION])

# --- invariant/structural capabilities ------------------------------------
cap("C14", "represent context",                 [SEMANTIC], note="context is an input of the meaning rule", kind="invariant")
cap("C15", "represent temporal conditions",     [], [A_MUTATION], kind="invariant",
    note="K_t -> K_{t+1} succession; historical identity != current-state equality")
cap("C16", "represent uncertainty",             [VERDICT], kind="invariant")
cap("C17", "represent assumptions",             [VERDICT], kind="invariant",
    note="assumptions enter warrant assessment")
cap("C18", "preserve provenance",               [], kind="invariant",
    note="STRUCTURALLY GUARANTEED by the simulator; NOT DISCRIMINATING - see negative results")
cap("C19", "preserve alternative hypotheses",   [HYPOTHESIS, DISCRIMINATION], kind="invariant")
cap("C20", "support non-identifiability",       [HYPOTHESIS, DISCRIMINATION], kind="invariant",
    note="Discrimination must be able to return UNDECIDED")
cap("C21", "distinguish observation from interpretation",[OBSERVATION, SEMANTIC], kind="invariant")
cap("C22", "semantic equivalence without representation identity",[SEMANTIC, RELATION], kind="invariant",
    note="requires linking available AT THE SEMANTIC carrier, not only at Representation")
cap("C23", "produce a smallest adequate answer",[DETERMINATION])
cap("C24", "support learning from new observations",[OBSERVATION], [A_MUTATION])
# --- ADDED (justified, Part III licence) ----------------------------------
cap("C25", "admit an observation as evidence under a policy",[EVIDENCE],
    note="ADDED. Corpus: Qualify: Observation x Policy -> Evidence, recorded G1. "
         "Protocol constraint 9: information != evidence. C0 holds no such atom.")

REQUIRED = list(CAP.keys())

def achieved(cid, kinds, pool):
    c = CAP[cid]
    return c["kinds"] <= kinds and c["atoms"] <= pool

def evaluate(ops):
    from .reach import reach, atom_pool
    kinds, wit = reach(ops)
    pool = atom_pool(ops)
    got = {c: achieved(c, kinds, pool) for c in REQUIRED}
    return dict(kinds=sorted(kinds), witness={k: list(v) for k, v in wit.items()},
                pool=sorted(pool), capabilities=got,
                lost=[c for c in REQUIRED if not got[c]],
                score=sum(got.values()), total=len(REQUIRED))
