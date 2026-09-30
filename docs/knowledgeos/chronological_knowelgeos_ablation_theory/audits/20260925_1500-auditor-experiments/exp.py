import sys, os, json, collections, importlib
LANE="/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/nrna1/docs/knowledgeos/chronological_knowelgeos_ablation_theory"
sys.path.insert(0, LANE+"/tests")
import test_f_pipeline as T
res = {}
def new():
    R = T.Root(); return R
def tr(R,*a):
    r = R.run("f_transition.py",*a); return r.returncode, (r.stderr or r.stdout).strip()[-300:]
def to_read_complete(R, fid="F9001", run="FR-F9001-001"):
    R.read_all(); tr(R,fid,"READING","--run",run); R.digests()
    assert tr(R,fid,"READ-COMPLETE","--run",run)[0]==0
def thin_inventory(R, fid="F9001", run="FR-F9001-001", quote="a"):
    assert R.run("f_units.py","--run",run,fid).returncode==0
    import f_checks; importlib.reload(f_checks)
    units=[u for u in R.C.read_jsonl(R.ledger(fid,"UNITS.jsonl")) if u["kind"]!="MARKUP"]
    it={"item_id":f"FCI-{fid}-0001","category":"CONCEPT","covers_units":[u["unit_id"] for u in units],"page":1,
        "verbatim_quote":quote,"content":"x"}
    R.write_jsonl(fid,"CONTENT-INVENTORY.jsonl",[it]); R.write_jsonl(fid,"UNIT-DISPOSITIONS.jsonl",[])
    cats={c:{"count":(1 if c=="CONCEPT" else 0),"checked":"x"} for c in f_checks.INVENTORY_CATEGORIES}
    json.dump(cats,open(R.ledger(fid,"CATEGORY-CHECK.json"),"w"))
    return len(units)

# E1 thin catch-all item passes CONTENT-EXTRACTED (fixture has definition-cue units on every line)
R=new(); to_read_complete(R); n=thin_inventory(R)
res["E1_thin_single_item_covering_all_%d_units_quote_'a'"%n]=tr(R,"F9001","CONTENT-EXTRACTED","--run","FR-F9001-001")
# E3 edit inventory after CONTENT-EXTRACTED; E2 catch-all contribution
inv=R.C.read_jsonl(R.ledger("F9001","CONTENT-INVENTORY.jsonl")); inv[0]["content"]="REWRITTEN AFTER GATE to fit a later hypothesis"
R.write_jsonl("F9001","CONTENT-INVENTORY.jsonl",inv)
R.phase1()
res["E2E3_catchall_contribution_after_post-gate_inventory_edit"]=tr(R,"F9001","RECONSTRUCTED","--run","FR-F9001-001")
ce=[e for e in R.C.events("F9001") if e["state"]=="CONTENT-EXTRACTED"][-1]
res["E3_CONTENT-EXTRACTED_event_evidence_keys"]=sorted(ce["evidence"].keys()), ce["evidence"].get("inventory")
# E4 L2 records: CORRECTNESS-FINDING with no reasoning; DOMAIN-INTERPRETATION carrying a KnowledgeOS design prescription
R.analyze()
recs=R.C.read_jsonl(R.ledger("F9001","ANALYSIS.jsonl"))
recs[0].update(kind="CORRECTNESS-FINDING",severity=None)
recs.append({"an_id":"FAN-F9001-002","level":2,"kind":"STRUCTURE","lens":"MATHEMATICAL","epistemic_class":"DOMAIN-INTERPRETATION",
 "statement":"KnowledgeOS should implement X as the aggregate root of its Kernel bounded context","inventory_refs":["FCI-F9001-0001"],
 "research_time":"t","run_id":"FR-F9001-001","model_id":"m"})
R.write_jsonl("F9001","ANALYSIS.jsonl",recs)
res["E4_correctness_finding_without_reasoning_plus_L3_prescription_as_L2"]=tr(R,"F9001","ANALYZED","--run","FR-F9001-001")
# E5 research smuggling
base={"rs_id":"FRS-F9001-001","kind":"HYPOTHESIS","level":3,"topics":["t"],"lens":"MATHEMATICAL","scale":"CORPUS","statement":"s",
 "epistemic_class":"THEORY-CANDIDATE","output_layer":"C","supporting_evidence":[{"f_id":"F9001","quote":"object X3 is defined as a map","evidence_kind":"CORPUS"}],
 "research_time":"t","run_id":"r","contract_sha256":"x","model_id":"m","analysis_refs":[],
 "falsification_condition":"f","validation_question":"v","competing_hypotheses":["h"],"contradicting_evidence":[],
 "disconfirmation_search":"done","result":"SUPPORTED","test_outcome":"SUPPORTED",
 "preregistration":{"population_rule":"p","temporal_scope":"t","selection_rule":"s","comparison_rule":"c","stopping_rule":"s","prediction":"p","registered_after_f":"F9001","outcome_vocabulary":["SUPPORTED","UNSUPPORTED","UNDETERMINED"]}}
R.write_jsonl("F9001","research.jsonl",[base])
res["E5_theory-candidate_per_file_scale_CORPUS_empty_analysis_refs_result_key"]=tr(R,"F9001","RESEARCHED","--run","FR-F9001-001")
# E5b structured disconfirmation_search as C10 specifies (object)
R2=new()
import f_checks; importlib.reload(f_checks)
os.makedirs(R2.ledger("F9001",""),exist_ok=True)
b2=dict(base,disconfirmation_search={"population":"this file","method":"LEXICAL","completeness":"EXHAUSTIVE","bound":"file","result":"none","negative_label":"NEGATIVE-BOUNDED"})
R2.write_jsonl("F9001","research.jsonl",[b2]); R2.write_jsonl("F9001","ANALYSIS.jsonl",[])
try:
    f_checks.check_research("F9001"); res["E5b_structured_disconfirmation_search"]="no crash"
except Exception as ex:
    res["E5b_structured_disconfirmation_search"]=f"CRASH {type(ex).__name__}: {ex}"
R2.close()
# E6 forged audit: hand-written AUDIT.json
json.dump({"run_id":"FR-F9001-001","audited_state":"RESEARCHED","verdict":"PASS"},open(R.ledger("F9001","AUDIT.json"),"w"))
res["E6_handwritten_AUDIT.json_PASS_without_f_audit_or_independent_audit"]=tr(R,"F9001","AUDITED","--run","FR-F9001-001")
res["E6_status"]=R.run("f_status.py").stdout.strip().splitlines()[-1]
R.close()

# E7 forged event line appended directly to the state ledger
R=new(); to_read_complete(R)
with open(os.path.join(R.fdir,"F-SERIES-STATE.jsonl"),"a") as f:
    f.write(json.dumps({"f_id":"F9001","seq":99,"from":"RESEARCHED","state":"AUDITED","utc":"x","run_id":"FR-F9001-001","evidence":{},"note":None,"protocol":"p"})+"\n")
res["E7_forged_event_status"]=R.run("f_status.py").stdout.strip().splitlines()[-1]
import f_common as C; importlib.reload(C)
res["E7_pairwise_legality_check_accepts_forged_event"]=all(e["state"] in C.ALLOWED_FROM and e["from"] in C.ALLOWED_FROM[e["state"]] for e in C.events("F9001"))
R.run("f_read_source.py","--run","FR-F9002-001","--info","F9002")
res["E7_next_file_READING_after_forgery"]=tr(R,"F9002","READING","--run","FR-F9002-001")
R.close()

# E8 escape hatch: CONTENT-EXTRACTION-UNRESOLVED -> AUDITED with no extraction; PLACEHOLDER likewise
for esc in ("CONTENT-EXTRACTION-UNRESOLVED","PLACEHOLDER"):
    R=new(); to_read_complete(R)
    a=tr(R,"F9001",esc,"--run","FR-F9001-001","--reason","too hard")
    au=R.run("f_audit.py","F9001","--run","FR-F9001-001").returncode
    b=tr(R,"F9001","AUDITED","--run","FR-F9001-001")
    res[f"E8_{esc}_then_audit_rc_then_AUDITED"]=(a[0],au,b)
    R.close()

# E9 look-ahead read of a later F-ID before the earlier one is AUDITED
R=new()
r=R.run("f_read_source.py","--run","FR-F9002-001","--page","1","F9002")
res["E9_reader_serves_later_F-ID_page_before_earlier_AUDITED_rc"]=r.returncode
R.close()

# E10 re-entry after AUDIT-FAILED
R=new(); to_read_complete(R)
R.inventory(); tr(R,"F9001","CONTENT-EXTRACTED","--run","FR-F9001-001"); R.phase1(); tr(R,"F9001","RECONSTRUCTED","--run","FR-F9001-001")
R.analyze(); tr(R,"F9001","ANALYZED","--run","FR-F9001-001"); R.research(); tr(R,"F9001","RESEARCHED","--run","FR-F9001-001")
R.run("f_audit.py","F9001","--run","FR-F9001-001")   # FAIL (independent audit missing)
res["E10_AUDIT-FAILED"]=tr(R,"F9001","AUDIT-FAILED","--run","FR-F9001-001")
res["E10_reenter_CONTENT-EXTRACTED_new_run_002"]=tr(R,"F9001","CONTENT-EXTRACTED","--run","FR-F9001-002")
res["E10_reenter_RESEARCHED_directly_same_run"]=tr(R,"F9001","RESEARCHED","--run","FR-F9001-001")
R.close()
R=new(); to_read_complete(R)
R.inventory(); tr(R,"F9001","CONTENT-EXTRACTED","--run","FR-F9001-001"); R.phase1(); tr(R,"F9001","RECONSTRUCTED","--run","FR-F9001-001")
R.analyze(); tr(R,"F9001","ANALYZED","--run","FR-F9001-001"); R.research(); tr(R,"F9001","RESEARCHED","--run","FR-F9001-001")
R.run("f_audit.py","F9001","--run","FR-F9001-001"); tr(R,"F9001","AUDIT-FAILED","--run","FR-F9001-001")
res["E10b_reenter_ANALYZED_skipping_inventory_gate"]=tr(R,"F9001","ANALYZED","--run","FR-F9001-001")
R.close()

# E11/E12 definition-cue and math detector evasion
import f_checks; importlib.reload(f_checks)
samples=["A kernel refers to the fixed part of the state.","Let K be the set of invariants.","K := the set of invariants.",
 "By a shape we mean a generating pattern.","We say that X is closed if it is stable.","The kernel is the fixed part of the state.",
 "A **kernel**: the fixed part.","**Definition 1.** A kernel is a set.","Kernel — the fixed part.","The term kernel stands for a set.",
 "X is defined as Y.","We define X as Y.","**kernel** is the fixed part."]
res["E11_definition_cue"]={s:bool(f_checks.DEFINITION_CUE.search(s)) for s in samples}
mtexts={"inline $x \\in K$":"The map $f: K \\to K$ is idempotent.\n","latex env":"\\begin{equation}\nf(x)=x\n\\end{equation}\n",
 "\\[ \\]":"\\[\nf(x)=x\n\\]\n","tilde fence":"~~~\ncode()\n~~~\n","indented code":"    def f(x):\n        return x\n",
 "unicode math":"∀x ∈ K: f(x) = x\n","$$ inline then text":"$$a=b$$ holds\nnext line one\nnext line two\n$$c$$\n"}
res["E12_unit_kinds"]={k:[u["kind"] for u in f_checks.units_of(v)] for k,v in mtexts.items()}
for k,v in res.items(): print(k,"=>",v)
