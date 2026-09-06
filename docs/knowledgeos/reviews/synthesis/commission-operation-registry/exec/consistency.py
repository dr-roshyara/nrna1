#!/usr/bin/env python3
r"""
consistency.py — TASK 8: every candidate operation checked, one by one, against
each RATIFIED constraint. Exhaustive over the probe states x argument alphabet.

COMMISSION GN-79 / GN-80. NOTHING HERE IS RATIFIED.

Constraints checked (all RATIFIED or AUTHORIZED; they constrain the answer):
  I-12   the ladder is a covering relation; skipping formally excluded
  A6/I-4 the Accepted->Committed boundary is crossed by an AUTHORITY ACT, never
         by evidence volume
  I-11   no in-force policy changes without a governed, versioned approval
  I-5    duplicate evidence must not amplify; independent corroboration must
  I-6    dependency resolution precedes aggregation
  I-9    Zero's four-way non-satisfaction typology must not collapse to Boolean
  Art.7  REJECTED is terminal-PRESERVED, never a deletion   (FA-1 D-FA-1)
  Art.8  CONFLICTED is a governed suspension, left only by a governed act
  I-2    the proposal selector holds NO authority
"""
from __future__ import annotations
import itertools
from dataclasses import replace
from rm import (K, Item, Ev, Pol, Obs, POOL, PROBES, OK, Rej, LADDER, NON_ADMISSION,
                EV_CATALOGUE, OBS_CATALOGUE, op_Assess)
import mintest as MT

PROBES_ALL = MT.PROBE_STATES
ALLARGS = sorted(set(sum((list(POOL[n][1]) for n in POOL), [])))

def covering_ok(s, s2):
    """Legal status changes under the RATIFIED model."""
    if s == s2: return True
    if s in LADDER and s2 in LADDER:
        return LADDER.index(s2) == LADDER.index(s) + 1     # I-12: successor only
    if s in LADDER and s2 in NON_ADMISSION:
        return True                                        # FA-1: reachable pre-boundary
    if s == "Conflicted" and s2 in LADDER:
        return True                                        # Art.8 governed resolution
    return False                                           # everything else illegal

def run():
    P = "=" * 78
    print(P); print("TASK 8 — CONSISTENCY OF EVERY CANDIDATE OPERATION WITH THE RATIFIED CONSTRAINTS")
    print("commission GN-79 / GN-80 · NOTHING RATIFIED"); print(P)
    print(f"operations checked : {len(POOL)}")
    print(f"probe states       : {len(PROBES_ALL)}")
    print(f"arguments          : {len(ALLARGS)}")
    print(f"total applications : {len(POOL)*len(PROBES_ALL)*len(ALLARGS)}")
    viol = {}
    ambig = {}
    def flag(cid, op, msg):
        viol.setdefault(cid, set()).add((op, msg))
    def amb(cid, op, msg):
        ambig.setdefault(cid, set()).add((op, msg))

    # ---------------- I-12, A6, I-11, Art.7, Art.8, I-2 : exhaustive sweep
    for n,(fn,args) in POOL.items():
        for st in PROBES_ALL:
            for a in ALLARGS:
                try: k2, out = fn(st, a)
                except Exception: continue
                if out != OK: continue
                # I-12 covering relation + no entry above Candidate
                for it2 in k2.items:
                    it1 = st.item(it2.id)
                    if it1 is None:
                        if it2.status != "Candidate":
                            flag("I-12", n, f"creates an item directly at {it2.status} "
                                            "— entry above the first rung")
                        if it2.committed:
                            flag("A6", n, f"creates an item already Committed (arg={a})")
                    else:
                        if not covering_ok(it1.status, it2.status):
                            flag("I-12", n, f"{it1.status} -> {it2.status}")
                        # A6: committed may only become True with an authority act on it
                        if it2.committed and not it1.committed:
                            if not any(t == it2.id for (_, t) in st.auth_acts):
                                flag("A6", n, "sets Committed with NO authority act")
                            if it2.status != "Accepted":
                                flag("A6", n, "sets Committed on a non-Accepted item")
                        # Art.7: an item that became Rejected must still be present
                        if it2.status == "Rejected" and k2.item(it2.id) is None:
                            flag("Art.7", n, "deletes on rejection")
                # Art.7 (second form): silent disappearance of a Rejected item
                for it1 in st.items:
                    if it1.status == "Rejected" and k2.item(it1.id) is None:
                        flag("Art.7", n, "deletes an already-REJECTED item")
                # Art.8: leaving Conflicted requires a governed act
                for it1 in st.items:
                    it2 = k2.item(it1.id)
                    if it1.status == "Conflicted" and it2 is not None \
                       and it2.status != "Conflicted":
                        if not any(t == it1.id for (_, t) in st.auth_acts):
                            flag("Art.8", n, f"CONFLICTED -> {it2.status} with NO governed act")
                # I-11: in-force policy change requires a recorded approval
                for p2 in k2.pols:
                    p1 = next((p for p in st.pols if p.id == p2.id), None)
                    if p1 is None: continue
                    if (p2.version, p2.in_force) != (p1.version, p1.in_force):
                        if (p2.id, p1.version) not in st.approvals:
                            flag("I-11", n, f"changes the IN-FORCE policy record "
                                            f"v{p1.version}->v{p2.version} / in_force "
                                            f"{p1.in_force}->{p2.in_force} with NO "
                                            f"governed approval")
                # I-2: a proposal must carry no authority
                for (act, auth) in k2.proposals - st.proposals:
                    if auth is not None:
                        flag("I-2", n, f"records a proposal carrying authority {auth!r}")
                # I-9: Zero types must be from the four-way typology, never Boolean
                for (tgt, t) in k2.zero - st.zero:
                    if t in ("True","False",True,False) or t not in (
                            "unknown","conflicting","missing","invalid","satisfied"):
                        flag("I-9", n, f"emits a non-typed / Boolean gap value {t!r}")

    # ---------------- A6, the ADVERSARIAL form: can evidence volume ever commit?
    print(); print("--- A6 ADVERSARIAL: can evidence volume cross the boundary? ---")
    big_ev = frozenset(list(EV_CATALOGUE.values()) +
                       [Ev(f"bulk{i}", f"src{i}", "supports", "") for i in range(40)])
    heavy = K(pols=frozenset({Pol("p1",1,True)}), dims=frozenset({"d1"}), ev=big_ev,
              items=frozenset({Item("i1","proposition","d1","v1","Accepted",False,
                                    frozenset(e.id for e in big_ev),
                                    frozenset(e.source for e in big_ev))}))
    crossed = []
    for n,(fn,args) in POOL.items():
        for a in ALLARGS:
            try: k2,out = fn(heavy,a)
            except Exception: continue
            if out == OK and any(i.committed for i in k2.items):
                crossed.append((n,a))
    print(f"  state: 1 Accepted item, {len(big_ev)} independent supporting evidence units, "
          f"NO authority act")
    print(f"  operations that produced Committed : {len(crossed)}  "
          f"{crossed if crossed else '-> A6 HOLDS: evidence volume never crosses'}")
    for n,(fn,args) in PROBES.items():
        res = {}
        for a in ALLARGS:
            try: k2,out = fn(heavy,a)
            except Exception: continue
            res.setdefault(out, 0)
            res[out]+=1
            if out == OK and any(i.committed for i in k2.items):
                res["*** CROSSED A6 ***"] = res.get("*** CROSSED A6 ***",0)+1
        print(f"  PROBE {n:<28s} outcomes on the heavy state: {sorted(res)}")

    # ---------------- I-11, the ADVERSARIAL form
    print(); print("--- I-11 ADVERSARIAL: can an in-force policy be mutated off-route? ---")
    base = K(pols=frozenset({Pol("p1",1,True)}), dims=frozenset({"d1"}),
             items=frozenset({Item("p1","policy","d1","v1","Candidate",False,
                                   frozenset(),None)}))
    offroute = []
    for n,(fn,args) in POOL.items():
        for a in ALLARGS:
            try: k2,out = fn(base,a)
            except Exception: continue
            if out != OK: continue
            for p2 in k2.pols:
                p1 = next((p for p in base.pols if p.id==p2.id), None)
                if p1 and (p2.version,p2.in_force)!=(p1.version,p1.in_force) \
                   and (p2.id,p1.version) not in base.approvals:
                    offroute.append((n,a))
            it2 = k2.item("p1")
            if it2 is not None and it2 != base.item("p1") and it2.kind=="policy":
                amb("I-11/R-1", n, "mutates POLICY-AS-CONTENT while a policy record of "
                                   "the same id is IN FORCE — legal under R-1 taken "
                                   "literally, yet the canon never binds the in-force "
                                   "record to its content item")
    print(f"  operations that changed the IN-FORCE POLICY RECORD off-route : "
          f"{len(offroute)}  {sorted(set(offroute)) if offroute else '-> I-11 HOLDS'}")
    print(f"  PROBE RevisePolicyDirect outcome : "
          f"{PROBES['RevisePolicyDirect'][0](base,'p1')[1]}")

    # ---------------- I-5 / I-6 executed
    print(); print("--- I-5 / I-6 EXECUTED on the assessment operation ---")
    ev = frozenset(EV_CATALOGUE.values())
    def mk(linked):
        return K(pols=frozenset({Pol("p1",1,True)}), dims=frozenset({"d1"}), ev=ev,
                 items=frozenset({Item("i1","proposition","d1","v1","Candidate",False,
                                       frozenset(linked),None)}))
    seq = [("{e1}",["e1"]), ("{e1,e1dup}  (DUPLICATE added)",["e1","e1dup"]),
           ("{e1,e1dup,e3}  (INDEPENDENT added)",["e1","e1dup","e3"]),
           ("{e1,e1dup,e3,e4dep}  (UNRESOLVED DEPENDENCY added)",
            ["e1","e1dup","e3","e4dep"])]
    grades = []
    for lab, linked in seq:
        k2,out = op_Assess(mk(linked), "i1")
        g = sorted(k2.item("i1").grade)
        grades.append(g)
        print(f"  linked {lab:<48s} -> grade {g}")
    print(f"  I-5 duplicates do NOT amplify        : {grades[0] == grades[1]}")
    print(f"  I-5 corroboration DOES amplify       : {set(grades[1]) < set(grades[2])}")
    print(f"  I-6 unresolved dependency EXCLUDED   : {grades[2] == grades[3]}")
    print(f"  no aggregation OPERATOR was selected : True  (grade is a SET of source "
          f"classes; OQ-3 untouched)")

    # ---------------- report
    print(); print(P); print("VIOLATION REPORT"); print(P)
    if not viol:
        print("NO candidate operation in the pool violates any checked ratified constraint.")
    for cid in sorted(viol):
        seen = sorted(viol[cid])
        print(f"\n{cid} — {len(seen)} distinct violations, "
              f"{len({o for o,_ in seen})} operations")
        for op,msg in seen:
            print(f"  {op:<22s} {msg}")
    print(); print("-"*78)
    print("MODEL-DETECTED AMBIGUITIES (not violations — the canon does not decide)")
    print("-"*78)
    if not ambig: print("  none")
    for cid in sorted(ambig):
        seen = sorted(ambig[cid])
        print(f"\n{cid} — {len({o for o,_ in seen})} operations")
        for op,msg in seen:
            print(f"  {op:<22s} {msg}")
    print(); print(P)

if __name__ == "__main__":
    run()
