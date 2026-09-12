"""THE FACTIVITY IMPOSSIBILITY WITNESS.

Theory v1.1 asserts BOTH:
    (1)  Knows(a,p,c,t) -> True(p,c,t)                       [DEF-1, factivity]
    (2)  K_{a,c,t} = Γ(E_{a,c,t}, Q_t, C_t, EC_t)            [v1.1 attribution]

Claim: (1) and (2) are JOINTLY UNSATISFIABLE for any total Γ that attributes at
all, because Γ's domain contains no truth.

Construction: exhibit two worlds w1 != w2, differing in the truth of p, that
produce an IDENTICAL epistemic state E.  Since Γ is a function of E, it returns
the same K in both.  If K attributes p, it is false in one of them.
"""
import random
from .world import World, Channel, DOMAINS
from .state import EpistemicState, knowledge_attribution
from .inquiry import Inquiry, Requirement, EpistemicContract
from .scenarios import LEXICON, POLICY, LENIENT, _mk
from . import transitions as T

def _run(truth_value, reported, rng):
    """Same reported token in both worlds; only the LATENT truth differs."""
    w = World(truth={"os": truth_value},
              channels=[Channel("c", "os", 1.0, source="sourceA")])
    E = EpistemicState(standard=LENIENT)
    T.OpenHypothesisSpace(E, "os", DOMAINS["os"])
    oid = T.Acquire(E, w, w.channels[0], rng)
    E.observations[oid]["token"] = reported          # channel is honest in w1, misleading in w2
    eid = T.Qualify(E, oid, POLICY)
    T.Assess(E, T.Interpret(E, eid, "network", LEXICON), "os", LENIENT)
    T.Determine(E, "os", LENIENT)
    return w, E

def _fingerprint(E):
    """Everything Γ could possibly read: the epistemic state, and nothing else."""
    return dict(
        observations=sorted((o["prop"], o["token"], o["source"], o["ambiguous"])
                            for o in E.observations.values()),
        evidence=sorted((e["prop"], e["token"], e["source"], e["reliability"])
                        for e in E.evidence.values()),
        interpretations=sorted((i["prop"], i["context"], i["readings"])
                               for i in E.interpretations.values()),
        assessments=sorted((a["prop"], tuple(sorted(a["per_h"].items())))
                           for a in E.assessments),
        determinations={p: (d["status"], tuple(d["A"]), d["standard_met"])
                        for p, d in E.determinations.items()},
        hypotheses={k: tuple(v) for k, v in E.hypotheses.items()},
        rejections={k: tuple(sorted(map(str, v))) for k, v in E.rejections.items()},
    )

def witness(policy="justified-unique"):
    rng = random.Random(20260902)
    w1, E1 = _run("RHEL9.8", "RHEL9.8", rng)      # honest source
    w2, E2 = _run("RHEL8.6", "RHEL9.8", rng)      # misleading source, same report
    Q, S, EC = _mk(["os"], [Requirement("r1", "os", "unique")], std=LENIENT, pol=policy)
    K1 = knowledge_attribution(E1, Q, "network", EC)
    K2 = knowledge_attribution(E2, Q, "network", EC)
    f1, f2 = _fingerprint(E1), _fingerprint(E2)
    att1 = K1["os"]["attributed"]; att2 = K2["os"]["attributed"]
    true1 = (K1["os"]["value"] == w1.truth["os"])
    true2 = (K2["os"]["value"] == w2.truth["os"])
    return dict(
        policy=policy,
        epistemic_states_identical=(f1 == f2),
        worlds_differ=(w1.truth != w2.truth),
        K_identical=(K1 == K2),
        attributed_w1=att1, attributed_w2=att2,
        factive_w1=true1 if att1 else None,
        factive_w2=true2 if att2 else None,
        factivity_violated=bool(att2 and not true2),
        truth_w1=w1.truth["os"], truth_w2=w2.truth["os"],
        attributed_value=K1["os"]["value"],
    )

def sweep():
    """Does ANY attribution policy escape?  Only by attributing nothing."""
    out = {}
    for pol in ("justified-unique", "justified-any", "none"):
        r = witness(pol)
        r["attributes_anything"] = bool(r["attributed_w1"] or r["attributed_w2"])
        out[pol] = r
    return out
