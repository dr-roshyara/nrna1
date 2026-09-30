#!/usr/bin/env python3
"""Revision-6 re-audit (G-LOG-0084): function-level probes with synthetic inputs only.
  A  the '/' range/enumeration question: R6 S3 matcher (RANGE6) vs the canonical G-LOG-0045 quarantine matcher
  B  H3: s3_lint_v6 layer-A binding by position vs by keys
  C  reader: reader_plan_check and the paged-mode run grammar (the module is IMPORTED; main() is never called)
  D  the production quote checker (p3b_s5_quotes.check) on the object-level quotes of attacks P12/P13 (fake resolver)
  E  ML boundary: imports of every module on the evidence / sampling path
Run: cd audit-p3b/r6-audit && python3 -B -W ignore probes.py > probes.out
"""
import importlib.util
import json
import os
import re

import fx

S = fx.SCRIPTS


def load(name, fname):
    sp = importlib.util.spec_from_file_location(name, os.path.join(S, fname))
    m = importlib.util.module_from_spec(sp)
    sp.loader.exec_module(m)
    return m


r6 = load("p3b_s5_r6_probe", "p3b_s5_r6.py")
c = fx.c

print("== A  '/' and related notations. Endpoints A=S0957, B=S2361 are assembled in memory and PRINTED AS A/B, so no "
      "file text implies an interval (H-19). Synthetic hold-out id S1500 lies inside the interval.")
HF = {"S1500"}
A, B = "S" + "0957", "S" + "2361"
forms = ["{A}/{B}", "{A}-{B}", "{A}..{B}", "{A}–{B}", "{A}, {B}", "{A} / {B}",
         "{A}--{B}", "{A} up to {B}", "between {A} and {B}", "{A}­-{B}", "{A}‐{B}",
         "{A}－{B}", "{A} \\u2013 {B}", "{A}​–{B}", "{A}:{B}", "{A}->{B}", "{A}→{B}",
         "{A}-1600 (abbreviated, S1500 inside)", "{A} to 1998 revision"]
print(f"{'form':40} {'R6 S3 RANGE6':14} {'canonical (G-LOG-0045)':24}")
for f in forms:
    t = f.format(A=A, B=B).replace("-1600", "-" + "1600")
    t = t.replace(A + "-1600", "S" + "1400" + "-" + "1600") if "abbreviated" in f else t
    a = bool(r6.RANGE6.search(json.dumps({"x": t}, ensure_ascii=False)))
    b = bool(c.sid_range_spans(t, HF))
    shown = f.replace("{A}", "A").replace("{B}", "B")
    print(f"{shown!r:40} {str(a):14} {str(b):24}{'  <-- differ' if a != b else ''}")

print("\n== B  H3: layer-A binding (s3_lint_v6)")
obj = {"working_label": "lab-x", "timeline": [], "births": {}, "absences": {}, "author_role": "see the register record"}
stripped = {"working_label": "lab-x", "author_role": "see the register record"}
renamed = {"working_label": "lab-x", "Timeline": [], "author_role": "see the register record"}
nolabel = {"timeline": [], "author_role": "see the register record"}
for name, recs, la in (("object with keys, pos 0, layer_a={0}", [obj], {0}),
                       ("keys stripped, pos 0, layer_a={0}", [stripped], {0}),
                       ("keys renamed, pos 0, layer_a={0}", [renamed], {0}),
                       ("no working_label, pos 0, layer_a={0}", [nolabel], {0}),
                       ("keys stripped, pos 1 (register slot), layer_a={0}", [{"working_label": "lab-x"}, stripped], {0}),
                       ("object-shaped record in register slot, layer_a={0}", [{"working_label": "lab-x"}, obj], {0}),
                       ("keys stripped, layer_a=() (key sniffing only)", [stripped], ())):
    out = r6.s3_lint_v6(recs, "lab-x", "OB9011-R6-L01", "OB9011", None, None, layer_a=la)
    print(f"  {name:52} -> {'CAUGHT' if any('layer A' in x for x in out) else 'missed'}")
print("  verifier call site: final_records = [obj] + register + P1-gap and layer_a={0} (p3b_s5_r6_verify.py l.159/163): "
      "the object is always at index 0 by construction")

print("\n== C  reader: reader_plan_check (defence in depth) and run grammar")
plan = {"batch_id": "OB9011", "revision": 6, "labels": {
    "lab-a": {"runs": {"OB9011-R6-L01": {"role": "SINGLE", "files": ["S0001"]}}},
    "lab-b": {"runs": {"OB9011-R6-L02U01": {"role": "UNIT", "files": ["S0002"]},
                       "OB9011-R6-L02S": {"role": "SYNTHESIS", "files": []}}}}}
for desc, args in (("valid", (plan, "OB9011-R6-L01", "OB9011", "S0001", "lab-a")),
                   ("no plan", (None, "OB9011-R6-L01", "OB9011", "S0001", "lab-a")),
                   ("plan of another batch", (plan, "OB9011-R6-L01", "OB9012", "S0001", "lab-a")),
                   ("unplanned run", (plan, "OB9011-R6-L03", "OB9011", "S0001", "lab-a")),
                   ("synthesis run reads", (plan, "OB9011-R6-L02S", "OB9011", "S0002", "lab-b")),
                   ("file not permitted", (plan, "OB9011-R6-L01", "OB9011", "S0002", "lab-a")),
                   ("forged --label", (plan, "OB9011-R6-L01", "OB9011", "S0001", "lab-b")),
                   ("other label's unit, correct owner label", (plan, "OB9011-R6-L02U01", "OB9011", "S0002", "lab-b"))):
    print(f"  {desc:42} -> {r6.reader_plan_check(*args)}")
reader = load("p3b_read_source_probe", "p3b_read_source.py")
paged_run = r"OB\d{4}-R2(\.\d+)?|OB\d{4}-R2S|OT\d{4}-R2|OA\d{4}-R2|" + reader.R6_RUN + "|" + reader.PILOT_RUN
plain_run = r"S4-PILOT-R2-\d{3}|OB\d{4}-R2(\.\d+)?|OB\d{4}-R2S|OT\d{4}-R2|OA\d{4}-R2"
for run in ("OB9011-R2", "OB9011-R2.1", "OB9011-R2S", "OB9011-R6-L03U01", "OB9011-R7-L01", "OB9011-R5-L01"):
    print(f"  run {run:18} paged-mode grammar {bool(re.fullmatch(paged_run, run))!s:5}  plain-mode grammar "
          f"{bool(re.fullmatch(plain_run, run))!s:5}  plan check applies: {bool(re.fullmatch(reader.R6_RUN, run))}")
print(f"  reader plan path  : {reader.R6_PLAN}")
print(f"  verifier plan path: <manifest header slice_root, default {fx.prep.SLICE_ROOT}>/<batch>.R6-PLAN.json "
      f"(reader does not check the manifest-bound r6_plan_sha256)")

print("\n== D  production quote checker on object-level quotes (attacks P12 / P13), fake resolver")
q = load("p3b_s5_quotes_probe", "p3b_s5_quotes.py")
fx.FakeResolver.CONTENTS = {"S0794": fx.synth_bytes("S0794"), "S0785": fx.synth_bytes("S0785")}
recs = [{"working_label": "lab-x",
         "absences": {"examples": {"supplied_by": {"source_id": "S0785", "anchor": "a",
                                                   "quote": "A fabricated sentence that no source contains."}}},
         "dependency_edges": [{"source_id": "S0794", "quote": "This file states that it depends (fabricated).",
                               "edge_class": "R2-EVIDENCED"},
                              {"source_id": "S0794", "quote": fx.evidence_sentence("S0794"), "edge_class": "R2-EVIDENCED"}]}]
res = q.check(recs, fx.FakeResolver())
print(f"  exact {res['exact']}  whitespace {res['whitespace']}  miss {len(res['miss'])}: {[m['path'] for m in res['miss']]}")

print("\n== E  ML boundary: imports on the evidence / sampling path")
ML = re.compile(r"^\s*(import|from)\s+(sklearn|torch|tensorflow|numpy|scipy|pandas|transformers|sentence_transformers|"
                r"gensim|faiss|openai|anthropic|xgboost|lightgbm)\b", re.M)
SIM = re.compile(r"embedding|cosine|jaccard|similarity|classifier|predict_proba", re.I)
for f in ("p3b_s5_r6.py", "p3b_s5_r6_verify.py", "p3b_s5_r5.py", "p3b_s5_verify.py", "p3b_read_source.py",
          "p3b_s5_common.py", "p3b_s5_quotes.py"):
    t = open(os.path.join(S, f), encoding="utf-8").read()
    imps = sorted(set(re.findall(r"^\s*(?:import|from)\s+([A-Za-z_][\w.]*)", t, re.M)))
    print(f"  {f:22} ML imports: {len(ML.findall(t))}  similarity/ML words: {len(SIM.findall(t))}  imports: {imps}")
