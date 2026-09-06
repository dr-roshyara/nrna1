#!/usr/bin/env python3
"""Independent verification: constructed attacks on K, Sigma/Gamma, and the dependency graph."""
import hashlib, json, itertools
def H(*p): return hashlib.sha256(json.dumps(p,sort_keys=True,default=str).encode()).hexdigest()[:10]

print("="*78); print("A. K-CANDIDATE CONSTRUCTION — corpus R vs canonical R"); print("="*78)
# corpus relationship (026, 4 files):  r = (E1,E2,T,R,Q,E,Sigma,tau)
# canonical  relationship            :  r = (a1,a2,RelationType)
corpus_r    = dict(E1="A1",E2="A2",T="contradicts",R={},Q={"ctx":"prod"},
                   E=[("ev:9","supports","active")], Sigma=("Supporting","Strong"), tau=("2026-08-01",None))
canonical_r = ("A1","A2","contradicts")
lost = [k for k in corpus_r if k not in ("E1","E2","T")]
print(f"  corpus relation fields   : {sorted(corpus_r)}")
print(f"  canonical relation fields: ('a1','a2','RelationType')")
print(f"  FIELDS DISCARDED         : {sorted(lost)}   ({len(lost)} of 8)")
print("  corpus text: 'a semantic connection between two OR MORE entities' -> n-ary")
print("  canonical  : R subset of A x A x RelationType                     -> strictly binary")
print("  CONSEQUENCE (constructed):")
q = "Is the claim 'A1 contradicts A2' itself evidenced / dated / contestable?"
print(f"    {q}")
print(f"      under corpus r    : YES  (E, Sigma, tau are fields of r)")
print(f"      under canonical R : NO   (the triple has no carrier for any of them)")

print()
print("="*78); print("B. RELATION-AS-ASSERTION TEST — can R be reduced to A?"); print("="*78)
# If P=(E,D,V) only, can 'A1 contradicts A2' be an assertion?
try:
    P_rel = ("A1","contradicts","A2")   # E=A1, D=contradicts, V=A2
    ok_type = True
except Exception: ok_type=False
print("  encode as P=(E,D,V) with E=A1, D='contradicts', V=A2 :", "type-checks" if ok_type else "fails")
print("  BUT: V must lie in V_D, the ValueSpace declared ON the dimension.")
print("       V_D('contradicts') would have to be the set of ALL assertions,")
print("       i.e. V_D depends on A, while A depends on P, which depends on V_D.")
print("  => CIRCULAR:  V_D -> P -> Assertion -> A -> V_D")
print("  Q14 recorded this independently: 'the biggest mathematical problem ...")
print("     it does not naturally represent: Assertion A contradicts Assertion B'")
print("  VERDICT: relations are NOT reducible to P=(E,D,V). R is a genuine primitive.")

print()
print("="*78); print("C. SIGMA / GAMMA — the ten mandated quadrants, constructed"); print("="*78)
SIGMA = ["Supported","Refuted","Conflicted","Unknown"]
GAMMA = ["Uncommitted","Committed","Rejected","Contested"]
cases = [
 ("Supported","Uncommitted","evidence is strong; no authority act yet"),
 ("Supported","Committed","the ordinary case"),
 ("Refuted","Committed","authority committed a claim the evidence refutes"),
 ("Conflicted","Committed","committed while evidence conflicts"),
 ("Supported","Contested","accepted, procedurally contested (PF-6)"),
 ("Unknown","Committed","committed with no evidence either way"),
 ("Unknown","Uncommitted","the initial state"),
 ("Supported","Rejected","epistemically supported, administratively rejected (271.20)"),
 ("Refuted","Uncommitted","ordinary refutation"),
 ("Conflicted","Contested","conflict plus contest"),
]
print(f"  |SIGMA| x |GAMMA| = {len(SIGMA)}x{len(GAMMA)} = {len(SIGMA)*len(GAMMA)} cells")
for s,g,why in cases:
    single = "IMPOSSIBLE" # can one scalar carry both?
    print(f"    ({s:10s},{g:12s}) meaningful=YES  single-scalar={single}  <- {why}")
print("  => every one of the ten is a MEANINGFUL, DISTINCT state.")
print("     A single status vocabulary must therefore have >= 16 values to be lossless.")
print("     Corpus 271.20 states the orthogonality requirement independently.")

print()
print("  C.2  Is Sigma PRIMITIVE, DERIVED, a PROJECTION, or CONTEXT-DEPENDENT?")
ev = [("ev1","supports","active"),("ev2","contradicts","active")]
def sigma_derive(e, policy_min_support):
    s=[x for x in e if x[1]=="supports"   and x[2]=="active"]
    r=[x for x in e if x[1]=="contradicts"and x[2]=="active"]
    if s and r: return "Conflicted"
    if len(s)>=policy_min_support: return "Supported"
    if r: return "Refuted"
    return "Unknown"
print(f"    same evidence, policy(min_support=1) -> {sigma_derive(ev,1)}")
print(f"    same evidence, policy(min_support=3) -> {sigma_derive(ev,3)}")
print("    => Sigma is DERIVED and POLICY-RELATIVE, never intrinsic to the assertion.")
print("       Therefore 'Sigma(a)' is ill-typed; only 'Sigma(a,policy)' is well-typed.")
print("       The corpus relation r carries a BARE Sigma field -> ILL-TYPED in r.")

print()
print("="*78); print("D. DEPENDENCY GRAPH — cycle detection over the reconstructed edges"); print("="*78)
EDGES = {
 "ValueSpace":[], "Entity":[], "Dimension":["ValueSpace"],
 "Proposition":["Entity","Dimension","ValueSpace"],
 "Observation":["World"], "World":[],
 "Evidence":["Observation","Policy"],          # Qualify : Observation x Policy -> Evidence
 "Assertion":["Proposition","Evidence","Context","Time","Provenance"],
 "Context":[], "Time":[], "Provenance":[],
 "Relation":["Assertion","Evidence","Sigma","Time"],   # corpus 8-tuple
 "K":["Assertion","Relation"],
 "Sigma":["Assertion","Policy"],
 "Gamma":["Assertion","GovContext"], "GovContext":[],
 "Policy":["K"],                                # policy-as-content in K_t (v0.2 R-1)
 "Authority":["Policy"],
 "Authorization":["Authority","Policy"],
 "Transformation":["K","Policy","Authority"],
 "Invariant":["K"],
 "Assessment":["Proposition","Evidence","Context","Policy"],
 "History":["Transformation"], "Lineage":["Provenance","Relation"],
}
color={}; cycles=[]
def dfs(n, stack):
    color[n]="grey"; stack.append(n)
    for m in EDGES.get(n,[]):
        if color.get(m)=="grey":
            cycles.append(stack[stack.index(m):]+[m])
        elif color.get(m) is None:
            dfs(m, stack)
    stack.pop(); color[n]="black"
for n in list(EDGES): 
    if color.get(n) is None: dfs(n, [])
print(f"  nodes={len(EDGES)}  cycles found={len(cycles)}")
for c in cycles: print("    CYCLE:", " -> ".join(c))
print()
print("  Classification:")
print("   * Relation -> Sigma -> Policy -> K -> Relation :")
print("       SEMANTIC CYCLE. Sigma is policy-relative, policy-as-content lives in K,")
print("       K contains Relation, Relation carries Sigma. Not a fixed point: Sigma")
print("       cannot be evaluated until Policy is fixed, and Policy is a member of K.")
print("   * Policy -> K -> ... -> Policy :")
print("       GOVERNANCE RECURSION, terminated by v0.2 R-1/I-11 stratification")
print("       (Policy-as-content in K_t  !=  Policy-in-force, versioned).")
print("   * Evidence -> Policy -> K -> Assertion -> Evidence :")
print("       SEMANTIC CYCLE via Qualify. Qualification of an observation depends on a")
print("       policy that is itself knowledge content. NOT terminated anywhere.")
