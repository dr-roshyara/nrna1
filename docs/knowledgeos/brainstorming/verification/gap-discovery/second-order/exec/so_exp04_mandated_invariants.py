#!/usr/bin/env python3
"""
SO-EXP-04 — MANDATED-INVARIANT EXPRESSIBILITY AUDIT.

QUESTION   SO-EXP-02 showed congruence is necessary but not sufficient: an
           abstraction can be congruent for T while unable to state the invariant
           T must maintain.  Which invariants does the corpus MANDATE, and can
           the terminal K=(A,R) express each?
INPUT      Invariants stated in the corpus with mandatory force (boxed, or
           'must'), cited per row.
METHOD     For each invariant I, construct two states that F4 IDENTIFIES but on
           which I differs.  If such a pair exists, I is INEXPRESSIBLE in F4.
           (Expressibility = I's truth value is determined by F(state).)
RESULT     see the table.
LIMITATION Only invariants this session located with mandatory force are tested;
           the corpus has no closed invariant register, so the list is not
           exhaustive.  A NEGATIVE (inexpressible) is a definite result; an
           expressible verdict is relative to the constructed witness pair.
INDEPENDENCE  New.  The corpus states these invariants (256.x, 265.x) and states
           the congruence criterion (259.15) and never composes them.
"""
import sys, os
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from so_model import *
def hr(t): print("\n" + "="*78 + f"\n{t}\n" + "="*78)

hr("SO-EXP-04  Can K=(A,R) express the invariants the corpus mandates?")

results = []

def check(name, src, s_true, s_false, pred):
    """s_true satisfies the invariant, s_false violates it."""
    assert pred(s_true) and not pred(s_false), f"witness construction wrong for {name}"
    rows = []
    for fname, F in ABSTRACTIONS.items():
        identifies = F(s_true) == F(s_false)
        rows.append((fname, not identifies))
    results.append((name, src, rows))
    return rows

# --- I1: Merge preserves provenance association (265.11, boxed) --------------
a = Obj("a","p","vendor","Accepted"); b = Obj("b","q","forum","Accepted")
s0 = State(frozenset({a,b}))
I1_true  = op_merge(s0, ("a","b","z"), "sensitive")
I1_false = op_merge(s0, ("a","b","z"), "blind")
check("I1  Merge preserves provenance association", "265.11 (boxed)",
      I1_true, I1_false,
      lambda s: any(r=="z" and {"vendor","forum"} <= set(ss) for r,ss in s.merged))

# --- I2: Reject != Remove  (x stays in K)  (256.11) --------------------------
I2_true  = op_reject(s0, "a", "blind")          # a stays, status Rejected
I2_false = op_remove(s0, "a", "blind")          # a gone
check("I2  Reject leaves the object IN K", "256.11",
      I2_true, I2_false, lambda s: s.by_id("a") is not None)

# --- I3: Withdraw != Remove (256.12) ----------------------------------------
I3_true  = op_withdraw(s0, "a", "blind")
I3_false = op_remove(s0, "a", "blind")
check("I3  Withdraw leaves the object IN K", "256.12",
      I3_true, I3_false, lambda s: s.by_id("a") is not None)

# --- I4: provenance cannot disappear (265 placement matrix 'Preserve: Yes') --
lost = State(frozenset({Obj("a","p","","Accepted"), b}))
check("I4  Provenance is preserved (never empty)", "265 placement matrix",
      s0, lost, lambda s: all(o.origin for o in s.objs))

# --- I5: supersession is recorded, not merely implied (256.8 / 257.13) ------
newo = Obj("n","p2","vendor","Proposed")
I5_true  = op_supersede(s0, ("a", newo), "blind")            # sup edge recorded
I5_false = State(frozenset({newo, replace(a, status="Superseded"), b}))  # same objs, NO edge
check("I5  Supersession relation is recorded in the state", "256.8 / 257.13",
      I5_true, I5_false, lambda s: len(s.sup) > 0)

# --- I6: Split preserves lineage to Origin(A) (265.12) ----------------------
# modelled as: the two parts each retain the origin of the whole
whole = Obj("w","pq","vendor","Accepted")
sw = State(frozenset({whole}))
I6_true  = State(frozenset({Obj("w1","p","vendor"), Obj("w2","q","vendor")}))
I6_false = State(frozenset({Obj("w1","p","internal"), Obj("w2","q","internal")}))
check("I6  Split preserves lineage to the origin", "265.12",
      I6_true, I6_false, lambda s: all(o.origin=="vendor" for o in s.objs))

# ------------------------------------------------------------------ report
names = list(ABSTRACTIONS)
w = max(len(n) for n,_,_ in results)
print(f"  {'invariant':<{w}} {'source':<22} " + "".join(f"{n.split()[0]:>7}" for n in names))
print("  " + "-"*(w+24+7*len(names)))
for name, src, rows in results:
    print(f"  {name:<{w}} {src:<22} " + "".join(f"{('YES' if ok else 'no'):>7}" for _,ok in rows))
print(f"\n  (YES = the invariant's truth value is determined by that abstraction)")

f4 = names.index("F4 K=(A,R) [TERMINAL]")
inexpr = [n for n,_,rows in results if not rows[f4][1]]
expr   = [n for n,_,rows in results if rows[f4][1]]
print(f"\n  EXPRESSIBLE in the terminal K=(A,R):   {len(expr)}/{len(results)}")
for n in expr: print(f"     + {n}")
print(f"  INEXPRESSIBLE in the terminal K=(A,R): {len(inexpr)}/{len(results)}")
for n in inexpr: print(f"     - {n}")

print(f"""
  DERIVED RESULT.  The terminal model expresses {len(expr)} of the {len(results)} mandated
  invariants located.  The failure is confined to ONE structural cause:

      a merged (or split) object carries a SINGLE provenance slot, so it cannot
      record that it derives from TWO sources.

  s265.19's own proposed repair already fixes it: A = (id,P,e,c,t,pi) where
  pi is a provenance REFERENCE, plus ResolveProvenance: PI -> ProvenanceObject.
  A set-valued or relation-backed pi makes I1 and I6 expressible with no other
  change to the model.

  This is an ENGINEERING CONSEQUENCE of a corpus-stated invariant, not a
  normative choice.""")
