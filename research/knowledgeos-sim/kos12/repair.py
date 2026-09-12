"""THE FACTIVITY REPAIR EXPERIMENT  (KR-SIM-2026-09-02-C)

Specified by the v1.2 review: three repairs for the obstruction

    factivity  vs.  Γ-determinacy

    R1  rename K_t          — distinguish the epistemic object from the world object
    R2  externalize factivity — truth is not computable from the epistemic state
    R3  partial Γ           — do not force evaluation where information is insufficient

Run over the SAME scenarios and the SAME worlds as the v1.1 run.
No new randomized experiment: the existing 10 000 worlds are re-used, paired across arms.
"""
import random
from kos.world import World, Channel, DOMAINS
from kos.state import EpistemicState
from kos.scenarios import LEXICON, POLICY, LENIENT, STRICT, _mk
from kos.inquiry import Requirement
from kos import transitions as T
from kos.randomized import gen_config, build

# --------------------------------------------------------------- verification channel
def verifiable(prop, cfg):
    """R3 needs an EXTERNAL verification channel. Not every property has one:
    a property is verifiable only when the situation affords direct confirmation."""
    return cfg.get("verifiable", False)

# --------------------------------------------------------------- the three Γ arms
def gamma_baseline(E, Q, EC, world=None, cfg=None):
    """v1.1/v1.2 as written: total, deterministic, CLAIMS FACTIVE KNOWLEDGE."""
    out = {}
    for p, d in E.determinations.items():
        if p not in Q.target: continue
        att = d["status"] == "unique" and d["standard_met"]
        out[p] = dict(status=d["status"], value=d["A"][0] if att and len(d["A"]) == 1 else None,
                      attributed=att, label="Knowledge", factivity_claimed=True)
    return out

def gamma_R1(E, Q, EC, world=None, cfg=None):
    """R1 — RENAME.  Identical behaviour; the object is no longer called Knowledge.
    DEF-1 factivity is declared to govern a DIFFERENT predicate the kernel never computes."""
    out = gamma_baseline(E, Q, EC)
    for p, r in out.items():
        r["label"] = "AttributedState"      # A_t, not K_t
        r["factivity_claimed"] = False
    return out

def gamma_R2(E, Q, EC, world=None, cfg=None):
    """R2 — EXTERNALIZE.  The kernel emits a CLAIM-TO-KNOWLEDGE; factivity is a success
    condition an external verifier applies afterwards.  No component asserts Knows."""
    out = gamma_baseline(E, Q, EC)
    for p, r in out.items():
        r["label"] = "ClaimToKnowledge"
        r["factivity_claimed"] = False
        r["externally_verified"] = None      # filled in by the verifier, never by the kernel
    return out

def gamma_R3(E, Q, EC, world=None, cfg=None):
    """R3 — PARTIAL Γ.  Attribute ONLY where an external verification channel exists.
    Where it does not, Γ is undefined — not false, not unknown-as-false."""
    out = gamma_baseline(E, Q, EC)
    for p, r in out.items():
        if not verifiable(p, cfg or {}):
            r["attributed"] = False; r["value"] = None
            r["label"] = "Undefined(Γ partial)"
        else:
            r["label"] = "Knowledge"
        r["factivity_claimed"] = (r["label"] == "Knowledge")
    return out

ARMS = dict(baseline=gamma_baseline, R1=gamma_R1, R2=gamma_R2, R3=gamma_R3)

# --------------------------------------------------------------- external verifier
def verify(K, world):
    """Reads world truth.  This is the ONLY component permitted to do so, and in R2 it
    is explicitly outside the kernel."""
    v = {}
    for p, r in K.items():
        if not r["attributed"]: continue
        v[p] = (p in world.truth and r["value"] == world.truth[p])
    return v

# --------------------------------------------------------------- the run
def run_arm(arm, seeds=(1, 7, 13, 101, 2718), trials=2000):
    g = ARMS[arm]
    n = attributed = false_attr = knowledge_claims = false_knowledge = 0
    decision_possible = 0
    for s in seeds:
        rng = random.Random(s)
        for _ in range(trials):
            n += 1
            cfg = gen_config(rng)
            cfg["verifiable"] = rng.random() < 0.35        # same stream for every arm
            w, E, Q, S, EC, _ = build(cfg, rng)
            K = g(E, Q, EC, w, cfg)
            att = {p: r for p, r in K.items() if r["attributed"]}
            attributed += len(att)
            ver = verify(K, w)
            false_here = sum(1 for p, ok in ver.items() if not ok)
            false_attr += false_here
            kc = {p: r for p, r in att.items() if r["factivity_claimed"]}
            knowledge_claims += len(kc)
            false_knowledge += sum(1 for p in kc if p in ver and not ver[p])
            # decision lane: can a proposal be formed from what the arm produces?
            if att: decision_possible += 1
    return dict(arm=arm, n=n,
                attributions=attributed, false_attributions=false_attr,
                knowledge_claims=knowledge_claims,
                false_knowledge_claims=false_knowledge,
                factivity_violation_rate=(false_knowledge/knowledge_claims if knowledge_claims else None),
                attribution_coverage=attributed/n,
                decision_lane_functional_rate=decision_possible/n)

def run_all():
    return {a: run_arm(a) for a in ARMS}

# --------------------------------------------------------------- R3' the corrected form
def gamma_R3prime(E, Q, EC, world=None, cfg=None):
    """R3′ — PARTIAL Γ, CONSULTING the channel.

    R3 as specified attributes where a verification channel EXISTS.  That is not enough:
    existence of a channel is not consultation of it.  R3′ attributes only where the
    channel was consulted AND its result is used as the attributed value.

    This is the only arm in which Γ is permitted to read an external verification result —
    and note that this makes Γ no longer a function of E alone, which is precisely the
    point of the impossibility.
    """
    out = gamma_baseline(E, Q, EC)
    for p, r in out.items():
        if not (cfg or {}).get("verifiable", False) or world is None:
            r["attributed"] = False; r["value"] = None
            r["label"] = "Undefined(Γ partial)"; r["factivity_claimed"] = False
        else:
            r["value"] = world.truth.get(p)          # the channel is CONSULTED
            r["attributed"] = r["value"] is not None
            r["label"] = "Knowledge" if r["attributed"] else "Undefined(Γ partial)"
            r["factivity_claimed"] = r["attributed"]
    return out

ARMS["R3prime"] = gamma_R3prime
