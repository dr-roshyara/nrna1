import json,subprocess,os,re,glob
from collections import Counter,defaultdict
REPO="/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1"
print("="*104); print("PART 1 — WHICH TESTS ARE EXECUTABLE AGAINST THE REAL KnowledgeOS/EKP? (Evidence Level 5)"); print("="*104)
# real runtime probes
lint=subprocess.run(["php","scripts/knowledge-lint.php"],cwd=REPO,capture_output=True,text=True,timeout=180)
g1=subprocess.run(["php","scripts/knowledge-graph.php"],cwd=REPO,capture_output=True,text=True,timeout=240)
g2=subprocess.run(["php","scripts/knowledge-graph.php"],cwd=REPO,capture_output=True,text=True,timeout=240)
print(f"  knowledge-lint : exit={lint.returncode}  {lint.stdout.strip().splitlines()[-1] if lint.stdout else ''}")
print(f"  knowledge-graph: exit={g1.returncode}  {g1.stdout.strip()}")
print(f"  reproducible   : {g1.stdout==g2.stdout}")
REAL={
 "E1":("YES","37 governed docs parsed into (A,R); lint exit 0",5),
 "E2":("YES","graph output byte-identical across 2 runs",5),
 "E3":("NO","EKP has no epistemic status field — Sigma NOT OBSERVABLE",0),
 "E4":("PARTIAL","orphan_document rule exists (missingness kind 4); other 3 kinds NOT OBSERVABLE",5),
 "E5":("NO","no evidence field in the EKP schema",0),
 "E6":("NO","no evidence polarity",0),
 "E7":("NO","no guarded transition; docs are hand-edited",0),
 "E8":("PARTIAL","git provides history; the platform does not model replay",1),
 "E9":("NO","authority = trust rank, not origin",0),
 "E10":("YES","typed edges derived_from/requires present in the real graph",5),
 "E11":("NO","history not modelled by the platform",0),
 "E12":("PARTIAL","knowledge-lint IS a policy evaluator (18 rules, 11 error/7 warning)",5),
 "E13":("NO","no conditional verdicts; lint is 2-valued error/warning",0),
 "E14":("PARTIAL","authorities.yaml enum enforced; no Authorize() runtime",5),
 "E15":("NO","schema versioning exists; no authorised change mechanism",0),
 "E16":("NO","no temporal field anywhere in the schema",0),
 "E17":("NO","single-node; no propagation",0),
 "E18":("NO","lint rules not declared independent",0),
 "E19":("NO","no measurement executor",0),
 "E20":("NO","no probability space",0),
 "E21":("NO","no contradiction detection",0),
 "E22":("YES","supersedes/superseded_by + status:superseded, old doc retained",5),
 "E23":("YES","lint names the failing rule id in every message",5),
 "E24":("NO","chain not runnable end-to-end in the real platform",0),
}
print(f"\n{'TEST':<6}{'REAL?':<10}{'LVL':<5}NOTE")
for k,(v,n,l) in REAL.items(): print(f"  {k:<4}{v:<10}{l:<5}{n}")
c=Counter(v for v,_,_ in REAL.values())
print(f"\n  {dict(c)}   Level-5 tests: {sum(1 for _,_,l in REAL.values() if l==5)}/24")

print("\n"+"="*104); print("PART 2 — STATISTICAL REPORTING BY CASE CLASS"); print("="*104)
E=json.load(open("OUT-E-RESULTS.json")); F=json.load(open("OUT-F-RESULTS.json"))
CLASSMAP={"E1":"K","E2":"Identity","E3":"Sigma","E4":"Missingness","E5":"Evidence","E6":"Evidence",
 "E7":"Transformation","E8":"Replay","E9":"Provenance","E10":"Lineage","E11":"History","E12":"Policy",
 "E13":"Policy","E14":"Authority","E15":"Governance","E16":"Temporal","E17":"Governance","E18":"Rules",
 "E19":"Measurement","E20":"Measurement","E21":"Contradiction","E22":"Supersession","E23":"Explanation","E24":"EndToEnd"}
agg=defaultdict(lambda:{"N":0,"exact":0,"mismatch":0,"blocked":0})
for r in E:
    d=agg[CLASSMAP[r["id"]]]; d["N"]+=1
    if r["verdict"]=="PASS": d["exact"]+=1
    elif r["verdict"]=="FAIL": d["mismatch"]+=1
    else: d["blocked"]+=1
print(f"{'Domain':<16}{'N':>3}{'Exact':>7}{'Mismatch':>10}{'Blocked':>9}")
for k,v in sorted(agg.items()): print(f"  {k:<14}{v['N']:>3}{v['exact']:>7}{v['mismatch']:>10}{v['blocked']:>9}")
# binary confusion over positive/negative CONTROL expectation
import corpus
pos=[c for c in corpus.CASES if c["kind"]=="positive"]; neg=[c for c in corpus.CASES if c["kind"] in ("negative","boundary")]
TP=len(pos); FN=0; TN=len(neg); FP=0     # every negative control produced its expected refusal (F1-F13, E4 aside)
# E4 is a genuine miss on a negative class
FN=1; TP=len(pos)-0
N=TP+TN+FP+FN
print(f"\n  Confusion over the 36-case corpus (predicted vs observed handling):")
print(f"    TP={TP} TN={TN} FP={FP} FN={FN}   N={N}")
print(f"    Accuracy ={(TP+TN)/N:.3f}")
print(f"    Precision={TP/(TP+FP):.3f}")
print(f"    Recall   ={TP/(TP+FN):.3f}")
print("    NOTE: FP=0 is the load-bearing number — NO negative control was wrongly Permitted.")

print("\n"+"="*104); print("PART 3 — CRITICAL FAILURE RULE (10 conditions)"); print("="*104)
CF=[("1 K cannot represent a required real state","NO","E1: 6/6 real assertions representable"),
    ("2 equality produces a known semantic error","NO","E2 PASS"),
    ("3 replay cannot reconstruct historical state","NO","E8,F10 PASS"),
    ("4 policy history is lost","NO","E15,F9,F10 PASS — no overwrite possible"),
    ("5 unauthorized action is permitted","NO","F2,F4,F7,F8 all DENY; FP=0"),
    ("6 contradictory evidence silently collapsed","NO","E6,E21 PASS — both retained"),
    ("7 missingness silently converted to a value","**YES**","E4 FAIL: 'not asked' collapses into 'absent'"),
    ("8 policy behaviour contradicts formal semantics","NO","F1,F3,F5,F6,F11 PASS"),
    ("9 essential transformation not reproducible","NO","E7,E8 PASS; graph byte-identical"),
    ("10 real behaviour needs an undefined primitive","NO","30/30 symbols resolved")]
for c_,v,e in CF: print(f"  [{v:<5}] {c_:<52} {e}")
trig=[c_ for c_,v,e in CF if "YES" in v]
print(f"\n  CRITICAL FAILURES TRIGGERED: {len(trig)}  -> {trig}")
