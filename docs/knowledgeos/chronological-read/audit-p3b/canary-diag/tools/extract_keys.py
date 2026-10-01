"""Static key/literal extraction over the R7 verifier path (function-scoped). Prints file:line func kind literal."""
import ast, sys, os
CR = "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/chronological-read/scripts"
# functions reached from verify_r7 / p3b_s5_verify.verify on the rev7 path
REACHED = {
 "p3b_s5_r7_verify.py": None,
 "p3b_s5_r7_evidence.py": None,
 "p3b_s5_r7_reconstruction.py": {"dated_position","file_position","precedence","summary_violations","lifecycle_violations",
                                 "s1_violations","_pointer_norm","s3_violations","empty_violations","edge_and_s2_violations"},
 "p3b_s5_r7_universe.py": {"load_contract","_path_rx","_any_found","consumer_violations","discover","norm","type_of",
                           "validation_violations","typing_violations","meta_violations","claims","binary_decisions",
                           "binary_decision_integrity","plan_derive_v7","plan_violations","run_owner","legacy_digest",
                           "namespace_violations","revision_violations","slice_view","slice_view_parse","slice_view_violations",
                           "input_manifest","input_manifest_violations","permitted_writes","canonical_reader","<module>"},
 "p3b_s5_r7_witness.py": None,
 "p3b_s5_verify.py": {"verify","birth_ok","birth_basis","model_ok","strip_quotes","strings","keys_of","earliest_track","hub_rec_eq","shape","<module>"},
 "p3b_s5_r5.py": {"is_binary","byte_pages","ack_token","byte_page_record","edge_class_violations","s2_violations","empty_label_violations","<module>"},
 "p3b_s5_r6.py": {"slice_required","plan_derive","plan_violations","run_owner","s1_violations_v6","_in_text","empty_violations_v6","<module>"},
}
def lits(node):
    out=[]
    for n in ast.walk(node):
        if isinstance(n, ast.Constant) and isinstance(n.value, str) and n.value and len(n.value) < 80:
            out.append(n.value)
    return out
for fn, keep in REACHED.items():
    src = open(os.path.join(CR, fn)).read()
    tree = ast.parse(src)
    funcs = {}
    for n in ast.walk(tree):
        if isinstance(n,(ast.FunctionDef,)):
            for m in ast.walk(n):
                if hasattr(m,"lineno"): funcs.setdefault(m.lineno, n.name)
    for n in ast.walk(tree):
        kind=None; lit=None
        if isinstance(n, ast.Call) and isinstance(n.func, ast.Attribute) and n.func.attr=="get" and n.args and isinstance(n.args[0], ast.Constant) and isinstance(n.args[0].value,str):
            kind, lit = "get", n.args[0].value
        elif isinstance(n, ast.Subscript) and isinstance(n.slice, ast.Constant) and isinstance(n.slice.value,str):
            kind, lit = "sub", n.slice.value
        elif isinstance(n, ast.Compare):
            cs=[c for c in [n.left]+n.comparators if isinstance(c, ast.Constant) and isinstance(c.value,str)]
            for c in cs:
                print(f"{fn}:{n.lineno}\t{funcs.get(n.lineno,'<module>')}\tcmp\t{c.value}") if (keep is None or funcs.get(n.lineno,'<module>') in keep) else None
            continue
        if kind and (keep is None or funcs.get(n.lineno,'<module>') in keep):
            print(f"{fn}:{n.lineno}\t{funcs.get(n.lineno,'<module>')}\t{kind}\t{lit}")
