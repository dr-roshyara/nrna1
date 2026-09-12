"""§25 FORMAL TYPE CHECK — the type table, declared BEFORE execution.

Every type: Input / Output / Dependencies / Allowed transformations /
Forbidden substitutions.  Notation collisions are recorded, not silently fixed.
"""
from dataclasses import dataclass, field
from typing import Any

TYPE_TABLE = {
 # name              inputs                      output/carrier      forbidden substitutions
 "RealityState":    (["-"],                      "world",            ["Observation"]),
 "Observation":     (["RealityState","Channel"], "percept",          ["RealityState","Evidence"]),
 "Information":     (["Observation"],            "signal content",   ["Evidence"]),
 "Evidence":        (["Observation","Policy"],   "admitted datum",   ["Observation","Interpretation"]),
 "Interpretation":  (["Evidence","Context"],     "semantic content", ["Evidence","Hypothesis"]),
 "Claim":           (["Interpretation"],         "asserted content", ["Knowledge"]),
 "Hypothesis":      (["Claim","HypothesisSpace"],"candidate",        ["Interpretation","Determination"]),
 "HypothesisSpace": (["Inquiry"],                "H_Q",              ["Determination"]),
 "EvidenceAssessment":(["Evidence","Hypothesis","HypothesisSpace","Model","EpistemicStandard","Context"],
                                                 "weight/verdict",   ["Determination"]),
 "EpistemicStandard":(["-"],                     "S^epi",            ["Authorization","Policy","RealityState"]),
 "Model":           (["-"],                      "(Struct,Assump,Param)", ["RealityState"]),
 "Determination":   (["EvidenceAssessment","HypothesisSpace"], "A_t ⊆ H_Q", ["Knowledge","Decision"]),
 "KnowledgeAttribution":(["Determination","EpistemicContract"], "Knows(a,p,c,t)", ["Determination","EpistemicState"]),
 "EpistemicState":  (["*"],                      "E_t",              ["KnowledgeState"]),
 "KnowledgeState":  (["EpistemicState","Inquiry","Context","EpistemicContract"], "K_t = Γ(E_t,…)", ["EpistemicState","IdealState"]),
 "Inquiry":         (["-"],                      "Q=(Target,Purpose,Context,Req,Constraints)", ["Decision"]),
 "IdealState":      (["Inquiry","Context","EpistemicStandard","EpistemicContract"], "I_t", ["KnowledgeState","RealityState"]),
 "Requirement":     (["Inquiry","EpistemicContract"], "r",           ["Gap"]),
 "Gap":             (["KnowledgeState","Requirement"], "Δ_t",        ["Zero","Innovation"]),
 "Zero":            (["Gap"],                    "predicate",        ["Gap","probability 0","certainty"]),
 "Proposal":        (["KnowledgeState","Gap"],   "proposed act",     ["Decision"]),
 "Decision":        (["Proposal"],               "chosen act",       ["Determination","Authorization"]),
 "Authorization":   (["Decision","Governance"],  "permission",       ["Decision","Action","EpistemicStandard"]),
 "Action":          (["Authorization"],          "world effect",     ["Authorization"]),
 "History":         (["*"],                      "append-only log",  ["KnowledgeState"]),
 "Identity":        (["-"],                      "stable id",        ["state equality"]),
}

# §25 notation-collision audit over the single-letter symbols the prompt names
NOTATION = {
 "P": ["Proposition (DEF-5)", "Probability (DEF-18)", "Preservation vector 𝒫 (§66)"],
 "H": ["Hypothesis", "History (DEF-25)", "HypothesisSpace H_Q"],
 "E": ["Evidence (DEF-7)", "EpistemicState E_t (v1.1)", "Expectation"],
 "K": ["KnowledgeState K_t (DEF-11)", "Kernel 𝒦 (DEF-32)", "Knowledge Space 𝕂"],
 "S": ["EpistemicStandard S^epi", "Support axis of Σ", "SemDomain"],
 "C": ["Context", "Conflict axis of Σ", "semantic core 𝒞 (DEF)"],
 "R": ["Requirement", "Resolution axis of Σ", "Representation space"],
 "M": ["Model M_t", "Measure", "Mapping"],
 "I": ["IdealState I_t", "Invariant I1..I9", "Information/Mutual information"],
 "Q": ["Inquiry Q", "Quotient"],
}
COLLISIONS = {k: v for k, v in NOTATION.items() if len(v) > 1}

@dataclass
class Provenance:
    """§19 — source provenance vs inferential provenance are different types."""
    kind: str                 # "source" | "inferential"
    origin: str               # channel id, or the transition that produced it
    inputs: tuple = ()        # ids of the artifacts consumed
    assumptions: tuple = ()   # declared assumptions used
    t: int = 0

@dataclass
class Artifact:
    id: str
    type: str
    content: Any
    prov: Provenance
    meta: dict = field(default_factory=dict)
    def __repr__(self): return f"<{self.type}:{self.id}>"
