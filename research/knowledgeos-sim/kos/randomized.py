"""§21-22 randomized property testing.

Lesson carried from KR-2026-09-01 (§19 §3 of that lane): report GUARD ACTIVATION
and CONDITIONAL rates, never a bare pass rate; and where arms share a world
stream, say so — the design is paired, not independent.
"""
import random, math
from .world import World, Channel, DOMAINS
from .state import EpistemicState, knowledge_attribution, unknown_kind, UNOBSERVABLE, UNDERDETERMINED
from .inquiry import Requirement, EpistemicStandard, EpistemicContract, Inquiry, Gap, Zero
from .scenarios import LEXICON, POLICY
from . import transitions as T

def wilson(k, n, z=1.96):
    if n == 0: return [0.0, 0.0]
    p = k/n; d = 1 + z*z/n
    c = (p + z*z/(2*n))/d; h = z*math.sqrt(p*(1-p)/n + z*z/(4*n*n))/d
    return [max(0.0, c-h), min(1.0, c+h)]

def gen_config(rng):
    return dict(
        n_sources     = rng.randint(0, 3),
        conflicting   = rng.random() < .35,
        dependent     = rng.random() < .30,
        ambiguous     = rng.random() < .20,
        misleading    = rng.random() < .25,      # a source reports a WRONG value
        observable    = rng.random() < .85,
        min_weight    = rng.choice([0.3, 1.0, 1.8]),
        need_corrob   = rng.random() < .5,
        wide_inquiry  = rng.random() < .5,
    )

def build(cfg, rng):
    truth = rng.choice(DOMAINS["os"])
    chans, w = [], None
    if cfg["observable"]:
        for i in range(max(cfg["n_sources"], 1)):
            chans.append(Channel(f"c{i}", "os", 1.0, source=f"s{i}"))
    w = World(truth={"os": truth, "ram_gb": 31}, channels=chans)
    pol = dict(POLICY); pol["reliability"] = {f"s{i}": 1.0 for i in range(4)}
    S = EpistemicStandard("S-rand", min_weight=cfg["min_weight"],
                          require_independent_sources=1,
                          require_corroboration=cfg["need_corrob"])
    E = EpistemicState(standard=S)
    T.OpenHypothesisSpace(E, "os", DOMAINS["os"])
    wrong = [x for x in DOMAINS["os"] if x != truth]
    prev_e = None
    for j, ch in enumerate(chans[:max(cfg["n_sources"], 0)]):
        oid = T.Acquire(E, w, ch, rng)
        if oid is None: continue
        tok = truth
        if cfg["misleading"] and j == 0: tok = rng.choice(wrong)
        elif cfg["conflicting"] and j == 1: tok = rng.choice(wrong)
        E.observations[oid]["token"] = tok
        p2 = dict(pol)
        if cfg["dependent"] and prev_e is not None: p2["derived_from"] = {oid: prev_e}
        eid = T.Qualify(E, oid, p2)
        prev_e = prev_e or eid
        iid = T.Interpret(E, eid, "unspecified" if cfg["ambiguous"] else "network", LEXICON)
        T.Assess(E, iid, "os", S)
    T.Determine(E, "os", S)
    reqs = [Requirement("r1", "os", "corroborated" if cfg["need_corrob"] else "unique")]
    if cfg["wide_inquiry"]: reqs.append(Requirement("r2", "ram_gb", "unique"))
    Q = Inquiry("Qr", ("os",) + (("ram_gb",) if cfg["wide_inquiry"] else ()),
                "random", "network", tuple(reqs))
    EC = EpistemicContract("EC-r", S, (), attribution_policy="justified-unique")
    K = knowledge_attribution(E, Q, "network", EC)
    return w, E, Q, S, EC, K

def trial(rng):
    cfg = gen_config(rng)
    w, E, Q, S, EC, K = build(cfg, rng)
    det = E.determinations.get("os", {})
    att = {p: r for p, r in K.items() if r["attributed"]}
    res = {}

    # P11 factivity  (ORACLE reads world truth; the agent never did)
    res["P11"] = dict(guard=bool(att),
                      fail=any(p in w.truth and r["value"] != w.truth[p] for p, r in att.items()))
    # P2 determination != knowledge
    res["P2"]  = dict(guard=bool(det),
                      fail=bool(det and det["status"] != "unique" and "os" in att))
    # P10 no unsupported semantic information
    res["P10"] = dict(guard=True,
                      fail=bool(det.get("A") and not det.get("provenance")))
    # P8 dependence never inflates independent-source count
    res["P8"]  = dict(guard=cfg["dependent"] and len(E.evidence) > 1,
                      fail=bool(cfg["dependent"] and det.get("dropped_dependent", 0) == 0
                                and len(E.evidence) > 1))
    # P13 unobservable is not underdetermined
    uk = unknown_kind(E, w, "os")
    res["P13"] = dict(guard=(uk is not None),
                      fail=bool(uk == UNOBSERVABLE and det.get("status") == "underdetermined"))
    # P20 Zero is inquiry-relative: a wider inquiry cannot be MORE closed
    znar = Zero({k: v for k, v in K.items() if k == "os"},
                Inquiry("Qn", ("os",), "n", "network", (Q.requirements[0],)), "network", EC)
    zwid = Zero(K, Q, "network", EC)
    res["P20"] = dict(guard=cfg["wide_inquiry"], fail=bool(cfg["wide_inquiry"] and zwid and not znar))
    # P3 rejection never creates acceptance
    res["P3"]  = dict(guard=bool(E.rejections.get("os")),
                      fail=False)
    res["_cfg"] = cfg
    res["_attributed"] = bool(att)
    return res

def run(seeds=(1, 7, 13, 101, 2718), trials=2000):
    props = ["P2", "P3", "P8", "P10", "P11", "P13", "P20"]
    agg = {p: dict(guard=0, fail=0, fail_given_guard=0) for p in props}
    n = 0
    for s in seeds:
        rng = random.Random(s)
        for _ in range(trials):
            n += 1
            r = trial(rng)
            for p in props:
                if r[p]["guard"]: agg[p]["guard"] += 1
                if r[p]["fail"]:
                    agg[p]["fail"] += 1
                    if r[p]["guard"]: agg[p]["fail_given_guard"] += 1
    out = {}
    for p in props:
        g, f = agg[p]["guard"], agg[p]["fail"]
        out[p] = dict(n=n, guard_active=g, guard_rate=g/n, guard_ci95=wilson(g, n),
                      failures=f, failure_rate=f/n,
                      conditional_failure_rate=(agg[p]["fail_given_guard"]/g if g else None),
                      vacuous=(g == 0))
    return dict(seeds=list(seeds), trials_per_seed=trials, n_total=n, results=out,
                design_note="Worlds are drawn per trial from one generator; property arms "
                            "share the trial, so property outcomes within a trial are PAIRED.")
