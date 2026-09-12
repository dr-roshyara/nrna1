"""C0 operator contracts.

An operator IS its declared atom set (plus documentation).  The reach engine
consults ONLY the atom set, so no operator can privately own a carrier kind.
`corpus` records the strength of primary-corpus support found in Part I.
"""
from .atoms import *

class Op:
    def __init__(self, name, atoms, corpus, note):
        self.name, self.atoms, self.corpus, self.note = name, frozenset(atoms), corpus, note
    def __repr__(self): return f"<{self.name}>"

C0 = {o.name: o for o in [
 Op("Observe",      {A_WORLD_CONTACT},
    "STRONG", "Q15 3.1.1 Observe(K_t,X_t,P,A,C,tau)->K_{t+1}; SD-1 acquisition family"),
 Op("Interpret",    {A_MEANING},
    "MODERATE", "Q15 3.2.1 Parse; 'Observation != Proposition != Knowledge'; obs/interp distinction file"),
 Op("Represent",    {A_ENCODING},
    "WEAK", "5 role-uses only; 'reality/observation/representation/knowledge missing distinction'"),
 Op("Relate",       {A_LINKING},
    "WEAK-MODERATE", "SD-6 relationship family; ratified primitive Relation; 7 role-uses"),
 Op("Discriminate", {A_DIFF_DECISION},
    "STRONG", "SD-2 Buddhi family (Distinguish/Compare/Classify/Separate/Accept/Reject/Defer)"),
 Op("Hypothesize",  {A_GENERATION},
    "WEAK", "Q16 status table edge Unknown->Hypothesized; 2 role-uses in primary corpus"),
 Op("Infer",        {A_ENTAILMENT},
    "MODERATE", "Q16 Infer(A1,A2,...,Rule)->A3 over assertions; SD-3 construction family"),
 Op("DetectGap",    {A_NORM_COMPARISON, A_DIFF_DECISION},
    "MODERATE", "Q15 3.5.3 DetectGap(K_t,I_t); classified corpus-side as O^? DERIVED EVALUATION"),
 Op("Challenge",    {A_NEGATION},
    "MODERATE", "kernel/ consistency-boundary docs; adversarial simulation step-025"),
 Op("Validate",     {A_WARRANT},
    "STRONG", "Q16 Verify/Corroborate edges; validation lane throughout corpus"),
 Op("Revise",       {A_MUTATION},
    "STRONG", "SD-4 revision family; Q15 2.2 history-preservation invariant"),
 Op("Determine",    {A_NORM_COMPARISON, A_CLOSURE},
    "MODERATE", "Buddhi = discrimination/determination power; Q15 3.6.2 Resolve Gap; zero-findings 5"),
 Op("Select",       {A_ACTION_PREFERENCE},
    "WEAK", "step-025g Lord algebra a* = Select(A_feasible,Objective); step-098 economics"),
]}

# The corpus records Qualify : Observation x Policy -> Evidence as an
# irreducible gap (G1).  C0 contains NO operator holding this atom.
QUALIFY = Op("Qualify", {A_QUALIFICATION}, "STRONG",
             "Qualify: Observation x Policy -> Evidence, recorded G1/irreducible in prior lane")

C0_NAMES = list(C0.keys())
C0_PLUS  = dict(C0); C0_PLUS["Qualify"] = QUALIFY     # repaired baseline
