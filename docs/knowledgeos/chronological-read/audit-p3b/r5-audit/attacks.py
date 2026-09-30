#!/usr/bin/env python3
"""Revision-5 independent adversarial audit harness (synthetic only).
Uses the committed synthetic Fixture + FakeResolver of test_p3b_s5_r5_verify (S95xx ids, synthetic bytes).
No corpus read, no reader call, no real resolver, no repo write.
Run:  cd <CR>/scripts/tests && PYTHONPATH=.:.. python3 -B /tmp/r5-audit/attacks.py
Each attack prints: ID | expected | observed (PASS = verify_r5 returned no failure) | failures.
"""
import copy
import hashlib
import json
import os
import shutil
import sys
import tempfile

sys.dont_write_bytecode = True
import test_p3b_s5_r5_verify as TV  # noqa: E402

r5, r5v, BATCH = TV.r5, TV.r5v, TV.BATCH
Fixture, FakeResolver, content, byte_log, tokens, lint_of = (TV.Fixture, TV.FakeResolver, TV.content, TV.byte_log,
                                                             TV.tokens, TV.lint_of)
RESULTS = []


def run_custom(batch, objs, log, slices, contents, reg=None):
    d = tempfile.mkdtemp(prefix="r5audit-")
    try:
        with open(os.path.join(d, "R5-BATCH.json"), "w", encoding="utf-8") as f:
            json.dump(batch, f)
        F, blog, cov = r5v.verify_r5(BATCH, d, objs, reg or [], log, lambda: FakeResolver(contents), None, None, slices)
        return F
    finally:
        shutil.rmtree(d, ignore_errors=True)


def run_fx(fx, mutate):
    b, objs, log, slices = (copy.deepcopy(fx.batch), copy.deepcopy(fx.objs), copy.deepcopy(fx.log),
                            copy.deepcopy(fx.slices))
    contents = dict(fx.contents)
    reg = []
    out = mutate(b, objs, log, slices, contents, reg)
    if out is not None:
        reg = out
    return run_custom(b, objs, log, slices, contents, reg)


def report(aid, expected, F, note=""):
    obs = "PASS" if not F else "FAIL"
    false_pass = expected == "FAIL" and obs == "PASS"
    RESULTS.append((aid, expected, obs, false_pass))
    print(f"{aid:6s} | expected {expected} | observed {obs}{'  <-- FALSE PASS' if false_pass else ''} | {note}")
    for x in F[:4]:
        print(f"         - {x[:150]}")


DEC = "syn-decomp"
SIN = "syn-single"
EMP = "syn-empty"
U = lambda b: b["labels"][DEC]
S = lambda b: b["labels"][SIN]
OI = {SIN: 0, DEC: 1, EMP: 2}


def relint(b, objs, lab, reg=()):
    b["labels"][lab]["lint"] = lint_of(objs[OI[lab]], list(reg))


# ---------------------------------------------------------------- baseline
report("BASE", "PASS", Fixture().run(), "committed valid fixture")


# ---------------------------------------------------------------- B: partition / sizing / plan integrity
def b1(b, o, l, s, c, reg):
    """SINGLE label whose real content is 700,000 bytes (> budget) declares its plan size as 30,000."""
    c["S9501"] = content("S9501", 700_000)
    l[:] = [e for e in l if e["page"]["source_id"] != "S9501"]
    l.extend(byte_log(f"{BATCH}-R5-L01", SIN, "S9501", c["S9501"]))
    S(b)["records"][f"{BATCH}-R5-L01"][0]["ack_tokens"] = tokens(c["S9501"])


report("B1", "FAIL", run_fx(Fixture(), b1), "SINGLE path, true R(L)=702,000 B > 600,000 B; declared sizes are never bound to content bytes")


def b1b(b, o, l, s, c, reg):
    """The committed 'valid' fixture itself: DECOMPOSED label declared 1,000,000 B, real content 4 x 5,000 B."""
    return None


report("B1b", "FAIL", run_fx(Fixture(), b1b),
       "committed fixture: DECOMPOSED declared 1.0 MB, actual content 20 KB (should be SINGLE) - PASSES")


def b2(b, o, l, s, c, reg):
    """Packing order permuted (frozen packing key would be S-id order: no meta) -> different unit membership."""
    order = ["S9513", "S9511", "S9512"]
    sizes = U(b)["sizes"]
    units = r5.partition(set(U(b)["files"]), set(U(b)["row_sources"]), sizes, lambda x: order.index(x))
    U(b)["packing_order"] = order
    U(b)["units"] = units
    runs = [f"{BATCH}-R5-L02U{i:02d}" for i in range(1, len(units) + 1)]
    U(b)["runs"]["units"] = runs
    recs = {}
    l[:] = [e for e in l if e["working_label"] != DEC]
    for run, uf in zip(runs, units):
        recs[run] = []
        for sid in uf:
            l.extend(byte_log(run, DEC, sid, c[sid]))
            recs[run].append({"source_id": sid, "reading_state": "WHOLE-FILE", "ack_tokens": tokens(c[sid]),
                              "pair_evidence_checks": []})
    U(b)["records"] = recs
    print("         (units by permuted order:", units, " vs frozen-key units:", Fixture().batch["labels"][DEC]["units"], ")")


report("B2", "FAIL", run_fx(Fixture(), b2), "packing order not recomputed from the slice's source_meta (packing_key)")


def b3(b, o, l, s, c, reg):
    b["plan_sha256"] = "not-a-hash"


report("B3", "FAIL", run_fx(Fixture(), b3), "plan_sha256 arbitrary / never checked")


# ---------------------------------------------------------------- C: R19 ordering
def c1(b, o, l, s, c, reg):
    U(b)["stages"] = {"units_validated_utc": "2026-10-01T10:00:00Z",
                      "synthesis_dispatched_utc": "2026-10-01T11:00:00+05:00"}   # = 06:00Z, 4 h BEFORE validation


report("C1", "FAIL", run_fx(Fixture(), c1), "timezone offset: synthesis 06:00Z < validation 10:00Z, lexicographic compare passes")


def c2(b, o, l, s, c, reg):
    U(b)["stages"] = {"units_validated_utc": "2026-10-01", "synthesis_dispatched_utc": "later"}


report("C2", "FAIL", run_fx(Fixture(), c2), "non-ISO strings ('2026-10-01' < 'later')")


def c3(b, o, l, s, c, reg):
    U(b)["stages"] = {"units_validated_utc": "2026-10-01T10:00:00Z", "synthesis_dispatched_utc": "2026-10-01T11:00:00Z"}
    for e in l:
        if e["run_id"].startswith(f"{BATCH}-R5-L02U"):
            e["utc"] = "2026-10-01T12:30:00Z"            # every unit read happens AFTER synthesis dispatch


report("C3", "FAIL", run_fx(Fixture(), c3), "unit reads (log utc 12:30Z) after units_validated (10:00Z) and synthesis (11:00Z)")


def c4(b, o, l, s, c, reg):
    U(b)["stages"] = {"units_validated_utc": "2026-10-01T10:00:00.5Z", "synthesis_dispatched_utc": "2026-10-01T10:00:00Z"}


report("C4", "FAIL", run_fx(Fixture(), c4), "fractional seconds: '10:00:00.5Z' vs '10:00:00Z' ('.'<'Z' lexicographically)")


# ---------------------------------------------------------------- D: reading state / coverage ownership
def d1(b, o, l, s, c, reg):
    """READ-PARTIAL file (coverage complete) used as a CONTRADICTS change point (item 10: only WHOLE-FILE)."""
    run = f"{BATCH}-R5-L02U01"
    for run_, recs in U(b)["records"].items():
        for r in recs:
            if r["source_id"] == "S9512":
                r["reading_state"] = "READ-PARTIAL"
    for tp in o[1]["timeline"]:
        if tp["source_id"] == "S9512":
            tp["change_vs_previous"] = "CONTRADICTS"
    relint(b, o, DEC)


report("D1", "FAIL", run_fx(Fixture(), d1), "reading_state_violations tests only 'CHANGES-*'; NARROWS/CONTRADICTS/RETRACTS escape")


def d1b(b, o, l, s, c, reg):
    for run_, recs in U(b)["records"].items():
        for r in recs:
            if r["source_id"] == "S9512":
                r["reading_state"] = "READ-PARTIAL"
    for tp in o[1]["timeline"]:
        if tp["source_id"] == "S9512":
            tp["change_vs_previous"] = "CHANGES-DEFINITION"
    relint(b, o, DEC)


report("D1b", "FAIL", run_fx(Fixture(), d1b), "control: same with CHANGES-DEFINITION")


def d2(b, o, l, s, c, reg):
    """Label ownership: a second SINGLE label shares file S9502. Its own run L03 never reads S9502; the L01 agent
    logs S9502 pages under --label syn-second. Coverage is grouped by the log's (agent-supplied) working_label."""
    lab, run = "syn-second", f"{BATCH}-R5-L03"
    c["S9503"] = content("S9503", 3_000)
    rows, s2 = {"S9503"}, {"S9502"}
    l.extend(byte_log(run, lab, "S9503", c["S9503"]))
    l.extend(byte_log(f"{BATCH}-R5-L01", lab, "S9502", c["S9502"]))     # read by label syn-single's agent (run L01)
    recs = [{"source_id": sid, "reading_state": "WHOLE-FILE", "ack_tokens": tokens(c[sid]), "pair_evidence_checks": []}
            for sid in sorted(rows | s2)]
    ob = TV.obj_for(lab, rows, s2, found_sid="S9502")
    o.append(ob)
    s[lab] = TV.slice_for(lab, rows, s2)
    b["labels"][lab] = {"path": "SINGLE", "files": sorted(rows | s2), "row_sources": sorted(rows),
                        "sizes": {"S9503": 3_000, "S9502": 2_000},
                        "runs": {"single": run, "units": [], "synthesis": None}, "records": {run: recs},
                        "lint": lint_of(ob, [])}
    run_l3 = [e for e in l if e["run_id"] == run]
    print("         (run L03 read:", sorted({e['page']['source_id'] for e in run_l3}), "; record claims WHOLE-FILE for S9502 and FOUND from it)")


report("D2", "FAIL", run_fx(Fixture(), d2), "WHOLE-FILE + FOUND backed by pages read in ANOTHER label's run (item 10 'label's own reading runs')")


def d3(b, o, l, s, c, reg):
    """Ack tokens: extra and duplicated tokens accepted."""
    rec = S(b)["records"][f"{BATCH}-R5-L01"][0]
    rec["ack_tokens"] = rec["ack_tokens"] + rec["ack_tokens"] + ["ACK-ffffffffffff"]


report("D3", "FAIL", run_fx(Fixture(), d3), "extra / duplicated / foreign ack tokens (superset test)")


def d4(b, o, l, s, c, reg):
    """A non-paged, non-byte read in an R5 run (page field absent): not flagged as a character-mode read."""
    l.append({"run_id": f"{BATCH}-R5-L01", "batch_id": BATCH, "working_label": SIN, "step": 7,
              "source_ids": ["S9502"], "refused": False, "bytes": {"S9502": 2000}})


report("D4", "FAIL", run_fx(Fixture(), d4), "whole-content (non-byte-mode) read entry in a revision-5 run")


def d5(b, o, l, s, c, reg):
    """Row-source file READ-PARTIAL with no escalation at all (item 10 requires CONTRACT-DEVIATION escalation)."""
    for run_, recs in U(b)["records"].items():
        for r in recs:
            if r["source_id"] == "S9513":
                r["reading_state"] = "NOT-CONSUMED"


report("D5", "FAIL", run_fx(Fixture(), d5), "NOT-CONSUMED row source, no CONTRACT-DEVIATION escalation naming it")


# ---------------------------------------------------------------- E: exposure
def e1(b, o, l, s, c, reg):
    """Synthesis agent reads a unit file using the UNIT's run id (reader accepts any R5-grammar run id)."""
    for e in byte_log(f"{BATCH}-R5-L02U01", DEC, U(b)["units"][0][0], c[U(b)["units"][0][0]]):
        e["utc"] = "2026-10-01T11:30:00Z"                 # after synthesis dispatch
        l.append(e)


report("E1", "FAIL", run_fx(Fixture(), e1), "synthesis read disguised under a unit run id, after synthesis dispatch")


def e2(b, o, l, s, c, reg):
    """Synthesis reads under an undeclared R5-grammar run id -> filtered out of blog (full verifier: alert only)."""
    l.extend(byte_log(f"{BATCH}-R5-L02U09", DEC, "S9514", c["S9514"]))
    l.extend(byte_log(f"{BATCH}-R5-L09", DEC, "S9511", c["S9511"]))


report("E2", "FAIL", run_fx(Fixture(), e2), "reads under undeclared run ids L02U09 / L09")


def e3(b, o, l, s, c, reg):
    """Read of another label's file by the synthesis run but logged under a different batch id."""
    for e in byte_log(f"{BATCH}-R5-L02S", DEC, "S9501", c["S9501"]):
        e["batch_id"] = "OB9599"
        l.append(e)


report("E3", "FAIL", run_fx(Fixture(), e3), "synthesis read with --batch OB9599")


def e4(b, o, l, s, c, reg):
    """Refused access attempt by the synthesis run (source-access attempt, level 2) - runbook stop condition."""
    l.append({"run_id": f"{BATCH}-R5-L02S", "batch_id": BATCH, "working_label": DEC, "step": 10,
              "source_ids": ["S9599"], "refused": True})


report("E4", "FAIL", run_fx(Fixture(), e4), "refused read attempt by synthesis (possible SEAL-BREACH-ATTEMPT)")


def e5(b, o, l, s, c, reg):
    """Two labels share one run id (one agent produces two labels: cross-label mixing)."""
    lab, run = "syn-second", f"{BATCH}-R5-L01"          # SAME run id as syn-single
    c["S9503"] = content("S9503", 3_000)
    rows, s2 = {"S9503"}, {"S9502"}
    l.extend(byte_log(run, lab, "S9503", c["S9503"]))
    l.extend(byte_log(run, lab, "S9502", c["S9502"]))
    recs = [{"source_id": sid, "reading_state": "WHOLE-FILE", "ack_tokens": tokens(c[sid]), "pair_evidence_checks": []}
            for sid in sorted(rows | s2)]
    ob = TV.obj_for(lab, rows, s2)
    o.append(ob)
    s[lab] = TV.slice_for(lab, rows, s2)
    # R5-BATCH is a dict: put syn-second first so syn-single (files S9501,S9502) is processed last?  permitted[run] is
    # overwritten by the later label; make both labels' reads permitted by giving the union to the last one.
    new = {lab: {"path": "SINGLE", "files": sorted(rows | s2), "row_sources": sorted(rows),
                 "sizes": {"S9503": 3_000, "S9502": 2_000},
                 "runs": {"single": run, "units": [], "synthesis": None}, "records": {run: recs}, "lint": lint_of(ob, [])}}
    new.update(b["labels"])
    b["labels"] = new


F = run_fx(Fixture(), e5)
report("E5", "FAIL", F, "two labels declare the same single-context run id")


# ---------------------------------------------------------------- F: S1
def f1(b, o, l, s, c, reg):
    """Unit records YES with a fabricated quote that does not occur in the file."""
    S(b)["records"][f"{BATCH}-R5-L01"][0]["pair_evidence_checks"] = [
        {"pair_id": "RP9", "supports": "YES", "quote": "THIS SENTENCE IS NOT IN THE FILE"}]


report("F1", "FAIL", run_fx(Fixture(), f1), "S1 YES quote not checked against the file bytes (content is in hand)")


def f2(b, o, l, s, c, reg):
    S(b)["records"][f"{BATCH}-R5-L01"][0]["pair_evidence_checks"] = [
        {"pair_id": "RP9", "supports": "NOT-DETERMINABLE"}]


report("F2", "PASS*", run_fx(Fixture(), f2), "blanket NOT-DETERMINABLE (no quote) always passes; not machine-decidable")


def f3(b, o, l, s, c, reg):
    """NO for pair RP0751 satisfied by register/escalation that are about RP0752 and merely mention RP0751."""
    s[SIN]["p3a_pairs"] = [{"pair_id": "RP0751", "a": SIN, "b": "other", "what_says_this": "basis S9501", "basis": "INFERRED"}]
    S(b)["records"][f"{BATCH}-R5-L01"][0]["pair_evidence_checks"] = [
        {"pair_id": "RP0751", "supports": "NO", "quote": "q"}]
    o[0]["escalations"] = [{"field": "pairs", "reason": "OTHER",
                            "detail": "VERDICT-EVIDENCE-CONFLICT: RP0752 (not RP0751, which is consistent)"}]
    rg = [{"working_label": SIN, "kind": "OBSERVATION", "topics": ["VERDICT-EVIDENCE-CONFLICT"],
           "statement": "conflict on RP0752; RP0751 checked and consistent"}]
    relint(b, o, SIN, rg)
    return rg


report("F3", "FAIL", run_fx(Fixture(), f3), "pair-id substring match anywhere in register/escalation text")


def f4(b, o, l, s, c, reg):
    """NO check with escalation of the wrong reason (not OTHER) - reason not checked by S1."""
    S(b)["records"][f"{BATCH}-R5-L01"][0]["pair_evidence_checks"] = [{"pair_id": "RP9", "supports": "NO", "quote": "q"}]
    o[0]["escalations"] = [{"field": "x", "reason": "LOAD", "detail": "VERDICT-EVIDENCE-CONFLICT: RP9"}]
    rg = [{"working_label": SIN, "kind": "VERDICT-EVIDENCE-CONFLICT RP9", "topics": []}]
    relint(b, o, SIN, rg)
    return rg


report("F4", "FAIL", run_fx(Fixture(), f4), "escalation reason LOAD (contract: OTHER) accepted (full verifier's G-05/HUB may catch LOAD on non-hub)")


# ---------------------------------------------------------------- H: S3
def s3cases():
    out = []
    for txt in ["S9511 through S9513", "S9511…S9513", "S9511 − S9513", "S9511‒S9513", "S9511-13", "S9511/S9513",
                "S9511 bis S9513", "S9511~S9513", "S9511 ― S9513", "S9511 until S9513", "S9511 – S9513"]:
        o = {"working_label": "x", "timeline": [], "note": f"sources {txt}"}
        out.append((txt, r5.s3_lint([o], "x", f"{BATCH}-R5-L02S", BATCH)))
    for txt in ["See the Register record 3", "cf. register", "see research-register", f"{BATCH}-R5:{BATCH}#3",
                f"{BATCH} : 3"]:
        o = {"working_label": "x", "timeline": [], "note": txt}
        out.append((txt, r5.s3_lint([o], "x", f"{BATCH}-R5-L02S", BATCH)))
    return out


print("H1     | S3 lint variants (empty list = lint PASS):")
for t, F in s3cases():
    print(f"         {t!r:38s} -> {'PASS (missed)' if not F else 'caught'}")


def h2(b, o, l, s, c, reg):
    """EMPTY object bypasses S3, edge-class and reading-state rules entirely."""
    o[2]["escalations"][0]["detail"] += " ; related sources S9511 to S9513"
    o[2]["dependency_edges"] = [{"target_label": "other", "kind": "USAGE"}]           # no edge_class
    o[2]["timeline"] = [{"source_id": "S9511", "change_vs_previous": "EXTENDS"}]      # a source of another label


report("H2", "FAIL", run_fx(Fixture(), h2), "EMPTY label: S-id range + class-less edge + timeline from another label's file")


def h3(b, o, l, s, c, reg):
    """Object without a 'timeline' key skips the S3 layer-A pointer check."""
    del o[0]["timeline"]
    o[0]["note"] = f"see the register record {BATCH}:3"
    relint(b, o, SIN)


report("H3", "FAIL", run_fx(Fixture(), h3), "layer-A pointer in an object lacking 'timeline' (full verifier SCHEMA/G-12 would catch)")


# ---------------------------------------------------------------- I / J: provenance and composite false PASS
def j1(b, o, l, s, c, reg):
    """Smallest composite: all unit records are valid and carry NO facts. Synthesis adds a definition change
    on S9512, a FOUND absence supplied by S9514 with a FOUND disposition, and an R2-EVIDENCED edge. Nothing in
    any unit record supports these claims; lint recomputed."""
    ob = o[1]
    for tp in ob["timeline"]:
        if tp["source_id"] == "S9512":
            tp["change_vs_previous"] = "CHANGES-DEFINITION"
    ob["absences"]["d"] = {"resolution": "FOUND", "supplied_by": {"source_id": "S9514", "anchor": "L1", "quote": "x"}}
    ob["stage2_dispositions"][0]["by_dimension"]["d"] = "FOUND"
    ob["dependency_edges"].append({"target_label": "invented-label", "kind": "USAGE", "edge_class": "R2-EVIDENCED",
                                   "source_id": "S9513", "quote": "synthetic line"})
    relint(b, o, DEC)
    print("         unit records:", json.dumps(U(b)["records"])[:200], "...")


report("J1", "FAIL", run_fx(Fixture(), j1), "synthesis claims with no record-level provenance (DECOMPOSED)")

def d5b(b, o, l, s, c, reg):
    """Honest NOT-CONSUMED row source (pages missing) with NO escalation in the final object: the object carries no
    trace that a required file was unread (item 10 / R17 disclosure)."""
    l[:] = [e for e in l if e["page"]["source_id"] != "S9513"]
    for run_, recs in U(b)["records"].items():
        for r in recs:
            if r["source_id"] == "S9513":
                r["reading_state"] = "NOT-CONSUMED"
                r["ack_tokens"] = []


report("D5b", "FAIL", run_fx(Fixture(), d5b), "unread required row source, object silent (no CONTRACT-DEVIATION escalation)")


def e6(b, o, l, s, c, reg):
    """Shared run id where the later label's files include the earlier label's reads (cross-label mixing)."""
    lab, run = "syn-sub", f"{BATCH}-R5-L01"            # same run as syn-single; files subset of syn-single's files
    rows, s2 = {"S9501"}, set()
    l.extend(byte_log(run, lab, "S9501", c["S9501"]))
    recs = [{"source_id": "S9501", "reading_state": "WHOLE-FILE", "ack_tokens": tokens(c["S9501"]),
             "pair_evidence_checks": [{"pair_id": "RP9", "supports": "YES", "quote": "q"}]}]
    ob = TV.obj_for(lab, rows, s2)
    o.append(ob)
    s[lab] = TV.slice_for(lab, rows, s2, [{"pair_id": "RP9", "a": lab, "b": "other", "what_says_this": "basis S9501",
                                          "basis": "b"}])
    new = {lab: {"path": "SINGLE", "files": ["S9501"], "row_sources": ["S9501"], "sizes": {"S9501": 30_000},
                 "runs": {"single": run, "units": [], "synthesis": None}, "records": {run: recs}, "lint": lint_of(ob, [])}}
    new.update(b["labels"])
    b["labels"] = new


report("E6", "FAIL", run_fx(Fixture(), e6), "two labels share one single-context run id (one agent, two labels)")

print()
fp = [r for r in RESULTS if r[3]]
print(f"SUMMARY: {len(RESULTS)} attacks, {len(fp)} false PASS: {[r[0] for r in fp]}")
