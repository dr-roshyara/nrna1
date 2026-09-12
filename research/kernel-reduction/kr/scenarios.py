"""The 16 deterministic scenarios of the protocol (Part X).

Each scenario declares (a) the ambient carriers the situation supplies and
(b) the capabilities that must be achievable for the scenario to be handled.
Scenario texts are the protocol's, using Nexus/KnowledgeOS examples.
"""
from .carriers import *

def S(sid, text, ambient, req):
    return dict(id=sid, text=text, ambient=list(ambient), required=list(req))

BASE = [WORLD, CONTEXT, INQUIRY, K_STATE]

SCENARIOS = [
 S("S1","Nexus server runs RHEL 9.8.",            BASE+[POLICY], ["C1","C2","C3","C18","C21","C25"]),
 S("S2","Server has 31 GB RAM.",                  BASE+[POLICY], ["C1","C2","C3","C25"]),
 S("S3","Nexus listens on port 8081.",            BASE+[POLICY], ["C1","C2","C3","C25"]),
 S("S4","Conflicting evidence: A says 8081, B says 8082.",
                                                  BASE+[POLICY], ["C1","C2","C3","C5","C18","C25","C11"]),
 S("S5","Ambiguous semantics: 'the server is available'.",
                                                  BASE,          ["C1","C2","C14","C19","C21"]),
 S("S6","Temporal revision: t1 version X, t2 version Y.",
                                                  BASE+[POLICY], ["C1","C11","C15","C24","C5"]),
 S("S7","Missing evidence: is 8081 externally reachable? no firewall evidence.",
                                                  BASE+[IDEAL_STATE], ["C8","C12","C20"]),
 S("S8","Competing hypotheses H1 direct vs H2 proxy-mediated.",
                                                  BASE+[POLICY],  ["C6","C5","C19","C10"]),
 S("S9","Model criticism: model fits, assumptions questionable.",
                                                  BASE+[POLICY,RULE], ["C7","C9","C10","C17","C16"]),
 S("S10","Same raw observation, different meaning under different context/purpose.",
                                                  BASE,          ["C2","C14","C21","C22"]),
 S("S11","Inquiry-relative adequacy: 'migrate?' vs 'migrate without downtime?'.",
                                                  BASE+[IDEAL_STATE], ["C12","C8","C23"]),
 S("S12","Smallest adequate answer from a large K_t.",
                                                  BASE+[IDEAL_STATE], ["C12","C23"]),
 S("S13","Select next evidence-gathering action under cost.",
                                                  BASE+[ACTION_SET,OBJECTIVE], ["C13"]),
 S("S14","Non-identifiability: observations cannot distinguish H1 from H2.",
                                                  BASE+[POLICY], ["C6","C19","C20","C5"]),
 S("S15","Provenance conflict: two observations, different authority.",
                                                  BASE+[POLICY], ["C18","C5","C25","C10"]),
 S("S16","Representation equivalence: two representations, same semantics.",
                                                  BASE,          ["C3","C4","C22","C2"]),
]

def run_scenarios(ops):
    """A scenario PASSES iff every required capability is achievable given the
    scenario's ambient carriers."""
    from .reach import reach, atom_pool
    from .capabilities import CAP, achieved
    pool = atom_pool(ops)
    out = []
    for sc in SCENARIOS:
        kinds, wit = reach(ops, ambient=sc["ambient"])
        failed = [c for c in sc["required"] if not achieved(c, kinds, pool)]
        out.append(dict(id=sc["id"], passed=not failed, failed_capabilities=failed,
                        kinds=sorted(kinds)))
    return out
