import sys; sys.path.insert(0,'.')
from kosmodel import *
from repairs import RepairB, is_orphan
D=Dimension("Version",("3.68","3.69","3.70"),"ordinal")
def A(pol=None,n=0,V="3.69"):
    evs=frozenset() if not n else frozenset({Evidence(Observation(f"s{i}","2026-01-0%d"%(i+1),"x"),pol,"active",f"s{i}","d",f"s{i}") for i in range(n)})
    return Assertion(Prop("Nexus",D,V),evs,"prod",Iv("2026-01-01",None),"api")
MIX=Assertion(Prop("Nexus",D,"3.69"),frozenset({
    Evidence(Observation("a","2026-01-01","x"),"supports","active","a","d","a"),
    Evidence(Observation("b","2026-01-02","y"),"contradicts","active","b","d","b")}),"prod",Iv("2026-01-01",None),"a")
r=RepairB(); pk=("Nexus","Version","3.69")
print("="*96); print("DISTINGUISHABILITY — seven mandatory states under Repair B"); print("="*96)
obs={}
obs["M1 NotAsked"]        = r.state(pk,None)
r.ask(pk)
obs["M2 Asked+Absent"]    = r.state(pk,None)
obs["M3 Asked+Unknown"]   = r.state(pk,A(n=0))
obs["M4 Supported"]       = r.state(pk,A("supports",1))
obs["M5 Refuted"]         = r.state(pk,A("contradicts",1))
obs["M6 Conflicted"]      = r.state(pk,MIX)
sup=A("supports",1); k=EMPTY
k,_=delta(k,"assert",sup); other=A("supports",1,"3.70"); k,_=delta(k,"assert",other)
obs["M7 Orphan"]          = ("STRUCTURAL", f"is_orphan={is_orphan(k,sup)}, epistemic state unchanged={r.state(pk,sup)}")
for s,v in obs.items(): print(f"  {s:<22} -> {v}")
vals=[v for k_,v in obs.items() if not k_.startswith("M7")]
print(f"\n  distinct epistemic tuples M1-M6: {len(set(vals))}/6  -> {'ALL DISTINCT' if len(set(vals))==6 else 'COLLISION'}")
k_orph,_=delta(EMPTY,"assert",sup)
k_link,_=delta(k_orph,"assert",other); k_link,_=delta(k_link,"relate",(sup.id,other.id,"refines"))
print(f"  M7 orphan is ORTHOGONAL: same epistemic state, is_orphan {is_orphan(k_orph,sup)} vs {is_orphan(k_link,sup)}")
print(f"     => M7 varies while M4 is held fixed  -> M7 distinguishable, and NOT an epistemic value.")
allok = len(set(vals))==6 and is_orphan(k_orph,sup) and not is_orphan(k_link,sup)
print(f"\n  RESULT: M1-M7 all distinguishable = {allok}")
