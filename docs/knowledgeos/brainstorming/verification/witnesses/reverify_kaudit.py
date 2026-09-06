#!/usr/bin/env python3
"""KNOWLEDGE-STATE-FINAL-AUDIT executable probes against K=(A,R), id=H(P,e,c,t,Pi)."""
import hashlib, json
def H(*p): return hashlib.sha256(json.dumps(p,sort_keys=True,default=str).encode()).hexdigest()[:12]
def A(P,e,c,t,Pi): return {"id":H(P,e,c,t,Pi),"P":P,"e":e,"c":c,"t":t,"Pi":Pi}

print("="*74)
print("PROBE 1 — id is a hash over a MUTABLE field (e.state)")
print("="*74)
a  = A(("svc","D.tls","1.3"), (("ev:1","supports","active"),),   "prod",("2026-08-01",None),"origin:scan")
a2 = A(("svc","D.tls","1.3"), (("ev:1","supports","withdrawn"),),"prod",("2026-08-01",None),"origin:scan")
R  = {(a["id"], "other-assertion", "supports")}
print(f"  id before withdrawal : {a['id']}")
print(f"  id after  withdrawal : {a2['id']}")
print(f"  SAME? {a['id']==a2['id']}")
dangling = a["id"] not in {a2["id"]}
print(f"  R edge {list(R)[0][:2]} now dangles: {dangling}")
print("  => the canonical field table declares e.state MUTABLE and id DERIVED from e.")
print("     Mutating state re-keys the assertion and breaks every R edge into it,")
print("     violating StructuralValid's own 'no dangling' clause. CONTRADICTION.")

print()
print("="*74)
print("PROBE 2 — merge = set union cannot deduplicate (Pi is inside id)")
print("="*74)
b1 = A(("svc","D.tls","1.3"), (("ev:1","supports","active"),),"prod",("2026-08-01",None),"origin:scan")
b2 = A(("svc","D.tls","1.3"), (("ev:2","supports","active"),),"prod",("2026-08-01",None),"origin:vendor")
K = {b1["id"]:b1, b2["id"]:b2}
print(f"  same proposition P={b1['P']}, two sources -> {len(K)} distinct assertions")
print(f"  ids: {b1['id']} vs {b2['id']}")
print("  => merge is idempotent only on IDENTICAL assertions. Corroboration (the same")
print("     fact from two sources) is indistinguishable from duplication. K grows")
print("     without bound under re-observation; no dedup operator is defined.")

print()
print("="*74)
print("PROBE 3 — Sigma never consults R: contradiction is invisible to epistemic status")
print("="*74)
c1 = A(("svc","D.tls","1.3"), (("ev:1","supports","active"),),"prod",("2026-08-01",None),"o1")
c2 = A(("svc","D.tls","1.2"), (("ev:2","supports","active"),),"prod",("2026-08-01",None),"o2")
Rc = {(c1["id"],c2["id"],"contradicts")}
def Sigma(x):
    s=[y for y in x["e"] if y[1]=="supports" and y[2]=="active"]
    r=[y for y in x["e"] if y[1]=="contradicts" and y[2]=="active"]
    if s and not r: return ("Supporting","Weak")
    if r and not s: return ("Refuting","Weak")
    return ("Neutral","None")
print(f"  R = {sorted(Rc)}")
print(f"  Sigma(c1) = {Sigma(c1)}")
print(f"  Sigma(c2) = {Sigma(c2)}")
print("  => Two assertions in an explicit 'contradicts' edge are BOTH 'Supporting'.")
print("     Sigma : Assertion x Policy -> (dir,str) has NO access to R by its own")
print("     signature. Epistemic status is blind to contradiction. Unless a policy")
print("     gate re-derives it, K can hold a fully-supported inconsistency.")

print()
print("="*74)
print("PROBE 4 — history-equality vs structural-equality on K")
print("="*74)
KA = {b1["id"]:b1}; KB = {b1["id"]:b1}
print(f"  K_A built by (import), K_B built by (import, add, remove): equal? {KA==KB}")
print("  => History is EXTERNAL to K, so states with different histories are EQUAL.")
print("     Consequence: GovernanceValid needs History, therefore GovernanceValid is")
print("     NOT a function of K. Two K-equal states can differ in governance validity.")
print("     The canonical theory calls this 'a boundary, not a defect' — but it means")
print("     validity is not a predicate over the declared state space.")
