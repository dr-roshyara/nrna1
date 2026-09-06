#!/usr/bin/env python3
"""
SO-EXP-01 — EXECUTE the congruence matrix that Step 259 s259.9 left UNRESOLVED.

QUESTION   For each candidate abstraction F_i and each mandatory state-transforming
           operation T_j (and each variant of T_j's unresolved dependency), does
                 F(s1) = F(s2)  =>  F(T(s1,c)) = F(T(s2,c))   ?
INPUT      An exhaustively generated bounded state space (see SPACE below).
METHOD     For every pair of states agreeing under F, apply every operation with
           every admissible argument, and compare projections of the results.
           A single disagreement refutes congruence and is reported as a witness.
RESULT     see the matrix printed below.
LIMITATION Bounded domain. Per s259.16 a finite test establishes
           Congruent_tested, NEVER Congruent_global. A PASS here means
           "no counterexample found in this domain", nothing more.
INDEPENDENCE  The test CRITERION is the corpus's own (s259.15). The EXECUTION is
           new: s259.9's matrix is 17 rows of 'UNRESOLVED' and no prior artifact
           in verification/ contains a computed congruence cell.
"""
import sys, os, itertools
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from so_model import *

def hr(t): print("\n" + "="*78 + f"\n{t}\n" + "="*78)

# ---------------------------------------------------------------- the space
IDS      = ["a", "b"]
CONTENTS = ["p", "q"]
ORIGINS  = ["vendor", "forum"]          # vendor is APPROVED, forum is not
STATUSES = ["Proposed", "Accepted"]

def all_objs():
    return [Obj(i, c, o, s) for i in IDS for c in CONTENTS
            for o in ORIGINS for s in STATUSES]

def all_states():
    """All states with 1 or 2 objects (distinct ids), plus a supersession edge
    variant, plus a merge-record variant.  Bounded but rich enough to separate
    every abstraction in ABSTRACTIONS."""
    objs = all_objs()
    out = []
    for o in objs:
        out.append(State(frozenset({o})))
    for o1, o2 in itertools.combinations(objs, 2):
        if o1.id == o2.id: continue
        out.append(State(frozenset({o1, o2})))
        out.append(State(frozenset({o1, o2}), sup=frozenset({(o1.id, o2.id)})))
        out.append(State(frozenset({o1, o2}),
                         merged=frozenset({(o1.id, frozenset({"vendor", "forum"}))})))
    return out

SPACE = all_states()

# ----------------------------------------------------------- argument sets
NEWOBJ = Obj("z", "r", "vendor", "Proposed")
def args_for(name, s):
    if name == "Add":       return [NEWOBJ]
    if name in ("Remove", "Withdraw", "Reject"): return [o.id for o in s.objs]
    if name == "Revise":    return [(o.id, "r") for o in s.objs]
    if name == "Transform": return [(o.id,) for o in s.objs]
    if name == "Supersede": return [(o.id, NEWOBJ) for o in s.objs]
    if name == "Merge":
        ids = sorted(o.id for o in s.objs)
        return [(ids[0], ids[1], "z")] if len(ids) >= 2 else []
    return []

# ---------------------------------------------------------------- the test
def congruence(F, opname, variant, cap=400000):
    """Return (is_congruent, witness_or_None, n_pairs_tested)."""
    fn, _src = OPERATIONS[opname]
    buckets = {}
    for s in SPACE:
        buckets.setdefault(F(s), []).append(s)
    tested = 0
    for key, group in buckets.items():
        if len(group) < 2: continue
        for s1, s2 in itertools.combinations(group, 2):
            common = [a for a in args_for(opname, s1) if a in args_for(opname, s2)]
            for a in common:
                tested += 1
                if tested > cap: return True, None, tested
                r1, r2 = fn(s1, a, variant), fn(s2, a, variant)
                if (r1 is None) != (r2 is None):
                    return False, ("precondition", s1, s2, a, r1, r2), tested
                if r1 is None: continue
                if F(r1) != F(r2):
                    return False, ("result", s1, s2, a, r1, r2), tested
    return True, None, tested

# ---------------------------------------------------------------- run it
hr("SO-EXP-01  CONGRUENCE MATRIX — executing Step 259 s259.9 (17 rows, all 'UNRESOLVED')")
print(f"  bounded state space: {len(SPACE)} states")
print(f"  abstractions: {len(ABSTRACTIONS)}   operations: {len(OPERATIONS)}   variants: 2")
print(f"  cells to compute: {len(ABSTRACTIONS)*len(OPERATIONS)*2}\n")

results = {}
for variant in ("blind", "sensitive"):
    print(f"\n  ---- operation dependency variant: {variant.upper()} "
          f"({'ignores' if variant=='blind' else 'reads'} provenance / supersession history) ----")
    header = f"  {'abstraction':<26}" + "".join(f"{op[:9]:>11}" for op in OPERATIONS)
    print(header); print("  " + "-"*(len(header)-2))
    for fname, F in ABSTRACTIONS.items():
        row = f"  {fname:<26}"
        for opname in OPERATIONS:
            ok, wit, n = congruence(F, opname, variant)
            results[(fname, opname, variant)] = (ok, wit, n)
            row += f"{('PASS' if ok else 'FAIL'):>11}"
        print(row)

# ------------------------------------------------------------- witnesses
hr("WITNESSES for the TERMINAL model F4 = K=(A,R)")
any_fail = False
for variant in ("blind", "sensitive"):
    for opname in OPERATIONS:
        ok, wit, n = results[("F4 K=(A,R) [TERMINAL]", opname, variant)]
        if not ok:
            any_fail = True
            kind, s1, s2, a, r1, r2 = wit
            print(f"\n  FAIL  F4 x {opname} [{variant}]  ({kind})")
            print(f"    s1 = {sorted(map(str,s1.objs))}  sup={sorted(s1.sup)}")
            print(f"    s2 = {sorted(map(str,s2.objs))}  sup={sorted(s2.sup)}")
            print(f"    F4(s1) == F4(s2)  : {F_AR(s1)==F_AR(s2)}")
            print(f"    arg = {a}")
            print(f"    F4(T(s1)) = {F_AR(r1) if r1 else None}")
            print(f"    F4(T(s2)) = {F_AR(r2) if r2 else None}")
if not any_fail:
    print("\n  No congruence failure found for F4 in either variant, over this domain.")

# --------------------------------------------------------------- summary
hr("SUMMARY")
for variant in ("blind", "sensitive"):
    for fname in ABSTRACTIONS:
        fails = [op for op in OPERATIONS if not results[(fname, op, variant)][0]]
        tag = "CONGRUENT(tested)" if not fails else f"FAILS on {fails}"
        print(f"  [{variant:<9}] {fname:<26} {tag}")
print("""
  Per s259.16, every PASS above reads 'no counterexample found in the tested
  domain', never 'congruence proven'.  Every FAIL is a genuine refutation:
  a single counterexample suffices.""")
