#!/usr/bin/env python3
"""EG-5 / addendum v2.8 (G-LOG-0106; decision package audit-p3b/eg5/EG5-V2.8-DECISION-PACKAGE.md §2.6, §2.7, §8):
the orchestrator tool's `assemble` (the batch assembly <B>-R7 and the EMPTY object of Annex E, `EMPTY-OBJECT v1`), the
RECORD IDS block of `render_prompt`, and the `freeze-final` check of the ASSEMBLED printed hashes.

Tests T110–T129 (acceptance ids of spec v2 §6/§7 and v3 §3), T-01…T-12 (tool unit tests of spec v2 §7; T-09 is
superseded by T123) and T-13′ (the EG-5c residual regression: the unchanged verifier alone still accepts evidence
records on an EMPTY label; the tool refuses them, R3′). T-13 (fixture sanitation) is DROPPED (package §8).

Synthetic only: the complete synthetic R7 batch of r7_full_fixture (temp dirs), synthetic hold-out sets, synthetic
slices for the pure golden tests. Refusal messages and assertion output carry no S-id and no label name.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_r7_assemble
"""
import collections
import contextlib
import hashlib
import io
import json
import os
import shutil
import subprocess
import sys
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(HERE)
sys.path.insert(0, HERE)
sys.path.insert(0, SCRIPTS)
import r7_full_fixture as FX                         # noqa: E402
import r7_execution_fixture as xf                    # noqa: E402
import p3b_s5_ops_testlib as testlib                 # noqa: E402
import p3b_s5_r7_orchestrate as O                    # noqa: E402

U, OB, R7 = FX.U, FX.OB, FX.R7
ADR = f"{U.LEDGER}/{R7}"
EMPTY = FX.EMPTY_LAB
DATE = "2026-10-01"
MODEL = "claude-opus-5-5[1m]"                         # the fixture agents' served model id
OUTS = ("objects.jsonl", "register.jsonl", "p1-gap-capture.jsonl")
STATE = FX.base()
PLAN = STATE["plan"]
LABELS = list(STATE["ctx"]["labels"])
IDX = {lab: L["index"] for lab, L in PLAN["labels"].items()}


# ------------------------------------------------------------------------------------------------ helpers
def final_run(lab):
    return next((r for r, d in PLAN["labels"][lab]["runs"].items() if d["role"] in ("SINGLE", "SYNTHESIS")), None)


def renumber_a(st):
    """v2.8 §2.6 (option (a)): run_id = <B>-R7; n = 1000·L + k per label and kind, k = file order;
    derived_from_records remapped consistently (the probe `probe_eg5b_options.renumber_a`)."""
    ctx = st["ctx"]
    for key, fld, pre in (("reg", "rs_id", ""), ("gap", "gap_id", "G")):
        k, mp = collections.Counter(), {}
        for r in ctx[key]:
            lab = r["working_label"]
            k[lab] += 1
            new = f"{R7}:{OB}:{pre}{1000 * IDX[lab] + k[lab]}"
            mp[r[fld]] = new
            r[fld] = new
            r["run_id"] = R7
        if key == "reg":
            for r in ctx[key]:
                r["derived_from_records"] = [mp.get(x, x) for x in r.get("derived_from_records") or []]


def with_checklist(st):
    """The EMPTY label's slice in_checklist = true, and the production-shaped entry dict label → bool."""
    st["ctx"]["slices"][EMPTY]["in_checklist"] = True
    st["entry_extra"] = dict(st.get("entry_extra") or {}, in_checklist={l: l == EMPTY for l in LABELS})


def rd(root, rel):
    p = os.path.join(root, rel)
    return open(p, "rb").read() if os.path.isfile(p) else None


def jl(data):
    return [json.loads(x) for x in (data or b"").decode("utf-8").split("\n") if x.strip()]


def strip_assembly(root):
    """Drop the assembly files the fixture wrote with T.write (the tool must produce them)."""
    for n in OUTS + ("READ-LOG.jsonl",):
        p = os.path.join(root, ADR, n)
        if os.path.isfile(p):
            os.remove(p)


def tree(root):
    out = {}
    for base, _, fs in os.walk(root):
        for f in fs:
            p = os.path.join(base, f)
            out[os.path.relpath(p, root)] = U.fsha(p)
    return out


def run(root, **kw):
    kw.setdefault("analysis_date", DATE)
    kw.setdefault("model_id", MODEL)
    with testlib.fake_holdout():
        return O.assemble(OB, root=root, **kw)


def printed_text(r):
    return "".join(line + "\n" for line in O.assemble_lines(r))


def set_marker(items, marker, text):
    for it in items:
        if it.get("kind") == "marker" and it["command"].startswith(f": S5-ORCH {marker} batch={OB};"):
            it["out"] = text
    return items


def materialize(mutate=None, post=None, before_assemble=None, assemble=True, marker_text=None, items_mut=None):
    """FX.verify with the fixture's T.write assembly replaced by the tool's output, produced INSIDE the ASSEMBLED
    marker (v2.8 §2.7: its stdout becomes the marker's printed_record_hashes), and the tool's `--check` output inside
    FINAL-VALIDATED. items_mut(items, root, out) may then alter the transcript items. Returns (verifier body, out)."""
    out = {}

    def m(st):
        renumber_a(st)
        if mutate:
            mutate(st)
        if "in_checklist" not in (st.get("entry_extra") or {}):     # the production entry shape (H-1: required)
            sl = st["ctx"]["slices"]
            st["entry_extra"] = dict(st.get("entry_extra") or {}, in_checklist={l: sl[l]["in_checklist"] for l in LABELS})
        user = (st.get("hooks") or {}).get("main")

        def main(items):
            root = FX.CURRENT["root"]
            strip_assembly(root)
            if before_assemble:
                before_assemble(root, out)
            text = fv = "ok"
            if assemble:
                try:
                    out["r"] = run(root)
                    text = printed_text(out["r"])
                    fv = printed_text(run(root, check=True))
                except O.Refused as e:
                    out["refused"] = str(e)
            set_marker(items, "ASSEMBLED", marker_text if marker_text is not None else text)
            set_marker(items, "FINAL-VALIDATED", fv)
            if items_mut:
                items = items_mut(items, root, out)
            return user(items) if user else items
        st.setdefault("hooks", {})["main"] = main
        if post:
            st["post"] = lambda root, adir, info: post(root, adir, info, out)
    body = FX.verify(STATE, m)
    return body, out


_SNAPS = {}


def snapshot(kind="plain"):
    """A persistent copy of the materialized fixture batch (plan, slices, manifest, run outputs; no assembly),
    taken inside the ASSEMBLED marker hook. kind: plain | checklist."""
    if kind not in _SNAPS:
        dst = tempfile.mkdtemp(prefix=f"r7asm-snap-{kind}-")

        def grab(root, out):
            shutil.copytree(root, dst, dirs_exist_ok=True)
        materialize(mutate=with_checklist if kind == "checklist" else None, before_assemble=grab, assemble=False)
        _SNAPS[kind] = dst
    return _SNAPS[kind]


_TEMP = []


def work(kind="plain"):
    dst = tempfile.mkdtemp(prefix="r7asm-work-")
    _TEMP.append(dst)
    shutil.copytree(snapshot(kind), dst, dirs_exist_ok=True)
    return dst


def tearDownModule():
    for d in _TEMP + list(_SNAPS.values()):
        shutil.rmtree(d, ignore_errors=True)


def lines_of(root, rel):
    return [l for l in rd(root, rel).decode("utf-8").split("\n") if l.strip()]


def put_lines(root, rel, lines):
    xf.put(root, rel, "".join(l + "\n" for l in lines))


def edit_entry(root, fn):
    p = os.path.join(root, FX.prep.MANIFEST)
    ls = open(p, encoding="utf-8").read().split("\n")
    for i, l in enumerate(ls[1:], 1):
        if l.strip() and json.loads(l).get("batch_id") == OB:
            e = json.loads(l)
            fn(e)
            ls[i] = json.dumps(e, sort_keys=True, ensure_ascii=False)
    open(p, "w", encoding="utf-8").write("\n".join(ls))


def label_with(kind, role="SINGLE", min_n=1):
    """A label whose final run has role `role` and at least min_n records of kind (reg|gap)."""
    n = collections.Counter(r["working_label"] for r in STATE["ctx"][kind])
    return next(l for l in LABELS if PLAN["labels"][l]["path"] != "EMPTY" and n[l] >= min_n
                and PLAN["labels"][l]["runs"][final_run(l)]["role"] == role)


def failures(body, needle):
    return [f for f in body.get("failures") or [] if needle in f]


# ------------------------------------------------------------------------------------------------ synthetic slices
TZNOTE = FX.v.TIER_Z_NOTE
POP = FX.c.POPULATION_BASIS
TSHA = "f" * 64


def syn_slice(**kw):
    s = {"tier": "Z", "hub": False, "in_checklist": False, "input_manifest_sha256": "a" * 64, "hub_record": None,
         "p3a_pairs": [{"pair_id": "P-1", "relationship": "SAME", "basis": "B1"},
                       {"pair_id": "P-2", "relationship": "SAME", "basis": "B1"},
                       {"pair_id": "P-3", "relationship": "OTHER", "basis": "B2"}],
         "semantic_status_mechanical": {"rule": "ROW-0", "value": None, "note": TZNOTE},
         "search_records": [{"record": "LABEL-HITS", "label": "syn-label", "terms": [], "raw_hits": {}, "ledger_hits": {}},
                            {"record": "DIMENSION", "dimension": "dim-b", "negative_label": "NEGATIVE-CENSUS"},
                            {"record": "DIMENSION", "dimension": "dim-a", "negative_label": "NEGATIVE-PARTIAL"}]}
    s.update(kw)
    return s


SYN_ENTRY = {"contract_sha256": "c" * 64, "in_checklist": {"syn-label": False, "syn-other": True}}


def golden(**over):
    """Annex E (EMPTY-OBJECT v1) for syn_slice(), written out field by field."""
    g = {"working_label": "syn-label", "batch_id": "OB9999", "run_id": "OB9999-R7", "contract_sha256": "c" * 64,
         "input_manifest_sha256": "a" * 64, "tier": "Z", "tier_causing_pair_ids": [], "hub": False,
         "timeline": [],
         "timeline_summary": {"first_lexical": None, "first_conceptual": None, "first_formal": None,
                              "first_operational": None, "first_governance": None, "later_support": [],
                              "later_refinement": [], "contradicted_by": [], "rejected_by": [],
                              "current_lifecycle": "NOT-EVIDENCED-IN-CAPTURE"},
         "semantic_status": None, "semantic_status_rule": "ROW-0", "semantic_status_note": TZNOTE,
         "semantic_evidence": {"d4": [], "d5": []}, "pair_breakdown": {"SAME|B1": 2, "OTHER|B2": 1},
         "type_status": "UNTYPED", "mathematical_status": "UNDECIDABLE-FROM-CORPUS", "primary_layer": "LAYER-UNRESOLVED",
         "secondary_roles": [],
         "escalations": [{"field": "absences", "reason": "OTHER", "detail": O.EMPTY_DETAIL}],
         "births": {k: "NOT-EVIDENCED-IN-CAPTURE" for k in ("lexical", "conceptual", "formal", "operational", "governance")},
         "absences": {d: {"resolution": "ESCALATED", "negative_label": n, "population_basis": POP, "supplied_by": None,
                          "reason": O.EMPTY_REASON}
                      for d, n in (("dim-a", "NEGATIVE-PARTIAL"), ("dim-b", "NEGATIVE-CENSUS"))},
         "stage2_dispositions": [], "census_reading_disagreements": [], "dependency_edges": [], "status_basis": {},
         "track_composition": {}, "hindsight_dependency": [], "superseded_by_sources": [], "proposed_by": "AI-AGENT",
         "evidence_presentation": "V1-PLUS-ROWS", "record_status": "PROPOSED", "model_id": MODEL,
         "generation_parameters": {"producer": "p3b_s5_r7_orchestrate.assemble", "producer_sha256": TSHA,
                                   "rule": "EMPTY-OBJECT v1", "mode": "DETERMINISTIC-NO-MODEL-CALL"},
         "author_role": "AI-AGENT (S5 orchestrator; EMPTY-OBJECT v1 rule)", "analysis_date": DATE}
    g.update(over)
    return g


def eo(sl=None, entry=None, label="syn-label"):
    return O.empty_object("OB9999", label, entry or SYN_ENTRY, sl or syn_slice(), DATE, MODEL, TSHA)


# ================================================================================================ pure: Annex E
class AnnexE(unittest.TestCase):
    def test_T117_golden_tier_z(self):
        self.assertEqual(eo(), golden())

    def test_T117_golden_tier_u_row0_row3_row4(self):
        u0 = syn_slice(tier="U", semantic_status_mechanical={"rule": "ROW-0", "value": None, "note": "NO-PAIRS"})
        self.assertEqual(eo(u0), golden(tier="U", semantic_status_note="NO-PAIRS"))
        u3 = syn_slice(tier="U", semantic_status_mechanical={"rule": "ROW-3", "value": "RECONCILED(x)", "note": None})
        self.assertEqual(eo(u3), golden(tier="U", semantic_status="RECONCILED(x)", semantic_status_rule="ROW-3",
                                        semantic_status_note=O.EMPTY_ROW34_NOTE))
        u4 = syn_slice(tier="U", semantic_status_mechanical={"rule": "ROW-4", "value": "IDENTITY-UNWITNESSED", "note": None})
        self.assertEqual(eo(u4), golden(tier="U", semantic_status="IDENTITY-UNWITNESSED", semantic_status_rule="ROW-4",
                                        semantic_status_note=O.EMPTY_ROW34_NOTE))

    def test_T117_R6_mechanical_rule_outside_rows_0_3_4(self):
        for sl in (syn_slice(tier="U", semantic_status_mechanical={"rule": "ROW-1", "value": "CONTESTED", "note": None}),
                   syn_slice(tier="U", semantic_status_mechanical={}),
                   syn_slice(tier="Z", semantic_status_mechanical={"rule": "ROW-3", "value": "x", "note": None})):
            with self.assertRaises(O.Refused) as cm:
                eo(sl)
            self.assertTrue(str(cm.exception).startswith("R6 "), str(cm.exception)[:80])

    def test_T118_hub_empty_load_escalations_carry_the_hub_record(self):
        hr = {"tier": "Z", "hits": 7}
        sl = syn_slice(hub=True, hub_record=hr, search_records=[
            {"record": "LABEL-HITS", "label": "syn-label", "terms": ["t"], "raw_hits": {"S0001": [[3, 0]]}, "ledger_hits": {}},
            {"record": "LABEL-HITS", "label": "syn-quiet", "terms": [], "raw_hits": {}, "ledger_hits": {}},
            {"record": "DIMENSION", "dimension": "dim-own", "negative_label": "NEGATIVE-CENSUS"},
            {"record": "DIMENSION", "dimension": "dim-quiet", "negative_label": "NEGATIVE-CENSUS", "hits_ref": "syn-quiet"},
            {"record": "DIMENSION", "dimension": "dim-noref", "negative_label": None, "hits_ref": "syn-absent"}])
        o = eo(sl)
        loads = [x for x in o["escalations"] if x["reason"] == "LOAD"]
        self.assertEqual([x["field"] for x in loads], ["absences.dim-noref", "absences.dim-own"])
        self.assertTrue(all(x["hub_record"] == hr and x["detail"] == O.EMPTY_LOAD for x in loads))
        self.assertEqual(o["escalations"][0], {"field": "absences", "reason": "OTHER", "detail": O.EMPTY_DETAIL})
        self.assertIs(o["hub"], True)
        self.assertEqual(set(o["absences"]), {"dim-own", "dim-quiet", "dim-noref"})
        self.assertTrue(all(a["resolution"] == "ESCALATED" for a in o["absences"].values()))
        self.assertNotIn("S0001", json.dumps(o))                          # hits are never copied into the object
        hr["hits"] = 8
        self.assertEqual(loads[0]["hub_record"], {"tier": "Z", "hits": 7})  # a copy, not an alias

    def test_non_hub_empty_has_no_load_escalation(self):
        sl = syn_slice(search_records=[
            {"record": "LABEL-HITS", "label": "syn-label", "terms": ["t"], "raw_hits": {"S0001": [[3, 0]]}, "ledger_hits": {}},
            {"record": "DIMENSION", "dimension": "dim-own", "negative_label": "NEGATIVE-CENSUS"}])
        self.assertEqual([x["reason"] for x in eo(sl)["escalations"]], ["OTHER"])

    def test_T123_T128_checklist_variant_and_byte_determinism(self):
        ck = eo(syn_slice(in_checklist=True), entry={"contract_sha256": "c" * 64, "in_checklist": {"syn-label": True}})
        self.assertEqual(ck["checklist_examined"], list(range(1, 24)))
        self.assertEqual(ck["generation_parameters"]["checklist"], "VACUOUS-OVER-EMPTY-REQUIRED-SET")
        plain = eo()
        self.assertNotIn("checklist_examined", plain)
        self.assertNotIn("checklist", plain["generation_parameters"])
        stripped = json.loads(json.dumps(ck))
        del stripped["checklist_examined"]
        del stripped["generation_parameters"]["checklist"]
        self.assertEqual(U.canon(stripped), U.canon(plain))               # only the two checklist keys differ
        self.assertEqual(U.canon(eo()), U.canon(plain))                   # byte-deterministic, both variants
        self.assertEqual(U.canon(ck), U.canon(eo(syn_slice(in_checklist=True),
                                                  entry={"contract_sha256": "c" * 64, "in_checklist": {"syn-label": True}})))

    def test_T126_R13_entry_dict_disagrees_with_the_slice(self):
        cases = [(syn_slice(), {"contract_sha256": "c" * 64, "in_checklist": {"syn-label": True}}),
                 (syn_slice(in_checklist=True), {"contract_sha256": "c" * 64, "in_checklist": {"syn-label": False}}),
                 (syn_slice(), {"contract_sha256": "c" * 64, "in_checklist": {"syn-other": False}}),   # label missing
                 (syn_slice(), {"contract_sha256": "c" * 64, "in_checklist": ["syn-label"]}),         # not a dict
                 (syn_slice(in_checklist=1), {"contract_sha256": "c" * 64, "in_checklist": {"syn-label": 1}})]
        for sl, entry in cases:
            with self.assertRaises(O.Refused) as cm:
                eo(sl, entry)
            self.assertTrue(str(cm.exception).startswith("R13 "), str(cm.exception)[:80])

    def test_T127_dict_membership_trap(self):
        entry = {"contract_sha256": "c" * 64, "in_checklist": {"syn-label": False, "x": False, "y": False}}
        o = eo(syn_slice(), entry)
        self.assertNotIn("checklist_examined", o)
        self.assertNotIn("checklist", o["generation_parameters"])

    def test_H1_absent_or_null_entry_dict_is_refused_R13(self):
        for entry in ({"contract_sha256": "c" * 64}, {"contract_sha256": "c" * 64, "in_checklist": None}):
            for sl in (syn_slice(), syn_slice(in_checklist=True)):
                with self.assertRaises(O.Refused) as cm:
                    eo(sl, entry)
                self.assertTrue(str(cm.exception).startswith("R13 "), str(cm.exception)[:80])

    def test_T_10_static_scan_of_the_constants(self):
        C = O.C
        consts = [O.EMPTY_DETAIL, O.EMPTY_REASON, O.EMPTY_LOAD, O.EMPTY_ROW34_NOTE, O.EMPTY_AUTHOR_ROLE,
                  O.EMPTY_OBJECT_V1, O.CHECKLIST_VACUOUS, O.EMPTY_PRODUCER, O.EMPTY_MODE]
        for s in consts + [json.dumps(eo(), ensure_ascii=False)]:
            self.assertIsNone(U.SID.search(s), s[:60])
            self.assertIsNone(C.POINTER7.search(s), s[:60])
            self.assertIsNone(FX.v.POINTER.search(s), s[:60])
            self.assertIsNone(__import__("re").search(r"\bOB\d{4}(?:-R7)?[:#]\d+", s), s[:60])
        self.assertIn(U.r5.EMPTY_MARK, O.EMPTY_DETAIL)
        self.assertEqual(O.EMPTY_OBJECT_V1, "EMPTY-OBJECT v1")

    def test_empty_object_passes_the_reconstruction_self_checks(self):
        reg = U.load_frozen_contract(FX.CR)[0]
        for sl in (syn_slice(), syn_slice(in_checklist=True)):
            entry = {"contract_sha256": "c" * 64, "in_checklist": {"syn-label": sl["in_checklist"]}}
            o = eo(sl, entry)
            with testlib.fake_holdout():
                self.assertEqual(O.self_check_violations(o, "OB9999", reg), [])


# ================================================================================================ render_prompt
class RecordIds(unittest.TestCase):
    def block(self, p):
        return [l for l in p.split("\n") if l.startswith("RECORD IDS") or l.startswith("  rs_id") or
                l.startswith("  Your ledger directory")]

    def test_record_ids_block_values(self):
        owners = U.run_owner(PLAN)
        m = {"entries": [{"category": "SLICE-VIEW", "path": "x/view.txt"}]}
        seen = set()
        for run, (lab, role, files) in sorted(owners.items()):
            p = O.render_prompt(run, OB, PLAN, m, xf.READER_ABS, xf.COMMIT, "/root")
            self.assertEqual(p.split("\n", 1)[0], xf.binding(run, OB, lab))
            self.assertEqual(p.count("S5-RUN-BINDING"), 1)
            L = IDX[lab]
            if role in ("SINGLE", "SYNTHESIS"):
                seen.add(role)
                b = "\n".join(self.block(p))
                self.assertIn(f"run_id is {R7}", b)
                self.assertIn(f"rs_id is {R7}:{OB}:<n>", b)
                self.assertIn(f"gap_id is {R7}:{OB}:G<n>", b)
                self.assertIn(f"n = {1000 * L + 1} … {1000 * L + 999}", b)
                self.assertIn(f"YOUR run id {run}", b)
                wr = p.index("WRITE ONLY these files")
                self.assertLess(wr, p.index("RECORD IDS"))
                self.assertLess(p.index("RECORD IDS"), p.index("NO other tool calls"))
            else:
                self.assertNotIn("RECORD IDS", p)       # a UNIT run writes no object / register / P1-gap record
        self.assertEqual(seen, {"SINGLE", "SYNTHESIS"})

    def test_rest_of_the_prompt_unchanged(self):
        run = final_run(label_with("reg"))
        lab = U.run_owner(PLAN)[run][0]
        m = {"entries": [{"category": "SLICE-VIEW", "path": "x/view.txt"}]}
        p = O.render_prompt(run, OB, PLAN, m, xf.READER_ABS, xf.COMMIT, "/root")
        rest = [l for l in p.split("\n") if l not in self.block(p)]
        self.assertEqual(rest[0], xf.binding(run, OB, lab))
        self.assertEqual(len(p.split("\n")) - len(rest), 3)
        self.assertEqual(sum(1 for l in rest if l.strip().startswith("python3 -B ")), 1)


# ================================================================================================ record identity (verifier facts)
class RecordIdentity(unittest.TestCase):
    def test_T110_label_scoped_numbering_passes(self):
        n = collections.Counter(r["working_label"] for r in STATE["ctx"]["reg"] if final_run(r["working_label"]))
        self.assertGreaterEqual(len(n), 2)
        body = FX.verify(STATE, renumber_a)
        self.assertEqual(body["result"], "BATCH-PASS", body["failures"][:5])

    def test_T111_dispatched_id_in_an_object_fails(self):
        lab = label_with("reg")
        body = FX.verify(STATE, lambda st: (renumber_a(st), FX.objs_of(st, lab).update(run_id=final_run(lab))))
        self.assertEqual(body["result"], "BATCH-FAIL")
        self.assertTrue(failures(body, "provenance ids"))

    def test_T112_dispatched_id_in_an_rs_id_fails(self):
        lab = label_with("reg")

        def m(st):
            renumber_a(st)
            r = next(x for x in st["ctx"]["reg"] if x["working_label"] == lab)
            r["rs_id"] = r["rs_id"].replace(f"{R7}:", f"{final_run(lab)}:", 1)
        body = FX.verify(STATE, m)
        self.assertEqual(body["result"], "BATCH-FAIL")
        self.assertTrue(failures(body, "G-07"))

    def test_T113_per_run_numbering_collides(self):
        def m(st):
            for key, fld, pre in (("reg", "rs_id", ""), ("gap", "gap_id", "G")):
                k = collections.Counter()
                for r in st["ctx"][key]:
                    k[r["working_label"]] += 1
                    r[fld] = f"{R7}:{OB}:{pre}{k[r['working_label']]}"
                    if key == "reg":
                        r["derived_from_records"] = []
        body = FX.verify(STATE, m)
        self.assertEqual(body["result"], "BATCH-FAIL")
        self.assertTrue(failures(body, "repeated"))

    def test_T114_claim_ref_run_is_the_assembly_id_fails(self):
        def m(st):
            renumber_a(st)
            run, cm = next((r, c) for r, c in sorted(st["claims"].items()) if c)
            for refs in cm.values():
                for ref in refs:
                    ref["run"] = R7
                break
        body = FX.verify(STATE, m)
        self.assertEqual(body["result"], "BATCH-FAIL")
        self.assertTrue(failures(body, "R7-E"))

    def test_T115_out_of_range_n_passes_the_verifier_alone(self):
        """B's probe (probe_overflow_novacuous): a non-colliding out-of-range n is invisible to the verifier, so the
        assembly refusal R11 is necessary."""
        def m(st):
            renumber_a(st)
            st["ctx"]["reg"][0]["rs_id"] = f"{R7}:{OB}:999999"
        self.assertEqual(FX.verify(STATE, m)["result"], "BATCH-PASS")


# ================================================================================================ integration
class Integration(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        seen = {}

        def post(root, adir, info, out):
            seen["files"] = {n: rd(root, f"{ADR}/{n}") for n in OUTS}
            seen["runs"] = {lab: {n: rd(root, f"{U.LEDGER}/{final_run(lab)}/{n}") for n in ("register.jsonl", "p1-gap.jsonl")}
                            for lab in LABELS if final_run(lab)}
            seen["entry"] = FX.v.load_manifest_entry(root, OB)[1]
            seen["slice"] = json.loads(rd(root, f"{FX.prep.SLICE_ROOT}/{OB}/{EMPTY}.json"))
        cls.body, cls.out = materialize(post=post)
        cls.seen = seen

    def test_T116_assembly_by_the_tool_passes_the_production_verifier(self):
        self.assertNotIn("refused", self.out, self.out.get("refused"))
        self.assertEqual(self.body["result"], "BATCH-PASS", self.body["failures"][:8])
        self.assertEqual(self.body["predicates"], {"U": "T", "W": "T", "E": "T", "R": "T"})
        r = self.out["r"]
        self.assertEqual((r["labels"], r["empty_objects"], r["objects"]), (len(LABELS), 1, len(LABELS)))

    def test_T116_objects_in_manifest_order_and_the_empty_object_is_annex_e(self):
        objs = jl(self.seen["files"]["objects.jsonl"])
        self.assertEqual([o["working_label"] for o in objs], self.seen["entry"]["labels"])
        e = objs[IDX[EMPTY] - 1]
        want = O.empty_object(OB, EMPTY, self.seen["entry"], self.seen["slice"], DATE, MODEL, O.tool_sha256())
        self.assertEqual(e, want)
        self.assertEqual(e["generation_parameters"]["producer_sha256"], U.fsha(O.__file__))

    def test_T116_T_03_blocks_in_manifest_order_file_order_kept_canonical_lines(self):
        for n, src in (("register.jsonl", "register.jsonl"), ("p1-gap-capture.jsonl", "p1-gap.jsonl")):
            want = b"".join(U.canon(x) + b"\n" for lab in self.seen["entry"]["labels"] if lab in self.seen["runs"]
                            for x in jl(self.seen["runs"][lab][src]))
            self.assertEqual(self.seen["files"][n], want, n)
        self.assertEqual(self.seen["files"]["objects.jsonl"],
                         b"".join(U.canon(o) + b"\n" for o in jl(self.seen["files"]["objects.jsonl"])))

    def test_EG5c_empty_label_has_no_register_or_gap_record(self):
        for n in ("register.jsonl", "p1-gap-capture.jsonl"):
            self.assertFalse([x for x in jl(self.seen["files"][n]) if x.get("working_label") == EMPTY])


class Checklist(unittest.TestCase):
    def test_T123_in_checklist_empty_label_vacuous_examination_passes(self):
        seen = {}

        def post(root, adir, info, out):
            seen["objs"] = jl(rd(root, f"{ADR}/objects.jsonl"))
        body, out = materialize(mutate=with_checklist, post=post)
        self.assertEqual(body["result"], "BATCH-PASS", body["failures"][:8])
        e = seen["objs"][IDX[EMPTY] - 1]
        self.assertEqual(e["checklist_examined"], list(range(1, 24)))
        self.assertEqual(e["generation_parameters"]["checklist"], "VACUOUS-OVER-EMPTY-REQUIRED-SET")
        self.assertEqual(out["r"]["checklist_vacuous"], 1)
        self.assertEqual(sum(1 for o in seen["objs"] if "checklist_examined" in o), 1)

    def _rewrite_empty(self, fn):
        def post(root, adir, info, out):
            rel = f"{ADR}/objects.jsonl"
            objs = jl(rd(root, rel))
            fn(objs[IDX[EMPTY] - 1])
            xf.put(root, rel, b"".join(U.canon(o) + b"\n" for o in objs))
        return post

    def test_T124_in_checklist_field_absent_or_incomplete_fails_G09(self):
        for fn in (lambda o: o.pop("checklist_examined"), lambda o: o.update(checklist_examined=list(range(1, 23)))):
            body, _ = materialize(mutate=with_checklist, post=self._rewrite_empty(fn))
            self.assertEqual(body["result"], "BATCH-FAIL")
            self.assertTrue(failures(body, "G-09"))

    def test_T125_field_on_a_non_checklist_empty_label_fails_schema(self):
        body, _ = materialize(post=self._rewrite_empty(lambda o: o.update(checklist_examined=list(range(1, 24)))))
        self.assertEqual(body["result"], "BATCH-FAIL")
        self.assertTrue(failures(body, "checklist_examined on a label not in the checklist"))


class Hub(unittest.TestCase):
    def test_T118_hub_empty_label_through_the_verifier(self):
        hr = {"synthetic_hub_record": True, "tier": "Z"}

        def m(st):
            sl = st["ctx"]["slices"][EMPTY]
            sl["hub"], sl["hub_record"] = True, hr
            own = next(r for r in sl["search_records"] if r.get("record") == "LABEL-HITS" and r.get("label") == EMPTY)
            s = sorted(sl.get("source_meta") or {"S0001": {}})[0]
            own["raw_hits"] = {s: [[0, 0]]}
        seen = {}

        def post(root, adir, info, out):
            seen["o"] = jl(rd(root, f"{ADR}/objects.jsonl"))[IDX[EMPTY] - 1]
        orig = FX.v.c.hubs                                   # a synthetic hub list line for the EMPTY label
        FX.v.c.hubs = lambda: dict(orig(), **{EMPTY: hr})
        try:
            body, out = materialize(mutate=m, post=post)
        finally:
            FX.v.c.hubs = orig
        self.assertNotIn("refused", out, out.get("refused"))
        loads = [x for x in seen["o"]["escalations"] if x["reason"] == "LOAD"]
        self.assertTrue(loads)
        self.assertTrue(all(x["hub_record"] == hr for x in loads))
        self.assertEqual(failures(body, "HUB "), [])
        self.assertEqual(body["result"], "BATCH-PASS", body["failures"][:8])


class Negatives(unittest.TestCase):
    """T-08: per-constant negatives of the EMPTY object through the production verifier."""
    def _fails(self, fn):
        def post(root, adir, info, out):
            rel = f"{ADR}/objects.jsonl"
            objs = jl(rd(root, rel))
            fn(objs[IDX[EMPTY] - 1])
            xf.put(root, rel, b"".join(U.canon(o) + b"\n" for o in objs))
        body, _ = materialize(post=post)
        self.assertEqual(body["result"], "BATCH-FAIL")

    def test_T_08_a_birth(self):
        self._fails(lambda o: o["births"].update(lexical="ESTABLISHED[S0001]"))

    def test_T_08_an_absence(self):
        self._fails(lambda o: next(iter(o["absences"].values())).update(resolution="GENUINELY-UNDEFINED-AFTER-CENSUS"))

    def test_T_08_a_timeline_point(self):
        self._fails(lambda o: o["timeline"].append({"source_id": "S0001", "historical_position": "x", "date_basis": "x",
                                                    "order": 1, "states": {"epistemic_class": "SOURCE", "quote": "q"},
                                                    "change_vs_previous": "FIRST", "date_applies_to_file": "CONFIRMED"}))

    def test_T_08_the_empty_escalation_removed(self):
        self._fails(lambda o: o.update(escalations=[]))

    def test_T_08_an_s_id_in_the_detail(self):
        self._fails(lambda o: o["escalations"][0].update(detail=O.EMPTY_DETAIL + " S0001"))


class Witnessing(unittest.TestCase):
    def test_T120_printed_hashes_recorded_and_freeze_final_checks_them(self):
        seen = {}

        def post(root, adir, info, out):
            arch = os.path.dirname(adir)
            ref = {n: rd(root, f"{ADR}/{n}") for n in ("WITNESS.jsonl", "WITNESS-DIGESTS.json")}
            recs = [json.loads(l) for l in ref["WITNESS.jsonl"].decode("utf-8").split("\n") if l.strip()]
            asm = [r for r in recs if r.get("kind") == "orchestrator" and r.get("marker") == "ASSEMBLED"]
            seen["printed"] = asm[-1]["printed_record_hashes"]
            seen["now"] = {f"{ADR}/{n}": U.fsha(os.path.join(root, ADR, n)) for n in OUTS}

            def ff():
                for n in ref:
                    p = os.path.join(root, ADR, n)
                    if os.path.isfile(p):
                        os.remove(p)
                with testlib.fake_holdout():
                    return O.freeze_final(OB, root=root, archive_root=arch, resolver_factory=FX.Fake)
            ff()
            seen["same"] = {n: rd(root, f"{ADR}/{n}") == ref[n] for n in ref}
            with open(os.path.join(root, ADR, "objects.jsonl"), "ab") as f:
                f.write(b"\n")
            try:
                ff()
            except O.Refused as e:
                seen["refused"] = str(e)
        materialize(post=post)
        for rel, h in seen["now"].items():
            self.assertEqual(seen["printed"].get(rel), h, rel)
        self.assertEqual(seen["same"], {"WITNESS.jsonl": True, "WITNESS-DIGESTS.json": True})
        self.assertIn("ASSEMBLED", seen.get("refused", ""))

    def test_T120_freeze_final_refuses_a_marker_without_printed_hashes(self):
        seen = {}

        def post(root, adir, info, out):
            for n in ("WITNESS.jsonl", "WITNESS-DIGESTS.json"):
                os.remove(os.path.join(root, ADR, n))
            try:
                with testlib.fake_holdout():
                    O.freeze_final(OB, root=root, archive_root=os.path.dirname(adir), resolver_factory=FX.Fake)
            except O.Refused as e:
                seen["refused"] = str(e)
        materialize(post=post, marker_text="ok")
        self.assertIn("ASSEMBLED", seen.get("refused", ""))

    # ---------------------------------------------------------------- H-2: FINAL-VALIDATED (--check) binds the hashes
    def _freeze(self, items_mut):
        """freeze_final on a batch whose transcript items were altered by items_mut; -> 'OK' or the refusal text."""
        seen = {}

        def post(root, adir, info, out):
            for n in ("WITNESS.jsonl", "WITNESS-DIGESTS.json"):
                os.remove(os.path.join(root, ADR, n))
            seen["disk"] = {f"{ADR}/{n}": U.fsha(os.path.join(root, ADR, n)) for n in OUTS}
            try:
                with testlib.fake_holdout():
                    O.freeze_final(OB, root=root, archive_root=os.path.dirname(adir), resolver_factory=FX.Fake)
                seen["res"] = "OK"
            except O.Refused as e:
                seen["res"] = str(e)
            recs = [json.loads(l) for l in (rd(root, f"{ADR}/WITNESS.jsonl") or b"").decode("utf-8").split("\n") if l.strip()]
            seen["recs"] = recs
        _, out = materialize(post=post, items_mut=items_mut)
        self.assertNotIn("refused", out, out.get("refused"))
        return seen

    @staticmethod
    def _zero(name):
        def fn(items, root, out):
            for it in items:
                if it.get("kind") == "marker" and it["command"].startswith(f": S5-ORCH FINAL-VALIDATED batch={OB};"):
                    it["out"] = "".join(("0" * 64 + l[64:] if l.endswith(f"  {ADR}/{name}") else l) + "\n"
                                        for l in it["out"].split("\n") if l)
            return items
        return fn

    def test_H2_baseline_tool_markers_freeze(self):
        self.assertEqual(self._freeze(lambda items, root, out: items)["res"], "OK")

    def test_H2_register_only_mismatch_at_final_validated(self):
        res = self._freeze(self._zero("register.jsonl"))["res"]
        self.assertIn("FINAL-VALIDATED", res)
        self.assertIn("register.jsonl", res)

    def test_H2_p1_gap_only_mismatch_at_final_validated(self):
        res = self._freeze(self._zero("p1-gap-capture.jsonl"))["res"]
        self.assertIn("FINAL-VALIDATED", res)
        self.assertIn("p1-gap-capture.jsonl", res)

    def test_H2_missing_final_validated_marker(self):
        def fn(items, root, out):
            return [it for it in items if not (it.get("kind") == "marker" and
                                               it["command"].startswith(f": S5-ORCH FINAL-VALIDATED batch={OB};"))]
        res = self._freeze(fn)["res"]
        self.assertIn("FINAL-VALIDATED", res)

    def test_H2_forged_later_assembled_marker_after_a_hand_edit(self):
        """ASSEMBLED (tool) → FINAL-VALIDATED (tool --check) → hand edit of register.jsonl → a later ASSEMBLED marker
        echoing the edited files' hashes. The last ASSEMBLED matches the disk; FINAL-VALIDATED does not → refused."""
        def fn(items, root, out):
            with open(os.path.join(root, ADR, "register.jsonl"), "ab") as f:
                f.write(b"\n")
            fv = next(it for it in items if it.get("kind") == "marker"
                      and it["command"].startswith(f": S5-ORCH FINAL-VALIDATED batch={OB};"))
            forged = dict(fv, command=f": S5-ORCH ASSEMBLED batch={OB}; echo", out=FX.assembled_prints(root), t=fv["t"] + 2)
            return items + [forged]
        seen = self._freeze(fn)
        self.assertIn("FINAL-VALIDATED", seen["res"])          # the ASSEMBLED check (run first) passed on the forgery
        self.assertIn("register.jsonl", seen["res"])
        self.assertEqual(seen["recs"], [])                      # nothing frozen


class Order(unittest.TestCase):
    def test_T_05_a_permuted_register_block_fails_the_s3_lint_binding(self):
        lab = label_with("reg", min_n=2)

        def post(root, adir, info, out):
            rel = f"{ADR}/register.jsonl"
            ls = lines_of(root, rel)
            idx = [i for i, l in enumerate(ls) if json.loads(l)["working_label"] == lab][:2]
            ls[idx[0]], ls[idx[1]] = ls[idx[1]], ls[idx[0]]
            put_lines(root, rel, ls)
        body, _ = materialize(post=post)
        self.assertEqual(body["result"], "BATCH-FAIL")
        self.assertTrue(failures(body, "R7-R S3"))


class ResidualEG5c(unittest.TestCase):
    def test_T_13_prime_verifier_alone_accepts_evidence_records_on_an_empty_label(self):
        """EG-5c residual (C-01): the fixture's EMPTY label carries register + P1-gap records in the T.write assembly and
        the UNCHANGED verifier returns BATCH-PASS. If this ever fails, the verifier closed EG-5c (a separate decision)."""
        n_reg = sum(1 for r in STATE["ctx"]["reg"] if r["working_label"] == EMPTY)
        n_gap = sum(1 for r in STATE["ctx"]["gap"] if r["working_label"] == EMPTY)
        self.assertGreater(n_reg, 0)
        self.assertGreater(n_gap, 0)
        self.assertEqual(FX.verify(STATE)["result"], "BATCH-PASS")

    def test_T_13_prime_the_tool_refuses_them(self):
        root = work()
        lab = label_with("reg")
        rel = f"{U.LEDGER}/{final_run(lab)}/register.jsonl"
        extra = [json.dumps(r, sort_keys=True) for r in STATE["ctx"]["reg"] if r["working_label"] == EMPTY]
        put_lines(root, rel, lines_of(root, rel) + extra)
        Refusals.refused(self, root, "R3′")


# ================================================================================================ tool: on a snapshot
class Tool(unittest.TestCase):
    def test_T_01_idempotent_rerun(self):
        root = work()
        r1 = run(root)
        st1 = {n: os.stat(os.path.join(root, ADR, n)) for n in OUTS}
        r2 = run(root)
        st2 = {n: os.stat(os.path.join(root, ADR, n)) for n in OUTS}
        self.assertEqual(r1["written"], 3)
        self.assertEqual(r2["written"], 0)
        self.assertEqual(O.assemble_lines(r1), O.assemble_lines(r2))
        for n in OUTS:
            self.assertEqual((st1[n].st_ino, st1[n].st_mtime_ns), (st2[n].st_ino, st2[n].st_mtime_ns))

    def test_T_02_R8_an_existing_output_altered(self):
        root = work()
        run(root)
        with open(os.path.join(root, ADR, "register.jsonl"), "ab") as f:
            f.write(b" ")
        Refusals.refused(self, root, "R8")

    def test_T_03_absent_empty_and_reordered_run_files(self):
        root = work()
        a = label_with("reg")
        ngap = collections.Counter(r["working_label"] for r in STATE["ctx"]["gap"])
        b = next(l for l in LABELS if l not in (a, EMPTY) and ngap[l] >= 2 and final_run(l))
        os.remove(os.path.join(root, U.LEDGER, final_run(a), "register.jsonl"))            # absent → zero records
        xf.put(root, f"{U.LEDGER}/{final_run(a)}/p1-gap.jsonl", b"")                       # empty → zero records
        rel = f"{U.LEDGER}/{final_run(b)}/p1-gap.jsonl"
        ls = lines_of(root, rel)
        put_lines(root, rel, [ls[1], ls[0]] + ls[2:] + [""])                                # blank lines are skipped
        r = run(root)
        self.assertEqual(r["absent_inputs"], 1)
        self.assertIn(f"ABSENT {U.LEDGER}/{final_run(a)}/register.jsonl", O.assemble_lines(r))
        reg = jl(rd(root, f"{ADR}/register.jsonl"))
        gap = jl(rd(root, f"{ADR}/p1-gap-capture.jsonl"))
        self.assertFalse([x for x in reg + gap if x["working_label"] == a])
        got_b = [x["gap_id"] for x in gap if x["working_label"] == b]
        self.assertEqual(got_b, [json.loads(l)["gap_id"] for l in [ls[1], ls[0]] + ls[2:]])
        pos = [IDX[x["working_label"]] for x in reg]
        self.assertEqual(pos, sorted(pos))                                                  # manifest-order blocks

    def test_T_04_a_stray_capture_file_in_a_run_directory_is_ignored_and_counted(self):
        ref = work()
        run(ref)
        root = work()
        lab = label_with("gap")
        xf.put(root, f"{U.LEDGER}/{final_run(lab)}/p1-gap-capture.jsonl", b"not json\n")
        r = run(root)
        self.assertEqual(r["ignored_capture_files"], 1)
        for n in OUTS:
            self.assertEqual(rd(root, f"{ADR}/{n}"), rd(ref, f"{ADR}/{n}"), n)

    def test_T_06_no_corpus_read(self):
        class Boom:
            def __init__(self, *a, **k):
                raise AssertionError("the assembly must not construct a corpus resolver")

            def read_many(self, sids):
                raise AssertionError("the assembly must not read corpus bytes")
        root = work()
        orig = O.c.dio.discovery_resolver
        O.c.dio.discovery_resolver = Boom
        try:
            r = run(root)
        finally:
            O.c.dio.discovery_resolver = orig
        self.assertEqual(r["objects"], len(LABELS))

    def test_T_12_check_writes_nothing_and_refuses_R9_on_a_difference(self):
        root = work()
        Refusals.refused(self, root, "R9", check=True)                                      # no assembly yet
        r = run(root)
        before = tree(root)
        rc = run(root, check=True)
        self.assertEqual(tree(root), before)
        self.assertEqual(O.assemble_lines(rc), O.assemble_lines(r))
        self.assertEqual(rc["written"], 0)
        with open(os.path.join(root, ADR, "p1-gap-capture.jsonl"), "ab") as f:
            f.write(b"\n")
        Refusals.refused(self, root, "R9", check=True)

    def test_T121_byte_determinism_across_cwd_tz_locale_and_hash_seed(self):
        drv = ("import sys\nsys.path[:0] = [%r, %r]\nimport p3b_s5_ops_testlib as t\nimport p3b_s5_r7_orchestrate as O\n"
               "with t.fake_holdout():\n    r = O.assemble(sys.argv[1], root=sys.argv[2], analysis_date=sys.argv[3], "
               "model_id=sys.argv[4])\nprint('\\n'.join(O.assemble_lines(r)))\n") % (HERE, SCRIPTS)
        res = []
        # cwd varies inside the repository: the shared import chain resolves the git top level at import time
        for tz, lc, seed, cwd in (("UTC", "C", "0", HERE), ("Pacific/Chatham", "C.UTF-8", "4242", FX.CR)):
            root = work()
            env = dict(os.environ, TZ=tz, LC_ALL=lc, LANG=lc, PYTHONHASHSEED=seed)
            p = subprocess.run([sys.executable, "-B", "-c", drv, OB, root, DATE, MODEL], cwd=cwd, env=env,
                               capture_output=True)
            self.assertEqual(p.returncode, 0, p.stderr.decode("utf-8", "replace")[-300:])
            res.append((p.stdout, {n: rd(root, f"{ADR}/{n}") for n in OUTS}))
        self.assertEqual(res[0], res[1])
        self.assertTrue(res[0][0])

    def test_printed_lines_cover_every_input_and_output_and_name_no_label(self):
        root = work()
        r = run(root)
        ls = O.assemble_lines(r)
        hashed = {l.split("  ", 1)[1]: l.split("  ", 1)[0] for l in ls if len(l) > 66 and l[64:66] == "  "}
        for n in OUTS:
            self.assertEqual(hashed[f"{ADR}/{n}"], U.fsha(os.path.join(root, ADR, n)))
        self.assertIn(f"{FX.prep.SLICE_ROOT}/{OB}.R7-PLAN.json", hashed)
        self.assertIn(FX.prep.MANIFEST, hashed)
        for lab in LABELS:
            if final_run(lab):
                self.assertIn(f"{U.LEDGER}/{final_run(lab)}/objects.jsonl", hashed)
        text = "\n".join(ls)
        self.assertFalse(any(lab in text for lab in LABELS))
        self.assertIsNone(U.SID.search(text))
        self.assertTrue(ls[-1].startswith(f"S5-ORCH assemble {OB}:"))

    def test_T127_entry_dict_with_every_label_false(self):
        root = work()
        edit_entry(root, lambda e: e.update(in_checklist={l: False for l in LABELS}))
        r = run(root)
        self.assertEqual(r["checklist_vacuous"], 0)
        self.assertNotIn("in_checklist_dict_absent", r)
        self.assertFalse([o for o in jl(rd(root, f"{ADR}/objects.jsonl")) if "checklist_examined" in o])

    def test_H1_absent_null_or_partial_entry_dict_is_refused_R13(self):
        for fn in (lambda e: e.pop("in_checklist"), lambda e: e.update(in_checklist=None),
                   lambda e: e.update(in_checklist={l: False for l in LABELS if l != LABELS[-1]})):
            root = work()
            edit_entry(root, fn)
            Refusals.refused(self, root, "R13")

    def test_cli(self):
        root = work()
        orig = (O.c.verify_frozen, O.c.assert_sealed)
        O.c.verify_frozen = O.c.assert_sealed = lambda: True
        try:
            for argv, want in ((["assemble", OB, "--root", root, "--date", DATE, "--model-id", MODEL], None),
                               (["assemble", OB, "--root", root, "--date", DATE, "--model-id", MODEL, "--check"], None),
                               (["assemble", OB, "--root", root, "--model-id", MODEL], "R12"),
                               (["assemble", OB, "--root", root, "--date", DATE], "R10")):
                so, se = io.StringIO(), io.StringIO()
                with testlib.fake_holdout(), contextlib.redirect_stdout(so), contextlib.redirect_stderr(se):
                    rc = O.main(argv)
                self.assertEqual(rc, 0 if want is None else 2, se.getvalue()[-200:])
                if want is None:
                    self.assertIn(f"  {ADR}/objects.jsonl\n", so.getvalue())
                    self.assertIn(f"S5-ORCH assemble {OB}:", so.getvalue())
                else:
                    self.assertIn(f"REFUSED: {want} ", se.getvalue())
        finally:
            O.c.verify_frozen, O.c.assert_sealed = orig


class Refusals(unittest.TestCase):
    def refused(self, root, code, **kw):
        before = tree(root)
        with self.assertRaises(O.Refused) as cm:
            run(root, **kw)
        msg = str(cm.exception)
        self.assertTrue(msg.startswith(code + " "), msg[:160])
        self.assertFalse(any(lab in msg for lab in LABELS), "a refusal message names a label")
        self.assertIsNone(U.SID.search(msg))
        self.assertEqual(tree(root), before, "a refusal wrote something")
        return msg

    def test_R1_plan_slice_or_addendum_hash(self):
        for rel in (f"{FX.prep.SLICE_ROOT}/{OB}.R7-PLAN.json", f"{FX.prep.SLICE_ROOT}/{OB}/{EMPTY}.json", U.ADDENDUM_PATH):
            root = work()
            with open(os.path.join(root, rel), "ab") as f:
                f.write(b" ")
            self.refused(root, "R1")

    def test_R1_batch_id(self):
        root = work()
        with self.assertRaises(O.Refused) as cm:
            with testlib.fake_holdout():
                O.assemble("OB12", root=root, analysis_date=DATE, model_id=MODEL)
        self.assertTrue(str(cm.exception).startswith("R1 "))

    def test_T_06_R2_absent_final_objects(self):
        root = work()
        os.remove(os.path.join(root, U.LEDGER, final_run(label_with("reg")), "objects.jsonl"))
        self.refused(root, "R2")

    def test_R3_object_count_or_owner(self):
        lab = label_with("reg")
        rel = f"{U.LEDGER}/{final_run(lab)}/objects.jsonl"
        for fn in (lambda ls: [], lambda ls: ls + ls,
                   lambda ls: [json.dumps(dict(json.loads(ls[0]), working_label=LABELS[0] if lab != LABELS[0] else LABELS[2]))],
                   lambda ls: [json.dumps(dict(json.loads(ls[0]), working_label=EMPTY))]):          # T-14
            root = work()
            put_lines(root, rel, fn(lines_of(root, rel)))
            self.refused(root, "R3")

    def test_T119_R3_prime_a_gap_record_naming_the_empty_label(self):
        root = work()
        lab = label_with("gap")
        rel = f"{U.LEDGER}/{final_run(lab)}/p1-gap.jsonl"
        ls = lines_of(root, rel)
        put_lines(root, rel, ls + [json.dumps(dict(json.loads(ls[0]), working_label=EMPTY))])
        self.refused(root, "R3′")

    def test_T129_R3_prime_a_record_naming_an_in_checklist_empty_label(self):
        for kind, name in (("reg", "register.jsonl"), ("gap", "p1-gap.jsonl")):
            root = work("checklist")
            lab = label_with(kind)
            rel = f"{U.LEDGER}/{final_run(lab)}/{name}"
            ls = lines_of(root, rel)
            put_lines(root, rel, ls + [json.dumps(dict(json.loads(ls[0]), working_label=EMPTY))])
            self.refused(root, "R3′")

    def test_R3_prime_a_record_naming_another_agent_label(self):
        a = label_with("reg")
        b = next(l for l in LABELS if l not in (a, EMPTY))
        root = work()
        rel = f"{U.LEDGER}/{final_run(a)}/register.jsonl"
        ls = lines_of(root, rel)
        ls[0] = json.dumps(dict(json.loads(ls[0]), working_label=b))
        put_lines(root, rel, ls)
        self.refused(root, "R3′")

    def test_T_07_R4_malformed_lines(self):
        lab = label_with("reg")
        rel = f"{U.LEDGER}/{final_run(lab)}/register.jsonl"
        for bad in ("{not json", "[1, 2]", '{"a": 1, "a": 2}', '{"a": NaN}', '{"a": Infinity}', '"text"'):
            root = work()
            put_lines(root, rel, lines_of(root, rel) + [bad])
            self.refused(root, "R4")
        root = work()
        xf.put(root, rel, rd(root, rel) + b'{"a": "\xff"}\n')
        self.refused(root, "R4")
        root = work()
        orel = f"{U.LEDGER}/{final_run(lab)}/objects.jsonl"
        o = lines_of(root, orel)[0]
        put_lines(root, orel, [o[:-1] + ',"working_label":"x"}'])                      # a duplicate key in the object
        self.refused(root, "R4")

    def test_T_07_R5_a_run_directory_for_the_empty_label(self):
        for suffix in ("", "S", "U01"):
            root = work()
            os.makedirs(os.path.join(root, U.LEDGER, f"{OB}-R7-L{IDX[EMPTY]:02d}{suffix}"))
            self.refused(root, "R5")

    def test_R7_self_check_failure(self):
        root = work()
        orig = O.empty_object

        def bad(*a, **k):
            o = orig(*a, **k)
            o["timeline"] = [{"source_id": "S0001", "change_vs_previous": "FIRST"}]
            return o
        O.empty_object = bad
        try:
            self.refused(root, "R7")
        finally:
            O.empty_object = orig

    def test_T122_R10_model_id(self):
        for m in ("claude-opus-5-5", "gpt-x", None, ""):
            self.refused(work(), "R10", model_id=m)
        root = work()
        lab = label_with("reg")
        rel = f"{U.LEDGER}/{final_run(lab)}/objects.jsonl"
        put_lines(root, rel, [json.dumps(dict(json.loads(lines_of(root, rel)[0]), model_id="claude-opus-5-5"))])
        self.refused(root, "R10")

    def test_T115_T_11_R11_n_out_of_range(self):
        lab = label_with("reg")
        L = IDX[lab]
        rel = f"{U.LEDGER}/{final_run(lab)}/register.jsonl"
        for n in (999999, 1000 * (L + 1) + 1, 1000 * L, 1000 * L + 1000):                    # far out; colliding; bounds
            root = work()
            ls = lines_of(root, rel)
            ls[0] = json.dumps(dict(json.loads(ls[0]), rs_id=f"{R7}:{OB}:{n}"))
            put_lines(root, rel, ls)
            self.refused(root, "R11")
        g = label_with("gap")
        grel = f"{U.LEDGER}/{final_run(g)}/p1-gap.jsonl"
        for gid in (f"{R7}:{OB}:G{1000 * (IDX[g] + 1) + 1}", f"{final_run(g)}:{OB}:G{1000 * IDX[g] + 1}", "G1"):
            root = work()
            ls = lines_of(root, grel)
            ls[0] = json.dumps(dict(json.loads(ls[0]), gap_id=gid))
            put_lines(root, grel, ls)
            self.refused(root, "R11")

    def test_R11_bounds_inclusive(self):
        lab = label_with("reg")
        L = IDX[lab]
        rel = f"{U.LEDGER}/{final_run(lab)}/register.jsonl"
        root = work()
        ls = lines_of(root, rel)
        ls[0] = json.dumps(dict(json.loads(ls[0]), rs_id=f"{R7}:{OB}:{1000 * L + 999}"))
        put_lines(root, rel, ls)
        run(root)                                                                            # in range: assembled

    def test_R12_date_format(self):
        for d in ("2026-1-01", "2026-13-01", "2026-02-30", "20261001", None, "2026-10-01T00:00"):
            self.refused(work(), "R12", analysis_date=d)

    def test_T126_R13_entry_disagrees_with_the_slice(self):
        for fn in (lambda e: e.update(in_checklist={l: l == EMPTY for l in LABELS}),
                   lambda e: e.update(in_checklist={l: l == LABELS[-1] for l in LABELS}),
                   lambda e: e.update(in_checklist=list(LABELS))):
            root = work()
            edit_entry(root, fn)
            self.refused(root, "R13")


if __name__ == "__main__":
    unittest.main()
