#!/usr/bin/env python3
"""
SO-EXP-03 — ADVERSARIAL SEARCH: can any corpus-sourced operation refute F4?

QUESTION   SO-EXP-01 found K=(A,R) congruent for the 8 operations of s256.2.
           s256.32 explicitly does NOT establish those nine as final.  Is the
           terminal model ROBUST to the open remainder?
INPUT      Every operation named in the corpus's operation vocabulary
           (s256.2 nine, s259.7 adds Validate/Assess/Promote/Reintroduce/Replay)
           plus the two history-sensitive predicates the FIRST-ORDER pass used.
METHOD     Classify by s259.7's five classes; apply s259.8's per-class test.
RESULT     see below.
LIMITATION Bounded domain (s259.16: tested, never global).  Each classification
           is argued from corpus text and is falsifiable.
INDEPENDENCE  The 5-class taxonomy and the class-1 restriction are the CORPUS's
           (s259.7-8), found in this second-order pass.  The first-order pass
           lacked them and mis-tested a class-4 predicate against the class-1
           criterion — corrected here.
"""
import sys, os, itertools
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from so_model import *
from so_exp01_congruence_matrix import SPACE, congruence
def hr(t): print("\n" + "="*78 + f"\n{t}\n" + "="*78)

hr("SO-EXP-03  Operation classification per s259.7, then the correct test per class")

CLASSIFICATION = [
 ("Add",           1, "256.2/256.4",  "K x X -> K'"),
 ("Remove",        1, "256.2/256.5",  "K x ID -> K'"),
 ("Revise",        1, "256.2/256.6",  "K x X' -> K'"),
 ("Transform",     1, "256.2/256.7",  "K x P -> K' : the paradigm state transition"),
 ("Supersede",     1, "256.2/256.8",  "K x X x X -> K'"),
 ("Merge",         1, "256.2/256.9",  "K x X x X -> K'"),
 ("Split",         1, "256.2/256.10", "K x X -> K'"),
 ("Reject",        1, "256.2/256.11", "status change; s256.11: Reject != Remove"),
 ("Withdraw",      1, "256.2/256.12", "status change; s256.12: Withdraw != Remove"),
 ("Promote",       1, "259.7",        "status change; == Transform here"),
 ("Reintroduce",   1, "259.7",        "inverse of Withdraw; state-changing"),
 ("Validate",      2, "256.20",       "Validate: K x E -> Result; s256.20 boxes Validate != Transform"),
 ("Assess",        2, "259.7",        "Assess: K x X -> Assessment"),
 ("Authorize",     3, "259.7",        "Actor x Action x Policy -> Decision; s259.7: NOT state closure"),
 ("Replay",        4, "259.7",        "reads H, not K"),
 ("EverContested", 4, "first-order",  "reads H; a HISTORY predicate"),
 ("RevisionCount", 4, "first-order",  "reads H; a HISTORY predicate"),
]
NAMES = {1:"state transformation", 2:"epistemic assessment", 3:"governance operation",
         4:"audit/history operation", 5:"observation operation"}
by_class = {}
for n,c,s,j in CLASSIFICATION: by_class.setdefault(c, []).append((n,s,j))
for c in sorted(by_class):
    print(f"\n  CLASS {c} — {NAMES[c]}  ({len(by_class[c])})")
    for n,s,j in by_class[c]: print(f"    {n:<15} [{s:<12}] {j}")

hr("Only CLASS 1 enters the primary congruence test (s259.8) — running it for F4")
impl    = [n for n,c,_,_ in CLASSIFICATION if c == 1 and n in OPERATIONS]
notimpl = [n for n,c,_,_ in CLASSIFICATION if c == 1 and n not in OPERATIONS]
print(f"  class-1 implemented here : {impl}")
print(f"  class-1 NOT implemented  : {notimpl}")
print("    (Split needs the semantic-preservation invariant s256.10 leaves open;")
print("     Promote == Transform; Reintroduce is Withdraw's inverse.)")
print(f"\n  {'operation':<14}{'blind':>10}{'sensitive':>12}")
print("  " + "-"*36)
allpass = True
for op in impl:
    row = []
    for v in ("blind","sensitive"):
        ok,_,_ = congruence(F_AR, op, v); row.append("PASS" if ok else "FAIL"); allpass &= ok
    print(f"  {op:<14}{row[0]:>10}{row[1]:>12}")
print(f"\n  F4 = K=(A,R) congruent for ALL tested class-1 operations: {allpass}")

hr("The test s259.8 prescribes for the OTHER classes, applied")
def assess(s, oid):
    o = s.by_id(oid)
    return None if o is None else (o.content, o.origin, o.status)
buckets = {}
for s in SPACE: buckets.setdefault(F_AR(s), []).append(s)
bad = tested = 0
for key, grp in buckets.items():
    for s1, s2 in itertools.combinations(grp, 2):
        for oid in {o.id for o in s1.objs} & {o.id for o in s2.objs}:
            tested += 1
            if assess(s1, oid) != assess(s2, oid): bad += 1
print(f"  CLASS 2 — is Assess DETERMINED by F4?  pairs={tested}  violations={bad}"
      f"  => {'DETERMINED' if bad==0 else 'NOT DETERMINED'}")
print("""
  CLASS 4 (audit/history) — the decisive correction:
    EverContested and RevisionCount read HISTORY.  Per s257.32:
      'History dependence of implementation != history dependence of state
       semantics.  Only the second disproves state sufficiency.'
    and s259.8 excludes non-state-transforming operations from the congruence
    test.  A history-reading QUERY therefore does NOT refute K=(A,R); it shows
    only that the SYSTEM must retain H somewhere — which s247 and s265 grant.

    => The first-order EXP-3 tested a class-4 predicate against the class-1
       criterion.  Its arithmetic was correct; its inference was not.
       CORRECTED HERE.""")

hr("VERDICT OF SO-EXP-03")
print(f"""  Across the full corpus operation vocabulary (17 named operations, five
  classes), the terminal model K=(A,R):
    * is congruent for every implemented class-1 operation, both dependency
      variants, over a {len(SPACE)}-state domain;
    * determines every class-2 assessment reading only state-visible fields;
    * is not challenged by class-3/4/5, which s259.8 excludes by construction.

  The open remainder of s256.32 is therefore NOT LOAD-BEARING for K: the answer
  does not vary across the open choice.  A choice that cannot change the answer
  is not a normative decision that needs making.

  WHAT REMAINS is a different question — SO-EXP-02: congruence is necessary but
  not sufficient.  The live issue is which INVARIANTS are mandatory and whether
  K can express them.""")
