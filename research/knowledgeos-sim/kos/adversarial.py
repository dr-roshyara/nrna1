"""§20 ADVERSARIAL TESTS — 18 cases designed to fool the simulator.

The system must prefer "cannot determine" over fabricated certainty.
Failures here are PRESERVED, never repaired by adjusting the generator (§28).
"""
import random
from .world import World, Channel, DOMAINS
from .state import EpistemicState, knowledge_attribution, unknown_kind
from .inquiry import Inquiry, Requirement, EpistemicContract, Gap, Zero
from .scenarios import LEXICON, POLICY, STRICT, LENIENT, _pipe, _mk
from . import transitions as T

CASES = {}
def case(cid, name):
    def deco(fn): CASES[cid] = dict(id=cid, name=name, fn=fn); return fn
    return deco

# ------------------------------------------------------------------ AD-1
@case("AD-1", "highly probable but FALSE claim from a confident source")
def ad1(rng):
    """A source the admission policy rates fully reliable reports the WRONG value.
    The agent has no way to know.  Does Γ attribute knowledge to a falsehood?"""
    w = World(truth={"os": "RHEL8.6"},
              channels=[Channel("c-mis", "os", 1.0, source="misleading")])
    E = EpistemicState(standard=LENIENT)
    T.OpenHypothesisSpace(E, "os", DOMAINS["os"])
    oid = T.Acquire(E, w, w.channels[0], rng)
    E.observations[oid]["token"] = "RHEL9.8"                # confidently wrong
    eid = T.Qualify(E, oid, POLICY)
    iid = T.Interpret(E, eid, "network", LEXICON)
    T.Assess(E, iid, "os", LENIENT)
    T.Determine(E, "os", LENIENT)
    Q, S, EC = _mk(["os"], [Requirement("r1", "os", "unique")], std=LENIENT)
    return dict(id="AD-1", world=w, E=E, Q=Q, S=S, EC=EC,
                K=knowledge_attribution(E, Q, "network", EC),
                probe="P11")

# ------------------------------------------------------------------ AD-2
@case("AD-2", "TRUE claim with weak evidence")
def ad2(rng):
    w = World(truth={"os": "RHEL9.8"},
              channels=[Channel("c-weak", "os", 1.0, source="weak")])
    E = EpistemicState(standard=STRICT)
    T.OpenHypothesisSpace(E, "os", DOMAINS["os"])
    oid = T.Acquire(E, w, w.channels[0], rng)
    eid = T.Qualify(E, oid, POLICY)                          # reliability .4
    iid = T.Interpret(E, eid, "network", LEXICON)
    T.Assess(E, iid, "os", STRICT)
    T.Determine(E, "os", STRICT)
    Q, S, EC = _mk(["os"], [Requirement("r1", "os", "unique")], std=STRICT)
    return dict(id="AD-2", world=w, E=E, Q=Q, S=S, EC=EC,
                K=knowledge_attribution(E, Q, "network", EC), probe="no-attribution")

# ------------------------------------------------------------------ AD-3/4
@case("AD-3", "duplicated evidence presented as two sources")
def ad3(rng):
    w = World(truth={"port": 8081},
              channels=[Channel("c1", "port", 1.0, source="sourceA"),
                        Channel("c2", "port", 1.0, source="sourceB")])
    E = EpistemicState(standard=STRICT)
    T.OpenHypothesisSpace(E, "port", DOMAINS["port"])
    o1 = T.Acquire(E, w, w.channels[0], rng); e1 = T.Qualify(E, o1, POLICY)
    o2 = T.Acquire(E, w, w.channels[1], rng)
    pol = dict(POLICY); pol["derived_from"] = {o2: e1}
    e2 = T.Qualify(E, o2, pol)
    for e in (e1, e2):
        T.Assess(E, T.Interpret(E, e, "network", LEXICON), "port", STRICT)
    naive = T.Determine(E, "port", STRICT, dependence_aware=False)
    aware = T.Determine(E, "port", STRICT, dependence_aware=True)
    Q, S, EC = _mk(["port"], [Requirement("r1", "port", "corroborated")], std=STRICT)
    return dict(id="AD-3", world=w, E=E, Q=Q, S=S, EC=EC, naive=naive, aware=aware,
                K=knowledge_attribution(E, Q, "network", EC), probe="P8")

@case("AD-4", "correlated evidence from one upstream feed")
def ad4(rng):
    return ad3(rng) | dict(id="AD-4", probe="P8")

# ------------------------------------------------------------------ AD-6/7
@case("AD-6", "wrong model with excellent fit")
def ad6(rng):
    from .scenarios import scenario_H
    s = scenario_H(rng); s["id"] = "AD-6"; s["probe"] = "P9"; return s

@case("AD-7", "correct model with sparse evidence")
def ad7(rng):
    w = World(truth={"x_causes_y": True}, channels=[])
    E = EpistemicState(standard=STRICT)
    E.models = {"M2": dict(id="M2", structure="Y ~ X + Z", assumptions=("Z adjusted",),
                           beta=0.4, r2=0.11, causal_status="identified")}
    T.OpenHypothesisSpace(E, "x_causes_y", [True, False])
    T.Determine(E, "x_causes_y", STRICT)
    Q, S, EC = _mk(["x_causes_y"], [Requirement("r1", "x_causes_y", "causal")], std=STRICT)
    return dict(id="AD-7", world=w, E=E, Q=Q, S=S, EC=EC,
                K=knowledge_attribution(E, Q, "network", EC), probe="no-fabrication")

# ------------------------------------------------------------------ AD-8
@case("AD-8", "rejected hypothesis later reinstated")
def ad8(rng):
    w = World(truth={"os": "Ubuntu22.04"},
              channels=[Channel("c1", "os", 1.0, source="sourceA")])
    E = EpistemicState(standard=LENIENT)
    T.OpenHypothesisSpace(E, "os", DOMAINS["os"])
    T.Reject(E, "os", "Ubuntu22.04", "believed rpm-based")
    T.Determine(E, "os", LENIENT)
    before = dict(E.determinations["os"])
    E.rejections["os"].discard("Ubuntu22.04")               # reinstatement
    E.record("Reinstate", "os:Ubuntu22.04", (), ("earlier rejection was unfounded",))
    oid = T.Acquire(E, w, w.channels[0], rng); eid = T.Qualify(E, oid, POLICY)
    T.Assess(E, T.Interpret(E, eid, "network", LEXICON), "os", LENIENT)
    after = T.Revise(E, "os", LENIENT)
    Q, S, EC = _mk(["os"], [Requirement("r1", "os", "unique")], std=LENIENT)
    return dict(id="AD-8", world=w, E=E, Q=Q, S=S, EC=EC, before=before, after=after,
                K=knowledge_attribution(E, Q, "network", EC), probe="history-preserved")

# ------------------------------------------------------------------ AD-9
@case("AD-9", "observation with ambiguous interpretation")
def ad9(rng):
    w = World(truth={"status": "available"},
              channels=[Channel("c-st", "status", 1.0, ambiguous=True, source="sourceA")])
    E = EpistemicState(standard=LENIENT)
    T.OpenHypothesisSpace(E, "status", ["reachable", "licensed", "powered-on"])
    oid = T.Acquire(E, w, w.channels[0], rng); eid = T.Qualify(E, oid, POLICY)
    iid = T.Interpret(E, eid, "unspecified", LEXICON)       # no disambiguating context
    T.Assess(E, iid, "status", LENIENT)
    T.Determine(E, "status", LENIENT)
    Q, S, EC = _mk(["status"], [Requirement("r1", "status", "unique")], std=LENIENT)
    return dict(id="AD-9", world=w, E=E, Q=Q, S=S, EC=EC,
                K=knowledge_attribution(E, Q, "network", EC),
                readings=E.interpretations[iid]["readings"], probe="ambiguity-preserved")

# ------------------------------------------------------------------ AD-18
@case("AD-18", "conclusion generated without supporting evidence")
def ad18(rng):
    """Inject a determination with no assessments behind it — P10 must catch it."""
    w = World(truth={"os": "RHEL9.8"}, channels=[])
    E = EpistemicState(standard=LENIENT)
    T.OpenHypothesisSpace(E, "os", DOMAINS["os"])
    E.determinations["os"] = dict(prop="os", A=["RHEL9.8"], status="unique", weight=9.9,
                                  weights={}, dropped_dependent=0, independent_sources=9,
                                  standard_met=True, causal_status="not-identified",
                                  provenance=(), assumptions=())     # NO provenance
    Q, S, EC = _mk(["os"], [Requirement("r1", "os", "unique")], std=LENIENT)
    return dict(id="AD-18", world=w, E=E, Q=Q, S=S, EC=EC,
                K=knowledge_attribution(E, Q, "network", EC), probe="P10")

ORDER = ["AD-1","AD-2","AD-3","AD-4","AD-6","AD-7","AD-8","AD-9","AD-18"]
