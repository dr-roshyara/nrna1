import sys; sys.path.insert(0,'.')
from kos279 import *
from repairs import RepairB
print("="*98); print("AFFECTED FALSIFICATION TESTS RE-RUN (F1,F3,F5,F6,F10) under Repair B"); print("="*98)
def mk(rid,res): return Rule(rid,lambda ctx,res=res: Verdict(res))
POL=Policy("P","v1",(mk("r1",PASS),),(),"HPA",("2026-01-01","2026-06-01"),frozenset({"prod"}))
store=PolicyStore(); store.activate(POL)
Q=RepairB(); res=[]
def t(fid,why,expected,observed,ok):
    res.append((fid,"PASS" if ok else "FAIL",why,observed))
# F1 — policy-free must NOT read as absence-of-inquiry
r=store.PolicyAt("2027-01-01"); asked=("policy","applicable","2027-01-01")
Q.ask(asked); inq=Q.state(asked,None)
t("F1","policy-free must not be interpreted as absence",
  "UNKNOWN(NoApplicablePolicy) AND inquiry recorded as Asked",
  f"{r} ; inquiry={inq}", r.value==UNKNOWN and r.reason=="NoApplicablePolicy" and inq==("Asked","Absent"))
# F3 — policy conflict must NOT collapse into unknown
c=Combine([Verdict(PASS),Verdict(CONFLICT,"incompatible")])
t("F3","conflict must not collapse into unknown","CONFLICT, not UNKNOWN",f"{c}",c.value==CONFLICT and c.value!=UNKNOWN)
# F5 — missing policy distinct from no inquiry
empty=PolicyStore(); r5=empty.PolicyAt("2026-03-01")
notasked=("policy","applicable","never")
t("F5","missing policy != no inquiry","UNKNOWN(missing) distinct from NotAsked",
  f"missing={r5}; never-asked={Q.state(notasked,None)}",
  r5.value==UNKNOWN and Q.state(notasked,None)==("NotAsked","-") and Q.state(asked,None)!=Q.state(notasked,None))
# F6 — expired policy must not be represented as nonexistent
exp_applicable=Applicable(POL,"2026-07-01"); still_in_history=POL.PID in [p.PID for p in store.history()]
t("F6","expired != nonexistent","not applicable BUT still retrievable",
  f"Applicable(t>t_e)={exp_applicable}; in history={still_in_history}",
  (not exp_applicable) and still_in_history)
# F10 — historical replay must preserve historical state
s2=PolicyStore()
p1=Policy("PH","v1",(mk("r1",PASS),),(),"HPA",("2026-01-01","2026-06-01"),frozenset({"prod"}))
p2=Policy("PH","v2",(mk("r1",DENY),),(),"HPA",("2026-06-01",None),frozenset({"prod"}))
s2.activate(p1); s2.activate(p2)
old=s2.get("2026-03-01"); new=s2.get("2026-08-01")
t("F10","replay must preserve historical state","PolicyAt(t_old)=v1",
  f"old={old.version}; new={new.version}", old.version=="v1" and new.version=="v2")
print(f"{'ID':<6}{'VERDICT':<9}{'WHY AFFECTED':<44}OBSERVED")
for f,v,w,o in res: print(f"{f:<6}{v:<9}{w:<44}{o[:46]}")
print(f"\n  {sum(1 for _,v,_,_ in res if v=='PASS')}/5 PASS")
print("\n  NOTE: F1 and F5 are the two that genuinely EXERCISE the repair —")
print("        'no applicable policy' (asked, absent) is now distinct from 'never enquired' (not asked).")
print("        Before the repair these were the same observation.")
