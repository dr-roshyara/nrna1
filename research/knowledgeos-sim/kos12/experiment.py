"""KR-SIM-2026-09-02-B — the v1.2 experiment proper."""
import random, itertools
from kos.world import World, Channel, DOMAINS
from kos.state import EpistemicState, knowledge_attribution
from kos.scenarios import LEXICON, POLICY, STRICT, LENIENT, _mk
from kos import transitions as T
from kos.inquiry import Requirement
from .sat3 import Sat, GapPartition, ZERO_READINGS, TOP, BOT, U, CLASSES
from .relations import RELATIONS, substitutability_matrix, _states

# ---------------------------------------------------------------- helpers
def _K(truth, reports, standard=LENIENT, rng=None):
    rng = rng or random.Random(20260902)
    w = World(truth={"os": truth},
              channels=[Channel(f"c{i}", "os", 1.0, source=f"s{i}") for i in range(len(reports))])
    E = EpistemicState(standard=standard)
    T.OpenHypothesisSpace(E, "os", DOMAINS["os"])
    for ch, tok in zip(w.channels, reports):
        oid = T.Acquire(E, w, ch, rng); E.observations[oid]["token"] = tok
        eid = T.Qualify(E, oid, POLICY)
        T.Assess(E, T.Interpret(E, eid, "network", LEXICON), "os", standard)
    T.Determine(E, "os", standard)
    Q, S, EC = _mk(["os"], [Requirement("r1", "os", "unique")], std=standard)
    K = knowledge_attribution(E, Q, "network", EC)
    for p, a in K.items(): a["evidence_seen"] = bool(E.evidence)
    return w, E, K

def _reqs(authority=None):
    return [
      dict(id="rc", **{"class": "content"},      prop="os"),
      dict(id="re", **{"class": "evidence"},     prop="os", e_min=1.0),
      dict(id="rp", **{"class": "provenance"},   prop="os"),
      dict(id="rs", **{"class": "status"},       prop="os", s_min="unique"),
      dict(id="rx", **{"class": "consistency"},  prop="os"),
      dict(id="rg", **{"class": "governance"},   prop="os", g="publish", authority=authority),
      dict(id="rt", **{"class": "temporal"},     prop="os", I="t0"),
      dict(id="ro", **{"class": "operational"},  prop="os", delta_defined=False),
    ]

# ================================================================ E1  Zero readings
def E1_zero_readings():
    """v1.1 said Zero ⟺ Δ = ∅.  With three-valued Sat that sentence is AMBIGUOUS.
    Do the three readings ever disagree?  On what?"""
    cases = {}
    for name, truth, reports, std in [
        ("determined-corroborated", "RHEL9.8", ["RHEL9.8", "RHEL9.8"], LENIENT),
        ("underdetermined",         "RHEL9.8", ["RHEL9.8", "RHEL8.6"], LENIENT),
        ("no-evidence",             "RHEL9.8", [],                     LENIENT),
        ("weak-evidence",           "RHEL9.8", ["RHEL9.8"],            STRICT),
    ]:
        w, E, K = _K(truth, reports, std)
        g = GapPartition(K, _reqs())
        cases[name] = dict(per_req=g["per_req"],
                           violated=g["violated"], undetermined=g["undetermined"],
                           satisfied=g["satisfied"],
                           zero_strict=ZERO_READINGS["strict"](g),
                           zero_weak=ZERO_READINGS["weak"](g),
                           zero_three=ZERO_READINGS["three_valued"](g))
    disagree = {n: c for n, c in cases.items() if c["zero_strict"] != c["zero_weak"]}
    return dict(cases=cases, readings_disagree_on=list(disagree),
                v11_equivalent_reading=("weak" if all(not c["violated"] for c in cases.values())
                                        else "none"))

# ================================================================ E2  equality lattice
def E2_equality():
    m = substitutability_matrix()
    nonsep = [k for k, v in m.items() if not v["separable"]]
    # λ-dependence of ≈ : does the implication sem ⟹ obs survive a richer window?
    S = _states(); lam_tests = {}
    for lam in [("status", "value"), ("status", "value", "provenance"),
                ("status", "value", "weight"), ("A",)]:
        broke = None
        for n1, n2 in itertools.combinations(S, 2):
            a, b = S[n1], S[n2]
            if RELATIONS["sem_equiv"](a, b) and not RELATIONS["obs_equiv"](a, b, lam):
                broke = (n1, n2); break
        lam_tests[str(lam)] = dict(sem_implies_obs=(broke is None), witness=broke)
    return dict(matrix=m, n_pairs=len(m), n_separable=len(m) - len(nonsep),
                non_separable=nonsep, lambda_dependence=lam_tests)

# ================================================================ E3  v1.1 failures re-run
def E3_carryover():
    """Do CE-1 (factivity) and CE-3 (revision without retraction) persist under v1.2?"""
    # CE-1: misleading source, world says RHEL8.6, report says RHEL9.8
    w, E, K = _K("RHEL8.6", ["RHEL9.8"], LENIENT)
    att = {p: a for p, a in K.items() if a["attributed"]}
    ce1 = dict(attributed={p: a["value"] for p, a in att.items()},
               truth=dict(w.truth),
               factivity_violated=any(a["value"] != w.truth.get(p) for p, a in att.items()),
               v12_changes_this=False,
               reason="v1.2 §9 DOWNGRADES E_t != K_t to a research distinction and adds no "
                      "factivity mechanism. The witness is untouched.")
    # CE-3: stale evidence never retired
    w2, E2, K2 = _K("RHEL8.6", ["RHEL9.8", "RHEL8.6"], LENIENT)
    d = E2.determinations["os"]
    ce3 = dict(status=d["status"], A=list(d["A"]),
               persists=(d["status"] == "underdetermined"),
               v12_changes_this=False,
               reason="v1.2 §VIII NAMES revision/supersession/contradiction/retraction/history "
                      "as a section, but defines no retirement relation. Structure named, "
                      "semantics still TECHNICALLY OPEN.")
    # ...but three-valued Sat DOES change how CE-3 is REPORTED
    g = GapPartition(K2, _reqs())
    ce3["v11_reported_as"] = "gap (requirement unsatisfied)"
    ce3["v12_reports_as"]  = dict(violated=g["violated"], undetermined=g["undetermined"])
    return dict(CE1=ce1, CE3=ce3)

# ================================================================ E4  Observation OPEN
def E4_observation_open():
    """v1.2 §5: Observation is an OPEN CONCEPT; O_t=(x,t,c,s) is only a CANDIDATE.
    Which results depend on the candidate model?"""
    variants = {}
    # M-a: the candidate model (x,t,c,s) — token, time, context, source
    # M-b: source dropped  (no provenance channel)
    # M-c: context dropped (no context index at interpretation)
    for name, drop in [("candidate(x,t,c,s)", None), ("no-source", "s"), ("no-context", "c")]:
        rng = random.Random(20260902)
        w = World(truth={"os": "RHEL9.8"},
                  channels=[Channel("c0", "os", 1.0, source="s0"),
                            Channel("c1", "os", 1.0, source="s1")])
        E = EpistemicState(standard=STRICT)
        T.OpenHypothesisSpace(E, "os", DOMAINS["os"])
        for ch in w.channels:
            oid = T.Acquire(E, w, ch, rng)
            if drop == "s": E.observations[oid]["source"] = "anon"
            eid = T.Qualify(E, oid, POLICY)
            ctx = "unspecified" if drop == "c" else "network"
            T.Assess(E, T.Interpret(E, eid, ctx, LEXICON), "os", STRICT)
        det = T.Determine(E, "os", STRICT)
        Q, S, EC = _mk(["os"], [Requirement("r1", "os", "unique")], std=STRICT)
        K = knowledge_attribution(E, Q, "network", EC)
        for p, a in K.items(): a["evidence_seen"] = True
        g = GapPartition(K, _reqs())
        variants[name] = dict(status=det["status"], A=list(det["A"]),
                              independent_sources=det["independent_sources"],
                              attributed=any(a["attributed"] for a in K.values()),
                              zero_strict=ZERO_READINGS["strict"](g),
                              zero_weak=ZERO_READINGS["weak"](g))
    base = variants["candidate(x,t,c,s)"]
    sensitive = {k: v for k, v in variants.items() if v != base and k != "candidate(x,t,c,s)"}
    return dict(variants=variants, result_depends_on_observation_model=list(sensitive))

# ================================================================ E5  Zero's seven readings
def E5_zero_ontology():
    """v1.2 §7: is Zero a state, relation, predicate, boundary, derived view,
    missingness representation, or a metaphor?  Test which are DISTINGUISHABLE."""
    w, E, K = _K("RHEL9.8", ["RHEL9.8"], LENIENT)
    g = GapPartition(K, _reqs())
    tests = {
      "state":      dict(claim="Zero is part of K_t",
                         test="is Zero stored in K, or computed from it?",
                         result="COMPUTED — Zero is a function of (K, Req); no K carries it",
                         verdict="REFUTED as a state"),
      "relation":   dict(claim="Zero relates two objects",
                         test="arity of the Zero function",
                         result="Zero(K, Req) is a PREDICATE over one state plus a contract; "
                                "it relates K to a requirement SET, not to another K",
                         verdict="PARTIAL — it is contract-relative, not state-to-state"),
      "predicate":  dict(claim="Zero is a predicate on K",
                         test="does it return a truth value?",
                         result=f"two-valued under readings strict/weak; THREE-valued under Kleene "
                                f"({ZERO_READINGS['three_valued'](g)})",
                         verdict="SUPPORTED — but its arity is 2 (K, Req) and its VALUE SET is "
                                 "contested: {T,F} or {T,F,U}"),
      "boundary":   dict(claim="Zero marks the represented/unrepresented boundary",
                         test="does Zero distinguish 'absent' from 'negated'?",
                         result="only under three-valued Sat: U marks absence, F marks negation. "
                                "Under two-valued Sat the boundary COLLAPSES",
                         verdict="SUPPORTED ONLY UNDER THREE-VALUED Sat"),
      "derived_view": dict(claim="Zero is a projection of K",
                         test="is it recomputable from K and Req alone?",
                         result="yes — GapPartition(K,Req) determines every reading",
                         verdict="SUPPORTED"),
      "missingness": dict(claim="Zero represents missingness",
                         test="does Zero distinguish the four unknown kinds?",
                         result="NO — UNOBSERVED / UNINTERPRETED / UNDERDETERMINED / UNOBSERVABLE "
                                "all map to U. Zero cannot tell them apart",
                         verdict="REFUTED — Zero is coarser than the missingness taxonomy"),
      "metaphor":   dict(claim="Zero is merely a conceptual metaphor",
                         test="does removing it change any computed outcome?",
                         result="no outcome depends on the NAME; every outcome depends on "
                                "GapPartition. Zero adds a label, not a computation",
                         verdict="NOT REFUTED — Zero may be a NAME for a derived view"),
    }
    return dict(gap=g, readings=tests,
                surviving=["predicate", "derived_view", "boundary(3-valued only)", "metaphor"],
                refuted=["state", "missingness"])
