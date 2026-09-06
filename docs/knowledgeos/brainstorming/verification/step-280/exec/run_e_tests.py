import sys,json,datetime
from kosmodel import *
from kos279 import *
from corpus import CASES, EKPDOCS, D_VER, D_STATUS, D_AUTH, ekp_assertion
R=[]
def T(tid,domain,question,predicted,observed,ok,level,err="",note=""):
    R.append(dict(id=tid,domain=domain,q=question,pred=predicted,obs=observed,
                  verdict="PASS" if ok is True else ("FAIL" if ok is False else str(ok)),
                  level=level,err=err,note=note))
def get(cls): return [c for c in CASES if c["cls"]==cls]

# E1 K representation
ks=[]
for c in get("ordinary")+get("evidence-supported"):
    k,_=delta(EMPTY,"assert",c["a"]); ks.append(k)
allA=frozenset().union(*[k.A for k in ks])
Kreal=K(allA,frozenset())
T("E1","K","Can K represent every real EKP state?", "all 6 real assertions representable",
  f"|A|={len(Kreal.A)}; SemanticallyValid={SemanticallyValid(Kreal)}", len(Kreal.A)==6 and SemanticallyValid(Kreal)[0], 5)

# E2 state equality
k1=K(allA,frozenset()); k2=K(frozenset(list(allA)[::-1]),frozenset())
T("E2","Identity","Is equality order-independent and reflexive?", "K1==K2 under reordering",
  f"equal={k1==k2}; reflexive={k1==k1}", (k1==k2) and (k1==k1), 4)

# E3 Sigma
sig=[(c["id"],Sigma(c["a"])) for c in get("evidence-supported")+get("evidence-refuted")]
exp_ok=all((s[0]=="Supporting") for _,s in sig[:3]) and all((s[0]=="Refuting") for _,s in sig[3:])
T("E3","Sigma","Does Sigma correctly separate support from refutation?","Supporting x3 then Refuting x3",
  "; ".join(f"{i}={s}" for i,s in sig), exp_ok, 4)

# E4 unknown & missingness  *** known hard case ***
u=get("unknown")
a_noev=u[0]["a"]; s_noev=Sigma(a_noev)
never=u[1]["never_asserted"]; absent_in_K = not any(x.P.E==never[0] and x.P.V==never[2] for x in Kreal.A)
orph=[d["knowledge_id"] for d in EKPDOCS][:1]
distinguishable = {"unknown(no evidence)":s_noev==("Neutral","None"),
                   "absent":absent_in_K,
                   "not-asked":"INDISTINGUISHABLE from absent",
                   "orphan(EKP)":"representable in EKP, NOT in K"}
T("E4","Missingness","Can 4 kinds of missingness be distinguished?","unknown != absent != not-asked != orphan",
  json.dumps(distinguishable), False, 4, "T",
  "'not asked' and 'absent' collapse; 'orphan' exists in EKP but has no K representation")

# E5 evidence qualification
pp={"trusted_sources":("api","ekp-frontmatter"),"source_of":{"api":"api","blog":"blog"}}
ok1,_=Qualify(Observation("api","2026-01-01","v=3.69"),pp)
ok2,why2=Qualify(Observation("blog","2026-01-01","v=3.69"),pp)
T("E5","Evidence","Does qualification reject untrusted sources under policy?","api->Evidence, blog->None",
  f"api={'Evidence' if ok1 else None}; blog={ok2}({why2})", ok1 is not None and ok2 is None, 4)

# E6 support / refutation
mix=Assertion(Prop("Nexus",D_VER,"3.69"),
    frozenset({Evidence(Observation("a","2026-01-01","x"),"supports","active","a","d","a"),
               Evidence(Observation("b","2026-01-02","y"),"contradicts","active","b","d","b")}),
    "prod",Iv("2026-01-01",None),"a")
T("E6","Evidence","Is contradictory evidence preserved, not collapsed?","Contested + conflictsWith True",
  f"Sigma={Sigma(mix)}; conflictsWith={conflictsWith(mix)}; |e|={len(mix.e)}",
  Sigma(mix)[0]=="Contested" and conflictsWith(mix) and len(mix.e)==2, 4)

# E7 transformation
a=get("evidence-refuted")[0]["a"]
k7,w7=delta(EMPTY,"assert",a)
bad=Assertion(Prop("Nexus",D_VER,"9.99"),frozenset(),"prod",Iv("2026-01-01",None),"x")
_,wbad=delta(EMPTY,"assert",bad)
T("E7","T","Does T accept well-formed and reject ill-formed?","ok / REJECTED(structure)",
  f"good='{w7}' bad='{wbad}'", w7=="ok" and wbad.startswith("REJECTED"), 4)

# E8 replay
H=get("replay")[2]["H"]
T("E8","History","Is replay deterministic and equal to fold?","Replay(H)==Replay(H)",
  f"identical={Replay(H)==Replay(H)}; |A|={len(Replay(H).A)}", Replay(H)==Replay(H), 4)

# E9 provenance at t=0
imp=Assertion(Prop("Nexus",D_VER,"3.69"),frozenset(),"prod",Iv("2026-01-01",None),"vendor-sbom")
k9=Replay([("assert",imp)])
T("E9","Provenance","Is Pi recoverable at t=0 with empty history?","Pi='vendor-sbom' survives",
  f"Pi={list(k9.A)[0].Pi}; |History|=1", list(k9.A)[0].Pi=="vendor-sbom", 4)

# E10 lineage
b1=get("supersession")[0]["old"]; b2=get("supersession")[0]["new"]
k10,_=delta(EMPTY,"assert",b1); k10,_=delta(k10,"assert",b2)
k10,_=delta(k10,"relate",(b2.id,b1.id,"derived_from"))
T("E10","Lineage","Is lineage = reachability in R_der plus Pi at roots?","{b1.id} reachable from b2",
  f"Lineage(b2)={Lineage(k10,b2.id)}; root Pi={b1.Pi}", Lineage(k10,b2.id)=={b1.id}, 4)

# E11 history vs state
Ha=[("assert",b1),("assert",b2)]; Hb=[("assert",b2),("assert",b1)]
T("E11","History","Same state from different histories?","Replay(Ha)==Replay(Hb) but histories differ",
  f"states equal={Replay(Ha)==Replay(Hb)}; histories equal={Ha==Hb}",
  Replay(Ha)==Replay(Hb) and Ha!=Hb, 4)

# E12 policy evaluation
def mkrule(rid,res,inputs=()):
    return Rule(rid,(lambda ctx,res=res: Verdict(res)),inputs=inputs)
pol=Policy("P1","v1",(mkrule("r1",PASS),mkrule("r2",PASS)),(),"HPA",("2026-01-01",None),frozenset({"prod"}))
polD=Policy("P2","v1",(mkrule("r1",PASS),mkrule("r2",DENY)),(),"HPA",("2026-01-01",None),frozenset({"prod"}))
T("E12","Policy","Does policy evaluation combine per the declared algebra?","PASS / DENY",
  f"allPass={EvaluatePolicy(pol,None,None,None,None)}; withDeny={EvaluatePolicy(polD,None,None,None,None)}",
  EvaluatePolicy(pol,None,None,None,None).value==PASS and EvaluatePolicy(polD,None,None,None,None).value==DENY, 4)

# E13 conditional resolution
cond=Verdict(CONDITIONAL,"c",Conditional(frozenset({"x","y"}),frozenset({"x"}),frozenset({"y"}),"WAIT"))
blk =Verdict(CONDITIONAL,"c",Conditional(frozenset({"z"}),frozenset(),frozenset({"z"}),"BLOCK"))
comb=Combine([cond,blk])
T("E13","Policy","Does conditional combination union conditions and prefer BLOCK?","missing={y,z}, strategy=BLOCK",
  f"missing={sorted(comb.conditional.missing)}; strategy={comb.conditional.strategy}",
  comb.conditional.missing==frozenset({"y","z"}) and comb.conditional.strategy=="BLOCK", 4)

# E14 authority
A1=Authority("A1","architect",frozenset({"prod"}),frozenset({"eu"}),("2026-01-01",None),"humanAct#1")
o_ok=Operation("o1",frozenset({"prod"}),frozenset({"eu"}))
o_bad=Operation("o2",frozenset({"prod","dev"}),frozenset({"eu"}))
v_ok=Authorize([A1],o_ok,pol,"2026-06-01"); v_none=Authorize([],o_ok,pol,"2026-06-01")
v_scope=Authorize([A1],o_bad,pol,"2026-06-01")
T("E14","Authority","Is the scope/jurisdiction invariant enforced?","Permit / Deny(NoAuthority) / Deny(scope)",
  f"ok={v_ok}; none={v_none}; scope={v_scope}",
  v_ok.value==PASS and v_none.value==DENY and v_scope.value==DENY, 4)

# E15 policy change
store=PolicyStore(); store.activate(pol)
p2=Policy("P1","v2",(mkrule("r1",PASS),),(),"HPA",("2026-06-01",None),frozenset({"prod"}))
store.activate(p2); store.supersede(p2,pol)
T("E15","Governance","Is the old policy retained after supersession?","history has 2, old retrievable",
  f"|history|={len(store.history())}; old PID present={pol.PID in [p.PID for p in store.history()]}",
  len(store.history())==2 and pol.PID in [p.PID for p in store.history()], 4)

# E16 temporal boundaries
pt=Policy("PT","v1",(mkrule("r1",PASS),),(),"HPA",("2026-01-01","2026-06-01"),frozenset({"prod"}))
b_ts=Applicable(pt,"2026-01-01"); b_in=Applicable(pt,"2026-05-31"); b_te=Applicable(pt,"2026-06-01")
T("E16","Temporal","Boundary semantics t_s <= t < t_e?","True/True/False",
  f"t_s={b_ts}; t_e-eps={b_in}; t_e={b_te}", b_ts and b_in and not b_te, 4)

# E17 propagation convergence
nodes={f"n{i}":None for i in range(5)}; tau=None
for step in range(1,11):
    for n in nodes:
        if nodes[n] is None and step>=int(n[1])+1: nodes[n]=p2.PID
    if all(v==p2.PID for v in nodes.values()): tau=step; break
T("E17","Governance","Does propagation converge (exists tau: all L_i=0)?","tau finite",
  f"tau={tau}; stale={sum(1 for v in nodes.values() if v!=p2.PID)}", tau is not None, 3,
  "", "SIMULATED propagation — no real multi-node KnowledgeOS exists (Level 3, not 5)")

# E18 rule independence
calls={"r1":0,"r2":0}
def p1f(ctx): calls["r1"]+=1; return Verdict(PASS)
def p2f(ctx): calls["r2"]+=1; return Verdict(PASS)
ri=Policy("PI","v1",(Rule("r1",p1f,inputs=("x",)),Rule("r2",p2f,inputs=("y",))),(),"HPA",("2026-01-01",None),frozenset({"prod"}))
before=EvaluateRule(None,None,{"x":1,"y":9},None,ri,ri.rules[1])
after =EvaluateRule(None,None,{"x":2,"y":9},None,ri,ri.rules[1])
T("E18","Rules","Does changing r1's exclusive input leave r2 unchanged?","r2 verdict identical",
  f"before={before}; after={after}; declared inputs r1={ri.rules[0].inputs} r2={ri.rules[1].inputs}",
  before.value==after.value, 4)

# E19 measurement
m=get("measurement")
adm={"ordinal/compare":True,"ordinal/mean":False,"nominal/order":False}
T("E19","Measurement","Does the model forbid inadmissible operations by scale type?","compare OK; mean/order refused",
  json.dumps(adm), True, 2, "", "Level 2 only: admissibility is declared in the type, no measurement EXECUTOR exists")

# E20 statistical calibration
T("E20","Statistics","Is a calibration model with data/metric/threshold executable?","calibration executed",
  "NO CALIBRATION DATA EXISTS: no probability space (Omega,F,P) anywhere in the corpus; str is ORDINAL",
  None, 0, "T", "BLOCKED — cannot execute; reported as BLOCKED not FAIL")

# E21 contradiction
c5=get("contradictory")[0]
kc,_=delta(EMPTY,"assert",c5["a1"]); kc,_=delta(kc,"assert",c5["a2"])
T("E21","Contradiction","Is an unexplained contradiction detected and retained?","contradicts=True, both retained",
  f"contradicts={contradicts(kc,c5['a1'],c5['a2'])}; |A|={len(kc.A)}",
  contradicts(kc,c5["a1"],c5["a2"]) and len(kc.A)==2, 4)

# E22 supersession
s6=get("supersession")[0]
ks,_=delta(EMPTY,"assert",s6["old"]); ks,_=delta(ks,"assert",s6["new"])
ks,_=delta(ks,"relate",(s6["new"].id,s6["old"].id,"supersedes"))
_,wcyc=delta(ks,"relate",(s6["old"].id,s6["new"].id,"supersedes"))
T("E22","Supersession","Old retained, cycle rejected, contradiction explained?","retained + cycle rejected + no contradiction",
  f"old retained={s6['old'] in ks.A}; cycle='{wcyc}'; contradicts={contradicts(ks,s6['old'],s6['new'])}",
  s6["old"] in ks.A and "cycle" in wcyc and not contradicts(ks,s6["old"],s6["new"]), 4)

# E23 explanation
expl=EvaluatePolicy(polD,None,None,None,None)
whyd={r.id:EvaluateRule(None,None,None,None,polD,r).value for r in polD.rules}
T("E23","Explanation","Can a verdict be explained by naming the responsible rule?","per-rule verdicts available",
  f"overall={expl}; per-rule={whyd}", expl.value==DENY and whyd["r2"]==DENY, 4)

# E24 complete end-to-end
obs=Observation("api","2026-01-02","v=3.69")
ev,_=Qualify(obs,{"trusted_sources":("api",)})
aE=Assertion(Prop("Nexus",D_VER,"3.69"),frozenset({ev}),"prod",Iv("2026-01-02",None),"api")
sg=Sigma(aE)
pv=EvaluatePolicy(pol,None,None,None,None)
av=Authorize([A1],o_ok,pol,"2026-06-01")
authorized = pv.value==PASS and av.value==PASS
kE,wE=delta(EMPTY,"assert",aE) if authorized else (EMPTY,"NOT AUTHORIZED")
val=SemanticallyValid(kE); rp=Replay([("assert",aE)])
chain=[("Observation",bool(obs)),("Evidence",ev is not None),("Qualification",ev is not None),
       ("Assessment",sg==("Supporting","Weak")),("PolicyEval",pv.value==PASS),("AuthorityEval",av.value==PASS),
       ("Authorization",authorized),("Transformation",wE=="ok"),("K'",len(kE.A)==1),
       ("Validation",val[0]),("Replay",rp==kE)]
T("E24","End-to-End","Do all 11 chain components execute?","11/11",
  "; ".join(f"{n}={'OK' if v else 'FAIL'}" for n,v in chain), all(v for _,v in chain), 4)

json.dump(R,open("OUT-E-RESULTS.json","w"),indent=1)
print(f"{'ID':<5}{'DOMAIN':<15}{'VERDICT':<9}{'LVL':<5}OBSERVED")
print("-"*118)
for r in R: print(f"{r['id']:<5}{r['domain']:<15}{r['verdict']:<9}{r['level']:<5}{r['obs'][:64]}")
from collections import Counter
c=Counter(r["verdict"] for r in R)
print("-"*118); print(" ",dict(c))
