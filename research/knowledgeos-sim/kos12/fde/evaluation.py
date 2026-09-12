"""Standing and Evaluation.  The two factors are kept SEPARATE by construction."""
from dataclasses import dataclass
from typing import Optional
from .boundary import Boundary

class Support:
    """S+ / S- channel value.  Deliberately not an enum of four states."""
    ABSENT  = False
    PRESENT = True

class Polarity:
    POSITIVE = "positive"
    NEGATIVE = "negative"
    NONE     = "none"

@dataclass(frozen=True)
class Standing:
    """S(p) = (S+(p), S-(p)),  (S+,S-) in {0,1}^2.

    The four combinations are DERIVED CONFIGURATIONS, never enum members:
        (1,0) positive support   (0,1) negative support
        (1,1) conflicting        (0,0) unsupported

    IMPORTANT: Standing carries NO reason.  Mixing them would merge two dimensions the
    experiment exists to keep apart (E12).
    """
    positive_support: bool = False
    negative_support: bool = False

    def configuration(self):
        return {(True, False): "positive-support", (False, True): "negative-support",
                (True, True):  "conflicting",      (False, False): "unsupported"
               }[(self.positive_support, self.negative_support)]

def fde_conflict_detector(standing: Standing) -> bool:
    """FDEConflictDetector -- deliberately NOT named Contr.

    Priest's FDE can represent a glut; that does NOT establish that KnowledgeOS
    epistemic contradiction is the same semantic condition.  Whether
    FDEConflict(p) == Contr(p) is TESTED, never assumed.
    """
    return standing.positive_support and standing.negative_support

@dataclass(frozen=True)
class Evaluation:
    """Evaluation(p) = Standing(p) x Boundary(p) x Context x Provenance."""
    standing: Standing
    boundary: Optional[Boundary] = None
    context: tuple = ()
    provenance: tuple = ()

class EvaluationAdapter:
    """Common interface -- the adapters are COMPETING REPRESENTATIONS, never
    competing KnowledgeOS ontologies."""
    name = "abstract"
    def evaluate(self, scenario):     raise NotImplementedError
    def representable(self, scenario): return True
