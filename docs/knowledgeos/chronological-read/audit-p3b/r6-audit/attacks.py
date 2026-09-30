#!/usr/bin/env python3
"""Revision-6 independent re-audit (G-LOG-0084): fresh attacks on the PRODUCTION verifier path
(p3b_s5_verify.verify on a synthetic OB9011 batch built by fx.py; fake resolver; synthetic bytes).

Each case: (id, finding, expected, mutate). Expected = what the contract text requires, reconstructed independently:
  FAIL  the batch is invalid and must be NOT-PASS
  PASS  a valid control (or a case the contract explicitly permits)
  BND   a case the verifier CANNOT decide from its inputs (epistemic / trust boundary): recorded, not scored as a defect
Verdict per case: OK (observed == expected), FALSE-PASS (expected FAIL, observed PASS), FALSE-FAIL, or BND-<observed>.
"R6-delta": the R6 failure lines that the attack adds over its own baseline (used where a baseline mutation is needed).

Run: cd audit-p3b/r6-audit && python3 -B -W ignore attacks.py > attacks.out
"""
import copy
import itertools
import json
import sys

import fx

OB, R6 = fx.OB, fx.R6
BASE = fx.build()
AL = BASE["alias"]
LAB = {a: l for l, a in AL.items()}
L1, L2, L3, L4, L5 = (LAB[f"L{i}"] for i in range(1, 6))
U = lambda lab, k: f"{OB}-R6-{AL[lab][0]}{int(AL[lab][1:]):02d}U{k:02d}"     # noqa: E731
RUNID = {lab: f"{OB}-R6-L{int(AL[lab][1:]):02d}" for lab in AL}
RESULTS = []


# ------------------------------------------------------------------ helpers (all mutate a deep copy)
def runs_of(st, lab, role=None):
    return [r for r, d in st["plan"]["labels"][lab]["runs"].items() if role is None or d["role"] == role]


def owner_run(st, lab, sid):
    return next(r for r, d in st["plan"]["labels"][lab]["runs"].items() if sid in d["files"])


def rec(st, lab, sid):
    r = owner_run(st, lab, sid)
    return next(x for x in st["runs"][r]["records"] if x["source_id"] == sid)


def final(st, lab):
    return next(r for r, d in st["plan"]["labels"][lab]["runs"].items() if d["role"] in ("SYNTHESIS", "SINGLE"))


def tlp(st, lab, sid):
    return next(p for p in fx.obj(st, lab)["timeline"] if p["source_id"] == sid)


def fact_for_claim(st, lab, cid):
    ref = st["runs"][final(st, lab)]["claim"][cid][0]
    r = next(x for x in st["runs"][ref["run"]]["records"] if x["source_id"] == ref["source_id"])
    return ref, r, next(f for f in r["facts"] if f["fact_id"] == ref["fact_id"])


def add_cd(st, lab, sid):
    fx.obj(st, lab)["escalations"].append({"field": "reading_state", "reason": "CONTRACT-DEVIATION",
                                           "detail": f"{sid} not read whole (auditor fixture)"})


def drop_pages(st, lab, sid, keep=lambda k, n: False):
    r = owner_run(st, lab, sid)
    st["runs"][r]["log"] = [e for e in st["runs"][r]["log"]
                            if e["page"]["source_id"] != sid or keep(e["page"]["page"], e["page"]["n_pages"])]


def ev_page(st, sid):
    b = st["contents"][sid]
    pos = b.find(fx.evidence_sentence(sid).encode())
    return next(k for k, (a, e) in enumerate(fx.pages(b), 1) if a <= pos < e)


def set_plan_file(st, f):
    p = copy.deepcopy(st["plan"])
    f(p)
    st["plan_file"] = p


def r6_lines(body):
    return [x for x in body["failures"] if x.startswith("R6 ")]


def case(cid, finding, expected, mutate, base_mutate=None, note=""):
    st = copy.deepcopy(BASE)
    if base_mutate:
        base_mutate(st)
    b0 = fx.run(st) if base_mutate else None
    if mutate:
        mutate(st)
    try:
        body = fx.run(st)
    except Exception as e:                                    # a crash is NOT-PASS (reported)
        body = {"result": f"EXC:{type(e).__name__}", "gates": {}, "failures": [f"EXCEPTION {type(e).__name__}: {str(e)[:120]}"]}
    obs = "PASS" if body["result"] == "PASS" else "FAIL"
    delta = [x for x in r6_lines(body) if b0 is None or x not in b0["failures"]]
    if expected == "BND":
        verdict = f"BND-{obs}"
    elif obs == expected:
        verdict = "OK"
    else:
        verdict = "FALSE-PASS" if obs == "PASS" else "FALSE-FAIL"
    key = [fx.alias(BASE, x)[:160] for x in (delta or r6_lines(body) or body["failures"])[:3]]
    base_note = "" if b0 is None else f" [baseline {b0['result']} {len(b0['failures'])}f]"
    RESULTS.append((cid, finding, expected, obs, body["gates"].get("R6"), len(body["failures"]), verdict, key, note))
    print(f"{cid:8} {finding:7} exp={expected:4} obs={obs:4} R6={body['gates'].get('R6')} nfail={len(body['failures'])}"
          f"{base_note} => {verdict}")
    for k in key:
        print(f"           | {k}")
    if note:
        print(f"           # {note}")
    sys.stdout.flush()
    return body


# ================================================================== 0. positive control
print("== 0 positive control (own fixture OB9011: 3 SINGLE + 2 DECOMPOSED labels, 3+2 units)")
case("C0", "CTRL", "PASS", None)

# ================================================================== R5-01 provenance
print("\n== R5-01 provenance: SOURCE -> READING RECORD -> FACT -> CLAIM")


def p1(st):     # missing fact
    ref, r, f = fact_for_claim(st, L3, "timeline:S0794")
    r["facts"].remove(f)
case("P1", "R5-01", "FAIL", p1, note="fact removed, claim map unchanged")


def p2(st):
    st["runs"][final(st, L3)]["claim"]["timeline:S0794"][0]["fact_id"] = "no-such-fact"
case("P2", "R5-01", "FAIL", p2, note="wrong fact id")


def p3(st):     # fact from another label's run (a valid TIMELINE fact of L5 re-pointed)
    ref5, _, f5 = fact_for_claim(st, L5, "timeline:S2640")
    st["runs"][final(st, L3)]["claim"]["timeline:S0794"] = [dict(ref5)]
case("P3a", "R5-01", "FAIL", p3, note="evidence ref to a fact in another label's run")


def p3b(st):    # same label, another unit run, with a fact copied there under the right source id
    _, r, f = fact_for_claim(st, L3, "timeline:S0794")
    other = [x for x in runs_of(st, L3, "UNIT") if x != owner_run(st, L3, "S0794")][0]
    tgt = st["runs"][other]["records"][0]
    tgt["facts"].append(dict(f, fact_id="moved"))
    st["runs"][final(st, L3)]["claim"]["timeline:S0794"] = [{"run": other, "source_id": "S0794", "fact_id": "moved"}]
case("P3b", "R5-01", "FAIL", p3b, note="fact placed in a unit run that is not permitted S0794")


def p3c(st):    # fact from another source in the same run
    run = owner_run(st, L3, "S0794")
    other = next(x for x in st["runs"][run]["records"] if x["source_id"] != "S0794")
    other["facts"].append({"fact_id": "xsrc", "kind": "TIMELINE", "quote": fx.evidence_sentence(other["source_id"]),
                           "change_candidate": "CHANGES-DEFINITION"})
    st["runs"][final(st, L3)]["claim"]["timeline:S0794"] = [{"run": run, "source_id": other["source_id"], "fact_id": "xsrc"}]
case("P3c", "R5-01", "FAIL", p3c, note="fact of another source in the same run")


def p3d(st):    # ref claims source S0794 but fact lives in another source's record (sid spoof inside the ref)
    run = owner_run(st, L3, "S0794")
    other = next(x for x in st["runs"][run]["records"] if x["source_id"] != "S0794")
    other["facts"].append({"fact_id": "spoof", "kind": "TIMELINE", "quote": fx.evidence_sentence(other["source_id"]),
                           "change_candidate": "CHANGES-DEFINITION"})
    st["runs"][final(st, L3)]["claim"]["timeline:S0794"] = [{"run": run, "source_id": "S0794", "fact_id": "spoof"}]
case("P3d", "R5-01", "FAIL", p3d, note="ref names the right source, the fact id exists only under another source")


def p4(st):     # whole-required claim on a READ-PARTIAL record (coverage honest: one page dropped)
    rec(st, L3, "S0794")["reading_state"] = "READ-PARTIAL"
    drop_pages(st, L3, "S0794", keep=lambda k, n: k != n)
    add_cd(st, L3, "S0794")
case("P4a", "R5-01", "FAIL", p4, note="CHANGES-DEFINITION + births on a READ-PARTIAL record")


def p5(st):
    _, _, f = fact_for_claim(st, L3, "timeline:S0794")
    f["quote"] = "Evidence clause S0794: the notion is retracted and replaced."
case("P5", "R5-01", "FAIL", p5, note="fact quote not in the authoritative bytes")


def p6(st):     # correct quote (present in the bytes) attached to a semantically unrelated fact
    _, _, f = fact_for_claim(st, L3, "timeline:S0794")
    b = st["contents"]["S0794"].decode()
    f["quote"] = b[100:140]
case("P6a", "R5-01", "BND", p6, note="quote = 40 bytes of filler text: referential link holds, no semantic support")


def p6b(st):
    _, _, f = fact_for_claim(st, L3, "timeline:S0794")
    f["quote"] = "e"
case("P6b", "R5-01", "BND", p6b, note="one-character quote; no minimum length / specificity rule exists")


def p7(st):
    _, _, f = fact_for_claim(st, L3, "timeline:S0794")
    f["kind"], f["birth_kind"] = "BIRTH", "formal"
    f.pop("change_candidate", None)
case("P7", "R5-01", "FAIL", p7, note="wrong-kind fact (BIRTH) for a timeline claim")


def p8(st):
    ref, r, f = fact_for_claim(st, L3, "timeline:S0794")
    r["facts"].append(dict(f))
case("P8", "R5-01", "FAIL", p8, note="duplicate fact id inside one record")


def p9a(st):    # record substituted after validation, hashes NOT updated
    st["_sub"] = True
    for x in st["prov"]:
        if x["label"] == L3 and x["stage"] == "units-validated":
            x["record_hashes"] = {r: "0" * 64 for r in runs_of(st, L3, "UNIT")}
case("P9a", "R5-01", "FAIL", p9a, note="units-validated binds other record hashes than the current files")


def p9b(st):    # consistent substitution: records edited, every declared hash recomputed by the writer
    _, _, f = fact_for_claim(st, L3, "timeline:S1733")
    f["change_candidate"] = "RETRACTS"
case("P9b", "R5-01", "BND", p9b, note="record content edited after validation with all declared hashes recomputed "
     "('auto'): the verifier cannot know the files ever differed")


def p10(st):    # timeline point with null source (schema keys intact)
    p = copy.deepcopy(tlp(st, L3, "S0787"))
    p["source_id"] = None
    p["change_vs_previous"] = "EXTENDS"
    fx.obj(st, L3)["timeline"].append(p)
case("P10", "R5-01", "FAIL", p10, note="extra EXTENDS point with source_id null: no source, no fact")


def p10b(st):
    p = copy.deepcopy(tlp(st, L3, "S0787"))
    p["source_id"] = ""
    p["change_vs_previous"] = "NOT-COMPARABLE"
    fx.obj(st, L3)["timeline"].append(p)
case("P10b", "R5-01", "FAIL", p10b, note="extra point with source_id ''")


def p10c(st):   # an evidence-free FIRST point placed before the real first appearance
    p = copy.deepcopy(tlp(st, L3, "S0785"))
    p["source_id"] = None
    p["change_vs_previous"] = "FIRST"
    fx.obj(st, L3)["timeline"].insert(0, p)
case("P10c", "R5-01", "FAIL", p10c, note="source-less FIRST point inserted before the real first appearance")


def p11(st):
    fx.obj(st, L3)["timeline"].append("S1890 EXTENDS the definition (1999)")
case("P11", "R5-01", "FAIL", p11, note="non-object timeline entry carrying a historical claim")


def p12(st):    # FOUND absence: object quote fabricated, fact valid
    o = fx.obj(st, L3)
    a = o["absences"]["examples"]
    a["supplied_by"]["quote"] = "A fabricated sentence that no source contains."
case("P12", "R5-01", "FAIL", p12, note="object-level supplied_by.quote not in bytes (fact is valid)")


def p13(st):    # R2 edge: object quote fabricated, fact valid
    o = fx.obj(st, L3)
    e = next(x for x in o["dependency_edges"] if x["edge_class"] == "R2-EVIDENCED")
    e["quote"] = "This file states that it depends on the synthetic target (fabricated)."
case("P13", "R5-01", "FAIL", p13, note="R2 edge quote (the addendum: 'quote that explicitly states the dependency') not in bytes")


def p14(st):    # TIMELINE fact candidate contradicts the claimed class
    _, _, f = fact_for_claim(st, L3, "timeline:S0794")
    f["change_candidate"] = "RESTATES"
case("P14", "R5-01", "BND", p14, note="the unit's change_candidate (RESTATES) ≠ claimed CHANGES-DEFINITION; the "
     "addendum says 'right kind and fields' but names no timeline field to compare")


def p15(st):    # unenumerated claim-bearing layer-A field
    fx.obj(st, L3)["timeline_summary"]["contradicted_by"] = ["S1890"]
case("P15", "R5-01", "BND", p15, note="timeline_summary.contradicted_by = [S1890] with no CONTRADICTS point and no fact: "
     "a claim-bearing field outside the enumerated claim list")


def p15b(st):
    fx.obj(st, L3)["superseded_by_sources"] = [{"source_id": "S2615", "position": 1}]
case("P15b", "R5-01", "BND", p15b, note="superseded_by_sources names S2615 with no fact: outside the enumerated claim list")

# ================================================================== R5-02 temporal
print("\n== R5-02 temporal (label L3, DECOMPOSED)")


def stage(lab, name, utc):
    def m(st):
        for x in st["prov"]:
            if x["label"] == lab and x["stage"] == name:
                x["utc"] = utc
    return m


def seq(*fs):
    def m(st):
        for f in fs:
            f(st)
    return m


case("T1", "R5-02", "FAIL", stage(L3, "synthesis-dispatched", "2026-11-02T13:00:00+05:00"),
     note="13:00+05:00 = 08:00Z, before units-validated 09:00Z (string-wise later)")
case("T1c", "R5-02", "PASS", stage(L3, "units-validated", "2026-11-02T11:00:00+02:00"), note="= 09:00Z: valid offset control")
case("T2", "R5-02", "FAIL", seq(stage(L3, "units-validated", "2026-11-02T09:29:59.900Z"),
                                stage(L3, "synthesis-dispatched", "2026-11-02T09:29:59.10Z")), note="fractional seconds, reversed")
case("T2c", "R5-02", "PASS", seq(stage(L3, "units-validated", "2026-11-02T09:29:59.1Z"),
                                 stage(L3, "synthesis-dispatched", "2026-11-02T09:29:59.900001Z")), note="fractional control")
for i, bad in enumerate(["2026-11-02 09:00:00Z", "2026-11-02T09:00Z", "2026-11-02T09:00:00", "2026-11-02T24:30:00Z",
                         "2026-11-02T09:00:00+0200", "2026-11-31T09:00:00Z", "later", "1730538000"]):
    case(f"T3.{i}", "R5-02", "FAIL", stage(L3, "units-validated", bad), note=f"malformed {bad!r}")
case("T3.z", "R5-02", "BND", stage(L3, "units-validated", "2026-11-02t09:00:00z"),
     note="lowercase t/z is valid RFC 3339 §5.6 (NOTE); rejection is conservative")
case("T3.nl", "R5-02", "FAIL", stage(L3, "units-validated", "2026-11-02T09:00:00Z\n"), note="trailing newline")


def all_offset(st):
    for x in st["prov"]:
        x["utc"] = {"units-validated": "2026-11-02T10:00:00+01:00", "synthesis-dispatched": "2026-11-02T10:30:00+01:00"}[x["stage"]]
    for r, d in st["runs"].items():
        for e in d.get("log") or []:
            e["utc"] = "2026-11-02T01:15:00-07:00"
        if d.get("manifest"):
            d["manifest"]["produced_utc"] = "2026-11-02T11:45:00+01:00"
case("T4", "R5-02", "PASS", all_offset, note="every time non-UTC but consistent")


def late_read(st):
    r = runs_of(st, L3, "UNIT")[1]
    st["runs"][r]["log"][0]["utc"] = "2026-11-02T09:00:01Z"
case("T5", "R5-02", "FAIL", late_read, note="one unit page read 1 s after units-validated")


def eq_read(st):
    r = runs_of(st, L3, "UNIT")[1]
    st["runs"][r]["log"][0]["utc"] = "2026-11-02T09:00:00Z"
case("T5c", "R5-02", "PASS", eq_read, note="read at exactly units-validated (≤ allowed)")
case("T7", "R5-02", "FAIL", stage(L3, "synthesis-dispatched", "2026-11-02T08:59:00Z"), note="synthesis before validation")
case("T7b", "R5-02", "FAIL", stage(L3, "synthesis-dispatched", "2026-11-02T09:00:00Z"), note="synthesis == validation (strict <)")


def prod_early(st):
    st["runs"][final(st, L3)]["manifest"]["produced_utc"] = "2026-11-02T09:20:00Z"
case("T8", "R5-02", "FAIL", prod_early, note="produced before synthesis dispatch")


def single_prod_early(st):
    st["runs"][final(st, L5)]["manifest"]["produced_utc"] = "2026-11-02T08:00:00Z"
case("T9", "R5-02", "FAIL", single_prod_early, note="SINGLE: RUN-MANIFEST produced before the reader-logged reads")


def dup_stage(st):     # real (late) validation first, a forged early one appended
    for x in list(st["prov"]):
        if x["label"] == L3 and x["stage"] == "units-validated":
            x["utc"] = "2026-11-02T09:45:00Z"
            st["prov"].append(dict(x, utc="2026-11-02T09:00:00Z"))
case("T10", "R5-02", "FAIL", dup_stage, note="two units-validated records for one label (09:45Z after dispatch, then "
     "09:00Z): ambiguous, precedence undefined")


def dup_stage_rev(st):
    for x in list(st["prov"]):
        if x["label"] == L3 and x["stage"] == "units-validated":
            st["prov"].append(dict(x, utc="2026-11-02T09:45:00Z"))
case("T10b", "R5-02", "FAIL", dup_stage_rev, note="same two records, other order")


def no_utc(st):
    st["runs"][runs_of(st, L3, "UNIT")[0]]["log"][2].pop("utc")
case("T11a", "R5-02", "FAIL", no_utc, note="read-log entry without utc")
case("T11b", "R5-02", "FAIL", lambda st: st.__setitem__("prov", [x for x in st["prov"] if x["label"] != L3]), note="stages missing")
case("T11c", "R5-02", "FAIL", lambda st: st["runs"][final(st, L3)]["manifest"].pop("produced_utc"), note="no produced_utc")
case("T11d", "R5-02", "FAIL", lambda st: st["runs"][final(st, L5)].__setitem__("manifest", None), note="SINGLE without RUN-MANIFEST")


def same_ts(st):
    for x in st["prov"]:
        pass
    for r in runs_of(st, L3, "UNIT"):
        for e in st["runs"][r]["log"]:
            e["utc"] = "2026-11-02T08:15:00Z"
case("T12", "R5-02", "PASS", same_ts, note="duplicate (identical) read timestamps are legitimate")

# ================================================================== R5-03 execution
print("\n== R5-03 execution / run set")


def extra_dir(rel, rows):
    return lambda st: st["extra"].__setitem__(rel, rows)


def entries_for(st, run_as, lab, sid, utc=fx.T_READ, batch=OB):
    b = st["contents"][sid]
    return [fx.page_entry(run_as, batch, lab, sid, b, k, utc, 7) for k in range(1, len(fx.pages(b)) + 1)]


s_u1 = sorted(BASE["plan"]["labels"][L3]["runs"][U(L3, 1)]["files"])[0]
case("E1", "R5-03", "FAIL", lambda st: extra_dir(f"ledger-p3b-r2/{OB}-R6-L03U09/READ-LOG.jsonl",
                                                 entries_for(st, f"{OB}-R6-L03U09", L3, s_u1))(st), note="undeclared R6 unit run")


def dup_run(st):     # L4's agent reads L4's file under L3's unit run (duplicate use of one run id)
    r = U(L3, 1)
    st["runs"][r]["log"] += entries_for(st, r, L4, "S2455")
case("E2", "R5-03", "FAIL", dup_run, note="one run id used by two labels")


def wrong_batch(st):
    st["runs"][U(L3, 1)]["log"][0]["batch_id"] = "OB9012"
case("E3", "R5-03", "FAIL", wrong_batch, note="entry names another batch")
case("E4", "R5-03", "FAIL", lambda st: extra_dir(f"ledger-p3b-r2/{OB}-R7-L03U01/READ-LOG.jsonl",
                                                 entries_for(st, f"{OB}-R7-L03U01", L3, s_u1))(st),
     note="wrong-revision run dir (R7) with reads of a planned file")
case("E5", "R5-03", "FAIL", lambda st: extra_dir(f"ledger-p3b-r2/{OB}-R5-L03U01/READ-LOG.jsonl",
                                                 entries_for(st, f"{OB}-R5-L03U01", L3, s_u1))(st), note="R5 run dir")


def foreign_run(st):
    st["runs"][U(L3, 1)]["log"][0]["run_id"] = U(L4, 1)
case("E6", "R5-03", "FAIL", foreign_run, note="entry names a foreign run inside a planned log")


def synth_as_unit(st):    # synthesis agent reads under a unit run id, honest (reader-written) time
    r = U(L3, 2)
    s = st["plan"]["labels"][L3]["runs"][r]["files"][0]
    st["runs"][r]["log"] += entries_for(st, r, L3, s, utc="2026-11-02T09:40:00Z")
case("E7", "R5-03", "FAIL", synth_as_unit, note="synthesis reads under a unit run id after dispatch (reader time)")


def unit_as_synth(st):
    r = f"{RUNID[L3]}S"
    st["runs"][r]["log"] = entries_for(st, r, L3, s_u1)
case("E8", "R5-03", "FAIL", unit_as_synth, note="reads under the synthesis run")
case("E9", "R5-03", "FAIL", lambda st: st["runs"][U(L3, 2)].__setitem__("log", None), note="per-run log missing")
case("E10", "R5-03", "BND", lambda st: extra_dir(f"ledger-p3b-r2/{R6}/READ-LOG.jsonl", entries_for(st, R6, L3, s_u1))(st),
     note="extra log in the assembled dir (the reader grammar cannot write there)")
for tag, rid in (("E11", f"{OB}-R2"), ("E11b", f"{OB}-R2.1"), ("E11c", f"{OB}-R2S")):
    case(tag, "R5-03", "FAIL", (lambda rid: lambda st: extra_dir(f"ledger-p3b-r2/{rid}/READ-LOG.jsonl",
                                [dict(e, page=None, mode=None) for e in entries_for(st, rid, L3, "S0794", utc="2026-11-02T09:40:00Z")])(st))(rid),
         note=f"L3's synthesis agent reads S0794 under legacy run id {rid} (reader grammar accepts it for OB batches) "
              f"after synthesis dispatch")


def refused(st):
    e = dict(st["runs"][U(L3, 1)]["log"][0], refused=True, refusal="PLAN", reason="file not permitted for this run")
    st["runs"][U(L3, 1)]["log"].append(e)
case("E12", "R5-03", "FAIL", refused, note="a refused entry (stop condition 16)")


def outside(st):
    r1, r2 = U(L3, 1), U(L3, 2)
    s = st["plan"]["labels"][L3]["runs"][r2]["files"][0]
    st["runs"][r1]["log"] += entries_for(st, r1, L3, s)
case("E13", "R5-03", "FAIL", outside, note="unit reads a file of another unit")


def charmode(st):
    e = st["runs"][U(L3, 1)]["log"][0]
    e["page"]["mode"] = "chars-r3"
case("E14", "R5-03", "FAIL", charmode, note="character-mode page")
case("E14b", "R5-03", "FAIL", lambda st: st["runs"][U(L3, 1)]["log"][0].__setitem__("page", None), note="entry without page")
case("E15", "R5-03", "BND", lambda st: extra_dir(f"ledger-p3b-r2/OB9012-R6-L01/READ-LOG.jsonl",
                                                 entries_for(st, "OB9012-R6-L01", L3, s_u1, batch="OB9012"))(st),
     note="read of an OB9011 file under another batch's run dir: outside this batch's verification scope")

# ================================================================== R5-04 label ownership
print("\n== R5-04 label ownership (run -> label from the plan)")


def swap_labels(st):
    for e in st["runs"][RUNID[L1]]["log"]:
        e["working_label"] = L2
    for e in st["runs"][RUNID[L2]]["log"]:
        e["working_label"] = L1
case("O1", "R5-04", "FAIL", swap_labels, note="run(L1) declares working_label L2 and vice versa")
case("O2", "R5-04", "FAIL", lambda st: set_plan_file(st, lambda p: p["labels"][L2]["runs"].update(
    {RUNID[L1]: {"role": "SINGLE", "files": p["labels"][L1]["files"]}})), note="plan: one run owned by two labels")
case("O3", "R5-04", "FAIL", lambda st: set_plan_file(st, lambda p: p["labels"].pop(L2)), note="plan: label missing (no owner)")
case("O4", "R5-04", "FAIL", lambda st: set_plan_file(st, lambda p: p["labels"].__setitem__("foreign-label-x", p["labels"].pop(L2))),
     note="plan: run owned by a label outside the batch")


def reorder(st):
    st["ctx"]["labels"] = [st["ctx"]["labels"][1], st["ctx"]["labels"][0]] + st["ctx"]["labels"][2:]
case("O5", "R5-04", "FAIL", reorder, note="manifest label order permuted; plan and run dirs from the old order")


def forged_label(st):
    for e in st["runs"][U(L3, 1)]["log"][:2]:
        e["working_label"] = L4
case("O6", "R5-04", "FAIL", forged_label, note="forged --label on two entries of a valid run")


def swap_objs(st):
    a, b = fx.obj(st, L1), fx.obj(st, L2)
    a["working_label"], b["working_label"] = L2, L1
case("O7", "R5-04", "FAIL", swap_objs, note="objects of L1/L2 exchanged (valid runs, malicious label metadata on the object)")

# ================================================================== R5-05 plan integrity
print("\n== R5-05 plan integrity (the plan must equal the independent derivation)")


def hashed(f):
    def m(st):
        set_plan_file(st, f)
    return m


case("L1", "R5-05", "FAIL", hashed(lambda p: p["labels"][L5]["sizes"].__setitem__("S2627", 9_999)), note="declared size altered (hash updated)")


def alter_content(st):     # resolver content grows past the budget; plan/logs from the old content
    st["contents"]["S2627"] = st["contents"]["S2627"] + b" x" * 300_000
case("L2", "R5-05", "FAIL", alter_content, note="authoritative content altered after planning")


def alter_same_size(st):
    b = bytearray(st["contents"]["S2627"])
    b[10:15] = b"QQQQQ"
    st["contents"]["S2627"] = bytes(b)
case("L2b", "R5-05", "FAIL", alter_same_size, note="content altered, same size (plan still equal; page hashes must catch it)")
case("L3", "R5-05", "FAIL", hashed(lambda p: p["labels"][L3].__setitem__("packing_order", list(reversed(p["labels"][L3]["packing_order"])))),
     note="packing order permuted")


def perm_units(st):
    def f(p):
        L = p["labels"][L3]
        a, b = L["units"][0], L["units"][1]
        a[0], b[0] = b[0], a[0]
        L["units"] = [sorted(u) for u in L["units"]]
        for k, u in enumerate(L["units"], 1):
            L["runs"][U(L3, k)]["files"] = u
    set_plan_file(st, f)
case("L4", "R5-05", "FAIL", perm_units, note="units permuted (a file swapped between units)")
case("L5", "R5-05", "FAIL", lambda st: st.__setitem__("plan_sha", fx.sha(fx.canon(dict(st["plan"], revision=5)))), note="stale plan hash")


def wrong_plan_right_hash(st):
    set_plan_file(st, lambda p: p["labels"][L4].__setitem__("path", "SINGLE"))
case("L6", "R5-05", "FAIL", wrong_plan_right_hash, note="correct hash of the wrong plan")


def stale_meta(st):     # plan derived under stale 02-FILES metadata (dates altered for L3 rows)
    meta = copy.deepcopy(fx.c.files_meta())
    for s in st["plan"]["labels"][L3]["row_sources"]:
        m = meta.setdefault(s, {})
        m.update(best_historical_date_basis="EXPLICIT", order_evidence="TEXT", mtime_block=None,
                 best_historical_date={"S0785": "2031-01-01", "S0787": "2030-01-01", "S0794": "2029-01-01"}[s])
    st["plan_file"] = fx.own_plan(OB, st["ctx"]["labels"], st["ctx"]["slices"], st["contents"], meta)
case("L7", "R5-05", "FAIL", stale_meta, note="plan derived from stale metadata")


def consistent_wrong_units(st):     # whole batch rebuilt consistently around a wrong partition
    def f(p):
        L = p["labels"][L4]
        allf = sorted(L["files"])
        L["units"] = [allf[:2], allf[2:]]
        L["runs"] = {U(L4, 1): {"role": "UNIT", "files": allf[:2]}, U(L4, 2): {"role": "UNIT", "files": allf[2:]},
                     f"{RUNID[L4]}S": {"role": "SYNTHESIS", "files": []}}
    set_plan_file(st, f)
    pf = st["plan_file"]["labels"][L4]
    recs = {x["source_id"]: x for r in runs_of(st, L4, "UNIT") for x in st["runs"][r]["records"]}
    logs = [e for r in runs_of(st, L4, "UNIT") for e in st["runs"][r]["log"]]
    cmap = st["runs"][f"{RUNID[L4]}S"]["claim"]
    for r, d in pf["runs"].items():
        if d["role"] != "UNIT":
            continue
        st["runs"][r] = {"records": [recs[s] for s in d["files"]],
                         "log": [dict(e, run_id=r) for e in logs if e["page"]["source_id"] in d["files"]]}
    for refs in cmap.values():
        for ref in refs:
            ref["run"] = next(r for r, d in pf["runs"].items() if ref["source_id"] in d["files"])
    st["plan"] = st["plan_file"]           # the fixture writer derives hashes from this (the forged) plan
case("L8", "R5-05", "FAIL", consistent_wrong_units, note="valid-looking but incorrect units: every artifact consistent "
     "with a forged partition")
case("L9", "R5-05", "FAIL", lambda st: st["entry"].__setitem__("r6_plan_sha256", None), note="no plan hash in the manifest entry")
case("L10", "R5-05", "FAIL", hashed(lambda p: p.__setitem__("note", "x")), note="extra key in the plan")

# ================================================================== R5-06 reading state x claim (label L4, file S2455)
print("\n== R5-06 reading state x claim type (L4 file S2455, DECOMPOSED; delta over a state-only baseline)")


def partial_base(state, drop="evidence"):
    def m(st):
        rec(st, L4, "S2455")["reading_state"] = state
        add_cd(st, L4, "S2455")
        o4 = fx.obj(st, L4)                  # R17: a GENUINELY-UNDEFINED census would (correctly) fail; neutralize it
        for dim, a in o4["absences"].items():
            if a["resolution"] == "GENUINELY-UNDEFINED-AFTER-CENSUS":
                a["resolution"], a["reason"] = "ESCALATED", "census incomplete: S2455 not read whole (auditor fixture)"
        if drop == "evidence":
            k = ev_page(st, "S2455")
            drop_pages(st, L4, "S2455", keep=lambda kk, n: kk != k)
        elif drop == "all":
            drop_pages(st, L4, "S2455")
    return m


def cls(c_):
    return lambda st: tlp(st, L4, "S2455").__setitem__("change_vs_previous", c_)


for state, drop in (("READ-PARTIAL", "evidence"), ("NOT-CONSUMED", "all"), ("READ-FAILED", "all")):
    tag = {"READ-PARTIAL": "RP", "NOT-CONSUMED": "NC", "READ-FAILED": "RF"}[state]
    b = partial_base(state, drop)
    case(f"R-{tag}-EXT", "R5-06", "PASS", b, note=f"{state} (pages dropped: {drop}) + EXTENDS point (permitted class); "
         f"the TIMELINE fact's quote lies on a page this run never logged")
    for c_ in ("CHANGES-DEFINITION", "CHANGES-TYPE", "CHANGES-TERM", "NARROWS", "CONTRADICTS", "RETRACTS", "SUPERSEDES"):
        case(f"R-{tag}-{c_[:9]}", "R5-06", "FAIL", cls(c_), base_mutate=b, note=f"{state} + {c_}")

    def birth(st):
        fx.obj(st, L4)["births"]["conceptual"] = "BIRTH-UNRESOLVED-MTIME-ONLY[S2455]"
        f = {"fact_id": "b-x", "kind": "BIRTH", "birth_kind": "conceptual", "quote": fx.evidence_sentence("S2455")}
        rec(st, L4, "S2455")["facts"].append(f)
        st["runs"][final(st, L4)]["claim"]["birth:conceptual:S2455"] = [{"run": owner_run(st, L4, "S2455"),
                                                                          "source_id": "S2455", "fact_id": "b-x"}]
    case(f"R-{tag}-birth", "R5-06", "FAIL", birth, base_mutate=b, note=f"{state} + birth (BIRTH-UNRESOLVED form, with a fact)")

    def found(st):
        o = fx.obj(st, L4)
        dim = sorted(o["absences"])[0]
        o["absences"][dim] = dict(o["absences"][dim], resolution="FOUND",
                                  supplied_by={"source_id": "S2455", "anchor": "x", "quote": fx.evidence_sentence("S2455")})
        rec(st, L4, "S2455")["facts"].append({"fact_id": "a-x", "kind": "ABSENCE", "dimension": dim, "finding": "DEFINES",
                                              "quote": fx.evidence_sentence("S2455")})
        st["runs"][final(st, L4)]["claim"][f"absence:{dim}"] = [{"run": owner_run(st, L4, "S2455"), "source_id": "S2455",
                                                                 "fact_id": "a-x"}]
    case(f"R-{tag}-found", "R5-06", "FAIL", found, base_mutate=b, note=f"{state} + FOUND absence")

    def edge(st):
        o = fx.obj(st, L4)
        o["dependency_edges"].append({"target_label": "t2", "kind": "USAGE", "source_id": "S2455",
                                      "quote": fx.evidence_sentence("S2455"), "edge_class": "R2-EVIDENCED"})
        rec(st, L4, "S2455")["facts"].append({"fact_id": "d-x", "kind": "DEPENDENCY", "target_label": "t2",
                                              "quote": fx.evidence_sentence("S2455")})
        st["runs"][final(st, L4)]["claim"]["edge:t2:S2455"] = [{"run": owner_run(st, L4, "S2455"), "source_id": "S2455",
                                                                "fact_id": "d-x"}]
    case(f"R-{tag}-edge", "R5-06", "BND", edge, base_mutate=b, note=f"{state} + R2 edge (the addendum requires no WHOLE-FILE for edges)")
    case(f"R-{tag}-tsum", "R5-06", "FAIL", lambda st: fx.obj(st, L4)["timeline_summary"].__setitem__("contradicted_by", ["S2455"]),
         base_mutate=b, note=f"{state} + timeline_summary.contradicted_by=[S2455] (a CONTRADICTS claim in another field)")
    case(f"R-{tag}-sup", "R5-06", "FAIL", lambda st: fx.obj(st, L4).__setitem__("superseded_by_sources", [{"source_id": "S2455", "position": 2}]),
         base_mutate=b, note=f"{state} + superseded_by_sources=[S2455] (feeds end(p), rev3 §A.10; a RETRACTS-like claim)")
case("R-noCD", "R5-06", "FAIL", lambda st: (rec(st, L4, "S2455").__setitem__("reading_state", "READ-PARTIAL")),
     note="READ-PARTIAL without a CONTRACT-DEVIATION escalation")
case("R-offscale", "R5-06", "FAIL", lambda st: rec(st, L4, "S2455").__setitem__("reading_state", "WHOLE-FILE "), note="state off-scale")


def whole_no_cov(st):
    drop_pages(st, L4, "S2455", keep=lambda k, n: k != 1)
case("R-wholeNoCov", "R5-06", "FAIL", whole_no_cov, note="WHOLE-FILE claimed, page 1 missing")


def ack_extra(st):
    rec(st, L4, "S2455")["ack_tokens"].append("ACK-000000000000")
case("R-ackExtra", "R5-06", "FAIL", ack_extra, note="extra ack token (R5-11)")

# ================================================================== R5-07 S1 (L1 pair RP1041 on S2131; L3 pairs)
print("\n== R5-07 S1 pair-evidence checks")


def chk(st, lab, sid, pid):
    return next(x for x in rec(st, lab, sid)["pair_evidence_checks"] if x["pair_id"] == pid)


def s1(field_updates, lab=L1, sid="S2131", pid="RP1041"):
    return lambda st: chk(st, lab, sid, pid).update(field_updates)


def vec(st, lab=L1, pid="RP1041", esc=True, reg=True, esc_detail=None):
    o = fx.obj(st, lab)
    if esc:
        o["escalations"].append({"field": "pair_breakdown", "reason": "OTHER",
                                 "detail": esc_detail or f"VERDICT-EVIDENCE-CONFLICT: {pid}"})
    if reg:
        src = next(x for x in st["ctx"]["reg"] if x.get("working_label") == lab) if any(
            x.get("working_label") == lab for x in st["ctx"]["reg"]) else dict(st["ctx"]["reg"][0], working_label=lab)
        n = 1 + max(int(x["rs_id"].rsplit(":", 1)[1]) for x in st["ctx"]["reg"])
        r = copy.deepcopy(src)
        r.update(rs_id=f"{R6}:{OB}:{n}", topics=["VERDICT-EVIDENCE-CONFLICT"],
                 statement=f"Pair {pid}: the file's evidence conflicts with the P3a verdict (auditor fixture).")
        st["ctx"]["reg"].append(r)


case("S1a", "R5-07", "PASS", None, note="correct YES + quote (= positive control)")
case("S1b", "R5-07", "FAIL", s1({"quote": "The source confirms the pair relationship verbatim (fabricated)."}), note="YES + fabricated quote")
case("S1c", "R5-07", "FAIL", s1({"quote": fx.evidence_sentence("S2813")}), note="YES + quote from another source")
case("S1d", "R5-07", "BND", s1({"quote": fx.evidence_sentence("S0794")}, L3, "S0794", "RP1238"),
     note="YES + a real quote of the same file that is about another pair (semantics)")
case("S1e", "R5-07", "BND", s1({"quote": "lorem"}), note="YES + substring collision ('lorem')")
case("S1f", "R5-07", "FAIL", s1({"supports": "NO", "pair_id": "RP1042"}), note="NO under the wrong pair id")
case("S1g", "R5-07", "FAIL", seq(s1({"supports": "NO"}), lambda st: vec(st, esc=False)), note="NO + register, no OTHER escalation")
case("S1h", "R5-07", "FAIL", seq(s1({"supports": "NO"}), lambda st: vec(st, esc_detail="VERDICT-EVIDENCE-CONFLICT: RP10410")),
     note="NO + escalation naming RP10410 (wrong token)")
def strip_vec(st):      # the historical L1 register already carries a VEC record naming RP1041: remove that topic first
    for x in st["ctx"]["reg"]:
        if x.get("working_label") == L1 and "VERDICT-EVIDENCE-CONFLICT" in (x.get("topics") or []):
            x["topics"] = [t for t in x["topics"] if t != "VERDICT-EVIDENCE-CONFLICT"]
case("S1h2", "R5-07", "FAIL", seq(strip_vec, s1({"supports": "NO"}), lambda st: vec(st, reg=False)),
     note="NO + escalation, no VEC register record (pre-existing VEC topic stripped)")
case("S1i", "R5-07", "PASS", seq(s1({"supports": "NO"}), lambda st: vec(st)), note="complete NO (register + OTHER escalation)")
case("S1j", "R5-07", "BND", s1({"supports": "NOT-DETERMINABLE", "quote": None}), note="NOT-DETERMINABLE")


def blanket_nd(st):
    for lab in st["ctx"]["labels"]:
        for r in runs_of(st, lab):
            for x in st["runs"][r].get("records") or []:
                for ck in x["pair_evidence_checks"]:
                    ck.update(supports="NOT-DETERMINABLE", quote=None)
case("S1k", "R5-07", "BND", blanket_nd, note="blanket NOT-DETERMINABLE (F2)")


def dup_pair(st):
    r = rec(st, L1, "S2131")
    r["pair_evidence_checks"].insert(0, {"pair_id": "RP1041", "supports": "NO", "quote": "fabricated conflicting passage"})
case("S1l", "R5-07", "FAIL", dup_pair, note="duplicate pair id: an unescalated NO with a fabricated quote precedes the YES")
case("S1m", "R5-07", "FAIL", s1({"supports": "MAYBE"}), note="supports off-scale")
case("S1n", "R5-07", "FAIL", lambda st: rec(st, L1, "S2131").__setitem__("pair_evidence_checks", []), note="required check missing")

# ================================================================== H3 layer-A binding (full path)
print("\n== H3 layer-A pointer binding (full path, object of L2)")
for i, txt in enumerate(["see the Register record for details", "cf. register", "rs_id pending",
                         f"{OB}:7", f"{R6}:{OB}:7", f"{OB}#7",
                         "register  record 7", "register entry 7", "rs-id 7", f"{OB} : 7", f"{OB}：7",
                         "see​ the register"]):
    exp = "FAIL"
    case(f"H3.{i}", "H3", exp, (lambda t: lambda st: fx.obj(st, L2).__setitem__("author_role", t))(t=txt),
         note=f"pointer text in layer A: {txt!r}")

# ================================================================== PASS => P^E^R^T^L^S
print("\n== PASS ⇒ P∧E∧R∧T∧L∧S: exactly one invariant violated, then combinations")
V = {
    "P": lambda st: st["runs"][final(st, L5)]["claim"].__setitem__("timeline:S2640", [{"run": RUNID[L5], "source_id": "S2640", "fact_id": "nope"}]),
    "E": lambda st: extra_dir(f"ledger-p3b-r2/{OB}-R6-L02U01/READ-LOG.jsonl", entries_for(st, f"{OB}-R6-L02U01", L2, "S2813"))(st),
    "R": lambda st: rec(st, L1, "S2131")["ack_tokens"].append(rec(st, L1, "S2131")["ack_tokens"][0]),
    "T": stage(L4, "synthesis-dispatched", "2026-11-02T08:30:00Z"),
    "L": hashed(lambda p: p["labels"][L5].__setitem__("packing_order", ["S2625"])),
    "S": s1({"quote": "fabricated S1 quote"}),
}
for k, f in V.items():
    b = case(f"I1-{k}", "PASS-INV", "FAIL", f, note=f"only {k} violated")
for n in (2, 3, 6):
    for combo in itertools.combinations("PERTLS", n):
        case(f"I{n}-{''.join(combo)}", "PASS-INV", "FAIL", seq(*[V[x] for x in combo]))

# ================================================================== trust boundary
print("\n== Trust boundary (can an authoritative input be forged / contradicted?)")


def forged_log(st):   # a log "written by the agent", not by the reader: extra field, no sha256, invented stdout
    r = U(L4, 1)
    for e in st["runs"][r]["log"]:
        e.update(stdout="written-by-agent", sha256={}, forged=True)
case("TB1", "TRUST", "BND", forged_log, note="READ-LOG.jsonl hand-written: nothing binds it to the reader")


def synth_read_backdated(st):
    r = U(L3, 2)
    s = st["plan"]["labels"][L3]["runs"][r]["files"][0]
    st["runs"][r]["log"] += entries_for(st, r, L3, s, utc="2026-11-02T08:59:00Z")
case("TB2", "TRUST", "BND", synth_read_backdated, note="E7 with a back-dated utc (log edited): indistinguishable from a unit read")


def prov_forged(st):
    for x in st["prov"]:
        if x["label"] == L3:
            x["utc"] = {"units-validated": "2026-11-02T09:05:00Z", "synthesis-dispatched": "2026-11-02T09:06:00Z"}[x["stage"]]
case("TB3", "TRUST", "BND", prov_forged, note="R6-PROVENANCE.jsonl rewritten by the orchestrator: declarations only")
case("TB4", "TRUST", "FAIL", lambda st: st["hdr"]["contract"].__setitem__("revision", 5), note="manifest header revision 5 (withdrawn)")
case("TB5", "TRUST", "BND", lambda st: st["hdr"]["contract"].__setitem__("revision", 7), note="manifest header revision 7 routes to R6")


def rev_str(st):
    st["hdr"]["contract"]["revision"] = "6"
case("TB6", "TRUST", "FAIL", rev_str, note="manifest header revision as a string")

# ------------------------------------------------------------------ summary
print("\n== SUMMARY")
cnt = {}
for r in RESULTS:
    cnt[r[6]] = cnt.get(r[6], 0) + 1
print("cases:", len(RESULTS), json.dumps(cnt, sort_keys=True))
for r in RESULTS:
    if r[6] in ("FALSE-PASS", "FALSE-FAIL"):
        print(f"  {r[6]:10} {r[0]:10} {r[1]:8} {r[8]}")
