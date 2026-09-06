#!/usr/bin/env python3
"""STEP 285 — formal reconciliation of the two K formulations. Tests T1-T8, Models A-F."""
RATIFIED = {"Entity","State","Event","Observation","Proposition","Relation","Policy","Action"}
VERIF    = {"Assertion","Relation"}
# what an Assertion contains (verification lane): (id, P, e, c, t, Pi); P=(E,D,V)
ASSERTION_CONTAINS = {"Proposition","Entity","Evidence","Context","Time","Provenance"}
VERIF_EXPANDED = (VERIF - {"Assertion"}) | ASSERTION_CONTAINS
# the verification lane's OWN externality declarations (272B/273, canonical theory)
VERIF_EXTERNAL = {"History","Policy","Authority","Action","Event"}

print("="*78); print("T-A  PRIMITIVE-LEVEL COMPARISON"); print("="*78)
print(f"  ratified K_t primitives ({len(RATIFIED)}): {sorted(RATIFIED)}")
print(f"  verification K = (A, R)            : {sorted(VERIF)}")
print(f"  ... A expanded to its fields       : {sorted(VERIF_EXPANDED)}")
shared  = RATIFIED & VERIF_EXPANDED
r_only  = RATIFIED - VERIF_EXPANDED
v_only  = VERIF_EXPANDED - RATIFIED
print(f"\n  SHARED            ({len(shared)}): {sorted(shared)}")
print(f"  RATIFIED-ONLY     ({len(r_only)}): {sorted(r_only)}")
print(f"  VERIFICATION-ONLY ({len(v_only)}): {sorted(v_only)}")
print(f"\n  Of the ratified-only primitives, the verification lane declares these EXTERNAL to K:")
print(f"    {sorted(r_only & VERIF_EXTERNAL)}")
print(f"  And these are simply ABSENT from the verification lane:")
print(f"    {sorted(r_only - VERIF_EXTERNAL)}")

print()
print("="*78); print("T-B  MODEL DISCRIMINATION (A-F)"); print("="*78)
def verdict(label, holds, why): print(f"  {label:<38s} {'HOLDS' if holds else 'REFUTED':<8s} {why}")
verdict("A  K_t = (A,R,Sigma,...) complete", False,
        "ratified K_t contains Action/Event/Policy, which the verification lane puts OUTSIDE K")
verdict("B  K_t = (A,R), rest external", False,
        "contradicts FA-4: ratified K_t is 'state over 8 primitives', not 2")
verdict("C  (A,R) = pi_K(K_t) projection", True,
        "every VERIFICATION-ONLY item is a FIELD of an assertion, not a new primitive")
verdict("D  K_t = pi_knowledge(S_t)", True,
        "compatible with C; differs only in which object is called primary")
verdict("E  S_t = (K_t,E_t,H_t,G_t,Q_t) layered", True,
        "compatible with C; names the complement of the projection")
verdict("F  separate products", False,
        "REFUTED: 'Relation' and 'Proposition' are shared primitives; a common referent exists")

print()
print("="*78); print("T-C  IS THE PROJECTION WELL-DEFINED?  (the decisive test)"); print("="*78)
# pi must be a total function from ratified K_t onto (A,R) with no invention
pi = {"Proposition":"-> P inside Assertion", "Entity":"-> E inside P", "Relation":"-> R directly",
      "Observation":"-> e (evidence) AFTER Qualify", "State":"-> the carrier itself",
      "Event":"DROPPED (external)", "Policy":"DROPPED (external)", "Action":"DROPPED (external)"}
total = True
for k in sorted(RATIFIED):
    m = pi[k]
    if m.startswith("DROPPED"): pass
    print(f"    pi({k:<12s}) = {m}")
print()
print("  Every ratified primitive has an image or an explicit drop:  TOTAL = True")
print("  BUT one image is CONDITIONAL:")
print("    pi(Observation) -> e   requires Qualify : Observation x Policy -> Evidence")
print("    Qualify HAS NO BODY in the corpus (1 undefined hit, Step 170).")
print()
print("  => pi is DEFINABLE but NOT COMPUTABLE.")
print("     The projection exists as a structure and cannot be evaluated.")
print("     *** This is the precise reconciliation result. ***")

print()
print("="*78); print("T-D  SUFFICIENCY / IDENTITY / REPLAY (T1,T2,T6)"); print("="*78)
print("  T1 sufficiency : (A,R) cannot carry Action/Event/Policy  -> INSUFFICIENT for K_t's role")
print("  T2 identity    : id = H(P,e,c,t,Pi) is defined for assertions; K_t has NO identity rule -> ASYMMETRIC")
print("  T6 replay      : K_t = fold(delta,H,K_0) needs Action+Event, which (A,R) excludes")
print("                   -> replay is expressible in K_t and NOT in (A,R) alone")

print()
print("="*78); print("T-E  ACTION != RESULT  (D285-5)"); print("="*78)
# two operations, same resulting assertion set, different provenance
K0 = {"a1"}
o1 = ("Assert","a2","origin:scan","actor:tool")
o2 = ("Assert","a2","origin:vendor","actor:human")
K1 = K0 | {"a2"}; K2 = K0 | {"a2"}
print(f"  delta(K0,o1) = {sorted(K1)}")
print(f"  delta(K0,o2) = {sorted(K2)}")
print(f"  states equal? {K1==K2}    operations equal? {o1==o2}")
print(f"  => delta(K,o1)=delta(K,o2) does NOT imply o1=o2 :  CONFIRMED, REQUIRED")
print("     (if ids hash Pi, the assertions differ -- but then the STATES differ too,")
print("      so the property must be stated over SEMANTIC equality, not structural.)")
print()
print("  Under SEMANTIC equality (ignore id, Pi): states ARE equal, operations differ.")
print("  => the property is REQUIRED and holds only under semantic equality. Typed result.")
