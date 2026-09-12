"""RequiredDistinctionScenario -- the 14+ scenario matrix, plus the required-distinction
set.  Every scenario carries provenance; an invented scenario would make the comparison
worthless."""
from dataclasses import dataclass, field
from typing import Tuple

@dataclass(frozen=True)
class Ev:
    """One evidence item as the agent holds it.  NEVER carries world truth."""
    polarity: bool
    source: str = "S1"
    context: str = "C1"
    time: int = 1
    layer: str = "evidence"          # observation | evidence | interpretation | rule
    status: str = "active"           # active | superseded | retracted

@dataclass(frozen=True)
class RequiredDistinctionScenario:
    name: str
    evidence: Tuple[Ev, ...] = ()
    assessed: bool = True
    observable: bool = True
    in_scope: bool = True
    interpreted: bool = True
    evaluator_exists: bool = True
    evidence_sufficient: bool = True
    rivals: int = 1
    represented: bool = True
    provenance: str = ""

S = RequiredDistinctionScenario
P  = lambda **k: Ev(polarity=True,  **k)
N  = lambda **k: Ev(polarity=False, **k)

SCENARIOS = [
 S("PositiveEvidence",    (P(),),                              provenance="matrix row 1"),
 S("NegativeEvidence",    (N(),),                              provenance="matrix row 2"),
 S("DirectContradiction", (P(), N()),                          provenance="matrix row 3"),
 S("NoEvidence",          (),                                  provenance="matrix row 4"),
 S("NotAssessed",         (P(),), assessed=False,              provenance="matrix row 5"),
 S("Unobservable",        (), observable=False,                provenance="matrix row 6"),
 S("Underdetermined",     (P(),), rivals=3,                    provenance="matrix row 7"),
 S("ConflictingSources",  (P(source="S1"), N(source="S2")),    provenance="matrix row 8"),
 S("SupersededEvidence",  (P(time=1, status="superseded"), N(time=2)),
                                                               provenance="matrix row 9"),
 S("ScopeExclusion",      (), in_scope=False,                  provenance="matrix row 10"),
 S("TemporalConflict",    (P(time=1), N(time=2)),              provenance="matrix row 11"),
 S("ContradictoryRules",  (P(layer="rule"), N(layer="rule")),  provenance="matrix row 12"),
 S("ContradictoryObservations",
                          (P(layer="observation"), N(layer="observation")),
                                                               provenance="matrix row 13"),
 S("ContradictoryInterpretations",
                          (P(layer="interpretation"), N(layer="interpretation")),
                                                               provenance="matrix row 14"),
 # two the matrix implies but does not list explicitly
 S("InsufficientEvidence",(P(),), evidence_sufficient=False,   provenance="protocol S2.4"),
 S("TheoryIncomplete",    (P(),), evaluator_exists=False,      provenance="protocol S2.5"),
 S("Absent",              (), represented=False,               provenance="protocol S2.3"),
 S("ContextConflict",     (P(context="C1"), N(context="C2")),  provenance="protocol S28"),
]
BY_NAME = {s.name: s for s in SCENARIOS}

def active(sc):   return tuple(e for e in sc.evidence if e.status == "active")
def pos(sc):      return tuple(e for e in active(sc) if e.polarity)
def neg(sc):      return tuple(e for e in active(sc) if not e.polarity)

# ---------------------------------------------------------------- required distinctions
# Carried over VERBATIM from KR-CONTR-EVAL's R_req (protocol S9 + I1-I5), re-expressed
# over this scenario set.  NOT extended here -- S11 of that experiment showed the
# required set is the decision that sets everything, so it is not silently widened.
REQUIRED = [
 ("PositiveEvidence","NegativeEvidence"),   ("NegativeEvidence","NoEvidence"),
 ("NoEvidence","Absent"),                   ("Absent","NotAssessed"),
 ("NotAssessed","ScopeExclusion"),          ("InsufficientEvidence","Underdetermined"),
 ("Underdetermined","Unobservable"),
 ("DirectContradiction","NoEvidence"),      ("DirectContradiction","Absent"),
 ("DirectContradiction","InsufficientEvidence"),
 ("DirectContradiction","TheoryIncomplete"),("DirectContradiction","NotAssessed"),
 # I11, PROPOSED -- carried in as a candidate, flagged, not treated as ratified
 ("DirectContradiction","PositiveEvidence"),
]
REQUIRED_PROVENANCE = ("KR-CONTR-EVAL-2026-09 R_req (protocol S9 + I1-I5); the last pair "
                       "is the PROPOSED I11 and is flagged as unratified")
