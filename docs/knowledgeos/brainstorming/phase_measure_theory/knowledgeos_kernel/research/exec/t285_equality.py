#!/usr/bin/env python3
"""
Applying the HPA review's mandate -- "each test specifies its equality relation" --
to D285-6's own claim:   (A,R) = pi_K(K_t).
Which equality?  The answer changes the strength of the result.
"""
print("="*78); print("Q. Under WHICH equality does  (A,R) = pi_K(K_t)  hold?"); print("="*78)

RATIFIED = {"Entity","State","Event","Observation","Proposition","Relation","Policy","Action"}
IMAGE    = {"Proposition","Entity","Relation","State","Observation"}   # pi's non-dropped images
DROPPED  = {"Event","Policy","Action"}
TARGET   = {"Assertion","Relation"}          # (A,R) as literally written

print("\n1. STRUCTURAL equality  (same components, same names)")
print(f"   pi image  = {sorted(IMAGE)}")
print(f"   target    = {sorted(TARGET)}")
print(f"   equal? {IMAGE==TARGET}   -> FALSE. 'Assertion' is not a ratified primitive at all.")
print("   => the claim is FALSE under structural equality.")

print("\n2. SEMANTIC equality  (same content, ignore packaging)")
# Assertion = (id,P,e,c,t,Pi): P<-Proposition/Entity, e<-Observation(after Qualify)
UNPACK = {"Proposition","Entity","Observation"}
print(f"   Assertion unpacks to carry: {sorted(UNPACK)}  (+ id,c,t,Pi which are not primitives)")
print(f"   plus Relation, plus State-as-carrier")
print(f"   covered by pi image? {UNPACK <= IMAGE}  -> TRUE")
print("   => the claim HOLDS under semantic equality -- and ONLY after unpacking Assertion.")

print("\n3. OBSERVATIONAL equality  (same answers to all mandatory queries)")
QUERIES = {"member":True,"contradicts":True,"supersede":True,"lineage":True,
           "replay":False,"policy-eval":False,"authorize":False}
ok=[q for q,v in QUERIES.items() if v]; no=[q for q,v in QUERIES.items() if not v]
print(f"   answerable in (A,R): {ok}")
print(f"   NOT answerable    : {no}   <- these need Event/Policy/Action")
print("   => FALSE under observational equality. The projection is LOSSY, by design.")

print()
print("="*78); print("RESULT -- the claim must be restated"); print("="*78)
print("""
  WRONG (as D285-6 originally wrote it):
      (A,R) = pi_K(K_t)

  CORRECT:
      (A,R)  =_semantic  pi_K(K_t)      after unpacking Assertion into its fields,
                                         and modulo the declared drop of {Event, Policy, Action}

  and explicitly NOT:
      =_structural      (Assertion is not a ratified primitive)
      =_observational   (replay, policy-eval and authorize are unanswerable in (A,R))
""")
print("  CONSEQUENCE: 'projection' is the right word only for a LOSSY, semantic-level map.")
print("  D285-6's Outcome B stands -- but it is weaker than an unqualified '=' suggested,")
print("  and the loss is now named: three primitives and three query classes.")
print()
print("  This is the SAME defect class D285-5 caught in the action/result property:")
print("  a property stated without naming its equality is not a well-formed proposition.")
print("  D285-5 caught it in someone else's property; it was present in my own.")
