import json
from kosmodel import *
from kos279 import *
F=[]
def T(fid,desc,expected,observed,ok,level,note=""):
    F.append(dict(id=fid,desc=desc,exp=expected,obs=observed,
                  verdict="PASS" if ok is True else ("FAIL" if ok is False else "BLOCKED"),level=level,note=note))
def mk(rid,res): return Rule(rid,lambda ctx,res=res: Verdict(res))
POL=Policy("P","v1",(mk("r1",PASS),),(),"HPA",("2026-01-01","2026-06-01"),frozenset({"prod"}))
A1=Authority("A1","architect",frozenset({"prod"}),frozenset({"eu"}),("2026-01-01",None),"humanAct#1")
o=Operation("o1",frozenset({"prod"}),frozenset({"eu"}))
store=PolicyStore(); store.activate(POL)

# F1 policy-free transition
r=store.PolicyAt("2027-01-01")
T("F1","Policy-free transition","Unknown(NoApplicablePolicy)",f"{r}",r.value==UNKNOWN and r.reason=="NoApplicablePolicy",4)
# F2 authority-free
r=Authorize([],o,POL,"2026-03-01")
T("F2","Authority-free","Deny(NoAuthority)",f"{r}",r.value==DENY and r.reason=="NoAuthority",4)
# F3 policy conflict
r=Combine([Verdict(PASS),Verdict(CONFLICT,"incompatible")])
T("F3","Policy conflict","CONFLICT (no silent selection)",f"{r}",r.value==CONFLICT,4)
# F4 authority conflict
A2=Authority("A2","auditor",frozenset({"dev"}),frozenset({"eu"}),("2026-01-01",None),"humanAct#2")
r=Authorize([A1,A2],o,POL,"2026-03-01")
T("F4","Authority conflict","Deny unless precedence declared",f"{r}",r.value==DENY and r.reason=="AuthorityConflict",4)
r2=Authorize([A1,A2],o,POL,"2026-03-01",precedence={"A1":10,"A2":1})
T("F4b","Authority conflict w/ precedence","Resolved",f"{r2}",r2.value==PASS,4)
# F5 missing policy
empty=PolicyStore(); r=empty.PolicyAt("2026-03-01")
T("F5","Missing policy","Unknown, no implicit default",f"{r}",r.value==UNKNOWN,4)
# F6 expired policy
T("F6","Expired policy","Not applicable at t>=t_end",
  f"Applicable(t_e)={Applicable(POL,'2026-06-01')}; Applicable(t_e+1m)={Applicable(POL,'2026-07-01')}",
  not Applicable(POL,"2026-06-01") and not Applicable(POL,"2026-07-01"),4)
# F7 revoked authority
AR=Authority("AR","tmp",frozenset({"prod"}),frozenset({"eu"}),("2026-01-01",None),"humanAct#3",revoked_at="2026-04-01")
hist=Authorize([AR],o,POL,"2026-02-01"); cur=Authorize([AR],o,POL,"2026-05-01")
T("F7","Revoked authority","historical reconstructable; current denies",
  f"at t0={hist}; at t1={cur}", hist.value==PASS and cur.value==DENY,4)
# F8 unauthorized policy mutation
try:
    POL.rules=(mk("rX",DENY),); mutated=True
except Exception as ex: mutated=False; exmsg=type(ex).__name__
T("F8","Unauthorized policy mutation","Deny; active policy unchanged",
  f"direct mutation raised={not mutated} ({exmsg if not mutated else '-'}); rules still={[r.id for r in store.history()[0].rules]}",
  (not mutated) and [r.id for r in store.history()[0].rules]==["r1"],4)
# F9 active policy integrity — verify STORED state
before=[p.PID for p in store.history()]
try: store.activate(Policy("P","v1",(mk("r1",PASS),),(),"HPA",("2026-01-01","2026-06-01"),frozenset({"prod"})))
except PolicyError as ex: dup=str(ex)
after=[p.PID for p in store.history()]
T("F9","Active policy integrity","duplicate version identity rejected; stored state unchanged",
  f"error='{dup}'; stored before={len(before)} after={len(after)}", before==after,4)
# F10 historical policy replay
s2=PolicyStore()
p1=Policy("PH","v1",(mk("r1",PASS),),(),"HPA",("2026-01-01","2026-06-01"),frozenset({"prod"}))
p2=Policy("PH","v2",(mk("r1",DENY),),(),"HPA",("2026-06-01",None),frozenset({"prod"}))
s2.activate(p1); s2.activate(p2); s2.supersede(p2,p1)
old=s2.get("2026-03-01"); new=s2.get("2026-08-01")
T("F10","Historical policy replay","PolicyAt(t_old)=v1, not v2",
  f"PolicyAt(2026-03)={old.version}; PolicyAt(2026-08)={new.version}; verdict_old={EvaluatePolicy(old,None,None,None,None)}",
  old.version=="v1" and new.version=="v2",4)
# F11 overlapping policy conflict
oa=Policy("OA","v1",(mk("r1",PASS),),(),"HPA",("2026-01-01","2026-12-01"),frozenset({"prod"}))
ob=Policy("OB","v1",(mk("r1",DENY),),(),"HPA",("2026-06-01","2027-01-01"),frozenset({"prod"}))
cls=ClassifyOverlap(oa,ob)
s3=PolicyStore(); s3.activate(oa); s3.activate(ob); r=s3.PolicyAt("2026-08-01")
T("F11","Overlapping policy conflict","CONFLICT or safe failure; never arbitrary Permit",
  f"classification={cls}; PolicyAt(overlap)={r}", cls=="CONFLICT" and r.value==CONFLICT,4)
# F12 governance propagation
nodes={f"n{i}":None for i in range(5)}; tau=None
for step in range(1,21):
    for i,n in enumerate(nodes):
        if step>i: nodes[n]=p2.PID
    if all(v==p2.PID for v in nodes.values()): tau=step; break
T("F12","Governance propagation","exists tau_c: all L_i=0",f"tau_c={tau}; stale=0",tau is not None,3,
  "SIMULATED — no real multi-node deployment exists. Level 3, NOT empirical Level 5.")
# F13 rule independence
seen={}
def r1p(ctx): seen["r1"]=ctx["C"]["x"]; return Verdict(PASS)
def r2p(ctx): seen["r2"]=ctx["C"]["y"]; return Verdict(PASS if ctx["C"]["y"]>0 else DENY)
RI=Policy("RI","v1",(Rule("r1",r1p,inputs=("x",)),Rule("r2",r2p,inputs=("y",))),(),"HPA",("2026-01-01",None),frozenset({"prod"}))
v_a=EvaluateRule(None,None,{"x":1,"y":5},None,RI,RI.rules[1])
v_b=EvaluateRule(None,None,{"x":999,"y":5},None,RI,RI.rules[1])
T("F13","Rule independence","r2 unchanged when r1's exclusive input changes",
  f"r2(x=1)={v_a}; r2(x=999)={v_b}", v_a.value==v_b.value,4)

json.dump(F,open("OUT-F-RESULTS.json","w"),indent=1)
print(f"{'ID':<6}{'VERDICT':<9}{'LVL':<5}{'DESCRIPTION':<34}OBSERVED")
print("-"*120)
for r in F: print(f"{r['id']:<6}{r['verdict']:<9}{r['level']:<5}{r['desc'][:32]:<34}{r['obs'][:52]}")
from collections import Counter
print("-"*120); print(" ",dict(Counter(r["verdict"] for r in F)))
print("  Level>=4 (substantive empirical):",sum(1 for r in F if r["level"]>=4),"/",len(F))
