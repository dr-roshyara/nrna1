#!/usr/bin/env python3
r"""
mintest.py — EXECUTES the operation necessity / minimality criterion.

COMMISSION GN-79 / GN-80. NOTHING HERE IS RATIFIED.

THE CRITERION EXECUTED (stated in step-277 §277.30 and step-272A §272A.16,
never executed there):

    o is primitive  <=>  exists r in R_mandatory : r not in Closure(T minus {o})

PROCEDURE
  0. Mechanically compute EFFECT-EQUIVALENCE classes of the candidate pool:
     two operations are equivalent iff they produce the same (state, outcome)
     on every (reachable-probe state, argument) pair. Names that collapse are
     reported, and the test runs over one representative per class.
  1. For each mandatory capability r, search for ALL MINIMAL SUPPORT SETS
     (witnesses) W subset-of pool such that r is reachable using only W.
     Bounded-depth BFS over (state, support) with subset-dominance pruning
     (sound for minimal supports).
  2. o is NECESSARY  <=>  exists r : every witness of r contains o.
  3. A registry S is SUFFICIENT <=> for every r, some witness of r is a subset
     of S. Enumerate ALL MINIMAL sufficient registries exactly.
  4. Subset-removal: for every pair and triple X, test whether pool minus X is
     still sufficient (interaction effects).
"""
from __future__ import annotations
import itertools, sys, time
from dataclasses import replace
from rm import (K, Item, Ev, Pol, Obs, POOL, PROBES, PROV, UNRATIFIED_DEPENDENCE,
                EV_CATALOGUE, OBS_CATALOGUE, Rej, OK, LADDER, FIELDS,
                relevant_ops, influence_closure)

# =============================================================== SEEDS
def S_base():   return K(pols=frozenset({Pol("p1", 1, True)}))
def S_dim():    return replace(S_base(), dims=frozenset({"d1"}))
def _it(id, st, grade=None, ev=frozenset(), committed=False):
    return Item(id, "proposition", "d1", "v1", st, committed, ev, grade)
def S_item():   return replace(S_dim(), items=frozenset({_it("i1", "Candidate")}))
def S_graded(): return replace(S_dim(), items=frozenset({_it("i1", "Candidate",
                                                             grade=frozenset({"s1"}))}))
def S_sup():    return replace(S_dim(), items=frozenset({_it("i1", "Supported")}))
def S_acc():    return replace(S_dim(), items=frozenset({_it("i1", "Accepted")}))
def S_ev():     return replace(S_item(), ev=frozenset(EV_CATALOGUE.values()))
def S_conf():   return replace(S_dim(), items=frozenset({_it("i1", "Conflicted")}))
def S_dec():    return replace(S_base(), decisions=frozenset({("d1", "admissible")}))
def S_obs():    return replace(S_base(), obs=frozenset({OBS_CATALOGUE["o1"]}))
# --- probe-only seeds: exist ONLY to give every GATED operation at least one
# --- state in which its guard is satisfied, so effect-equivalence is not a
# --- probe-coverage artefact. They are never used as capability seeds.
def S_two():    return replace(S_dim(), items=frozenset({_it("i1","Candidate"), _it("i2","Candidate")}))
def S_auth():   return replace(S_acc(), auth_acts=frozenset({("knower","i1"),("knower","p1")}))
def S_appr():   return replace(S_base(), auth_acts=frozenset({("knower","p1")}),
                               approvals=frozenset({("p1",1)}))
def S_confA():  return replace(S_conf(), auth_acts=frozenset({("knower","i1")}))
def S_withd():  return replace(S_dim(), items=frozenset({Item("i1","proposition","d1","v1",
                               "Candidate",False,frozenset(),None,True,1)}))
def S_linked(): return replace(S_dim(), ev=frozenset(EV_CATALOGUE.values()),
                               items=frozenset({_it("i1","Candidate",
                                     grade=None, ev=frozenset({"e1","e1dup","e3","e4dep"}))}))
def S_mixed():  return replace(S_linked(), ev=frozenset(EV_CATALOGUE.values()) |
                               {Ev("r_e1","s1R","refutes","")},
                               items=frozenset({_it("i1","Candidate",
                                     ev=frozenset({"e1","r_e1"}))}))
def S_ready():  return replace(S_ev(), auth_acts=frozenset({("knower","i1")}),
                               validations=frozenset({("K",True)}),
                               zero=frozenset({("EC","unknown")}),
                               proposals=frozenset({("next",None)}),
                               items=frozenset({_it("i1","Candidate",grade=frozenset({"s1"}),
                                                    ev=frozenset({"e1"}))}),
                               hist=(("seed","-"),))
def S_polfor(): return replace(S_base(), pols=frozenset({Pol("p1",1,True)}),
                               auth_acts=frozenset({("knower","p1")}))
def S_zero():   return replace(S_base(), zero=frozenset({("EC","unknown")}))

# ======================================================== MANDATORY CAPABILITIES
# R_A — derived in OPERATION-REGISTRY-DERIVATION.md §2 from the RATIFIED
# surface (v0.2 AUTHORIZED + FA-1..FA-9 RATIFIED). Grade per row.
def g_supported(k): return any(a.status == "Supported" for a in k.items)
def g_accepted(k):  return any(a.status == "Accepted" for a in k.items)
def g_committed(k): return any(a.committed and a.status == "Accepted" for a in k.items)
def g_polv2(k):     return any(p.version >= 2 and p.in_force for p in k.pols) and bool(k.approvals)
def g_compose(k):   return any(a.grade == frozenset({"s1", "s3"}) for a in k.items)
def g_zero(k):      return any(t in ("unknown", "conflicting", "missing", "invalid")
                               for (_, t) in k.zero)
def g_qualify(k):   return bool(k.qualified)
def g_rejected(k):  return any(a.status == "Rejected" for a in k.items)
def g_conflicted(k):return any(a.status == "Conflicted" for a in k.items)
def g_resolved(k):  return any(a.id == "i1" and a.status == "Supported" for a in k.items)
def g_unknown(k):   return "d1" in k.dims and not any(a.dim == "d1" for a in k.items)
def g_proposal(k):  return any(auth is None for (_, auth) in k.proposals)
def g_decision(k):  return bool(k.decisions)
def g_act(k):       return any(o.caused_by for o in k.obs)
def g_replay(k):    return ("H", True) in k.replays

CAPS = [
 # id     seed        goal          depth grade                     forced-by (ratified)
 ("A1",  S_graded,  g_supported,   4, {"item.status"}, "RATIFIED-FORCED",  "ladder + I-12 (Supported reachable only from Candidate)"),
 ("A2",  S_sup,     g_accepted,    3, {"item.status"}, "RATIFIED-FORCED",  "ladder + I-12 + v0.2 Determination concept row"),
 ("A3",  S_acc,     g_committed,   3, {"item.committed"}, "RATIFIED-FORCED",  "A6 / I-4 — authority act crosses the boundary"),
 ("A4",  S_base,    g_polv2,       4, {"pols","approvals"}, "RATIFIED-FORCED",  "I-11 + v0.2 §3 stratification loop"),
 ("A5",  S_ev,      g_compose,     6, {"item.grade"}, "RATIFIED-FORCED",  "I-5 + I-6 (the only TESTED pair)"),
 ("A6",  S_base,    g_zero,        2, {"zero"}, "RATIFIED-FORCED",  "Zero(K,EC) + I-9 four-way typology"),
 ("A7",  S_base,    g_qualify,     3, {"qualified"}, "RATIFIED-FORCED",  "SourceObs != SemanticObs; Evidence concept row"),
 ("A8",  S_item,    g_rejected,    2, {"item.status"}, "RATIFIED-FORCED",  "FA-1 D-FA-1 / Art.7 REJECTED terminal-preserved"),
 ("A9",  S_ev,      g_conflicted,  4, {"item.status"}, "RATIFIED-FORCED",  "FA-1 D-FA-1 / Art.8 CONFLICTED governed suspension"),
 ("A10", S_conf,    g_resolved,    3, {"item.status"}, "RATIFIED-FORCED",  "FA-1 D-FA-1 / Art.8 'until governed resolution'"),
 ("A11", S_base,    g_unknown,     2, {"dims"}, "RATIFIED-FORCED",  "Art.9 / I-9 UNKNOWN != ABSENT (FA-1 evidence layer)"),
 ("A12", S_base,    g_proposal,    3, {"proposals"}, "RATIFIED-FORCED",  "I-2 proposal != decision; v0.2 §3 flow node"),
 ("A13", S_ev,      g_decision,    8, {"decisions"}, "RATIFIED-FORCED-CAPABILITY / PROPOSED-FORMULATION",
                                                          "042 DC 6-tuple + I-3; the SIX-SLOT CONJUNCTION is NOT ratified"),
 ("A14", S_dec,     g_act,         2, {"obs"}, "PROPOSED",         "action loop; far side OPEN BY RULING (OQ-4)"),
 ("A15", S_item,    g_replay,      2, {"replays"}, "PROPOSED",         "State != History — a corpus law, NOT in the ratified surface"),
]
CAP_IDS = [c[0] for c in CAPS]
CAPFIELDS = {c[0]: c[4] for c in CAPS}
RATIFIED_CAPS  = [c[0] for c in CAPS if c[5].startswith("RATIFIED")]
PROPOSED_CAPS  = [c[0] for c in CAPS if c[5] == "PROPOSED"]

# ==================================================== 0. EFFECT-EQUIVALENCE
PROBE_STATES = [S_base(), S_dim(), S_item(), S_graded(), S_sup(), S_acc(),
                S_ev(), S_conf(), S_dec(), S_obs(), S_two(), S_auth(), S_appr(),
                S_confA(), S_withd(), S_linked(), S_mixed(), S_ready(), S_polfor(),
                S_zero(), replace(S_base(), pols=frozenset({Pol("p1",1,False)}))]

def signature(name):
    fn, args = POOL[name]
    sig = []
    allargs = sorted(set(sum((list(POOL[n][1]) for n in POOL), [])))
    for st in PROBE_STATES:
        for a in allargs:
            try:
                k2, out = fn(st, a)
            except Exception as e:
                k2, out = st, "EXC:" + type(e).__name__
            sig.append((a, out, k2 == st, repr(sorted(map(str, k2.items)))[:200],
                        repr(sorted(map(str, k2.rels))), repr(sorted(map(str, k2.ev))),
                        repr(sorted(k2.dims)), repr(sorted(map(str, k2.pols))),
                        repr(sorted(k2.approvals)), repr(sorted(k2.auth_acts)),
                        repr(sorted(k2.zero)), repr(sorted(map(str, k2.proposals))),
                        repr(sorted(map(str, k2.decisions))),
                        repr(sorted(map(str, k2.validations))),
                        repr(sorted(map(str, k2.replays))),
                        repr(sorted(k2.qualified)), repr(sorted(map(str, k2.obs)))))
    return tuple(sig)

def effect_classes():
    sigs = {}
    for n in POOL:
        sigs.setdefault(signature(n), []).append(n)
    return [sorted(v) for v in sigs.values()]

# ============================================== 1. WITNESS SEARCH (the CLOSURE)
def _key(st):
    """State identity for the search. HIST is append-only and read only for
    non-emptiness, so collapsing it to a 0/1 marker is sound and is what makes
    deduplication possible at all."""
    return replace(st, hist=() if not st.hist else (("*", "*"),))

def reachable(ops, seed_fn, goal, maxdepth, budget=300000):
    """Plain bounded BFS using ONLY `ops`. True / False / None(budget)."""
    start = seed_fn()
    if goal(start): return True
    seen = {_key(start)}
    frontier = [start]
    n = 0
    for _ in range(maxdepth):
        nxt = []
        for st in frontier:
            for name in ops:
                fn, args = POOL[name]
                for a in args:
                    n += 1
                    if n > budget: return None
                    try: k2, out = fn(st, a)
                    except Exception: continue
                    if out != OK: continue
                    kk = _key(k2)
                    if kk in seen: continue
                    if goal(k2): return True
                    seen.add(kk); nxt.append(k2)
        frontier = nxt
        if not frontier: break
    return False

def _phaseA(names, seed_fn, goal, maxdepth, budget=1200000):
    """Iterative deepening wrapper: cheap depths first, so that once a witness
    exists the subset-dominance prune does most of the work at greater depths."""
    acc, ex = set(), False
    for d in range(1, maxdepth + 1):
        u, e = _phaseA_at(names, seed_fn, goal, d, budget, acc)
        acc |= u; ex = ex or e
        if e: break
    return acc, ex

def _phaseA_at(names, seed_fn, goal, maxdepth, budget, seedfound):
    """PHASE A - support-tracking BFS with subset-dominance pruning. Returns the
    UNION of the operations appearing in any support that reached the goal. Its
    only purpose is to narrow the exact enumeration in Phase B."""
    found = [frozenset({o}) for o in ()]
    known = [frozenset(w) for w in ()]
    def dominated(T): return any(W <= T for W in found)
    start = seed_fn()
    if goal(start): return set(), False
    seen = {_key(start): [frozenset()]}
    frontier = [(start, frozenset())]
    n = 0
    for _ in range(maxdepth):
        nxt = []
        for st, sup in frontier:
            if dominated(sup): continue
            for name in names:
                nsup = sup | {name}
                if dominated(nsup): continue
                fn, args = POOL[name]
                for a in args:
                    n += 1
                    if n > budget:
                        return (set().union(*found) if found else set()), True
                    try: k2, out = fn(st, a)
                    except Exception: continue
                    if out != OK: continue
                    if goal(k2):
                        found.append(nsup); found = _minimal(found); continue
                    ant = seen.setdefault(_key(k2), [])
                    if any(W <= nsup for W in ant): continue
                    ant[:] = [W for W in ant if not nsup <= W] + [nsup]
                    nxt.append((k2, nsup))
        frontier = nxt
        if not frontier: break
    return (set().union(*found) if found else set()), False

def witnesses(names, seed_fn, goal, maxdepth, goalfields, maxsize=4):
    """ALL MINIMAL support sets, in two phases.

    PHASE A narrows the pool to the operations that actually appear on some
    successful path (support-tracking BFS, subset-dominance pruned).
    PHASE B enumerates EXACTLY, in increasing size, every minimal subset of that
    narrowed pool that reaches the goal, with two sound prunes:
      P1  a witness must contain an operation that WRITES a goal field, because
          the last step of any successful path does;
      P2  supersets of an already-found witness are skipped.

    DIRECTION OF THE BOUND: anything missed by Phase A's budget, Phase B's size
    cap, or a per-subset BFS budget can only UNDERSTATE multiplicity and
    OVERSTATE necessity - never the reverse."""
    goalwriters = {n for n in names if FIELDS[n][1] & goalfields}
    if not goalwriters: return [], False
    if goal(seed_fn()):  return [frozenset()], False
    rel, _C = relevant_ops(goalfields, names)
    relA, exhaustedA = _phaseA(sorted(rel), seed_fn, goal, maxdepth)
    pool = sorted((relA | goalwriters) & set(rel))
    found, exhausted = [], exhaustedA
    for k in range(1, min(maxsize, len(pool)) + 1):
        for S in itertools.combinations(pool, k):
            Ss = set(S)
            if not (Ss & goalwriters): continue
            if any(W <= Ss for W in found): continue
            r = reachable(S, seed_fn, goal, maxdepth)
            if r is None: exhausted = True
            elif r: found.append(frozenset(S))
    return _minimal(found), exhausted

def _minimal(sets):
    out = []
    for s in sorted(sets, key=len):
        if not any(t <= s for t in out):
            out.append(s)
    return out

# ================================== 3. MINIMAL SUFFICIENT REGISTRY ENUMERATION
def minimal_registries(fam):
    """fam: dict cap -> list of witness sets. Returns all MINIMAL S such that
    for every cap, some witness is a subset of S. Exact."""
    cur = [frozenset()]
    for cap in sorted(fam):
        ws = fam[cap]
        if not ws:
            return None            # capability unreachable => NO sufficient registry
        new = []
        for C in cur:
            for W in ws:
                new.append(C | W)
        cur = _minimal(new)
    return cur

def sufficient(fam, S):
    return all(any(W <= S for W in fam[c]) for c in fam)

# ============================================================== MAIN
def main():
    t0 = time.time()
    P = "=" * 78
    print(P); print("EXECUTED OPERATION-NECESSITY / MINIMALITY TEST")
    print("commission GN-79 / GN-80 · ARCHITECTURE-THEORY lane · NOTHING RATIFIED")
    print(P)
    print(f"candidate pool size (names)          : {len(POOL)}")
    print(f"mandatory capabilities (R_A)         : {len(CAPS)}"
          f"  [{len(RATIFIED_CAPS)} ratified-forced, {len(PROPOSED_CAPS)} proposed]")
    print(f"adversarial probes (not pool members): {len(PROBES)}")

    # ---- 0 effect equivalence
    print(); print(P); print("STEP 0 — MECHANICAL EFFECT-EQUIVALENCE OF THE CANDIDATE POOL")
    print("Two names are equivalent iff identical (state,outcome) on every probe.")
    print(P)
    cls = sorted(effect_classes(), key=lambda c: (-len(c), c[0]))
    collapsed = [c for c in cls if len(c) > 1]
    print(f"distinct effect classes              : {len(cls)}")
    print(f"classes containing >1 name           : {len(collapsed)}")
    for c in collapsed:
        print("  COLLAPSED: " + " == ".join(c))
    def is_inert(n):
        """INERT = a genuine READ: whenever it SUCCEEDS it leaves K unchanged.
        An operation that merely REJECTS on every probe is GATED, not inert, and
        must stay in the search pool."""
        fn, args = POOL[n]
        ok_seen = False
        for st in PROBE_STATES:
            for a in args:
                try: k2, out = fn(st, a)
                except Exception: continue
                if out == OK:
                    ok_seen = True
                    if not (k2.items == st.items and k2.rels == st.rels and
                            k2.ev == st.ev and k2.dims == st.dims and k2.pols == st.pols and
                            k2.approvals == st.approvals and k2.auth_acts == st.auth_acts and
                            k2.zero == st.zero and k2.proposals == st.proposals and
                            k2.decisions == st.decisions and k2.validations == st.validations and
                            k2.replays == st.replays and k2.qualified == st.qualified and
                            k2.obs == st.obs):
                        return False
        return ok_seen
    inert = [c[0] for c in cls if is_inert(c[0])]
    reps = [c[0] for c in cls]
    print(f"representatives used for the test    : {len(reps)}")
    print(f"NO-OP-ON-K names (never change state on any probe): {len(inert)}")
    print("  " + ", ".join(sorted(inert)))

    # A no-op-on-K operation can never appear in any witness of a reachability
    # goal, so it is excluded from the search pool and reported separately.
    search = [n for n in reps if n not in inert]
    print(f"search pool (state-changing reps)    : {len(search)}")
    print("  " + ", ".join(sorted(search)))

    # ---- 1 witnesses
    print(); print(P); print("STEP 1 — WITNESS SEARCH  (Closure computed per capability)"); print(P)
    fam = {}
    budget_hit = []
    for cid, seed_fn, goal, depth, gfields, grade, forced in CAPS:
        _t = time.time()
        ws, hit = witnesses(search, seed_fn, goal, depth, gfields)
        print(f"[{cid} searched in {time.time()-_t:.1f}s]", flush=True)
        fam[cid] = ws
        if hit: budget_hit.append(cid)
        print(f"\n{cid}  depth<={depth}  grade={grade}")
        print(f"     forced by: {forced}")
        if not ws:
            print("     *** UNREACHABLE with the whole pool — capability NOT EXPRESSIBLE ***")
        else:
            print(f"     minimal witnesses: {len(ws)}")
            for W in sorted(ws, key=lambda s: (len(s), sorted(s))):
                print("       {" + ", ".join(sorted(W)) + "}")
        realizers = sorted(set().union(*ws)) if ws else []
        print(f"     ops appearing in some witness ({len(realizers)}): {', '.join(realizers)}")
    print(f"\nwitness-size cap: 4  (all minimal witnesses of size <= 4 enumerated EXACTLY)")
    if budget_hit:
        print(f"!! per-subset BFS budget reached somewhere for: {budget_hit}")
    print("DIRECTION OF THE BOUND: any witness missed by the size cap or a budget can only")
    print("UNDERSTATE multiplicity and OVERSTATE necessity - never the reverse.")

    # ---- 1b CONSTRUCTED WITNESSES for capabilities with no witness of size <= 4
    print(); print(P); print("STEP 1b — CONSTRUCTED WITNESSES (for capabilities where the")
    print("size-4 enumeration returned nothing: reachability shown by an explicit program)")
    print(P)
    PROGRAMS = {
      "A13": (S_ev, [("LinkEvidence","i1:e1"), ("Assess","i1"), ("Validate","-"),
                     ("Authorize","i1"), ("ComputeZero","-"), ("Propose","next"),
                     ("Decide","-")]),
    }
    for cid, (seed_fn, prog) in PROGRAMS.items():
        goal = [c[2] for c in CAPS if c[0] == cid][0]
        k = seed_fn(); trace = []
        for name, arg in prog:
            k, out = POOL[name][0](k, arg)
            trace.append(f"{name}({arg}) -> {out}")
        ok = goal(k)
        sup = sorted({n for n, _ in prog})
        print(f"\n{cid}: program of {len(prog)} steps, support size {len(sup)}")
        for t in trace: print("     " + t)
        print(f"     GOAL REACHED: {ok}")
        print(f"     support: {{{', '.join(sup)}}}")
        if ok and not fam[cid]:
            fam[cid] = [frozenset(sup)]
            print(f"     -> recorded as a CONSTRUCTED (not proven-minimal) witness; the")
            print(f"        executed enumeration showed NO witness of size <= 4, so any")
            print(f"        minimal witness for {cid} has size >= 5.")

    # ---- 2 per-operation necessity
    def run_necessity(caps, label):
        sub = {c: fam[c] for c in caps}
        print(); print(P); print(f"STEP 2 — PER-OPERATION NECESSITY over {label}")
        print("  o NECESSARY  <=>  exists r : EVERY witness of r contains o")
        print(P)
        nec = {}
        for o in sorted(search):
            lost = [c for c in sub if sub[c] and all(o in W for W in sub[c])]
            if lost: nec[o] = lost
        print(f"NECESSARY operations: {len(nec)}")
        for o, lost in sorted(nec.items()):
            print(f"  {o:<22s} loses {', '.join(sorted(lost))}")
        notnec = [o for o in sorted(search) if o not in nec]
        print(f"NOT necessary (removal costs no capability): {len(notnec)}")
        print("  " + ", ".join(notnec))
        return nec, sub

    nec_all, fam_all = run_necessity(CAP_IDS, "R_A (all 15 capabilities)")
    nec_rat, fam_rat = run_necessity(RATIFIED_CAPS, "R_A_ratified (13 ratified-forced only)")
    STRICT = [c for c in RATIFIED_CAPS if c != "A13"]
    nec_str, fam_str = run_necessity(
        STRICT, "R_A_strict (12: ratified-forced AND ratified-formulated; A13 dropped "
                "because its six-slot conjunction is PROPOSED, not ratified)")

    # UNIQUE-REALIZER CROSS-CHECK: an independent, non-search argument for necessity.
    print(); print(P)
    print("STEP 2b — UNIQUE-WRITER CROSS-CHECK (independent of the witness search)")
    print("  If exactly ONE operation in the pool writes a field the goal requires, its")
    print("  necessity does not depend on the search's completeness at all.")
    print(P)
    from collections import defaultdict
    writers = defaultdict(list)
    for n in search:
        for f in FIELDS[n][1]:
            writers[f].append(n)
    sole = {f: v[0] for f, v in writers.items() if len(v) == 1}
    print(f"fields with exactly ONE writer in the search pool: {len(sole)}")
    for f in sorted(sole):
        print(f"  {f:<22s} <- only {sole[f]}")
    multi = {f: sorted(v) for f, v in writers.items() if len(v) > 1}
    print(f"\nfields with MORE THAN ONE writer (where necessity DOES depend on the search):")
    for f in sorted(multi):
        print(f"  {f:<22s} <- {', '.join(multi[f])}")

    # ---- 3 minimal registries
    for label, sub in (("R_A (all 15)", fam_all),
                       ("R_A_ratified (13)", fam_rat),
                       ("R_A_strict (12)", fam_str)):
        print(); print(P); print(f"STEP 3 — ALL MINIMAL SUFFICIENT REGISTRIES over {label}"); print(P)
        regs = minimal_registries(sub)
        if regs is None:
            print("NO SUFFICIENT REGISTRY EXISTS — at least one capability is unreachable.")
            continue
        print(f"number of MINIMAL sufficient registries : {len(regs)}")
        sizes = sorted(set(len(r) for r in regs))
        print(f"sizes                                   : {sizes}")
        inter = set(regs[0]).intersection(*[set(r) for r in regs])
        union = set().union(*regs)
        print(f"INTERSECTION (in EVERY minimal registry, size {len(inter)}):")
        print("  " + ", ".join(sorted(inter)))
        print(f"UNION (appears in at least one, size {len(union)}):")
        print("  " + ", ".join(sorted(union)))
        print(f"UNDETERMINED BAND (union minus intersection, size {len(union - inter)}):")
        print("  " + ", ".join(sorted(union - inter)))
        print(f"ALL {len(regs)} MINIMAL REGISTRIES — ENUMERATED, NONE SELECTED:")
        for j, r in enumerate(sorted(regs, key=lambda x: (len(x), sorted(x))), 1):
            extra = sorted(set(r) - inter)
            print(f"  R{j} (|R|={len(r)})  = CORE + {{{', '.join(extra)}}}")
        print("  where CORE = {" + ", ".join(sorted(inter)) + "}")
        # cross-check: intersection == necessary set?
        nec = (nec_all if "all 15" in label else
               (nec_str if "strict" in label else nec_rat))
        print(f"CROSS-CHECK  intersection == necessary-set ? "
              f"{sorted(inter) == sorted(nec)}")

    # ---- 4 subset removal (interaction)
    print(); print(P); print("STEP 4 — SUBSET REMOVAL (interaction effects), over R_A_strict (12)")
    print("  R_A_strict is used because every one of its capabilities is BOTH")
    print("  ratified-forced AND ratified-formulated.")
    print(P)
    fam_rat = fam_str; nec_rat = nec_str
    full = set(search)
    pairs_lost, triples_lost = [], []
    for X in itertools.combinations(sorted(search), 2):
        if not sufficient(fam_rat, full - set(X)):
            lost = [c for c in fam_rat if not any(W <= (full - set(X)) for W in fam_rat[c])]
            pairs_lost.append((X, lost))
    print(f"pairs tested: {len(list(itertools.combinations(sorted(search),2)))}   "
          f"pairs whose joint removal costs a capability: {len(pairs_lost)}")
    singles = set(nec_rat)
    pure_interaction = [(X, l) for (X, l) in pairs_lost
                        if X[0] not in singles and X[1] not in singles]
    print(f"of those, PURE INTERACTION pairs (neither member individually necessary): "
          f"{len(pure_interaction)}")
    for X, l in pure_interaction:
        print(f"  {{{X[0]}, {X[1]}}}  ->  loses {', '.join(sorted(l))}")
    nonnec = [o for o in sorted(search) if o not in singles]
    tri = list(itertools.combinations(nonnec, 3))
    for X in tri:
        if not sufficient(fam_rat, full - set(X)):
            lost = [c for c in fam_rat if not any(W <= (full - set(X)) for W in fam_rat[c])]
            triples_lost.append((X, lost))
    pure_tri = [(X, l) for (X, l) in triples_lost
                if not any(set(p) <= set(X) for (p, _) in pure_interaction)]
    print(f"triples over non-necessary ops tested: {len(tri)}   "
          f"lossy: {len(triples_lost)}   NEW (not explained by a lossy pair): {len(pure_tri)}")
    for X, l in pure_tri[:20]:
        print(f"  {{{', '.join(X)}}}  ->  loses {', '.join(sorted(l))}")

    print(); print(P); print(f"DONE in {time.time()-t0:.1f}s"); print(P)

if __name__ == "__main__":
    main()
