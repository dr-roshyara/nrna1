"""§12 TRANSITION TEST — Acquire · Interpret · Assess · Determine · Attribute ·
Revise · Reject · Accept · Supersede.

NOT assumed to be final kernel operators.  Each declares the semantic effect it
introduces, so §23's no-smuggling audit can check that no other function
produces it.
"""
from .state import EpistemicState
from .types import Provenance

# ---------------------------------------------------------------- Acquire
def Acquire(E, world, channel, rng):
    """RealityState × Channel → Observation.  The ONLY channel for world content."""
    raw = world.query(channel, rng)
    if raw is None:
        return None
    oid = E.nid("o")
    E.observations[oid] = dict(id=oid, prop=raw["prop"], token=raw["token"],
                               source=raw["source"], ambiguous=raw["ambiguous"],
                               t=raw["t"], channel=channel.id,
                               prov=Provenance("source", channel.id, (), (), raw["t"]))
    E.record("Acquire", oid, (), ())
    return oid

# ---------------------------------------------------------------- Qualify
def Qualify(E, oid, policy):
    """Observation × Policy ⇀ Evidence.  Admission, not meaning (DEF-7)."""
    o = E.observations[oid]
    if o["source"] in policy.get("excluded_sources", ()):
        return None
    eid = E.nid("e")
    E.evidence[eid] = dict(id=eid, of=oid, prop=o["prop"], token=o["token"],
                           source=o["source"], reliability=policy["reliability"].get(o["source"], .5),
                           derived_from=policy.get("derived_from", {}).get(oid),
                           prov=Provenance("source", f"Qualify({oid})", (oid,),
                                           ("admission policy " + policy["id"],), E.t))
    E.record("Qualify", eid, (oid,), (policy["id"],))
    return eid

# ---------------------------------------------------------------- Interpret
def Interpret(E, eid, context, lexicon):
    """Evidence × Context → Interpretation.  Context-relative and MANY-VALUED:
    an ambiguous token yields several admissible interpretations (§2.8, P5)."""
    ev = E.evidence[eid]
    readings = lexicon.get((ev["token"], context)) or lexicon.get(ev["token"]) or [ev["token"]]
    iid = E.nid("i")
    E.interpretations[iid] = dict(id=iid, of=eid, prop=ev["prop"], context=context,
                                  readings=tuple(readings),
                                  prov=Provenance("inferential", f"Interpret({eid})",
                                                  (eid,), (f"lexicon@{context}",), E.t))
    E.record("Interpret", iid, (eid,), (f"lexicon@{context}",))
    return iid

# ---------------------------------------------------------------- Hypothesis space
def OpenHypothesisSpace(E, prop, candidates):
    """Inquiry → H_Q.  Rejection of one member is NOT acceptance of another."""
    E.hypotheses[prop] = list(candidates)
    E.rejections.setdefault(prop, set())
    E.record("OpenHypothesisSpace", prop, (), ())
    return E.hypotheses[prop]

# ---------------------------------------------------------------- Assess
def Assess(E, iid, prop, standard, model=None):
    """EA(e,h,H_Q,M,S,C) — TARGET-RELATIVE: a weight per hypothesis, never a
    single scalar 'strength of the evidence' (§2.9)."""
    it = E.interpretations[iid]
    ev = E.evidence[it["of"]]
    H = E.hypotheses.get(prop, [])
    per_h = {}
    for h in H:
        supports = h in it["readings"]
        per_h[h] = (ev["reliability"] if supports else 0.0)
    a = dict(id=E.nid("a"), of=iid, evidence=ev["id"], prop=prop, per_h=per_h,
             source=ev["source"], derived_from=ev["derived_from"],
             model=(model or {}).get("id"),
             prov=Provenance("inferential", f"Assess({iid})", (iid,),
                             (f"standard {standard.id}",) + ((f"model {model['id']}",) if model else ()), E.t))
    E.assessments.append(a)
    E.record("Assess", a["id"], (iid,), (standard.id,))
    return a

# ---------------------------------------------------------------- Determine
def Determine(E, prop, standard, dependence_aware=True):
    """Det(E,Q,C,S) = A_t ⊆ H_Q.  SET-VALUED (§2.8).
    |A|=0 no admissible determination · |A|=1 unique · |A|>1 underdetermined."""
    H = [h for h in E.hypotheses.get(prop, []) if h not in E.rejections.get(prop, set())]
    rel = [a for a in E.assessments if a["prop"] == prop]

    # §G / P8 — evidence dependence must not be double counted
    counted, seen_roots = [], set()
    for a in rel:
        root = a["derived_from"] or a["evidence"]
        if dependence_aware and root in seen_roots:
            continue
        seen_roots.add(root); counted.append(a)
    dropped = len(rel) - len(counted)

    weights = {h: sum(a["per_h"].get(h, 0.0) for a in counted) for h in H}
    srcs    = {h: {a["source"] for a in counted if a["per_h"].get(h, 0) > 0} for h in H}
    best = max(weights.values()) if weights else 0.0

    A = [h for h in H
         if weights[h] >= standard.min_weight
         and len(srcs[h]) >= standard.require_independent_sources
         and (not standard.require_corroboration or len(srcs[h]) >= 2)]
    if len(A) > 1:
        A = [h for h in A if weights[h] == max(weights[x] for x in A)]

    status = ("unique" if len(A) == 1 else
              "underdetermined" if len(A) > 1 else
              "cannot-determine")
    det = dict(prop=prop, A=list(A), status=status, weight=best,
               weights=weights, dropped_dependent=dropped,
               independent_sources=max((len(s) for s in srcs.values()), default=0),
               standard_met=(status == "unique" and best >= standard.min_weight),
               causal_status="not-identified",
               provenance=tuple(a["id"] for a in counted),
               assumptions=tuple(sorted({x for a in counted for x in a["prov"].assumptions})))
    E.determinations[prop] = det
    E.record("Determine", prop, tuple(a["id"] for a in counted), det["assumptions"])
    return det

# ---------------------------------------------------------------- Reject / Accept
def Reject(E, prop, h, reason):
    """AX / P3 — Reject(H1) ≠ Accept(H2).  Rejection removes ONE member only."""
    E.rejections.setdefault(prop, set()).add(h)
    E.record("Reject", f"{prop}:{h}", (), (reason,))

def Accept(E, prop, h, reason):
    """Explicit acceptance — never a side effect of rejecting something else."""
    E.determinations.setdefault(prop, dict(prop=prop, A=[], status="cannot-determine",
                                           weight=0.0, weights={}, dropped_dependent=0,
                                           independent_sources=0, standard_met=False,
                                           causal_status="not-identified",
                                           provenance=(), assumptions=()))
    E.record("Accept", f"{prop}:{h}", (), (reason,))

# ---------------------------------------------------------------- Revise / Supersede
def Revise(E, prop, standard):
    """AX-4 non-monotonic.  K_{t+1} ≠ K_t is normal; history is NOT rewritten."""
    prior = E.determinations.get(prop)
    if prior is not None:
        E.history.append(dict(t=E.t, transition="Snapshot(pre-revision)",
                              produced=f"{prop}@{E.t}", inputs=(), assumptions=(),
                              superseded=dict(prior)))
    det = Determine(E, prop, standard)
    E.record("Revise", prop, (), ())
    return det

def Supersede(E, prop, old_claim, new_claim, reason):
    """A superseded claim stays historically traceable (§17)."""
    E.history.append(dict(t=E.t, transition="Supersede", produced=f"{prop}",
                          inputs=(old_claim,), assumptions=(reason,),
                          superseded=old_claim, replacement=new_claim))

# ---------------------------------------------------------------- decision lane
def Propose(E, K, gap, options):
    """§16 — Proposal ≠ Decision (AX-6, THM-3)."""
    return dict(kind="Proposal", options=tuple(options), because=tuple(r.id for r in gap))

def Decide(E, proposal, chooser):
    """Determination does NOT imply a unique decision."""
    return dict(kind="Decision", chosen=chooser(proposal["options"]), from_=proposal)

def Authorize(decision, governance):
    """Decision ≠ Authorization."""
    return dict(kind="Authorization", granted=governance.get(decision["chosen"], False),
                decision=decision)

def Act(authorization, world, effect=None):
    """Authorization ≠ Action."""
    if not authorization["granted"]:
        return dict(kind="Action", executed=False, reason="not authorized")
    if effect: world.advance(effect)
    return dict(kind="Action", executed=True)

TRANSITIONS = ["Acquire","Qualify","Interpret","OpenHypothesisSpace","Assess",
               "Determine","Reject","Accept","Revise","Supersede",
               "Propose","Decide","Authorize","Act"]
