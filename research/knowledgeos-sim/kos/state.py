"""§2.1 / §2.12 — Epistemic State E_t and Knowledge State K_t = Γ(E_t,Q,C,EC).

v1.1 CORRECTION (from the definitions audit):
    E_{a,c,t} is the epistemic state; K_{a,c,t} = KnowledgeAttribution(E) ⊆ E.
Knowledge is therefore NEVER the whole epistemic state.
"""
from dataclasses import dataclass, field
from .types import Artifact, Provenance

@dataclass
class EpistemicState:
    """E_t — everything the agent holds, true or not."""
    observations:    dict = field(default_factory=dict)
    interpretations: dict = field(default_factory=dict)
    evidence:        dict = field(default_factory=dict)
    hypotheses:      dict = field(default_factory=dict)   # prop -> HypothesisSpace
    assessments:     list = field(default_factory=list)
    determinations:  dict = field(default_factory=dict)   # prop -> A_t + status
    attributions:    dict = field(default_factory=dict)   # prop -> attribution record
    rejections:      dict = field(default_factory=dict)   # prop -> set of rejected h
    models:          dict = field(default_factory=dict)
    standard:        object = None
    history:         list = field(default_factory=list)   # APPEND-ONLY (AX-5, DEF-25)
    identity:        str = "agent-1"
    t:               int = 0
    _seq:            int = 0

    def nid(self, pfx):
        self._seq += 1
        return f"{pfx}{self._seq}"

    def record(self, transition, produced, inputs, assumptions=()):
        """History is append-only: superseded content is never rewritten (§17)."""
        self.history.append(dict(t=self.t, transition=transition,
                                 produced=produced, inputs=tuple(inputs),
                                 assumptions=tuple(assumptions)))

    def snapshot_K(self, Q, C, EC):
        return knowledge_attribution(self, Q, C, EC)


def knowledge_attribution(E, Q, C, EC):
    """Γ : (E_t,Q_t,C_t,EC_t) → K_t.

    Γ selects from E ONLY what the contract lets it attribute.  It has no access
    to world truth; factivity is checked afterwards by an INDEPENDENT oracle
    (§24), which is what makes P11 a real test rather than a definition.
    """
    K = {}
    for prop, det in E.determinations.items():
        if prop not in [t for t in Q.target]:
            continue
        rec = dict(status=det["status"], A=tuple(det["A"]),
                   independent_sources=det.get("independent_sources", 0),
                   weight=det.get("weight", 0.0),
                   causal_status=det.get("causal_status", "not-identified"),
                   provenance=det.get("provenance", ()),
                   assumptions=det.get("assumptions", ()))
        pol = EC.attribution_policy
        if pol == "justified-unique":
            rec["attributed"] = (det["status"] == "unique"
                                 and det.get("standard_met", False))
        elif pol == "justified-any":                      # deliberately laxer arm
            rec["attributed"] = det["status"] in ("unique", "underdetermined")
        elif pol == "none":
            rec["attributed"] = False
        else:
            raise ValueError(f"unknown attribution policy {pol}")
        rec["value"] = det["A"][0] if (rec["attributed"] and len(det["A"]) == 1) else None
        K[prop] = rec
    return K


# ---- §6 four-way unknown taxonomy (must NOT collapse into one UNKNOWN) -------
UNOBSERVED     = "UNOBSERVED"       # a channel exists, it was never queried
UNINTERPRETED  = "UNINTERPRETED"    # observation held, no semantic content assigned
UNDERDETERMINED= "UNDERDETERMINED"  # evidence present, |A_t| > 1
UNOBSERVABLE   = "UNOBSERVABLE"     # no channel exists at all

def unknown_kind(E, world, prop):
    if not world.observable(prop):
        return UNOBSERVABLE
    if not any(o["prop"] == prop for o in E.observations.values()):
        return UNOBSERVED
    if not any(i["prop"] == prop for i in E.interpretations.values()):
        return UNINTERPRETED
    det = E.determinations.get(prop)
    if det and det["status"] == "underdetermined":
        return UNDERDETERMINED
    return None
