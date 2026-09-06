#!/usr/bin/env python3
"""
THEORY-CONSTRUCTION-TEST: build the smallest complete KnowledgeOS instance
STRICTLY from the canonical theory (CANONICAL-KNOWLEDGEOS-THEORY.md, as amended
by THEORY-CLOSURE-AUDIT.md).  Every point where a definition must be INVENTED is
recorded in INVENTED[] rather than silently supplied.
"""
import hashlib, json
INVENTED = []
def invent(tag, why): INVENTED.append((tag, why)); return None

# ---------- layer 0: (E, D, V_D, Time, Origin) --------------------------------
E1 = "svc:payments"
D_TLS = {"ID":"D.tls","Name":"tls_version","ValueSpace":{"1.2","1.3"},"Type":"nominal","Domain":"security"}
D_BKP = {"ID":"D.bkp","Name":"backup_freq","ValueSpace":{"hourly","daily","none"},"Type":"nominal","Domain":"ops"}

# ---------- Evidence (audit §7 amended, 9 fields) -----------------------------
def Ev(ref, source, observation, context, time, method, provenance, polarity, state):
    return dict(ref=ref, source=source, observation=observation, context=context,
                time=time, method=method, provenance=provenance,
                polarity=polarity, state=state)
e1 = Ev("ev:1","scan-tool","tls=1.3","prod","2026-08-01","tls-probe","chain:tool@host","supports","active")
e2 = Ev("ev:2","vendor-doc","tls=1.2","prod","2026-07-01","doc-read","chain:vendor","supports","active")

# ---------- Assertion = (id, P, e, c, t, Pi) ----------------------------------
def H(*parts):
    return hashlib.sha256(json.dumps(parts, sort_keys=True, default=str).encode()).hexdigest()[:12]
def A(P, e, c, t, Pi):
    return {"id":H(P,e,c,t,Pi), "P":P, "e":e, "c":c, "t":t, "Pi":Pi}
A1 = A(("svc:payments","D.tls","1.3"), (("ev:1","supports","active"),), "prod", ("2026-08-01", None), "origin:scan")
A2 = A(("svc:payments","D.tls","1.2"), (("ev:2","supports","active"),), "prod", ("2026-07-01", None), "origin:vendor")

# ---------- K = (A, R) --------------------------------------------------------
R = {(A1["id"], A2["id"], "contradicts")}
K0 = {"A": {A1["id"]:A1, A2["id"]:A2}, "R": R}

# ---------- REQUIRED CAPABILITY 1: UNKNOWN ------------------------------------
# theory: Unknown is irreducible; K = (A,R) has no Unknown carrier.
# An assertion whose V is unknown must still name a Value in V_D.
try:
    A_unknown = A(("svc:payments","D.bkp","?"), (), "prod", ("2026-08-01",None), "origin:none")
    ok = "?" in D_BKP["ValueSpace"]
except Exception as ex:
    ok = False
if not ok:
    invent("UNKNOWN-VALUE",
           "P=(E,D,V) requires V in V_D. '?' is not in V_D. Representing "
           "'value unknown' requires either widening every V_D with a bottom "
           "element or a new carrier. Neither is defined in the theory.")

# ---------- REQUIRED CAPABILITY 2: MISSINGNESS ('not asked') ------------------
# 'D.bkp was never assessed' is a statement about a DIMENSION, not an assertion.
invent("MISSINGNESS-NOT-ASSESSED",
       "K=(A,R) contains no set of RECOGNISED DIMENSIONS D_t. 'not assessed' and "
       "'assessed, nothing found' both render as: no assertion mentioning D.bkp. "
       "The two are provably indistinguishable in K.")

# ---------- REQUIRED CAPABILITY 3: UNCERTAINTY --------------------------------
invent("UNCERTAINTY",
       "Sigma:(dir,str) is ORDINAL and DERIVED, never stored, and is a function "
       "of (Assertion,Policy). There is no field on Assertion or Evidence able to "
       "carry U(H)=(type,value,model,scope,source). Uncertainty of the CLAIM "
       "cannot be distinguished from strength of the EVIDENCE FOR it.")

# ---------- REQUIRED CAPABILITY 4: NON-IDENTIFIABILITY ------------------------
invent("NON-IDENTIFIABILITY",
       "Identifiable(g,Omega) quantifies over W1,W2 in a WORLD SPACE W with an "
       "observation function Omega:W->O. The canonical 8-layer ontology has no W "
       "and no Omega. The property is not statable, let alone decidable.")

# ---------- Policy ------------------------------------------------------------
Policy = {"id":"p.sec","version":3,
          "Gates":frozenset({"g.evidence_present","g.no_open_contradiction"}),
          "ValidityInterval":("2026-01-01",None), "ResolutionBehavior":"Block"}
def gate(g, d):
    if g=="g.evidence_present":       return len(d["e"])>0
    if g=="g.no_open_contradiction":  return not any(x[0]==d["id"] or x[1]==d["id"] for x in K0["R"])
    return None
def Apply(p, d):
    vals=[gate(g,d) for g in p["Gates"]]
    if any(v is False for v in vals): return False
    if any(v is None  for v in vals): return p["ResolutionBehavior"]
    return True

# ---------- Authorization: the corpus pipeline (audit §12) --------------------
# a_t = Sarathi(K,I,Z,L,Q,C,Policy) ; c_t = Authorize(N,a_t,Policy) ; e_t = Execute(c_t)
a_t = {"op":"commit", "target":A1["id"]}
invent("SARATHI-BODY", "Sarathi has a 7-ary signature and no body anywhere in the corpus.")
def Authorize(N, a, p):
    invent("AUTHORIZE-BODY",
           "Authorize:(N,A,Policy)->C is a SIGNATURE ONLY. No body exists in the "
           "corpus. Codomain declared C (commands) but the outcome enum includes "
           "Rejected/Deferred/Modified, which are not commands: the declared type "
           "is wrong and the partiality is unacknowledged.")
    invent("KNOWER-SPACE-N",
           "N (the Knower) is never defined. Its space, identity criterion and "
           "equality are absent, so 'the same authority act' is not expressible.")
    return {"cmd":a["op"], "target":a["target"], "by":N, "policyVersion":p["version"]}
c_t = Authorize("N:architect", a_t, Policy)
def Execute(c): return {"event":"Committed", "target":c["target"], "at":"2026-08-02"}
e_t = Execute(c_t)

# ---------- delta -------------------------------------------------------------
def Pre(K, ev): return ev["target"] in K["A"]
def delta(K, ev):
    invent("DELTA-COMMIT-SEMANTICS",
           "delta(K,e) must place the target in a COMMITTED governance status. "
           "Gamma is typed Assertion x GovCtx -> {...} but is DERIVED and NOT a "
           "component of K, so delta has nowhere in K to write the result. The "
           "state transition for the system's central act is undefined.")
    return K
K1 = delta(K0, e_t) if Pre(K0, e_t) else K0

# ---------- History / Lineage -------------------------------------------------
History = [e_t]
Lineage = "Pi o R_der*"   # no derivation edges in this instance

# ---------- Assessment / verdict ---------------------------------------------
def Sigma(a, p):
    sup=[x for x in a["e"] if x[1]=="supports" and x[2]=="active"]
    return ("Supporting","Weak") if sup else ("Neutral","None")
verdict = {A1["id"]:Apply(Policy,A1), A2["id"]:Apply(Policy,A2)}

# ---------- report ------------------------------------------------------------
print("="*78); print("SMALLEST COMPLETE INSTANCE — CONSTRUCTION RESULT"); print("="*78)
print(f"K0: |A|={len(K0['A'])}  |R|={len(K0['R'])}   R={sorted(K0['R'])}")
print(f"Sigma(A1,p)={Sigma(A1,Policy)}   Sigma(A2,p)={Sigma(A2,Policy)}")
print(f"verdict (Apply) = {verdict}")
print(f"c_t = {c_t}")
print(f"e_t = {e_t}")
print(f"K1 is K0 : {K1 is K0}   |History|={len(History)}")
print()
print(f"OBJECTS THAT COULD NOT BE CONSTRUCTED WITHOUT INVENTING A DEFINITION: {len(INVENTED)}")
for i,(tag,why) in enumerate(INVENTED,1):
    print(f"\n  [{i}] {tag}\n      {why}")
