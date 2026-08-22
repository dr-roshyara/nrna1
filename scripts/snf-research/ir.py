"""KOS-SNF-IR v0.1 — research-only common intermediate semantic representation.

Design scope (HPA commission, KOS-SNF Research Simulation Refinement / Phase 1):
  * RESEARCH-ONLY. This is NOT canonical SNF, NOT a KnowledgeOS representation,
    NOT the port encoding. It exists to let independent mechanisms be compared by a
    mechanism-independent distance. Identity authority is explicitly absent here.
  * Minimal but covering the pilot needs: entities, predicates/events, arguments,
    semantic roles, negation, modality, quantification, temporal relations,
    coreference, optional/unknown/ambiguous roles, uncertainty, abstention, provenance.

Discipline:
  * No gold here — this module knows nothing about corpora or expected answers.
  * `provenance` records WHICH mechanism produced the IR (never grants authority).
"""

from __future__ import annotations

from dataclasses import dataclass, field, asdict
from typing import Any

IR_VERSION = "KOS-SNF-IR v0.1"

# ---------------------------------------------------------------------------
# Controlled vocabularies (v0.1 — minimal, pilot-sufficient)
# ---------------------------------------------------------------------------

# Semantic roles (Pāṇinian kāraka mapping in parentheses).
ROLE_AGENT = "AGENT"            # kartṛ
ROLE_PATIENT = "PATIENT"        # karman
ROLE_INSTRUMENT = "INSTRUMENT"  # karaṇa
ROLE_RECIPIENT = "RECIPIENT"    # sampradāna
ROLE_LOCATION = "LOCATION"      # adhikaraṇa
ROLE_SOURCE = "SOURCE"          # apādāna
ROLES = (ROLE_AGENT, ROLE_PATIENT, ROLE_INSTRUMENT, ROLE_RECIPIENT,
         ROLE_LOCATION, ROLE_SOURCE)

# Argument status: what the mechanism knows about the presence of a role.
#   EXPRESSED      — the surface licenses the role and fills it
#   UNKNOWN        — a role may exist but the mechanism cannot determine it
#   NOT_EXPRESSED  — the surface does not express the role (absence, not ignorance)
#   AMBIGUOUS      — the surface licenses the role but its filler is underdetermined
#   NOT_LICENSED   — the predicate's frame does not license this role
ARG_EXPRESSED = "EXPRESSED"
ARG_UNKNOWN = "UNKNOWN"
ARG_NOT_EXPRESSED = "NOT_EXPRESSED"
ARG_AMBIGUOUS = "AMBIGUOUS"
ARG_NOT_LICENSED = "NOT_LICENSED"
ARG_STATUSES = (ARG_EXPRESSED, ARG_UNKNOWN, ARG_NOT_EXPRESSED, ARG_AMBIGUOUS,
                ARG_NOT_LICENSED)

MODALITY_ASSERTED = "ASSERTED"
MODALITY_NECESSARY = "NECESSARY"
MODALITY_POSSIBLE = "POSSIBLE"
MODALITY_REQUESTED = "REQUESTED"
MODALITIES = (MODALITY_ASSERTED, MODALITY_NECESSARY, MODALITY_POSSIBLE,
              MODALITY_REQUESTED)

TEMP_NONE = "NONE"
TEMP_PRESENT = "PRESENT"
TEMP_PAST = "PAST"
TEMP_FUTURE = "FUTURE"
TEMPORALS = (TEMP_NONE, TEMP_PRESENT, TEMP_PAST, TEMP_FUTURE)

QUANT_NONE = "NONE"
QUANT_ALL = "ALL"
QUANT_SOME = "SOME"
QUANTIFIERS = (QUANT_NONE, QUANT_ALL, QUANT_SOME)

# Uncertainty status: the mechanism's own epistemic state about THIS IR.
UNC_RESOLVED = "RESOLVED"   # mechanism is confident the IR is the intended reading
UNC_AMBIGUOUS = "AMBIGUOUS"  # mechanism knows another reading is plausible
UNC_UNKNOWN = "UNKNOWN"      # mechanism cannot determine the intended reading
UNC_STATUSES = (UNC_RESOLVED, UNC_AMBIGUOUS, UNC_UNKNOWN)


@dataclass
class Argument:
    """A single argument slot of a predicate."""
    role: str
    entity: str | None
    status: str = ARG_EXPRESSED

    def to_dict(self) -> dict[str, Any]:
        return {"role": self.role, "entity": self.entity, "status": self.status}

    @staticmethod
    def from_dict(d: dict[str, Any]) -> "Argument":
        return Argument(role=d["role"], entity=d.get("entity"),
                        status=d.get("status", ARG_EXPRESSED))


@dataclass
class IR:
    """One candidate meaning (KOS-SNF-IR v0.1).

    provenance records the mechanism that produced this candidate. It is a
    bookkeeping field for the experiment, NOT an authority marker.
    """
    predicate: str
    arguments: list[Argument] = field(default_factory=list)
    negation: bool = False
    modality: str = MODALITY_ASSERTED
    temporal: str = TEMP_NONE
    quantification: str = QUANT_NONE
    uncertainty: str = UNC_RESOLVED
    confidence: float = 1.0
    provenance: str = ""

    def role_map(self) -> dict[str, Argument]:
        """Return {role: argument} for EXPRESSED arguments (first wins)."""
        out: dict[str, Argument] = {}
        for a in self.arguments:
            if a.role in out:
                continue
            out[a.role] = a
        return out

    def to_dict(self) -> dict[str, Any]:
        d = asdict(self)
        d["version"] = IR_VERSION
        return d

    @staticmethod
    def from_dict(d: dict[str, Any]) -> "IR":
        args = [Argument.from_dict(a) for a in d.get("arguments", [])]
        return IR(
            predicate=d["predicate"],
            arguments=args,
            negation=bool(d.get("negation", False)),
            modality=d.get("modality", MODALITY_ASSERTED),
            temporal=d.get("temporal", TEMP_NONE),
            quantification=d.get("quantification", QUANT_NONE),
            uncertainty=d.get("uncertainty", UNC_RESOLVED),
            confidence=float(d.get("confidence", 1.0)),
            provenance=d.get("provenance", ""),
        )


def make_ir(predicate: str, *role_entity_pairs: tuple[str, str | None],
            negation: bool = False, modality: str = MODALITY_ASSERTED,
            temporal: str = TEMP_NONE, quantification: str = QUANT_NONE,
            uncertainty: str = UNC_RESOLVED, confidence: float = 1.0,
            provenance: str = "") -> IR:
    """Convenience constructor. Absent roles are recorded as NOT_EXPRESSED.

    Example:
        make_ir("APPROVE", (ROLE_AGENT, "committee"), (ROLE_PATIENT, "order"))
    """
    args = []
    for role, entity in role_entity_pairs:
        status = ARG_EXPRESSED if entity is not None else ARG_NOT_EXPRESSED
        args.append(Argument(role=role, entity=entity, status=status))
    return IR(predicate=predicate, arguments=args, negation=negation,
              modality=modality, temporal=temporal, quantification=quantification,
              uncertainty=uncertainty, confidence=confidence, provenance=provenance)


def _canon_entity(name: str | None) -> str | None:
    """Lowercase, singularize (toy: strip trailing -s) — surface normalization
    belongs to the mechanisms, not here; this only makes gold writing stable."""
    if name is None:
        return None
    s = name.strip().lower()
    if s.endswith("ies") and len(s) > 4:
        return s[:-3] + "y"
    if s.endswith("s") and not s.endswith("ss") and len(s) > 3:
        return s[:-1]
    return s
