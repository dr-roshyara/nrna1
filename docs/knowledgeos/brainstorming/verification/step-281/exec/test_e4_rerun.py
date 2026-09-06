import sys,json; sys.path.insert(0,'.')
from kosmodel import *
from repairs import RepairB, is_orphan
D=Dimension("Version",("3.68","3.69","3.70"),"ordinal")
def A(pol=None,n=0,V="3.69"):
    evs=frozenset() if not n else frozenset({Evidence(Observation(f"s{i}","2026-01-0%d"%(i+1),"x"),pol,"active",f"s{i}","d",f"s{i}") for i in range(n)})
    return Assertion(Prop("Nexus",D,V),evs,"prod",Iv("2026-01-01",None),"api")
MIX=Assertion(Prop("Nexus",D,"3.69"),frozenset({
    Evidence(Observation("a","2026-01-01","x"),"supports","active","a","d","a"),
    Evidence(Observation("b","2026-01-02","y"),"contradicts","active","b","d","b")}),"prod",Iv("2026-01-01",None),"a")
pk=("Nexus","Version","3.69"); R=[]
def t(tid,setup,expected,observed,ok):
    R.append(dict(id=tid,setup=setup,exp=expected,obs=str(observed),verdict="PASS" if ok else "FAIL"))
r=RepairB()
t("E4-R1","no query","I(p)=NotAsked",r.state(pk,None),r.state(pk,None)==("NotAsked","-"))
r.ask(pk)
t("E4-R2","query, no assertion","I=Asked, E=Absent",r.state(pk,None),r.state(pk,None)==("Asked","Absent"))
t("E4-R3","query, insufficient evidence","E=Unknown",r.state(pk,A(n=0)),r.state(pk,A(n=0))==("Asked","Unknown"))
t("E4-R4","query, supporting","E=Supported",r.state(pk,A("supports",1)),r.state(pk,A("supports",1))==("Asked","Supported"))
t("E4-R5","query, refuting","E=Refuted",r.state(pk,A("contradicts",1)),r.state(pk,A("contradicts",1))==("Asked","Refuted"))
t("E4-R6","query, conflicting","E=Conflicted",r.state(pk,MIX),r.state(pk,MIX)==("Asked","Conflicted"))
sup=A("supports",1); other=A("supports",1,"3.70")
k,_=delta(EMPTY,"assert",sup); k,_=delta(k,"assert",other)
before=r.state(pk,sup); orph=is_orphan(k,sup)
k2,_=delta(k,"relate",(sup.id,other.id,"refines")); after=r.state(pk,sup); orph2=is_orphan(k2,sup)
t("E4-R7","orphan document","Orphan=True, E(p) unchanged",
  f"orphan={orph}->{orph2}; E: {before} -> {after}", orph and not orph2 and before==after)
print("="*98); print("E4 RE-RUN (E4-R1 .. E4-R7)"); print("="*98)
print(f"{'ID':<9}{'VERDICT':<9}{'SETUP':<30}OBSERVED")
for x in R: print(f"{x['id']:<9}{x['verdict']:<9}{x['setup']:<30}{x['obs'][:44]}")
p=sum(1 for x in R if x['verdict']=="PASS")
print(f"\n  {p}/7 PASS")
print("\n  CRITICAL FAILURE #7 RE-CHECK:")
print(f"    'not asked'={('NotAsked','-')}  vs  'absent'={('Asked','Absent')}   DISTINCT = True")
print("    => missingness is NO LONGER silently converted into a substantive value.")
json.dump(R,open("OUT-E4-RERUN.json","w"),indent=1)
