#!/usr/bin/env python3
"""STEP 291: O / O_K / T reconciliation + closure classification (mandate s2,s5,s6,s7)."""
print("="*78); print("STEP 291  OMEGA / TAU RECONCILIATION AND CLOSURE AUDIT"); print("="*78)

SYMS=[
 ("O",  "256.2 / 259.7 / 272A",
  "the operation family; 259.7 splits it into FIVE kinds",
  "family of heterogeneous signatures", "whole system", "NOT ENUMERATED",
  "no", "no"),
 ("T",  "258.9 / 259.8 / 260",
  "state-transforming operations only; 'T: K -> K'",
  "subset of O (kind 1)", "semantic state", "NOT ENUMERATED",
  "no", "no"),
 ("O_K","261.21",
  "'the closed set of permitted state OBSERVATIONS'",
  "observation functions on K", "state observations", "explicitly NOT CLOSED",
  "no", "no"),
 ("O(261.5)","261.5",
  "used as the index of ~ over HISTORIES: forall O in O",
  "observations", "histories", "NOT ENUMERATED",
  "no", "no"),
]
print("\n--- symbol reconciliation ---")
hdr=("symbol","locus","corpus definition","type","scope","closed?","canonical?","procedure?")
for s in SYMS:
    print(f"\n  {s[0]:10} [{s[1]}]")
    print(f"     def   : {s[2]}")
    print(f"     type  : {s[3]:36} scope: {s[4]}")
    print(f"     closed: {s[5]:36} canonical: {s[6]}   procedure: {s[7]}")

print("""
--- COLLISION FOUND ---
  The glyph 'O' carries TWO meanings:
    (a) 256.2/259.7/272A : the OPERATION family
    (b) 261.5            : the index of ~ over histories, i.e. OBSERVATIONS
  261.21 then introduces O_K for 'permitted state observations'.
  => O is overloaded across operations and observations. DOCUMENTARY defect.
  => T subset O (259.7 kind 1). O_K relates to kind 5. THREE distinct objects,
     which Step 289's graph had collapsed into a single {O,T} cut candidate.""")

# ---- enumeration vs closure (mandate s7) ----
print("\n--- ENUMERATION vs CLOSURE (mandate s7) ---")
MECH=[
 ("finite enumeration",
  "256.2 gives 9; 259.7 adds 8 more; 277 declares classification CLOSED, minimality OPEN",
  "PARTIAL - a CANDIDATE list (14 forced, upper bound 18), never a membership rule"),
 ("a closed type/schema/interface",
  "259.7's FIVE-KIND classification + explicit signatures "
  "(Assess: K x X -> Assessment; Authorize: Actor x Action x Policy -> Decision)",
  "PRESENT - the corpus supplies a schema, and 277 calls classification CLOSED"),
 ("a declared admissibility predicate",
  "259.8: operations enter the congruence test 'IF AND ONLY IF the corpus establishes "
  "them as state-changing'; 42.9/42.41 Admissible(o,K)",
  "PRESENT IN FORM, ABSENT IN CONTENT - the predicate is named, not evaluable"),
 ("a governance boundary",
  "261.23 cond 2 treats registry closure as a precondition; 259 as a precondition",
  "ASSERTED, never discharged"),
]
for m,ev,verdict in MECH:
    print(f"\n  mechanism : {m}")
    print(f"  evidence  : {ev}")
    print(f"  verdict   : {verdict}")

print("""
  => CRITICAL: 'not enumerated' != 'not closed'.
     The corpus has a SCHEMA (five kinds, typed signatures) and 277 declares
     CLASSIFICATION CLOSED while MINIMALITY OPEN. What is missing is not an
     enumeration; it is a MEMBERSHIP RULE saying which operations are MANDATORY.""")

print("\n--- observation universe: classification A-E (mandate s6) ---")
CASES=[("A explicitly closed","every permitted observation enumerated","NO - none enumerated anywhere"),
       ("B schema-closed, not enumerated","a type/schema bounds what can be observed",
        "PARTIAL - 259.7 kind 5 names 'observation operations' but gives NO schema for them"),
       ("C semantically bounded, not formally closed","an intended boundary, no exhaustive definition",
        "CLOSEST FIT - 261.21 says O_K is 'the closed set of permitted state observations', "
        "i.e. closure is ASSUMED IN THE DEFINITION and then denied in the same section"),
       ("D open","no defensible boundary","NO - 261.21 asserts a boundary exists"),
       ("E undetermined","insufficient evidence","APPLIES TO THE SCHEMA QUESTION")]
for c,d,v in CASES: print(f"  {c:34} {v}")
print("""
  => OBSERVATIONS: case C (semantically bounded, not formally closed), with the
     schema question at E. NOTE the self-reference: 261.21 DEFINES O_K as
     'the closed set of permitted state observations' and then boxes
     'O_K is not yet completely closed'. The definition presupposes what the
     caveat withdraws. DOCUMENTARY + FORMAL.
  => OPERATIONS: closer to case B - a five-kind schema exists; membership does not.""")

print("\n--- is closure of O/T NECESSARY for the candidate ==_K to be DEFINED? ---")
for prop,ans in [
 ("definability",   "NO  - 261.21's formula is WELL-FORMED for any set O_K"),
 ("totality",       "YES - a total predicate needs O_K's extension"),
 ("decidability",   "YES - and 012 s35 bounds it independently of O_K"),
 ("canonicality",   "YES - 'which O_K' is exactly the normative question"),
 ("computability",  "YES - needs the extension AND each O evaluable"),
 ("operational completeness","YES - needs T's membership")]:
    print(f"  {prop:26} {ans}")
print("""
  => The six are NOT interchangeable. ==_K is DEFINABLE without closing O/T;
     it is not TOTAL, DECIDABLE, CANONICAL or COMPUTABLE without it.
     Step 289/290 said '== is blocked on O_K'. Precisely: its EXTENSION is
     blocked; its DEFINITION is not.""")
print("\n"+"="*78)
