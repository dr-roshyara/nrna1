"""§5 REQUIRED SCENARIO FAMILIES A..J — every scenario has a semantic purpose."""
import random
from .world import World, Channel, DOMAINS
from .state import EpistemicState, knowledge_attribution, unknown_kind
from .inquiry import (Inquiry, Requirement, EpistemicStandard, EpistemicContract,
                      Gap, Zero, Adequate, ideal_state)
from . import transitions as T

LEXICON = {
    ("available", "network"):  ["reachable"],
    ("available", "licensing"):["licensed"],
    "available":               ["reachable", "licensed", "powered-on"],
    "RHEL9.8":                 ["RHEL9.8"],
    "RHEL8.6":                 ["RHEL8.6"],
    "Ubuntu22.04":             ["Ubuntu22.04"],
    8081:                      [8081],
    8082:                      [8082],
    "direct":                  ["direct"],
    "proxy":                   ["proxy"],
}
POLICY = dict(id="pol-default", reliability={"sourceA": 1.0, "sourceB": 1.0,
                                             "weak": 0.4, "misleading": 1.0},
              excluded_sources=(), derived_from={})
STRICT  = EpistemicStandard("S-strict",  min_weight=1.0, require_independent_sources=1,
                            require_corroboration=True)
LENIENT = EpistemicStandard("S-lenient", min_weight=0.3, require_independent_sources=1,
                            require_corroboration=False)

def _pipe(E, world, chans, prop, cands, standard, rng, context="network",
          policy=None, model=None):
    policy = policy or POLICY
    T.OpenHypothesisSpace(E, prop, cands)
    for ch in chans:
        oid = T.Acquire(E, world, ch, rng)
        if oid is None: continue
        eid = T.Qualify(E, oid, policy)
        if eid is None: continue
        iid = T.Interpret(E, eid, context, LEXICON)
        T.Assess(E, iid, prop, standard, model)
    return T.Determine(E, prop, standard)

def _mk(target, reqs, purpose="ops", context="network", std=LENIENT, pol="justified-unique"):
    S = std
    EC = EpistemicContract("EC-1", S, (), attribution_policy=pol)
    Q = Inquiry("Q", tuple(target), purpose, context, tuple(reqs))
    return Q, S, EC

# ------------------------------------------------------------------ A
def scenario_A(rng):
    """SIMPLE DETERMINATION — evidence uniquely determines the target.
    Verify Knowledge is NOT simply copied from Observation."""
    w = World(truth={"os": "RHEL9.8"},
              channels=[Channel("c-os-a", "os", 1.0, source="sourceA"),
                        Channel("c-os-b", "os", 1.0, source="sourceB")])
    E = EpistemicState(standard=STRICT)
    Q, S, EC = _mk(["os"], [Requirement("r1", "os", "unique")], std=STRICT)
    _pipe(E, w, w.channels, "os", DOMAINS["os"], S, rng)
    K = knowledge_attribution(E, Q, "network", EC)
    return dict(id="A", world=w, E=E, K=K, Q=Q, S=S, EC=EC,
                expect="unique determination, attributed, K != raw observation")

# ------------------------------------------------------------------ B
def scenario_B(rng):
    """INCOMPLETE EVIDENCE — anti-fabrication.  The system must NOT invent."""
    w = World(truth={"reachable": True},
              channels=[Channel("c-os-a", "os", 1.0)])       # no channel for `reachable`
    E = EpistemicState(standard=STRICT)
    Q, S, EC = _mk(["reachable"], [Requirement("r1", "reachable", "unique")], std=STRICT)
    T.OpenHypothesisSpace(E, "reachable", DOMAINS["reachable"])
    T.Determine(E, "reachable", S)
    K = knowledge_attribution(E, Q, "network", EC)
    return dict(id="B", world=w, E=E, K=K, Q=Q, S=S, EC=EC,
                unknown=unknown_kind(E, w, "reachable"),
                expect="cannot-determine, Zero=false, Gap nonempty, nothing invented")

# ------------------------------------------------------------------ C
def scenario_C(rng):
    """COMPETING HYPOTHESES — H1 eliminated, H2/H3 indistinguishable.
    Reject(H1) must NOT become Accept(H2)."""
    w = World(truth={"os": "RHEL8.6"},
              channels=[Channel("c-os-a", "os", 1.0, source="sourceA")])
    E = EpistemicState(standard=LENIENT)
    Q, S, EC = _mk(["os"], [Requirement("r1", "os", "unique")], std=LENIENT)
    T.OpenHypothesisSpace(E, "os", DOMAINS["os"])
    T.Reject(E, "os", "Ubuntu22.04", "package manager is rpm-based")
    # evidence that cannot separate the two remaining rpm distributions
    oid = T.Acquire(E, w, Channel("c-fam", "os", 1.0), rng)
    E.observations[oid]["token"] = "rpm-family"
    eid = T.Qualify(E, oid, POLICY)
    iid = T.Interpret(E, eid, "network", {"rpm-family": ["RHEL9.8", "RHEL8.6"]})
    T.Assess(E, iid, "os", S)
    det = T.Determine(E, "os", S)
    K = knowledge_attribution(E, Q, "network", EC)
    return dict(id="C", world=w, E=E, K=K, Q=Q, S=S, EC=EC,
                expect="H1 rejected; A={RHEL9.8,RHEL8.6}; underdetermined; no attribution")

# ------------------------------------------------------------------ D
def scenario_D(rng):
    """DIFFERENT EPISTEMIC STANDARDS — reality/observation/evidence/inquiry FIXED,
    only S^epi changes.  Standards must not change reality or evidence."""
    out = {}
    for std in (LENIENT, STRICT):
        w = World(truth={"os": "RHEL9.8"},
                  channels=[Channel("c-os-a", "os", 1.0, source="sourceA")])   # ONE source
        E = EpistemicState(standard=std)
        Q, S, EC = _mk(["os"], [Requirement("r1", "os", "unique")], std=std)
        _pipe(E, w, w.channels, "os", DOMAINS["os"], S, rng)
        out[std.id] = dict(det=E.determinations["os"],
                           K=knowledge_attribution(E, Q, "network", EC),
                           evidence=[dict(e) for e in E.evidence.values()],
                           truth=dict(w.truth), E=E, Q=Q, EC=EC, world=w)
    return dict(id="D", arms=out,
                expect="same evidence & reality; determination differs by standard")

# ------------------------------------------------------------------ E
def scenario_E(rng):
    """DIFFERENT INQUIRIES over a FIXED K_t — inquiry-relative adequacy."""
    w = World(truth={"os": "RHEL9.8", "ram_gb": 31},
              channels=[Channel("c-os-a", "os", 1.0, source="sourceA"),
                        Channel("c-os-b", "os", 1.0, source="sourceB")])
    E = EpistemicState(standard=LENIENT)
    _pipe(E, w, w.channels, "os", DOMAINS["os"], LENIENT, rng)
    Q1 = Inquiry("Q1", ("os",), "identify OS", "network", (Requirement("r1", "os", "unique"),))
    Q2 = Inquiry("Q2", ("os", "ram_gb"), "suitability for workload X", "network",
                 (Requirement("r1", "os", "unique"), Requirement("r2", "ram_gb", "unique")))
    EC = EpistemicContract("EC-1", LENIENT, (), attribution_policy="justified-unique")
    K = knowledge_attribution(E, Q2, "network", EC)          # same E, widest target
    return dict(id="E", world=w, E=E, K=K, EC=EC, S=LENIENT,
                Q1=Q1, Q2=Q2,
                I1=ideal_state(Q1, "network", LENIENT, EC),
                I2=ideal_state(Q2, "network", LENIENT, EC),
                gap1=Gap(knowledge_attribution(E, Q1, "network", EC), Q1, "network", EC),
                gap2=Gap(K, Q2, "network", EC),
                expect="same K_t, different I_t, different Δ_t")

# ------------------------------------------------------------------ F
def scenario_F(rng):
    """CONFLICTING EVIDENCE — must be preservable as unresolved, not averaged."""
    w = World(truth={"port": 8081},
              channels=[Channel("c-p-a", "port", 1.0, source="sourceA"),
                        Channel("c-p-b", "port", 1.0, source="sourceB")])
    E = EpistemicState(standard=LENIENT)
    T.OpenHypothesisSpace(E, "port", DOMAINS["port"])
    for ch, tok in ((w.channels[0], 8081), (w.channels[1], 8082)):
        oid = T.Acquire(E, w, ch, rng); E.observations[oid]["token"] = tok
        eid = T.Qualify(E, oid, POLICY)
        iid = T.Interpret(E, eid, "network", LEXICON)
        T.Assess(E, iid, "port", LENIENT)
    det = T.Determine(E, "port", LENIENT)
    Q, S, EC = _mk(["port"], [Requirement("r1", "port", "unique")], std=LENIENT)
    K = knowledge_attribution(E, Q, "network", EC)
    return dict(id="F", world=w, E=E, K=K, Q=Q, S=S, EC=EC,
                expect="conflict preserved: A={8081,8082}, no averaging, no attribution")

# ------------------------------------------------------------------ G
def scenario_G(rng):
    """DEPENDENT EVIDENCE — e2 derived from e1; naive aggregation double counts."""
    w = World(truth={"port": 8081},
              channels=[Channel("c-p-a", "port", 1.0, source="sourceA"),
                        Channel("c-p-copy", "port", 1.0, source="sourceB")])
    res = {}
    for aware in (False, True):
        E = EpistemicState(standard=STRICT)
        T.OpenHypothesisSpace(E, "port", DOMAINS["port"])
        pol = dict(POLICY); pol["derived_from"] = {}
        o1 = T.Acquire(E, w, w.channels[0], rng); E.observations[o1]["token"] = 8081
        e1 = T.Qualify(E, o1, pol)
        o2 = T.Acquire(E, w, w.channels[1], rng); E.observations[o2]["token"] = 8081
        pol2 = dict(pol); pol2["derived_from"] = {o2: e1}   # e2 is a COPY of e1
        e2 = T.Qualify(E, o2, pol2)
        for e in (e1, e2):
            iid = T.Interpret(E, e, "network", LEXICON)
            T.Assess(E, iid, "port", STRICT)
        res[aware] = T.Determine(E, "port", STRICT, dependence_aware=aware)
        res[aware]["_E"] = E
    Q, S, EC = _mk(["port"], [Requirement("r1", "port", "corroborated")], std=STRICT)
    return dict(id="G", world=w, naive=res[False], aware=res[True], Q=Q, S=S, EC=EC,
                E=res[True]["_E"], K=knowledge_attribution(res[True]["_E"], Q, "network", EC),
                expect="dependence-aware run drops the copy; naive run double counts")

# ------------------------------------------------------------------ H
def scenario_H(rng):
    """MODEL MISMATCH — M1 fits well but is causally wrong; M2 is causally correct.
    Fit alone must NOT produce knowledge."""
    r = random.Random(20260902)
    xs, ys = [], []
    for _ in range(4000):
        z = r.gauss(0, 1)
        xs.append(z + r.gauss(0, .3))          # X <- Z
        ys.append(2*z + r.gauss(0, .3))        # Y <- Z ; NO X -> Y edge
    n = len(xs); mx = sum(xs)/n; my = sum(ys)/n
    sxy = sum((a-mx)*(b-my) for a, b in zip(xs, ys)); sxx = sum((a-mx)**2 for a in xs)
    syy = sum((b-my)**2 for b in ys)
    M1 = dict(id="M1", structure="Y ~ X", assumptions=("no confounding",),
              beta=sxy/sxx, r2=(sxy**2)/(sxx*syy), causal_status="not-identified")
    M2 = dict(id="M2", structure="Y ~ X + Z", assumptions=("Z observed and adjusted",),
              beta=0.0, r2=None, causal_status="identified")
    w = World(truth={"x_causes_y": False}, channels=[])
    E = EpistemicState(standard=STRICT); E.models = {"M1": M1, "M2": M2}
    Q, S, EC = _mk(["x_causes_y"], [Requirement("r1", "x_causes_y", "causal")], std=STRICT)
    T.OpenHypothesisSpace(E, "x_causes_y", [True, False])
    T.Determine(E, "x_causes_y", S)
    K = knowledge_attribution(E, Q, "network", EC)
    return dict(id="H", world=w, E=E, K=K, Q=Q, S=S, EC=EC, M1=M1, M2=M2,
                expect="high fit under M1, causal_status not-identified, no causal knowledge")

# ------------------------------------------------------------------ I
def scenario_I(rng):
    """RETROSPECTIVE REVISION — K_2 != K_1, identity stable, history preserved."""
    w = World(truth={"os": "RHEL9.8"},
              channels=[Channel("c-os-a", "os", 1.0, source="sourceA")])
    E = EpistemicState(standard=LENIENT)
    Q, S, EC = _mk(["os"], [Requirement("r1", "os", "unique")], std=LENIENT)
    _pipe(E, w, w.channels, "os", DOMAINS["os"], S, rng)
    K1 = knowledge_attribution(E, Q, "network", EC); id1 = E.identity
    w.advance({"os": "RHEL8.6"}); E.t = w.t                     # reality changed
    ch = Channel("c-os-c", "os", 1.0, source="sourceB")
    oid = T.Acquire(E, w, ch, rng); eid = T.Qualify(E, oid, POLICY)
    iid = T.Interpret(E, eid, "network", LEXICON); T.Assess(E, iid, "os", S)
    T.Revise(E, "os", S)
    K2 = knowledge_attribution(E, Q, "network", EC)
    return dict(id="I", world=w, E=E, K=K2, K1=K1, K2=K2, Q=Q, S=S, EC=EC,
                identity_stable=(id1 == E.identity),
                history_len=len(E.history),
                expect="K1 != K2, identity stable, K1 recoverable from history")

# ------------------------------------------------------------------ J
def scenario_J(rng):
    """UNOBSERVABLE PROPERTY — must NOT become false / p=0 / rejected / absent."""
    w = World(truth={"reachable": True}, channels=[Channel("c-os-a", "os", 1.0)])
    E = EpistemicState(standard=STRICT)
    Q, S, EC = _mk(["reachable"], [Requirement("r1", "reachable", "determined")], std=STRICT)
    T.OpenHypothesisSpace(E, "reachable", DOMAINS["reachable"])
    T.Determine(E, "reachable", S)
    K = knowledge_attribution(E, Q, "network", EC)
    return dict(id="J", world=w, E=E, K=K, Q=Q, S=S, EC=EC,
                unknown=unknown_kind(E, w, "reachable"),
                expect="UNOBSERVABLE, not observed-false and not probability zero")

FAMILIES = [scenario_A, scenario_B, scenario_C, scenario_D, scenario_E,
            scenario_F, scenario_G, scenario_H, scenario_I, scenario_J]
