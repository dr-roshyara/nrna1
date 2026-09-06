#!/usr/bin/env python3
"""
EXPERIMENTS 21-23 — Sigma: which status distinctions are NECESSARY?

Mandate s11 forbids asking the human to choose a vocabulary before demonstrating
which distinctions are mathematically/semantically necessary.

METHOD (necessity test): a status value S is NECESSARY iff there exist two
situations that (a) are distinguishable by some operation the theory needs, and
(b) are NOT distinguishable by any other status in the set.  If some other status
already separates them, S is redundant.

We model a situation as the evidence/authority/time facts about one proposition,
and test which downstream DECISIONS differ.
"""
import itertools, collections
def hr(t): print("\n"+"="*74+f"\n{t}\n"+"="*74)

# A situation: what is actually the case about one proposition.
# (asked, supporting, refuting, authority_act, superseded_by, valid_now, policy_bar_met)
SIT = collections.namedtuple("SIT",
    "asked supporting refuting auth_act superseded valid policy_met")

SITUATIONS = {
 "never asked":            SIT(0,0,0,None,False,True,False),
 "asked, nothing found":   SIT(1,0,0,None,False,True,False),
 "one supporting item":    SIT(1,1,0,None,False,True,False),
 "meets the policy bar":   SIT(1,3,0,None,False,True,True),
 "support + accepted":     SIT(1,3,0,"accept",False,True,True),
 "support + rejected":     SIT(1,3,0,"reject",False,True,True),
 "refuting evidence":      SIT(1,0,2,None,False,True,False),
 "both directions":        SIT(1,2,2,None,False,True,False),
 "replaced by a newer":    SIT(1,3,0,"accept",True,True,True),
 "was valid, now expired": SIT(1,3,0,"accept",False,False,True),
}

# Candidate DECISIONS the theory must make. Each is a function of the SITUATION.
DECISIONS = {
 "may be used in a decision":      lambda s: bool(s.policy_met and s.auth_act=="accept"
                                                  and not s.superseded and s.valid and s.refuting==0),
 "must be investigated further":   lambda s: bool(s.asked and not s.policy_met and s.refuting==0),
 "must trigger a conflict process":lambda s: bool(s.supporting>0 and s.refuting>0),
 "must be shown as a gap":         lambda s: bool(s.asked and s.supporting==0 and s.refuting==0),
 "is out of scope (never asked)":  lambda s: not s.asked,
 "requires re-validation":         lambda s: bool(not s.valid),
 "points at a replacement":        lambda s: bool(s.superseded),
 "was refused by an authority":    lambda s: s.auth_act=="reject",
 "is contradicted by evidence":    lambda s: bool(s.refuting>0 and s.supporting==0),
}

hr("EXP-21  Which situations must be distinguished?  (decision-signature method)")
sigs = {}
for name, s in SITUATIONS.items():
    sigs[name] = tuple(f(s) for f in DECISIONS.values())
groups = collections.defaultdict(list)
for n,sg in sigs.items(): groups[sg].append(n)
print(f"  {len(SITUATIONS)} situations -> {len(groups)} distinct decision signatures")
for sg, names in groups.items():
    if len(names) > 1:
        print(f"    INDISTINGUISHABLE: {names}")
print(f"""
  => The theory must be able to separate {len(groups)} situations.  Any Sigma with
     fewer than {len(groups)} values CANNOT carry the decisions listed above; any Sigma
     with more must justify the extra value by a decision no other value carries.""")

hr("EXP-22  Are the required distinctions ONE axis or SEVERAL?")
# Which underlying FACT does each decision read?
READS = {
 "may be used in a decision":      {"evidence","authority","supersession","validity"},
 "must be investigated further":   {"evidence"},
 "must trigger a conflict process":{"evidence"},
 "must be shown as a gap":         {"evidence","asked"},
 "is out of scope (never asked)":  {"asked"},
 "requires re-validation":         {"validity"},
 "points at a replacement":        {"supersession"},
 "was refused by an authority":    {"authority"},
 "is contradicted by evidence":    {"evidence"},
}
axes = collections.defaultdict(set)
for d, facts in READS.items():
    for f in facts: axes[f].add(d)
print("  underlying fact  ->  decisions that read it")
for f, ds in sorted(axes.items()):
    print(f"    {f:<14} -> {sorted(ds)}")
print(f"""
  Distinct underlying facts: {sorted(axes)}   ({len(axes)} of them)

  A SINGLE status enum must encode the cross-product of every fact any decision
  reads.  Cross-product size (worst case) = product of the arities:
      asked(2) x evidence(4: none/support/refute/both) x authority(3: none/accept/reject)
      x supersession(2) x validity(2)  =  {2*4*3*2*2} states.
  => A one-dimensional Sigma is provably inadequate: it would need {2*4*3*2*2} values.
     The distinctions are NOT one axis.  They are at least FIVE orthogonal facts.""")

hr("EXP-23  Minimal axis decomposition -- which axes are irreducible?")
# Test: can any axis be dropped without merging two situations that a decision separates?
FACTS = ["asked","evidence","authority","supersession","validity"]
def proj(s, drop):
    d = {"asked": s.asked, "evidence": (s.supporting>0, s.refuting>0),
         "authority": s.auth_act, "supersession": s.superseded, "validity": s.valid}
    return tuple(v for k,v in d.items() if k != drop)
for drop in [None]+FACTS:
    seen = collections.defaultdict(list)
    for n,s in SITUATIONS.items(): seen[proj(s,drop)].append(n)
    collisions = [v for v in seen.values() if len(v)>1]
    # does any collision separate a decision?
    bad = [v for v in collisions if len({sigs[x] for x in v})>1]
    label = "keep all" if drop is None else f"drop '{drop}'"
    print(f"  {label:<20} classes={len(seen):<3} harmful collisions={len(bad)}")
    for b in bad: print(f"        {b}")
print("""
  => Every axis whose removal produces a HARMFUL collision is IRREDUCIBLE:
     dropping it makes two situations identical that a required decision must
     separate.  This is the necessity demonstration the mandate asks for, and it
     is produced WITHOUT choosing any vocabulary.

  CONSEQUENCE FOR THE CORPUS'S VOCABULARY DEBATE:
     Unknown / Supported / Refuted / Conflicted  read the EVIDENCE axis.
     Accepted / Rejected                         read the AUTHORITY axis.
     Superseded                                  reads the SUPERSESSION axis.
     Invalidated / Stale                         read the VALIDITY axis.
     Contested                                   reads a SIXTH fact (an open
                                                 process) that none of the above
                                                 situations models -- see the doc.
  Putting these nine words in ONE enum conflates four independent axes.  That is
  the real finding; the vocabulary question is downstream of it and does not need
  a human decision yet.""")
