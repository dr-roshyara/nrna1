"""Typed boundary / reason.  EXPERIMENTAL VOCABULARY -- these names carry no authority
and are not a KnowledgeOS ontology.  They are candidates until the research establishes
them, per the standing rejection of 'just implement the nine categories'."""
from dataclasses import dataclass
from typing import Optional

class BoundaryFacet:
    """WHERE the deficiency lies."""
    OBSERVATION    = "observation"
    INTERPRETATION = "interpretation"
    EVIDENCE       = "evidence"
    THEORY         = "theory"
    SCOPE          = "scope"
    REPRESENTATION = "representation"
    WORLD          = "world"
    NONE           = None

class BoundaryCondition:
    """WHAT KIND of deficiency it is."""
    UNOBSERVABLE       = "unobservable"
    UNOBSERVED         = "unobserved"
    UNINTERPRETED      = "uninterpreted"
    NOT_ASSESSED       = "not-assessed"
    INSUFFICIENT       = "insufficient-evidence"
    UNDERDETERMINED    = "underdetermined"
    NO_EVALUATOR       = "theory-incomplete"
    OUT_OF_SCOPE       = "out-of-scope"
    NOT_PRESENT        = "not-present"
    SUPERSEDED         = "superseded"
    NONE               = None

@dataclass(frozen=True)
class Boundary:
    facet: Optional[str] = None
    condition: Optional[str] = None

# ---------------------------------------------------------------- reason representations
# Alternatives are SUPPORTED rather than one being hard-coded, so the experiment can ask
#     R1  ~=_{R_req}  R2
# instead of asking which representation "looks better".
class FlatReason:
    """One opaque categorical token."""
    name = "FlatReason"
    @staticmethod
    def of(b: Boundary): return (b.condition,)

class LocusModalityReason:
    """The E13 factorization: (locus, modality)."""
    name = "LocusModalityReason"
    MODALITY = {
      "unobservable":"inaccessible", "unobserved":"not-yet-done",
      "uninterpreted":"not-yet-done", "not-assessed":"not-yet-done",
      "insufficient-evidence":"insufficient", "underdetermined":"non-discriminating",
      "theory-incomplete":"no-evaluator", "out-of-scope":"out-of-scope",
      "not-present":"not-present", "superseded":"superseded", None:None}
    @staticmethod
    def of(b: Boundary): return (b.facet, LocusModalityReason.MODALITY.get(b.condition))

class StructuredBoundary:
    """The full pair, unfactored."""
    name = "StructuredBoundary"
    @staticmethod
    def of(b: Boundary): return (b.facet, b.condition)

REASON_REPRESENTATIONS = [FlatReason, LocusModalityReason, StructuredBoundary]
