#!/usr/bin/env python3
"""Revision-6 re-audit (G-LOG-0084), R5-08: EMPTY must not be a bypass. Full production path.

Construction (own): in the OB9011 fixture, label L2 is turned into a genuine EMPTY label by giving its slice an empty
required set (no stage-2 files, no row sources). The plan is re-derived independently (fx.own_plan), L2's run artifacts
are removed, and the object is made a valid EMPTY resolution (births NOT-EVIDENCED-IN-CAPTURE, no timeline, no edges,
no FOUND / GENUINELY-UNDEFINED, an escalation naming EMPTY-REQUIRED-SET). Then each attack adds one kind of content.
Run: cd audit-p3b/r6-audit && python3 -B -W ignore empty.py > empty.out
"""
import copy
import json

import fx

OB = fx.OB
BASE = fx.build()
LAB = {a: l for l, a in BASE["alias"].items()}
L1, L2 = LAB["L1"], LAB["L2"]
RES = []


def make_empty(st):
    sl = st["ctx"]["slices"][L2]
    sl["stage2_files"] = []
    sl.setdefault("bundle", {})["rows_verbatim"] = []
    st["plan"] = fx.own_plan(OB, st["ctx"]["labels"], st["ctx"]["slices"], st["contents"], fx.c.files_meta())
    assert st["plan"]["labels"][L2]["path"] == "EMPTY"
    for r in [r for r in st["runs"] if r.startswith(f"{OB}-R6-L02")]:
        del st["runs"][r]
    o = fx.obj(st, L2)
    o["births"] = {k: "NOT-EVIDENCED-IN-CAPTURE" for k in o["births"]}
    o["timeline"], o["dependency_edges"], o["stage2_dispositions"] = [], [], []
    o["timeline_summary"].update(first_lexical=None)
    for dim, a in o["absences"].items():
        if a["resolution"] in ("FOUND", "GENUINELY-UNDEFINED-AFTER-CENSUS"):
            a.update(resolution="ESCALATED", supplied_by=None, reason="empty required set (auditor fixture)")
    o["escalations"].append({"field": "absences", "reason": "OTHER", "detail": "EMPTY-REQUIRED-SET: no required file"})


def case(cid, expected, mutate, note):
    st = copy.deepcopy(BASE)
    make_empty(st)
    if mutate:
        mutate(st)
    body = fx.run(st)
    obs = "PASS" if body["result"] == "PASS" else "FAIL"
    verdict = f"BND-{obs}" if expected == "BND" else ("OK" if obs == expected else ("FALSE-PASS" if obs == "PASS" else "FALSE-FAIL"))
    key = [fx.alias(BASE, x)[:160] for x in ([x for x in body["failures"] if x.startswith("R6 ")] or body["failures"])[:3]]
    RES.append((cid, verdict, note))
    print(f"{cid:6} R5-08  exp={expected:4} obs={obs:4} R6={body['gates'].get('R6')} nfail={len(body['failures'])} => {verdict}")
    for k in key:
        print(f"           | {k}")
    print(f"           # {note}")


def tl_point(sid, cls="FIRST"):
    p = copy.deepcopy(fx.obj(BASE, L1)["timeline"][0])
    p.update(source_id=sid, change_vs_previous=cls)
    return p


case("EM0", "PASS", None, "valid EMPTY label (positive control; plan re-derived: L2 EMPTY, no runs)")
case("EM1", "FAIL", lambda st: fx.obj(st, L2)["timeline"].append(tl_point("S2813")), "EMPTY + timeline point on its former file")
case("EM2", "FAIL", lambda st: fx.obj(st, L2)["births"].__setitem__("lexical", "BIRTH-UNRESOLVED-MTIME-ONLY[S2813]"), "EMPTY + S-id birth")


def em3(st):
    o = fx.obj(st, L2)
    d = sorted(o["absences"])[0]
    o["absences"][d].update(resolution="FOUND", supplied_by={"source_id": "S2813", "anchor": "a", "quote": "q"})
case("EM3", "FAIL", em3, "EMPTY + FOUND absence")


def em4(st):
    o = fx.obj(st, L2)
    o["absences"][sorted(o["absences"])[0]]["resolution"] = "GENUINELY-UNDEFINED-AFTER-CENSUS"
case("EM4", "FAIL", em4, "EMPTY + GENUINELY-UNDEFINED")
case("EM5", "FAIL", lambda st: fx.obj(st, L2)["dependency_edges"].append(
    {"target_label": "x", "kind": "USAGE", "source_id": "S2813", "quote": "q", "edge_class": "R1-STRUCTURAL"}), "EMPTY + R1 edge")
case("EM6", "FAIL", lambda st: fx.obj(st, L2)["dependency_edges"].append(
    {"target_label": "x", "kind": "USAGE", "source_id": "S2813", "quote": fx.evidence_sentence("S2813"),
     "edge_class": "R2-EVIDENCED"}), "EMPTY + R2 edge with a true quote")
case("EM7", "FAIL", lambda st: fx.obj(st, L2)["timeline"].append(tl_point("S2131", "EXTENDS")), "EMPTY + point on another label's file")


def em8(st):
    d = copy.deepcopy(fx.obj(st, L1)["stage2_dispositions"][0])
    fx.obj(st, L2)["stage2_dispositions"].append(d)
case("EM8", "FAIL", em8, "EMPTY + a stage-2 disposition (claim)")


def em9(st):
    run = f"{OB}-R6-L02"
    st["extra"][f"ledger-p3b-r2/{run}/READ-LOG.jsonl"] = [fx.page_entry(run, OB, L2, "S2813", st["contents"]["S2813"], 1, fx.T_READ, 1)]
case("EM9", "FAIL", em9, "EMPTY label's agent reads under its old SINGLE run id (unplanned run)")
case("EM10", "FAIL", lambda st: fx.obj(st, L2).__setitem__("author_role", "reviewed S2813–S2820"), "EMPTY + S-id range")
case("EM11", "FAIL", lambda st: fx.obj(st, L2).__setitem__("author_role", "see the register record"), "EMPTY + layer-A pointer")
case("EM12", "FAIL", lambda st: fx.obj(st, L2)["escalations"].pop(), "EMPTY without the EMPTY-REQUIRED-SET escalation")
case("EM13", "FAIL", lambda st: fx.obj(st, L2)["timeline_summary"].__setitem__("contradicted_by", ["S2131"]),
     "EMPTY + timeline_summary.contradicted_by = [another label's file]")
case("EM14", "FAIL", lambda st: fx.obj(st, L2).__setitem__("superseded_by_sources", [{"source_id": "S2131", "position": 1}]),
     "EMPTY + superseded_by_sources = [another label's file]")
case("EM15", "FAIL", lambda st: fx.obj(st, L2)["timeline"].append("S2131 CHANGES-DEFINITION"), "EMPTY + non-object timeline entry")
print("\n== SUMMARY", {v: sum(1 for r in RES if r[1] == v) for v in sorted({r[1] for r in RES})})
for r in RES:
    if r[1] in ("FALSE-PASS", "FALSE-FAIL"):
        print(f"  {r[1]} {r[0]} {r[2]}")
