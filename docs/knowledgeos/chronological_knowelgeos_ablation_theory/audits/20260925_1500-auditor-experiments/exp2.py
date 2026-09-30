import sys, os, json, importlib
LANE="/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/nrna1/docs/knowledgeos/chronological_knowelgeos_ablation_theory"
sys.path.insert(0, LANE+"/tests"); sys.path.insert(0, LANE+"/scripts")
import test_f_pipeline as T
t=T.T("test_full_happy_path_requires_independent_audit_then_passes"); t.setUp()
R, run = t._f9002_read()
tr=lambda *a:(lambda r:(r.returncode,(r.stderr or r.stdout).strip()[-200:]))(R.run("f_transition.py",*a))
R.run("f_units.py","--run",run,"F9002")
import f_checks; importlib.reload(f_checks)
units=R.C.read_jsonl(R.ledger("F9002","UNITS.jsonl"))
print([(u["unit_id"],u["kind"],u["definition_cue"]) for u in units])
nm=[u["unit_id"] for u in units if u["kind"]!="MARKUP"]
R.write_jsonl("F9002","CONTENT-INVENTORY.jsonl",[{"item_id":"FCI-F9002-0001","category":"CONCEPT","covers_units":nm,"page":1,"verbatim_quote":"is","content":"x"}])
R.write_jsonl("F9002","UNIT-DISPOSITIONS.jsonl",[])
json.dump({c:{"count":(1 if c=="CONCEPT" else 0),"checked":"x"} for c in f_checks.INVENTORY_CATEGORIES},open(R.ledger("F9002","CATEGORY-CHECK.json"),"w"))
print("thin catch-all over", nm, "(math + definition cue included), quote 'is', no DEFINITION/FORMULA item:", tr("F9002","CONTENT-EXTRACTED","--run",run))
# S-label / S-id inside L1 inventory and contributions
inv=R.C.read_jsonl(R.ledger("F9002","CONTENT-INVENTORY.jsonl")); inv[0]["content"]="as established in S0472, this is the Kernel Invariant K-7 of the S-Series"
R.write_jsonl("F9002","CONTENT-INVENTORY.jsonl",inv)
R.phase1(fid="F9002", anchor="short second file with one quotable sentence")
c=R.C.read_jsonl(R.ledger("F9002","contributions.jsonl")); c[0]["labels"]=["S0472-kernel-invariant"]; c[0]["statement"]="S0472 says so"
R.write_jsonl("F9002","contributions.jsonl",c)
print("S-id in L1 inventory content + contribution label/statement:", tr("F9002","RECONSTRUCTED","--run",run))
t.tearDown()
