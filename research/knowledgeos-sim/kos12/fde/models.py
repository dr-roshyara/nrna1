"""The four competing representations.  Each implements the same interface so the
comparison is between REPRESENTATIONS, never between KnowledgeOS ontologies."""
from .evaluation import EvaluationAdapter, Standing, Evaluation
from .boundary import Boundary, BoundaryFacet as F, BoundaryCondition as C
from .scenarios import pos, neg, active

NOT_REPRESENTABLE = "«not-representable»"

def _boundary_of(sc):
    """The typed boundary a scenario exhibits.  Only the STRUCTURED model may read it."""
    if not sc.represented:          return Boundary(F.REPRESENTATION, C.NOT_PRESENT)
    if not sc.in_scope:             return Boundary(F.SCOPE,          C.OUT_OF_SCOPE)
    if not sc.observable:           return Boundary(F.WORLD,          C.UNOBSERVABLE)
    if not sc.evaluator_exists:     return Boundary(F.THEORY,         C.NO_EVALUATOR)
    if not sc.interpreted:          return Boundary(F.INTERPRETATION, C.UNINTERPRETED)
    if not sc.assessed:             return Boundary(F.EVIDENCE,       C.NOT_ASSESSED)
    if not sc.evidence_sufficient:  return Boundary(F.EVIDENCE,       C.INSUFFICIENT)
    if sc.rivals > 1:               return Boundary(F.EVIDENCE,       C.UNDERDETERMINED)
    if not active(sc):              return Boundary(F.OBSERVATION,    C.UNOBSERVED)
    if any(e.status == "superseded" for e in sc.evidence):
                                    return Boundary(F.EVIDENCE,       C.SUPERSEDED)
    return Boundary(None, None)

def _standing(sc):
    return Standing(positive_support=bool(pos(sc)), negative_support=bool(neg(sc)))

class ClassicalModel(EvaluationAdapter):
    """Domain {P, not-P}.  Two-valued: it has NO way to say 'neither' or 'both'."""
    name = "Classical"
    def representable(self, sc):
        st = _standing(sc)
        # a classical bivalent value exists only for exactly one polarity
        return st.positive_support != st.negative_support
    def evaluate(self, sc):
        if not self.representable(sc): return NOT_REPRESENTABLE
        return ("P",) if pos(sc) else ("¬P",)

class K3Model(EvaluationAdapter):
    """Kleene three-valued {T,F,U}."""
    name = "K3"
    def evaluate(self, sc):
        st = _standing(sc)
        if st.positive_support and st.negative_support: return ("U",)
        if not (sc.evaluator_exists and sc.interpreted and sc.observable and sc.in_scope
                and sc.assessed and sc.evidence_sufficient and sc.rivals == 1
                and sc.represented):
            return ("U",)
        if st.positive_support: return ("T",)
        if st.negative_support: return ("F",)
        return ("U",)

class FDEModel(EvaluationAdapter):
    """Two independent support channels (S+, S-).  The useful part extracted from FDE:
    positive and negative support are represented INDEPENDENTLY, rather than encoding
    contradiction as a special replacement value.

    It carries NO boundary -- which is the point: (0,0) cannot say WHY."""
    name = "FDE"
    def evaluate(self, sc):
        st = _standing(sc)
        return (st.positive_support, st.negative_support)

class StructuredModel(EvaluationAdapter):
    """Standing x Boundary x Context x Provenance -- the factorized candidate."""
    name = "Structured"
    def __init__(self, reason_repr=None):
        from .boundary import StructuredBoundary
        self.reason_repr = reason_repr or StructuredBoundary
        self.name = f"Structured[{self.reason_repr.name}]"
    def evaluate(self, sc):
        st = _standing(sc)
        b  = _boundary_of(sc)
        return (Evaluation(standing=st, boundary=b,
                           context=tuple(sorted({e.context for e in active(sc)})),
                           provenance=tuple(sorted({e.source for e in active(sc)}))),
                ) and ((st.positive_support, st.negative_support),
                       self.reason_repr.of(b),
                       tuple(sorted({e.context for e in active(sc)})),
                       tuple(sorted({e.source for e in active(sc)})))

def default_models():
    from .boundary import FlatReason, LocusModalityReason, StructuredBoundary
    return [ClassicalModel(), K3Model(), FDEModel(),
            StructuredModel(StructuredBoundary)]
