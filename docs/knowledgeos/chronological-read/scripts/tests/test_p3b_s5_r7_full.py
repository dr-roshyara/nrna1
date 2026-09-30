#!/usr/bin/env python3
"""R7 acceptance matrix THROUGH THE PRODUCTION VERIFIER (p3b_s5_verify.verify; frozen addendum v2.3 §11, T01–T97).

Positive control first (SINGLE + DECOMPOSED + EMPTY → BATCH-PASS); then every negative case as ONE mutation of the
valid synthetic batch (r7_full_fixture), expected to yield BATCH-FAIL (or BATCH-UNDETERMINED) with a failure owned by
the named predicate. Positive boundary controls (T44, T65, T72, T84, T91, T93) must stay BATCH-PASS. Statistics (T30–T37,
T68–T70) run through estimate_v7 in test_p3b_s5_r7_stats; static contract tests (T43, T80, T94) are marked. v2.4
(G-LOG-0089): NDB-1/DC-1 resolved by META rows; T98, T103, T104 here, INV-LEX in test_p3b_s5_r7_v24_proposal.
No corpus, no reader process, no repository write (temp dirs only).
  cd scripts/tests && PYTHONPATH=.:.. python3 -m unittest test_p3b_s5_r7_full
"""
import copy
import hashlib
import json
import os
import re
import shutil
import sys
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
sys.path.insert(0, os.path.dirname(HERE))
import r7_full_fixture as FX                        # noqa: E402
import r7_harness_fixture as hf                     # noqa: E402
import r7_execution_fixture as xf                   # noqa: E402
import p3b_s5_r7_universe as U                      # noqa: E402
import p3b_s5_r7_verify as V7                       # noqa: E402
import p3b_s5_r7_reconstruction as C                # noqa: E402

r5 = U.r5
STATE = None
CONTRA = None


def contra_mut(ctx, plan):
    """SGL label: point 1 CONTRADICTS point 0 (a genuine relation; its CONTRADICTION fact is generated)."""
    o = next(x for x in ctx["objs"] if x["working_label"] == "relation-8-tuple-vs-triple-reduction")
    tl = o["timeline"]
    tl[1]["change_vs_previous"] = "CONTRADICTS"
    o["timeline_summary"]["contradicted_by"] = [{"source_id": tl[1]["source_id"], "contradicts": tl[0]["source_id"]}]


def contra():
    global CONTRA
    if CONTRA is None:
        CONTRA = FX.base(pre_obj_mut=contra_mut)
    return CONTRA


def base():
    global STATE
    if STATE is None:
        STATE = FX.base()
    return STATE


def runs(st):
    o = U.run_owner(st["plan"])
    big = st["big"]
    units = sorted(r for r, (lab, role, _) in o.items() if lab == big and role == "UNIT")
    syn = next(r for r, (lab, role, _) in o.items() if lab == big and role == "SYNTHESIS")
    sgl = next(r for r, (lab, role, _) in o.items() if lab == "relation-8-tuple-vs-triple-reduction")
    rev = next(r for r, (lab, role, _) in o.items() if lab == "reverification-updated-dependency-chain-and-matrix")
    return units[0], units[1], syn, sgl, rev


SGL_LAB, REV_LAB = "relation-8-tuple-vs-triple-reduction", "reverification-updated-dependency-chain-and-matrix"


def obj(st, lab):
    return FX.objs_of(st, lab)


def edit_bash(fn):
    return lambda cs: [dict(c, input=dict(c["input"], command=fn(c["input"]["command"]))) if c["name"] == "Bash" else c for c in cs]


def insert(call, at=1):
    return lambda cs: cs[:at] + [call] + cs[at:]


def drop_reads_of(sid):
    return lambda cs: [c for c in cs if not (c["name"] == "Bash" and c["input"]["command"].endswith(" " + sid))]


class R7(unittest.TestCase):
    def fails(self, mutate, pred, needle, st=None, verdict="BATCH-FAIL"):
        body = FX.verify(st or base(), mutate)
        self.assertEqual(body["result"], verdict, [x[:160] for x in body["failures"][:6]])
        if pred:
            self.assertEqual(body["predicates"][pred], "F" if verdict == "BATCH-FAIL" else "U", body["predicates"])
        self.assertTrue(any(needle in x for x in body["failures"]), [x[:160] for x in body["failures"][:12]])
        return body

    def passes(self, st=None, mutate=None):
        body = FX.verify(st or base(), mutate)
        self.assertEqual(body["result"], "BATCH-PASS", [x[:200] for x in body["failures"][:12]])
        self.assertEqual(body["predicates"], {"U": "T", "W": "T", "E": "T", "R": "T"})
        return body


# ---------------------------------------------------------------- positive control and verdict layers
class PositiveControl(R7):
    def test_T38_single_decomposed_empty_batch_passes(self):
        paths = {L["path"] for L in base()["plan"]["labels"].values()}
        self.assertEqual(paths, {"SINGLE", "DECOMPOSED", "EMPTY"})
        body = self.passes()
        self.assertEqual(body["gates"]["R7"], "BATCH-PASS")

    def test_T80_output_schema_has_only_the_batch_layer(self):
        body = FX.verify(base())
        self.assertIn(body["result"], ("BATCH-PASS", "BATCH-FAIL", "BATCH-UNDETERMINED"))
        self.assertNotEqual(body["result"], "PASS")
        self.assertEqual(set(body["predicates"]), {"U", "W", "E", "R"})
        self.assertFalse(any(k.startswith(("statistics", "program", "STATISTICS", "PROGRAM")) for k in body))

    def test_T77_T81_T82_program_accepted(self):
        ok = {"OB1": "BATCH-PASS", "OB2": "BATCH-PASS"}
        self.assertTrue(V7.program_accepted(ok, "STATISTICS-VALID"))
        self.assertFalse(V7.program_accepted(ok, "STATISTICS-NOT-ESTIMABLE"))            # T77
        self.assertFalse(V7.program_accepted(ok, "STATISTICS-REJECTED"))                 # T81
        self.assertFalse(V7.program_accepted(dict(ok, OB2="BATCH-UNDETERMINED"), "STATISTICS-VALID"))   # T82
        self.assertFalse(V7.program_accepted({}, "STATISTICS-VALID"))

    def test_T79_kleene_F_dominates_U_and_T78_undetermined(self):
        self.assertEqual(V7.compose({"U": "T", "W": "U", "E": "F", "R": "T"}), "BATCH-FAIL")
        self.assertEqual(V7.compose({"U": "T", "W": "U", "E": "T", "R": "T"}), "BATCH-UNDETERMINED")
        body = FX.verify(base(), lambda st: st.update(no_archive=True))
        self.assertEqual(body["result"], "BATCH-UNDETERMINED")
        self.assertEqual(body["predicates"]["W"], "U")
        _, _, _, sgl, _ = runs(base())
        body = FX.verify(base(), lambda st: (st.update(no_archive=True), obj(st, SGL_LAB)["timeline"][0].pop("change_vs_previous")))
        self.assertEqual(body["result"], "BATCH-FAIL")

    def test_revision_6_is_superseded(self):
        body = FX.verify(base(), lambda st: st.update(header_extra={"contract": {"revision": 6, "sha256": U.ADDENDUM_SHA256,
                                                                                   "path": U.ADDENDUM_PATH}}))
        self.assertEqual(body["result"], "FAIL")
        self.assertTrue(any("revision 6 is superseded" in x for x in body["failures"]))


# ---------------------------------------------------------------- Universe (RC-1)
class Universe(R7):
    def test_T01_null_source(self):
        self.fails(lambda st: obj(st, SGL_LAB)["timeline"][0].update(source_id=None), "U", "not an S-id")

    def test_T02_missing_source(self):
        self.fails(lambda st: obj(st, SGL_LAB)["timeline"][0].pop("source_id"), "U", "without source_id")

    def test_T03_unknown_sid_key(self):
        self.fails(lambda st: obj(st, SGL_LAB)["timeline"][0].update(x_notes="see S0001"), "U", "untyped S-id-bearing")

    def test_T04_non_object_timeline_element(self):
        self.fails(lambda st: obj(st, SGL_LAB)["timeline"].append("S0001 restates"), "U", "is not an object")

    def test_T07_duplicate_relation(self):
        def m(st):
            cb = obj(st, SGL_LAB)["timeline_summary"]["contradicted_by"]
            cb.append(dict(cb[0]))
        self.fails(m, "U", "duplicate relation", st=contra())

    def test_T09_undeclared_run_directory(self):
        self.fails(lambda st: st.update(post=lambda r, a, i: xf.put(r, f"{U.LEDGER}/{FX.OB}-R7-L03U09/READ-LOG.jsonl", "{}\n")),
                   "U", "neither a planned run")

    def test_T10_legacy_R2_read_after_dispatch(self):
        def m(st):
            u1, _, syn, _, _ = runs(st)
            st["entry_extra"] = {"legacy_dirs": [f"{FX.OB}-R2"]}
            st["post"] = lambda r, a, i: xf.put(r, f"{U.LEDGER}/{FX.OB}-R2/READ-LOG.jsonl", '{"run_id": "legacy"}\n')
            cmd = xf.reader_cmd(syn, FX.OB, st["big"], sorted(st["plan"]["labels"][st["big"]]["files"])[0], 1).replace(syn, f"{FX.OB}-R2")
            st["hooks"] = {syn: insert(hf.bash(cmd, "x"))}
        body = self.fails(m, "U", "legacy directories differ")
        self.assertEqual(body["predicates"]["W"], "F")

    def test_T11_unknown_namespace(self):
        self.fails(lambda st: st.update(post=lambda r, a, i: xf.put(r, f"{U.LEDGER}/{FX.OB}-R8-L01/x.jsonl", "{}\n")),
                   "U", "neither a planned run")

    def test_T13_revision_and_contract_binding(self):
        for c_ in ({"revision": "7", "sha256": U.ADDENDUM_SHA256, "path": U.ADDENDUM_PATH},
                   {"revision": 7, "sha256": "0" * 64, "path": U.ADDENDUM_PATH}):
            body = FX.verify(base(), lambda st, c_=c_: st.update(header_extra={"contract": c_}))
            self.assertIn(body["result"], ("FAIL", "BATCH-FAIL"))
            self.assertTrue(any("R7-U" in x and ("integer" in x or "sha256" in x) for x in body["failures"]))

    def test_T39_T40_T41_T42_schema_validation(self):
        self.fails(lambda st: obj(st, SGL_LAB)["timeline"][1].pop("change_vs_previous"), "U", "without change_vs_previous")
        self.fails(lambda st: obj(st, SGL_LAB).update(extra_note="x"), "U", "keys outside the schema")
        self.fails(lambda st: next(a for a in obj(st, SGL_LAB)["absences"].values()).update(resolution="FOUNDX"), None, "FOUNDX")
        self.fails(lambda st: obj(st, SGL_LAB)["timeline"].append(copy.deepcopy(obj(st, SGL_LAB)["timeline"][1])), "U", "duplicate point")

    def test_T95_prohibited_path_in_input_manifest(self):
        def m(st):
            u1 = runs(st)[0]

            def mm(ms):
                ms[u1]["entries"].append({"path": "P3B-HOLDOUT-SEAL.json", "owner_run": u1, "category": "PLAN",
                                          "sha256": "0" * 64, "declared_role": "UNIT"})
            st["manifest_mut"] = mm
        self.fails(m, "U", "prohibited path")

    def test_T89_frozen_manifest_hash_mismatch(self):
        def m(st):
            u1 = runs(st)[0]
            st["manifest_mut"] = lambda ms: ms[u1]["entries"][0].update(sha256="1" * 64)
        self.fails(m, "U", "frozen-integrity failure")

    def test_RC03_missing_manifest_entry_file_fails_its_integrity_check(self):
        """Re-check RC-03 (G-LOG-0092): a frozen I(run) entry whose file is absent at verification is a frozen-integrity
        FAILURE of that entry, never a skipped check."""
        def m(st):
            u1 = runs(st)[0]

            def drop(root, adir, info):
                e = info["manifests"][u1]["entries"][0]
                os.remove(os.path.join(root, e["path"]))
            st["post"] = drop
        self.fails(m, "U", "missing at verification (frozen-integrity failure)")

    def test_T97_misdeclared_synthesis_manifest(self):
        def m(st):
            u1, _, syn, _, _ = runs(st)
            st["manifest_mut"] = lambda ms: ms[syn].update(entries=[e for e in ms[syn]["entries"] if f"/{u1}/" not in e["path"]])
        body = self.fails(m, "U", "≠ the Universe derivation")
        self.assertEqual(body["predicates"]["W"], "F")

    def test_T98_ndb1_meta_mention_passes(self):
        """v2.4 (G-LOG-0089, NDB-1): an S-id mention in a rev3 free-text location is typed META and has no force."""
        def m(st):
            s = obj(st, SGL_LAB)["timeline"][0]["source_id"]
            next(iter(obj(st, SGL_LAB)["absences"].values()))["reason"] = f"{s} mentions it only in passing"
        self.passes(mutate=m)

    def test_T103_T104_dc1_escalation_field(self):
        def own(st):
            s = obj(st, SGL_LAB)["timeline"][0]["source_id"]
            obj(st, SGL_LAB)["escalations"].append({"field": f"timeline[{s}].order", "reason": "SCHEMA-LIMITATION", "detail": "x"})
        body = FX.verify(base(), own)          # typed; G-09 still requires a SCHEMA-LIMITATION register record (unchanged)
        self.assertEqual(body["predicates"]["U"], "T", body["failures"][:4])
        self.assertFalse(any("escalations[*]" in x for x in body["failures"]))

        def foreign(st):
            obj(st, SGL_LAB)["escalations"].append({"field": f"{obj(st, SGL_LAB)['timeline'][0]['source_id']} supports this",
                                                     "reason": "SCHEMA-LIMITATION", "detail": "x"})
        self.fails(foreign, "U", "not an own-object location")


# ---------------------------------------------------------------- Witness (RC-2)
class Witness(R7):
    def test_T12_T20_T21_T22_identity(self):
        u1, u2, _, _, _ = runs(base())
        for old, new in (("--label " + base()["big"], "--label " + SGL_LAB), (f"--batch {FX.OB}", "--batch OB9999"),
                         (f"--run {u1}", f"--run {u2}")):
            self.fails(lambda st, o=old, n=new: st.update(hooks={u1: edit_bash(lambda c: c.replace(o, n))}), "W", "W2")

    def test_T19_synthesis_reader_call(self):
        def m(st):
            _, _, syn, _, _ = runs(st)
            f = sorted(st["plan"]["labels"][st["big"]]["files"])[0]
            st["hooks"] = {syn: insert(hf.bash(xf.reader_cmd(syn, FX.OB, st["big"], f, 1), "x"))}
        self.fails(m, "W", "W8")          # a synthesis run cannot even form a canonical reader call

    def test_T24_truncated_T25_T28_page_hash_T26_duplicate_T27_reordered(self):
        u1 = runs(base())[0]

        def tpath(i):
            return os.path.join(FX.CURRENT["arch"], "subagents", f"agent-{i['agents'][u1]}.jsonl")

        def trunc(r, a, i):
            ls = open(tpath(i)).read().splitlines()
            open(tpath(i), "w").write("\n".join(ls[: len(ls) // 2]) + "\n")
        self.fails(lambda st: st.update(post=trunc), "W", "W1-COUNT-MISMATCH")

        def midline(r, a, i):
            b = open(tpath(i), "rb").read()
            open(tpath(i), "wb").write(b[:-50])
        self.fails(lambda st: st.update(post=midline), "W", "W1-PARSE")
        flip = lambda c: c
        self.fails(lambda st: st.update(hooks={u1: lambda cs: [dict(c, out=re.sub(r"page sha256 ([0-9a-f])", lambda m: "page sha256 " + ("0" if m.group(1) != "0" else "1"), c["out"], count=1))
                                                               if c["name"] == "Bash" else c for c in cs]}), "W", "page sha256")

        def dup(r, a, i):
            ls = open(tpath(i)).read().splitlines()
            open(tpath(i), "w").write("\n".join(ls + ls[1:3]) + "\n")
        self.fails(lambda st: st.update(post=dup), "W", "W1-CHAIN")

        def swap(r, a, i):
            ls = open(tpath(i)).read().splitlines()
            ls[1], ls[2] = ls[2], ls[1]
            open(tpath(i), "w").write("\n".join(ls) + "\n")
        self.fails(lambda st: st.update(post=swap), "W", "W1-CHAIN")

    def test_T49_T50_T51_T52_T53_capability_closure(self):
        u1 = runs(base())[0]
        cases = ((hf.bash("cat docs/knowledgeos/chronological-read/02-FILES.jsonl", "..."), "W8"),
                 (hf.bash("ls", "x"), "W8 unauthorized Bash"),
                 (hf.bash(xf.reader_cmd(u1, FX.OB, base()["big"], "S0001", 1) + " > /tmp/x", ""), "noncanonical"),
                 (hf.write("/tmp/elsewhere.json", "{}"), "W8 Write outside"),
                 (hf.read("/x/S9990-holdout.txt", "x"), "SEAL"))
        for call, needle in cases:
            self.fails(lambda st, c=call: st.update(hooks={u1: insert(c)}), "W", needle)

    def test_T54_T55_T56_T57_T58_T59_completeness(self):
        u2 = runs(base())[1]
        self.fails(lambda st: st.update(post=lambda r, a, i: os.remove(os.path.join(a, "subagents", f"agent-{i['agents'][u2]}.jsonl"))),
                   "W", "W1-TRANSCRIPT-MISSING")

        def main_mut(fn):
            return lambda st: st.update(hooks={"main": fn})

        def aid_of(st):
            return "a" + hashlib.sha256(u2.encode()).hexdigest()[:16]

        def no_count(its):
            for it in its:
                if it["kind"] == "notification" and it["task_id"] == "a" + hashlib.sha256(u2.encode()).hexdigest()[:16]:
                    it["tool_uses"] = None
            return its
        self.fails(main_mut(no_count), "W", "W1-HARNESS-COUNT-MISSING")
        self.fails(lambda st: st.update(hooks={u2: lambda cs: []}), "W", "W1-ZERO-TOOL")

        def twice(its):
            n = next(it for it in its if it["kind"] == "notification" and it["task_id"] == "a" + hashlib.sha256(u2.encode()).hexdigest()[:16])
            return its + [dict(n, t=n["t"] + 40)]
        self.fails(main_mut(twice), "W", "W1-NOTIFICATION-MULTIPLE")

        def dupfile(r, a, i):
            p = os.path.join(a, "subagents", f"agent-{i['agents'][u2]}.jsonl")
            shutil.copy(p, p.replace(".jsonl", "-copy.jsonl"))
        self.fails(lambda st: st.update(post=dupfile), "W", "W1-TRANSCRIPT-DUPLICATE")

        def none(its):
            return [it for it in its if not (it["kind"] == "notification" and it["task_id"] == "a" + hashlib.sha256(u2.encode()).hexdigest()[:16])]
        self.fails(main_mut(none), "W", "W1-NOTIFICATION-ABSENT")

    def test_T16_T18_declared_stages_must_equal_derived(self):
        self.fails(lambda st: st.update(post=lambda r, a, i: xf.put(r, f"{U.LEDGER}/{FX.OB}-R7/R7-PROVENANCE.jsonl",
                   json.dumps({"label": SGL_LAB, "stage": "produced", "t": "2026-01-01T00:00:00Z"}) + "\n")), "W", "declared stage")

    def test_T60_T61_T62_monotonicity(self):
        adr = f"{U.LEDGER}/{FX.OB}-R7"

        def rm(r, a, i):
            p = os.path.join(r, adr, "WITNESS-UNIT.jsonl")
            ls = open(p).read().splitlines()
            open(p, "w").write("\n".join(ls[1:]) + "\n")
        body = FX.verify(base(), lambda st: st.update(post=rm))
        self.assertEqual(body["result"], "BATCH-PASS")          # removing a unit line keeps W_unit ⊆ W_final (subset)

        def mod(r, a, i):
            p = os.path.join(r, adr, "WITNESS-UNIT.jsonl")
            ls = open(p).read().splitlines()
            ls[0] = ls[0].replace('"kind":"dispatch"', '"kind":"dispatch","x":1')
            open(p, "w").write("\n".join(ls) + "\n")
        self.fails(lambda st: st.update(post=mod), "W", "W7a")

        def ext(r, a, i):
            p = os.path.join(r, adr, "WITNESS-UNIT-DIGESTS.json")
            d = json.load(open(p))
            d["extractor_sha256"] = "0" * 64
            json.dump(d, open(p, "w"))
        self.fails(lambda st: st.update(post=ext), "W", "extractor_sha256 changed")

        def drop_final(r, a, i):                                                    # T60 proper: a unit record absent in the final witness
            p = os.path.join(r, adr, "WITNESS.jsonl")
            ls = open(p).read().splitlines()
            unit = set(open(os.path.join(r, adr, "WITNESS-UNIT.jsonl")).read().splitlines())
            open(p, "w").write("\n".join(l for l in ls if l not in unit or l.find('"kind":"read"') < 0) + "\n")
        self.fails(lambda st: st.update(post=drop_final), "W", "W7")

    def test_T83_archive_digest_mismatch(self):
        self.fails(lambda st: st.update(post=lambda r, a, i: open(os.path.join(a, "main.jsonl"), "a").write(
            json.dumps({"type": "user", "uuid": "zz", "parentUuid": None, "timestamp": "2026-10-01T10:00:00.000Z"}) + "\n")),
                   "W", "W7")

    def test_T84_T93_positive_input_reads(self):
        def m(st):
            u1 = runs(st)[0]
            plan_rel = f"{FX.prep.SLICE_ROOT}/{FX.OB}.R7-PLAN.json"

            def hook(cs):
                with open(os.path.join(FX.CURRENT["root"], plan_rel), encoding="utf-8") as f:
                    return cs[:1] + [hf.read(os.path.join(FX.CURRENT["root"], plan_rel), hf.cat_n(f.read()))] + cs[1:]
            st["hooks"] = {u1: hook}
        self.passes(mutate=m)

    def test_T85_T86_T87_T88_input_reads_outside_I_run(self):
        u1, u2, syn, sgl, _ = runs(base())
        self.fails(lambda st: st.update(hooks={syn: insert(hf.read("/nonexistent/README.md", "x"))}), "W", "read outside I(run)")

        def other_unit(cs):
            p = os.path.join(FX.CURRENT["root"], U.LEDGER, u2, "file-reading-records.jsonl")
            return cs[:1] + [hf.read(p, "     1\tx\n")] + cs[1:]
        self.fails(lambda st: st.update(hooks={u1: other_unit}), "W", "read outside I(run)")

        def other_label(cs):
            p = os.path.join(FX.CURRENT["root"], U.LEDGER, sgl, "file-reading-records.jsonl")
            return cs[:1] + [hf.read(p, "     1\tx\n")] + cs[1:]
        self.fails(lambda st: st.update(hooks={syn: other_label}), "W", "read outside I(run)")
        self.fails(lambda st: st.update(hooks={u1: insert(hf.read("/data/S9991.txt", "x"))}), "W", "SEAL")

    def test_T90_displayed_input_content_mismatch(self):
        u1 = runs(base())[0]
        self.fails(lambda st: st.update(hooks={u1: lambda cs: [dict(cs[0], out=cs[0]["out"].replace('"', "'", 1))] + cs[1:]}),
                   "W", "content_match false")

    def test_T91_T92_persisted_outputs(self):
        u1 = runs(base())[0]
        st0 = base()
        f = sorted(st0["plan"]["labels"][st0["big"]]["runs"][u1]["files"])[0]
        out, _ = xf.reader_out(u1, FX.OB, st0["big"], f, st0["contents"][f], 1)

        def persisted(read_name):
            def hook(cs):
                i = next(k for k, c in enumerate(cs) if c["name"] == "Bash")
                c = dict(cs[i], persist="p1.txt")
                # the harness shows a Read as the line-numbered view (cat -n), as characterized 2026-09-27
                return cs[:i] + [c, hf.read(f"/harness/session/tool-results/{read_name}", hf.cat_n(out))] + cs[i + 1:]
            return hook

        def put_file(r, a, i):
            xf.put(a, "tool-results/p1.txt", out)
        self.passes(mutate=lambda st: st.update(hooks={u1: persisted("p1.txt")}, pre_freeze=put_file))
        self.fails(lambda st: st.update(hooks={u1: persisted("other-agent.txt")}, pre_freeze=put_file), "W", "read outside I(run)")


class DC3DecidedBinary(R7):
    """v2.6-DC3 B-form (G-LOG-0099): a required binary file DECIDED in the hash-bound binary-decisions file is carried in
    the frozen plan (`binary_decisions`), is never passed to the reader, has a NOT-CONSUMED record, and its dispositions
    match its decision → covered (G-04, census, reading rule). Every mismatch FAILS. T110–T116."""
    SID = "S1487"                                   # an uncited stage-2 hit file of the SINGLE label (synthetic bytes)

    @staticmethod
    def binary_bytes(s):
        return b"\x00" * len(FX.synth(s))                         # NUL bytes: r5.is_binary → NUL-BYTE (reader refuses)

    def decide(self, st, decision, dispose=None, escalate=None, header=True, plan=True, binary_content=True):
        sgl = runs(st)[3]
        s = self.SID
        dispose = dispose or decision
        escalate = (decision == "NOT-CONSUMED-ESCALATED") if escalate is None else escalate
        st["hooks"] = {sgl: drop_reads_of(s)}
        st["readlog_mut"] = lambda run, rows: [x for x in rows if x["source_ids"] != [s]] if run == sgl else rows
        for r in st["records"][sgl]:
            if r["source_id"] == s:
                r.update(reading_state="NOT-CONSUMED", ack_tokens=[], facts=[])
        o = obj(st, SGL_LAB)
        for d in o.get("stage2_dispositions") or []:
            if d.get("source_id") == s:
                if dispose == "FALSE-HIT":
                    d["method"] = "BINARY-DECIDED"
                    d["by_dimension"] = {k: "FALSE-HIT" for k in d["by_dimension"]}
                else:
                    d["method"] = "NOT-CONSUMED-ESCALATED"
                    d["by_dimension"] = {k: "ESCALATED" for k in d["by_dimension"]}
        if dispose != "FALSE-HIT":                    # an ESCALATED hit makes a GENUINELY-UNDEFINED resolution impossible
            for dim, a in (o.get("absences") or {}).items():
                if isinstance(a, dict) and str(a.get("resolution")).startswith("GENUINELY-UNDEFINED"):
                    a["resolution"] = "ESCALATED"
                    o["escalations"].append({"field": f"absences.{dim}", "reason": "CONTRACT-DEVIATION",
                                             "detail": f"{dim}: census incomplete (a binary file not consumed)"})   # names no file
        if escalate:
            o["escalations"].append({"field": "reading", "reason": "CONTRACT-DEVIATION",
                                     "detail": f"{s} not consumed: binary content, decided {decision} (G-LOG-0096)"})
        if plan:
            st["plan"]["labels"][SGL_LAB]["binary_decisions"] = {s: decision}
        binary = self.binary_bytes(s) if binary_content else FX.synth(s)
        if binary_content:                                     # equal length: the plan sizes do not change
            FX.CONTENT_OVERRIDE[s] = binary                    # (FX.verify loads overrides before the mutation runs)
        body = json.dumps({"artifact": "BINARY-DECISIONS", "authority": "G-LOG-0096", "decisions": [
            {"source_id": s, "decision": decision, "content_sha256": hashlib.sha256(binary).hexdigest(),
             "authorized_by": "G-LOG-0096"}]}, sort_keys=True).encode()
        if header:
            st["header_extra"] = {"r7_binary_decisions_sha256": hashlib.sha256(body).hexdigest(),
                                  "r7_binary_decisions_authority": "G-LOG-0096"}
        prev = st.get("post")

        def post(root, adir, info):
            xf.put(root, U.BINARY_DECISIONS_PATH, body)
            if prev:
                prev(root, adir, info)
        st["post"] = post

    def test_T110_decided_false_hit_passes(self):
        self.passes(mutate=lambda st: self.decide(st, "FALSE-HIT"))

    def test_T111_decided_not_consumed_escalated_passes(self):
        self.passes(mutate=lambda st: self.decide(st, "NOT-CONSUMED-ESCALATED"))

    def test_T112_disposition_mismatch_fails(self):
        self.fails(lambda st: self.decide(st, "FALSE-HIT", dispose="NOT-CONSUMED-ESCALATED", escalate=True),
                   "E", "binary decision")
        self.fails(lambda st: self.decide(st, "NOT-CONSUMED-ESCALATED", dispose="FALSE-HIT", escalate=True),
                   "E", "binary decision")

    def test_T113_not_consumed_without_escalation_fails(self):
        self.fails(lambda st: self.decide(st, "NOT-CONSUMED-ESCALATED", escalate=False), "E", "CONTRACT-DEVIATION")

    def test_T114_undecided_unread_file_still_fails_G04(self):
        """An unread required file that is NOT in the decisions stays uncovered (DC-3 behaviour for undecided files)."""
        body = FX.verify(base(), lambda st: self.decide(st, "NOT-CONSUMED-ESCALATED", header=False, plan=False))
        self.assertEqual(body["result"], "BATCH-FAIL")
        self.assertTrue(any("neither read whole nor scanned" in x for x in body["failures"]), body["failures"][:6])

    def test_T115_decisions_hash_mismatch_fails(self):
        def m(st):
            self.decide(st, "FALSE-HIT")
            st["header_extra"] = {"r7_binary_decisions_sha256": "0" * 64}
        self.fails(m, "U", "binary decisions")

    def test_T116_plan_without_the_decisions_fails(self):
        self.fails(lambda st: self.decide(st, "FALSE-HIT", plan=False), "U", "frozen plan")

    def test_T117_decision_on_a_text_file_fails(self):
        """Reader/verifier agreement: a 'decided binary' that the reader would NOT refuse (text bytes) could let an agent
        skip a text file — it FAILS."""
        self.fails(lambda st: self.decide(st, "FALSE-HIT", binary_content=False), "U", "not binary")

    def test_T118_undecided_binary_required_file_fails(self):
        def m(st):
            FX.CONTENT_OVERRIDE[self.SID] = self.binary_bytes(self.SID)
        self.fails(m, "U", "undecided binary")

    def test_frame_is_unchanged_by_the_decisions(self):
        st = base()
        labels = list(st["plan"]["labels"])
        slices = st["ctx"]["slices"]
        contents = {s: FX.synth(s) for L in st["plan"]["labels"].values() for s in L["files"]}
        a = U.plan_derive_v7(FX.OB, labels, slices, contents, FX.c.files_meta())
        b = U.plan_derive_v7(FX.OB, labels, slices, contents, FX.c.files_meta(), {self.SID: "FALSE-HIT"})
        strip = lambda p: {lab: {k: v for k, v in L.items() if k != "binary_decisions"} for lab, L in p["labels"].items()}
        self.assertEqual(strip(a), strip(b))                     # paths, files, sizes, units, runs: identical
        self.assertEqual(b["labels"][SGL_LAB]["binary_decisions"], {self.SID: "FALSE-HIT"})
        import p3b_s5_r7_stats as S
        self.assertEqual(S.frame_attributes(a, slices), S.frame_attributes(b, slices))

    def test_reader_call_on_a_decided_binary_is_a_W4_stop(self):
        def m(st):
            self.decide(st, "FALSE-HIT")
            sgl = runs(st)[3]
            s = self.SID

            def refuse(cs):
                return cs[:1] + [hf.bash(xf.reader_cmd(sgl, FX.OB, SGL_LAB, s, 1), "REFUSED: BINARY-CONTENT (NUL-BYTE)",
                                         exit_code=1)] + cs[1:]
            st["hooks"] = {sgl: refuse}
        self.fails(m, "W", "W4 refused reader call")


class SliceView(R7):
    """v2.7 EG-2 (G-LOG-0104): every read label has a SLICE-VIEW = the deterministic lossless view of its frozen slice,
    bound in I(run). T119–T121."""
    def view_path(self, root):
        return os.path.join(root, FX.prep.SLICE_ROOT, FX.OB, f"{SGL_LAB}.view.txt")

    def test_T119_views_present_and_bound(self):
        body = self.passes()
        self.assertEqual(body["result"], "BATCH-PASS")

    def test_T120_tampered_view_fails(self):
        def m(st):
            def tamper(root):
                p = self.view_path(root)
                t = open(p, encoding="utf-8").read()
                open(p, "w", encoding="utf-8").write(t.replace("\tN\t", "\tN\t ", 1))
            st["view_mut"] = tamper
        self.fails(m, "U", "slice view")

    def test_T121_missing_view_fails(self):
        self.fails(lambda st: st.update(view_mut=lambda root: os.remove(self.view_path(root))), "U", "missing")


class F01PersistedOutputIdentity(R7):
    """Independent-audit finding F-01 (G-LOG-0091): a persisted-output Read is authorized only for the EXACT path the
    harness announced for this agent, and only if the displayed content equals the archived artifact. A basename
    collision authorizes nothing."""
    ANNOUNCED = "/harness/session/tool-results/p1.txt"

    def setUp(self):
        st0 = base()
        self.u1 = runs(st0)[0]
        f = sorted(st0["plan"]["labels"][st0["big"]]["runs"][self.u1]["files"])[0]
        self.out, _ = xf.reader_out(self.u1, FX.OB, st0["big"], f, st0["contents"][f], 1)

    def mut(self, path_fn, shown=None):
        out = self.out

        def hook(cs):
            i = next(k for k, c in enumerate(cs) if c["name"] == "Bash")
            c = dict(cs[i], persist="p1.txt")
            return cs[:i] + [c, hf.read(path_fn(), hf.cat_n(out if shown is None else shown))] + cs[i + 1:]

        def put_file(r, a, i):
            xf.put(a, "tool-results/p1.txt", out)
        return lambda st: st.update(hooks={self.u1: hook}, pre_freeze=put_file)

    def test_exact_announced_path_passes(self):
        self.passes(mutate=self.mut(lambda: self.ANNOUNCED))

    def test_unrelated_path_same_basename_fails(self):
        self.fails(self.mut(lambda: "/elsewhere/p1.txt"), "W", "read outside I(run)")

    def test_repository_path_outside_I_run_with_colliding_basename_fails(self):
        self.fails(self.mut(lambda: os.path.join(FX.CURRENT["root"], "scripts", "p1.txt")), "W", "read outside I(run)")

    def test_audit_reproducer_sealed_style_path_with_fabricated_content_fails(self):
        path = "/repo/docs/knowledgeos/chronological-read/P3B-HOLDOUT-SEALED-DIR/p1.txt"
        self.fails(self.mut(lambda: path, shown="SECRET CONTENT\n" * 5), "W", "read outside I(run)")

    def test_announced_path_with_fabricated_content_fails(self):
        self.fails(self.mut(lambda: self.ANNOUNCED, shown="FABRICATED\n"), "W", "content_match false")

    def test_path_normalization(self):
        self.passes(mutate=self.mut(lambda: "/harness/session/x/../tool-results/p1.txt"))     # lexically the same path
        self.fails(self.mut(lambda: "p1.txt"), "W", "read outside I(run)")                     # relative: never identity
        self.fails(self.mut(lambda: "/harness/session/tool-results/sub/p1.txt"), "W", "read outside I(run)")


# ---------------------------------------------------------------- Evidence
class Evidence(R7):
    def test_T14_T15_T23_readlog_reconciliation(self):
        u1 = runs(base())[0]
        self.fails(lambda st: st.update(readlog_mut=lambda run, rows: rows + [dict(rows[0])] if run == u1 else rows),
                   "E", "READ-LOG read with no witnessed call")
        self.fails(lambda st: st.update(readlog_mut=lambda run, rows: rows[:-1] if run == u1 else rows),
                   "E", "witnessed read missing from READ-LOG")
        self.fails(lambda st: st.update(readlog_mut=lambda run, rows: rows + [dict(rows[0], refused=True, page=None)] if run == u1 else rows),
                   "E", "refused calls")

    def test_T05_d4_without_contradiction_fact(self):
        def m(st):
            s = obj(st, SGL_LAB)["timeline"][0]["source_id"]
            obj(st, SGL_LAB)["semantic_evidence"]["d4"] = [{"source_id": s, "quote": "fabricated sentence"}]
        self.fails(m, "E", "claim d4:")

    def test_T06_lifecycle_without_fact(self):
        def m(st):
            s = obj(st, SGL_LAB)["timeline"][0]["source_id"]
            o = obj(st, SGL_LAB)
            o["superseded_by_sources"] = [{"source_id": s, "position": C.file_position(s, FX.c.files_meta())}]
            o["timeline_summary"]["current_lifecycle"] = "SUPERSEDED (fixture)"
        self.fails(m, "E", "claim lifecycle:")

    def test_T17_claim_evidence_repointed(self):
        def m(st):
            sgl = runs(st)[3]
            for cid in st["claims"][sgl]:
                st["claims"][sgl][cid] = [{"run": sgl, "source_id": "S0000", "fact_id": "F999"}]
        self.fails(m, "E", "has no valid evidence path")

    def test_T29_quote_on_unwitnessed_pages(self):
        def m(st):
            sgl = runs(st)[3]
            s = obj(st, SGL_LAB)["timeline"][0]["source_id"]
            st["hooks"] = {sgl: drop_reads_of(s)}
            st["readlog_mut"] = lambda run, rows: [x for x in rows if x["source_ids"] != [s]] if run == sgl else rows
        self.fails(m, "E", "WHOLE-FILE without complete witnessed coverage")

    def test_T64_non_permitted_normalization_T96_input_only_quote(self):
        def upper(st):
            sgl = runs(st)[3]
            for r in st["records"][sgl]:
                for f in r["facts"]:
                    f["quote"] = f["quote"].upper()
        self.fails(upper, "E", "quote not anchored")

        def from_input(st):
            sgl = runs(st)[3]
            for r in st["records"][sgl]:
                for f in r["facts"]:
                    f["quote"] = SGL_LAB                       # text of the slice (an input file), not of the source bytes
        self.fails(from_input, "E", "quote not anchored")


class Anchoring(R7):
    """Byte-offset anchoring with UNIQUE source text (content overrides), through the production verifier."""
    SID = None

    def st_unique(self, quote_where):
        st0 = base()
        s = obj(st0, SGL_LAB)["timeline"][0]["source_id"]           # a claim-bearing source (its facts carry the quote)
        lines = "".join(f"line {i:05d} of {s} {FX.marker(s)}\n" for i in range(1200))
        raw = lines.encode()
        pages = r5.byte_pages(raw)
        b = pages[0][1]
        start = raw.rfind(b"\n", 0, b) + 1                     # the numbered line that CROSSES the page boundary
        span = raw[start: raw.find(b"\n", b)].decode()
        p2 = raw[pages[1][0]: pages[1][1]]
        k = p2.find(b"\nline ") + 1
        late = p2[k: p2.find(b"\n", k)].decode()               # a whole numbered line inside page 2 only
        assert raw.count(span.encode()) == 1 and raw.count(late.encode()) == 1 and start < b
        quote = span if quote_where == "span" else late

        def mut(ctx, plan):
            pass
        st = FX.base(pre_obj_mut=mut, overrides={s: raw})
        sgl = runs(st)[3]
        for r in st["records"][sgl]:
            if r["source_id"] == s:
                for f in r["facts"]:
                    f["quote"] = quote
        return st, s, sgl

    def test_T65_cross_page_both_witnessed_passes(self):
        st, s, sgl = self.st_unique("span")
        self.passes(st=st)

    def test_T66_cross_page_one_unwitnessed(self):
        st, s, sgl = self.st_unique("span")
        cmd2 = f"--page 2 {s}"
        self.fails(lambda x: x.update(hooks={sgl: lambda cs: [c for c in cs if not (c["name"] == "Bash" and c["input"]["command"].endswith(cmd2))]},
                                      readlog_mut=lambda run, rows: [y for y in rows if not (y["source_ids"] == [s] and y["page"]["page"] == 2)] if run == sgl else rows),
                   "E", "quote not anchored", st=st)

    def test_T63_only_occurrence_on_unwitnessed_page(self):
        st, s, sgl = self.st_unique("late")
        cmd2 = f"--page 2 {s}"
        self.fails(lambda x: x.update(hooks={sgl: lambda cs: [c for c in cs if not (c["name"] == "Bash" and c["input"]["command"].endswith(cmd2))]},
                                      readlog_mut=lambda run, rows: [y for y in rows if not (y["source_ids"] == [s] and y["page"]["page"] == 2)] if run == sgl else rows),
                   "E", "quote not anchored", st=st)


# ---------------------------------------------------------------- Reconstruction
class Reconstruction(R7):
    def test_T08_contradictory_duplicate_pair_check(self):
        def m(st):
            sgl = runs(st)[3]
            r = st["records"][sgl][0]
            r["pair_evidence_checks"] += [{"pair_id": "RP0001", "supports": "YES", "quote": FX.marker(r["source_id"])},
                                          {"pair_id": "RP0001", "supports": "NO", "quote": FX.marker(r["source_id"])}]
        self.fails(m, "R", "duplicate pair check")

    def test_T44_not_established_contradiction_is_valid(self):
        def mut(ctx, plan):
            contra_mut(ctx, plan)
            o = next(x for x in ctx["objs"] if x["working_label"] == SGL_LAB)
            for p in o["timeline"][:2]:
                p["date_applies_to_file"] = "NOT-CONFIRMED"
        st = FX.base(pre_obj_mut=mut)
        o = obj(st, SGL_LAB)
        self.assertTrue(o["timeline_summary"]["contradicted_by"])
        self.assertEqual(set(C.contradiction_precedence(o, FX.c.files_meta()).values()), {"NOT-ESTABLISHED"})
        self.passes(st=st)

    def test_T45_T74_lateness_without_established_precedence(self):
        def m(st):
            o = obj(st, SGL_LAB)
            p = next(x for x in o["timeline"] if x["change_vs_previous"] == "EXTENDS")
            p["date_applies_to_file"] = "NOT-CONFIRMED"
            o["timeline_summary"]["later_refinement"] = [p["source_id"]]
        self.fails(m, "R", "without ESTABLISHED precedence")

    def test_T46_T47_T48_contradiction_target(self):
        other = obj(contra(), REV_LAB)["timeline"][0]["source_id"]                     # a point of ANOTHER object
        for t_, needle in ((lambda s0, s1: other, "not a timeline point"), (lambda s0, s1: s1, "contradicts itself"),
                           (lambda s0, s1: "S0001", "not a timeline point")):
            def m(st, t_=t_):
                o = obj(st, SGL_LAB)
                s0, s1 = o["timeline"][0]["source_id"], o["timeline"][1]["source_id"]
                o["timeline_summary"]["contradicted_by"].append({"source_id": s1, "contradicts": t_(s0, s1)})
            self.fails(m, "R", needle, st=contra())

    def test_T72_S4_restates_S3_may_contradict_S1(self):
        def mut(ctx, plan):
            o = next(x for x in ctx["objs"] if x["working_label"] == SGL_LAB)
            tl = o["timeline"]
            tl[1]["change_vs_previous"], tl[2]["change_vs_previous"] = "CONTRADICTS", "RESTATES"
            o["timeline_summary"]["contradicted_by"] = [{"source_id": tl[1]["source_id"], "contradicts": tl[0]["source_id"]},
                                                        {"source_id": tl[2]["source_id"], "contradicts": tl[0]["source_id"]}]
        self.passes(st=FX.base(pre_obj_mut=mut))

    def test_T73_contradicts_point_missing_from_contradicted_by(self):
        self.fails(lambda st: obj(st, SGL_LAB)["timeline_summary"].update(contradicted_by=[]), "R", "missing from contradicted_by",
                   st=contra())

    def test_T75_summary_entry_not_a_timeline_point(self):
        self.fails(lambda st: obj(st, SGL_LAB)["timeline_summary"].update(later_support=["S0001"]), "R", "not a timeline point")

    def test_T76_rejected_by_point_not_whole_file(self):
        def mut(ctx, plan):
            o = next(x for x in ctx["objs"] if x["working_label"] == SGL_LAB)
            o["timeline"][1]["change_vs_previous"] = "RETRACTS"
            o["timeline_summary"]["rejected_by"] = [o["timeline"][1]["source_id"]]
        st = FX.base(pre_obj_mut=mut)
        s = obj(st, SGL_LAB)["timeline"][1]["source_id"]

        def m(x):
            sgl = runs(x)[3]
            for r in x["records"][sgl]:
                if r["source_id"] == s:
                    r["reading_state"] = "READ-PARTIAL"
            obj(x, SGL_LAB)["escalations"].append({"field": "reading", "reason": "CONTRACT-DEVIATION", "detail": f"{s} read partially"})
        self.fails(m, "E", "requires whole-file evidence", st=st)

    def test_T71_combo_not_established_contradiction_unanchored_quote(self):
        def mut(ctx, plan):
            contra_mut(ctx, plan)
            o = next(x for x in ctx["objs"] if x["working_label"] == SGL_LAB)
            for p in o["timeline"][:2]:
                p["date_applies_to_file"] = "NOT-CONFIRMED"
        st = FX.base(pre_obj_mut=mut)

        def m(x):
            rev = runs(x)[3]
            for r in x["records"][rev]:
                for f in r["facts"]:
                    if f["kind"] == "CONTRADICTION":
                        f["quote"] = f["quote"].upper()
        body = self.fails(m, "E", "quote not anchored", st=st)
        self.assertEqual(body["predicates"]["R"], "T")


if __name__ == "__main__":
    unittest.main()
