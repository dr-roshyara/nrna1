"""Tests for the OB0018 decomposition-fidelity pilot tooling (synthetic data; no corpus content)."""
import importlib.util
import itertools
import json
import os
import sys
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(HERE)
sys.path.insert(0, SCRIPTS)
spec = importlib.util.spec_from_file_location("ob", os.path.join(SCRIPTS, "p3b_ob0018_pilot.py"))
ob = importlib.util.module_from_spec(spec)
spec.loader.exec_module(ob)

UNITS = [{"run_id": "U1", "files": ["S0001", "S0003"]}, {"run_id": "U2", "files": ["S0002", "S0004"]}]
FEAS_OK = {"ok": True, "reasons": []}
L2_OK = {"verifier_pass": True, "quote_misses": 0}
M_OK = {"adjacency_baseline_upheld": 0, "residual_conflicts": 0, "residual_calibration": 0, "endpoint_validity": 1.0}


class Constants(unittest.TestCase):
    def test_namespace_and_label(self):
        self.assertEqual((ob.S5_BATCH, ob.BATCH), ("OB0018", "PX0018"))
        self.assertEqual(ob.LABEL, "s1620-removal-test-provenance-base-case-and-user-decisions")
        self.assertTrue(ob.ROOT.startswith("pilot-s5-decomp/"))

    def test_budgets_frozen(self):
        self.assertEqual((ob.UNIT_BUDGET, ob.REREAD_BUDGET_C, ob.AUDIT_BUDGET), (300_000, 300_000, 600_000))

    def test_run_ids_match_reader_pilot_pattern(self):
        import re
        runs = [ob.BASELINE, ob.AUDITOR] + list(ob.ARMS.values()) + [f"PX0018-U0{i}" for i in (1, 2, 3)]
        self.assertTrue(all(re.fullmatch(r"PX\d{4}-[USA]\d{2}", r) for r in runs))

    def test_no_production_run_id(self):
        self.assertFalse(any(r.startswith("OB") for r in [ob.BASELINE, ob.AUDITOR] + list(ob.ARMS.values())))

    def test_judgment_fields(self):
        self.assertEqual(set(ob.JUDGMENT_FIELDS), {"births", "absences.resolution", "semantic_status", "type_status",
                                                   "mathematical_status", "primary_layer", "stage2_dispositions"})


class RequiredAndAdjacency(unittest.TestCase):
    def test_required_set_union(self):
        sl = {"hub": False, "stage2_files": ["S0002"],
              "bundle": {"rows_verbatim": [json.dumps({"source_id": "S0001"}), json.dumps({"source_id": "S0003"})]}}
        self.assertEqual(ob.required_set(sl), (["S0001", "S0002", "S0003"], ["S0002"], ["S0001", "S0003"]))

    def test_required_set_hub_has_no_stage2(self):
        sl = {"hub": True, "stage2_files": ["S0002"], "bundle": {"rows_verbatim": [json.dumps({"source_id": "S0001"})]}}
        self.assertEqual(ob.required_set(sl)[0], ["S0001"])

    def test_adjacencies_cross_unit_only(self):
        self.assertEqual(ob.adjacencies(["S0001", "S0003"], UNITS), [])
        self.assertEqual(ob.adjacencies(["S0001", "S0002", "S0003", "S0004"], UNITS),
                         [("S0001", "S0002"), ("S0002", "S0003"), ("S0003", "S0004")])

    def test_adjacencies_ignore_files_not_in_units(self):
        self.assertEqual(ob.adjacencies(["S0001", "S0009", "S0002"], UNITS), [("S0001", "S0002")])


class Invariants(unittest.TestCase):
    def recs(self):
        return [{"source_id": "S0001", "absence_evidence": [{"dimension": "d", "finding": "MENTIONS", "quote": "Alpha  beta"}],
                 "stage2_dispositions": [{"source_id": "S0001", "by_dimension": {"d": "FOUND"}}]},
                {"source_id": "S0002", "absence_evidence": [{"dimension": "d", "finding": "DEFINES", "quote": "alpha beta"}],
                 "stage2_dispositions": [{"source_id": "S0002", "by_dimension": {"d": "UNSUPPLIED-DIMENSION"}}]},
                {"source_id": "S0003", "absence_evidence": [{"dimension": "d", "finding": "DEFINES", "quote": "gamma"}],
                 "stage2_dispositions": [{"source_id": "S0003", "by_dimension": {"d": "FOUND"}}]}]

    def test_found_without_defines_is_conflict(self):
        c = ob.disposition_conflicts(ob.record_dispositions(self.recs()), ob.record_findings(self.recs()))
        self.assertIn(("S0001", "d", "FOUND", ["MENTIONS"]), [(x[0], x[2], x[3], x[4]) for x in c])

    def test_defines_with_unsupplied_is_conflict(self):
        c = ob.disposition_conflicts(ob.record_dispositions(self.recs()), ob.record_findings(self.recs()))
        self.assertIn(("S0002", "d", "UNSUPPLIED-DIMENSION", ["DEFINES"]), [(x[0], x[2], x[3], x[4]) for x in c])

    def test_consistent_pair_is_not_conflict(self):
        c = ob.disposition_conflicts(ob.record_dispositions(self.recs()), ob.record_findings(self.recs()))
        self.assertNotIn("S0003", [x[0] for x in c])

    def test_cross_unit_undefined(self):
        f = ob.record_findings(self.recs())
        self.assertEqual(ob.cross_unit_undefined({"d": {"resolution": "GENUINELY-UNDEFINED-AFTER-CENSUS"}}, f), ["d"])
        self.assertEqual(ob.cross_unit_undefined({"d": {"resolution": "FOUND"}}, f), [])

    def test_calibration_pairs_normalized_exact_and_cross_unit(self):
        p = ob.calibration_pairs(self.recs(), UNITS)
        self.assertEqual(p, [{"dimension": "d", "a": "S0001", "b": "S0002", "findings": ["MENTIONS", "DEFINES"]}])

    def test_calibration_same_unit_not_paired(self):
        recs = self.recs()
        recs[1]["source_id"] = "S0003"
        self.assertEqual(ob.calibration_pairs(recs[:2], UNITS), [])

    def test_residual_calibration(self):
        p = ob.calibration_pairs(self.recs(), UNITS)
        k = lambda sid: (sid, "raw", "1", "0", "d")
        self.assertEqual(len(ob.residual_calibration(p, {k("S0001"): "FOUND", k("S0002"): "FALSE-HIT"})), 1)
        self.assertEqual(ob.residual_calibration(p, {k("S0001"): "FOUND", k("S0002"): "FOUND"}), [])


class Edges(unittest.TestCase):
    def test_endpoint_validity_and_both_readings(self):
        e = [{"target_label": "lab-x", "kind": "USAGE", "quote": "uses lab-x here"},
             {"target_label": "not-a-label", "kind": "USAGE", "quote": "q"}]
        b = [{"target_label": "lab-x", "kind": "USAGE", "quote": "co-label only"}]
        m = ob.edge_metrics(e, b, frozenset({"lab-x"}))
        self.assertEqual(m["endpoint_validity"], 0.5)
        self.assertAlmostEqual(m["jaccard_R1"], 0.5)
        self.assertEqual(m["jaccard_R2"], 0.0)

    def test_no_edges_is_valid(self):
        self.assertEqual(ob.edge_metrics([], [], frozenset())["endpoint_validity"], 1.0)


class Compare(unittest.TestCase):
    def base(self):
        return {"births": {"lexical": "ESTABLISHED-LEXICAL-BIRTH[S0001]"}, "absences": {"d": {"resolution": "FOUND",
                "supplied_by": {"source_id": "S0001"}}}, "semantic_status": None, "type_status": "CLOSED",
                "primary_layer": "DERIVED", "secondary_roles": ["OPERATIONAL"], "dependency_edges": [],
                "timeline": [{"source_id": "S0002", "change_vs_previous": "EXTENDS"}], "escalations": [],
                "stage2_dispositions": [{"source_id": "S0001", "by_dimension": {"d": "FOUND"}}]}

    def cls(self, rows, field):
        return next(r["class"] for r in rows if r["field"] == field)

    def test_identical_objects_all_exact(self):
        rows = ob.compare_fields(self.base(), self.base())
        self.assertTrue(all(r["class"] == "exact" for r in rows))

    def test_birth_coarse_and_different(self):
        a = self.base()
        a["births"]["lexical"] = "ESTABLISHED-LEXICAL-BIRTH[S0002]"
        self.assertEqual(self.cls(ob.compare_fields(self.base(), a), "births.lexical"), "coarse")
        a["births"]["lexical"] = "NOT-EVIDENCED-IN-CAPTURE"
        self.assertEqual(self.cls(ob.compare_fields(self.base(), a), "births.lexical"), "different")

    def test_stage2_per_key(self):
        a = self.base()
        a["stage2_dispositions"][0]["by_dimension"]["d"] = "FALSE-HIT"
        rows = ob.compare_fields(self.base(), a)
        r = next(r for r in rows if r["field"].startswith("stage2_dispositions.S0001.") and r["field"].endswith(".d"))
        self.assertEqual((r["class"], r["judgment"]), ("different", True))

    def test_adjacency_metric(self):
        a = self.base()
        a["timeline"][0]["change_vs_previous"] = "RESTATES"
        rows = ob.adjacency_metric([["S0001", "S0002"]], self.base(), a)
        self.assertEqual(rows, [{"pair": ["S0001", "S0002"], "baseline": "EXTENDS", "arm": "RESTATES", "equal": False}])


class Outcome(unittest.TestCase):
    ROWS_OK = [{"field": "births.lexical", "judgment": True, "disposition": "EXACT"}]

    def test_faithful(self):
        self.assertEqual(ob.outcome_of("A", FEAS_OK, L2_OK, self.ROWS_OK, M_OK, [])[0], "FAITHFUL")

    def test_violation_invalid(self):
        self.assertEqual(ob.outcome_of("A", FEAS_OK, L2_OK, self.ROWS_OK, M_OK, ["re-read in arm A"])[0], "INVALID")

    def test_feasibility_undetermined(self):
        self.assertEqual(ob.outcome_of("A", {"ok": False, "reasons": ["x"]}, L2_OK, self.ROWS_OK, M_OK, [])[0],
                         "UNDETERMINED")

    def test_unadjudicated_judgment_undetermined(self):
        rows = [{"field": "births.lexical", "judgment": True, "disposition": "UNADJUDICATED"}]
        self.assertEqual(ob.outcome_of("A", FEAS_OK, L2_OK, rows, M_OK, [])[0], "UNDETERMINED")

    def test_unadjudicated_non_judgment_does_not_block(self):
        rows = [{"field": "tier", "judgment": False, "disposition": None}]
        self.assertEqual(ob.outcome_of("A", FEAS_OK, L2_OK, rows, M_OK, [])[0], "FAITHFUL")

    def test_baseline_upheld_judgment_not_faithful(self):
        rows = [{"field": "primary_layer", "judgment": True, "disposition": "BASELINE-UPHELD"}]
        self.assertEqual(ob.outcome_of("A", FEAS_OK, L2_OK, rows, M_OK, [])[0], "NOT-FAITHFUL")

    def test_baseline_upheld_non_judgment_allowed(self):
        rows = [{"field": "secondary_roles", "judgment": False, "disposition": "BASELINE-UPHELD"}]
        self.assertEqual(ob.outcome_of("A", FEAS_OK, L2_OK, rows, M_OK, [])[0], "FAITHFUL")

    def test_protocol_violation_any_field(self):
        rows = [{"field": "tier", "judgment": False, "disposition": "PROTOCOL-VIOLATION"}]
        self.assertEqual(ob.outcome_of("A", FEAS_OK, L2_OK, rows, M_OK, [])[0], "NOT-FAITHFUL")

    def test_each_metric_bound(self):
        for k, v in (("adjacency_baseline_upheld", 1), ("residual_conflicts", 1), ("residual_calibration", 1),
                     ("endpoint_validity", 0.99)):
            self.assertEqual(ob.outcome_of("A", FEAS_OK, L2_OK, self.ROWS_OK, dict(M_OK, **{k: v}), [])[0],
                             "NOT-FAITHFUL", k)

    def test_level2(self):
        self.assertEqual(ob.outcome_of("A", FEAS_OK, {"verifier_pass": False, "quote_misses": 0}, self.ROWS_OK, M_OK,
                                       [])[0], "NOT-FAITHFUL")
        self.assertEqual(ob.outcome_of("A", FEAS_OK, {"verifier_pass": True, "quote_misses": 1}, self.ROWS_OK, M_OK,
                                       [])[0], "NOT-FAITHFUL")


class Decision(unittest.TestCase):
    def test_mapping_exhaustive(self):
        vals = ("FAITHFUL", "NOT-FAITHFUL", "UNDETERMINED", "INVALID")
        for a, b, c in itertools.product(vals, repeat=3):
            d = ob.decision({"A": a, "B": b, "C": c})
            if a == "FAITHFUL":
                self.assertEqual(d, "EVIDENCE-FOR-ARCHITECTURE-A")
            elif a != "NOT-FAITHFUL":
                self.assertEqual(d, "UNDETERMINED")
            elif b == "FAITHFUL":
                self.assertEqual(d, "EVIDENCE-FOR-ARCHITECTURE-B")
            elif b != "NOT-FAITHFUL":
                self.assertEqual(d, "UNDETERMINED")
            elif c == "FAITHFUL":
                self.assertEqual(d, "EVIDENCE-FOR-ARCHITECTURE-C")
            elif c != "NOT-FAITHFUL":
                self.assertEqual(d, "UNDETERMINED")
            else:
                self.assertEqual(d, "NO-ARM-SHOWN-FAITHFUL")


class Blinding(unittest.TestCase):
    def test_key_is_permutation_and_deterministic(self):
        k = ob.blind_key("abc")
        self.assertEqual(sorted(k.values()), ["P", "Q", "R"])
        self.assertEqual(k, ob.blind_key("abc"))

    def test_strip_identity(self):
        o = ob.strip_identity({"run_id": "PX0018-S03", "model_id": "m", "x": "PX0018-S03:PX0018:1"}, "PX0018-S03")
        self.assertNotIn("run_id", o)
        self.assertNotIn("PX0018-S03", json.dumps(o))


class Scan(unittest.TestCase):
    def test_reader_call_with_sid_is_clean(self):
        calls = [{"id": "1", "name": "Bash",
                  "input": {"command": "python3 x/p3b_read_source.py --run PX0018-U01 --batch PX0018 --page 1 S1620"}}]
        self.assertEqual(ob.transcript_scan(calls, {"docs/knowledgeos/secret.md"}, {"hold/file.md"}, run="PX0018-U01"), [])

    def test_corpus_path_is_violation(self):
        calls = [{"id": "1", "name": "Read", "input": {"file_path": "/r/docs/knowledgeos/secret.md"}}]
        self.assertEqual(ob.transcript_scan(calls, {"docs/knowledgeos/secret.md"}, set()),
                         [("1", "PROTOCOL-VIOLATION", "Read", "corpus path or blob spec named")])

    def test_holdout_is_seal_breach(self):
        calls = [{"id": "2", "name": "Bash", "input": {"command": "cat P3B-HOLDOUT-SEAL.json"}}]
        self.assertEqual(ob.transcript_scan(calls, set(), {"P3B-HOLDOUT-SEAL"})[0][1], "SEAL-BREACH")


class Prompts(unittest.TestCase):
    def test_sections(self):
        self.assertIn("section 1", ob.prompt_for("PX0018-U02"))
        self.assertIn("section 2", ob.prompt_for("PX0018-S00"))
        self.assertIn("section 3", ob.prompt_for("PX0018-S03"))
        self.assertIn("section 4", ob.prompt_for("PX0018-A01"))

    def test_prompt_names_run_and_namespace(self):
        p = ob.prompt_for("PX0018-S01")
        self.assertIn("--run PX0018-S01 --batch PX0018", p)
        self.assertIn(ob.CONTRACT, p)


class Guard(unittest.TestCase):
    def test_refuses_short_commit(self):
        with self.assertRaises(ob.PilotError):
            ob.authorized("abc123")

    def test_refuses_without_authorization(self):
        if os.path.exists(ob._p("OB0018-AUTHORIZATION.json")):
            self.skipTest("authorization exists")
        with self.assertRaises(ob.PilotError):
            ob.authorized("0" * 40)

    def test_stage_names(self):
        self.assertEqual([s for s, _ in ob.STAGES], ["authorization", "plan", "units", "invariants", "syntheses",
                                                     "blind", "audit", "unseal"])
        self.assertIn("#AUDIT-KEY.json", dict(ob.STAGES)["blind"])


class FrozenPlan(unittest.TestCase):
    def test_plan_reproduces_partition(self):
        path = ob._p("OB0018-PLAN.json")
        if not os.path.exists(path):
            self.skipTest("plan not generated")
        import p3b_s5_prepare as P
        import p3b_s5_pilot as PL
        b = json.load(open(path, encoding="utf-8"))["body"]
        sizes = P.blob_sizes()
        self.assertEqual([u["files"] for u in b["units"]], PL.partition(b["required"], sizes, budget=ob.UNIT_BUDGET))
        self.assertEqual((len(b["required"]), b["required_bytes"], len(b["units"])), (34, 771717, 3))
        self.assertEqual(b["adjacencies"], [list(x) for x in ob.adjacencies(b["row_sources"], b["units"])])

    def test_slice_matches_frozen_manifest(self):
        path = ob._p(f"slices/{ob.LABEL}.json")
        if not os.path.exists(path):
            self.skipTest("slice not written")
        import p3b_s5_common as C
        import p3b_s5_prepare as P
        text = open(path, encoding="utf-8").read()
        man = [json.loads(x) for x in open(os.path.join(C.CR, P.MANIFEST), encoding="utf-8") if x.strip()]
        row = next(b for b in man[1:] if b["batch_id"] == "OB0018")
        # the pilot slice is a revision-3 artifact: after R7 activation (G-LOG-0102) the manifest keeps its hash as
        # `rev3_slice_sha256` (the R7 slices carry run_id B-R7 and therefore other hashes)
        self.assertEqual(P.sha256_bytes(text[:-1].encode("utf-8")), (row.get("rev3_slice_sha256") or row["slice_sha256"])[ob.LABEL])


# ------------------------------------------------------------------ regression tests for audit findings M-1…M-8
class M1AuditPath(unittest.TestCase):
    def test_audit_stage_reads_auditor_directory(self):
        self.assertIn("@PX0018-A01/AUDIT.jsonl", dict(ob.STAGES)["audit"])
        self.assertTrue(ob.stage_path("@PX0018-A01/AUDIT.jsonl").endswith("pilot-s5-decomp/PX0018-A01/AUDIT.jsonl"))

    def test_contract_names_the_same_path(self):
        txt = open(os.path.join(SCRIPTS, os.pardir, ob.CONTRACT), encoding="utf-8").read()
        self.assertIn("pilot-s5-decomp/PX0018-A01/AUDIT.jsonl", txt)

    def test_freeze_refuses_missing_file(self):
        import tempfile
        tmp = tempfile.mkdtemp()
        saved = (ob._p, ob.STAGES)
        try:
            ob._p = lambda name: os.path.join(tmp, name)
            ob.STAGES = (("x", ["MISSING.json"]),)
            with self.assertRaises(ob.PilotError):
                ob.cmd_freeze("x")
            self.assertFalse(os.path.exists(os.path.join(tmp, "PROVENANCE.jsonl")))
        finally:
            ob._p, ob.STAGES = saved
            import shutil
            shutil.rmtree(tmp, ignore_errors=True)


class M2PerHitKey(unittest.TestCase):
    OBJ = {"stage2_dispositions": [
        {"source_id": "S0001", "hit_kind": "raw", "hit_key": 100, "term_index": 0, "by_dimension": {"d": "FOUND"}},
        {"source_id": "S0001", "hit_kind": "raw", "hit_key": 900, "term_index": 0, "by_dimension": {"d": "FALSE-HIT"}}]}

    def test_two_hits_in_one_file_not_collapsed(self):
        self.assertEqual(len(ob.object_dispositions(self.OBJ)), 2)

    def test_compare_names_each_hit(self):
        other = json.loads(json.dumps(self.OBJ))
        other["stage2_dispositions"][1]["by_dimension"]["d"] = "FOUND"
        rows = [r for r in ob.compare_fields(self.OBJ, other) if r["field"].startswith("stage2_dispositions.")]
        self.assertEqual(len(rows), 2)
        self.assertEqual(sorted(r["class"] for r in rows), ["different", "exact"])
        self.assertTrue(any(":900#" in r["field"] and r["class"] == "different" for r in rows))

    def test_order_independent(self):
        rev = {"stage2_dispositions": list(reversed(self.OBJ["stage2_dispositions"]))}
        self.assertEqual(ob.object_dispositions(rev), ob.object_dispositions(self.OBJ))

    def test_conflicts_per_hit(self):
        c = ob.disposition_conflicts(ob.object_dispositions(self.OBJ), {("S0001", "d"): {"MENTIONS"}})
        self.assertEqual([(x[0], x[1], x[3]) for x in c], [("S0001", "100", "FOUND")])

    def test_calibration_file_level(self):
        pairs = [{"dimension": "d", "a": "S0001", "b": "S0002", "findings": ["DEFINES", "MENTIONS"]}]
        disp = ob.object_dispositions(self.OBJ)
        disp[("S0002", "raw", "5", "0", "d")] = "FALSE-HIT"
        self.assertEqual(len(ob.residual_calibration(pairs, disp)), 1)
        disp[("S0002", "raw", "5", "0", "d")] = "FOUND"
        self.assertEqual(ob.residual_calibration(pairs, disp), [])


class M3NoLoadEscalation(unittest.TestCase):
    def test_contract_uses_other_not_load(self):
        txt = open(os.path.join(SCRIPTS, os.pardir, ob.CONTRACT), encoding="utf-8").read()
        self.assertIn('reason **OTHER** and the detail "re-read budget exhausted', txt)
        self.assertNotIn("(reason LOAD)", txt)


class M4AdjacencyViolation(unittest.TestCase):
    CMP = {"fields": [{"field": "tier", "class": "exact", "judgment": False}],
           "adjacency": [{"pair": ["S0001", "S0002"], "equal": False}, {"pair": ["S0002", "S0003"], "equal": True}]}

    def test_protocol_violation_on_adjacency_reaches_outcome(self):
        rows, bu = ob.arm_rows(self.CMP, {"adjacency.S0002": "PROTOCOL-VIOLATION"})
        self.assertEqual(bu, 0)
        self.assertEqual(ob.outcome_of("A", FEAS_OK, L2_OK, rows, M_OK, [])[0], "NOT-FAITHFUL")

    def test_upheld_counted_and_unadjudicated_undetermined(self):
        self.assertEqual(ob.arm_rows(self.CMP, {"adjacency.S0002": "BASELINE-UPHELD"})[1], 1)
        rows, _ = ob.arm_rows(self.CMP, {})
        self.assertEqual(ob.outcome_of("A", FEAS_OK, L2_OK, rows, M_OK, [])[0], "UNDETERMINED")

    def test_equal_rows_not_added(self):
        rows, _ = ob.arm_rows(self.CMP, {"adjacency.S0002": "JUDGMENT-CALL"})
        self.assertEqual([r["field"] for r in rows if r["field"].startswith("adjacency.")], ["adjacency.S0002"])


class M5ArmCMandatory(unittest.TestCase):
    def test_skipped_adjacency_file_detected(self):
        self.assertEqual(ob.arm_c_skipped([["S1623", "S1627"], ["S1627", "S1629"]], ["S1623", "S1629"]), ["S1627"])
        self.assertEqual(ob.arm_c_skipped([["S1623", "S1627"]], ["S1623", "S1627", "S0001"]), [])


class M6ReaderDiscipline(unittest.TestCase):
    def call(self, cmd):
        return [{"id": "1", "name": "Bash", "input": {"command": cmd}}]

    def test_own_run_clean(self):
        c = self.call("cd /r && python3 /r/scripts/p3b_read_source.py --run PX0018-U01 --batch PX0018 --label l --step 1 --page 1 S1620")
        self.assertEqual(ob.transcript_scan(c, set(), set(), run="PX0018-U01"), [])

    def test_foreign_run_flagged(self):
        c = self.call("python3 p3b_read_source.py --run PX0018-U02 --batch PX0018 --page 1 S1620")
        self.assertEqual(ob.transcript_scan(c, set(), set(), run="PX0018-U01")[0][1], "PROTOCOL-VIOLATION")

    def test_foreign_batch_and_production_run_flagged(self):
        for cmd in ("python3 p3b_read_source.py --run PX0018-U01 --batch PX0004 --page 1 S1620",
                    "python3 p3b_read_source.py --run OB0018-R2 --batch OB0018 --page 1 S1620"):
            self.assertEqual(len(ob.transcript_scan(self.call(cmd), set(), set(), run="PX0018-U01")), 1, cmd)

    def test_no_read_arm_flagged_even_under_own_run(self):
        c = self.call("python3 p3b_read_source.py --run PX0018-S01 --batch PX0018 --page 1 S1620")
        self.assertEqual(ob.transcript_scan(c, set(), set(), run="PX0018-S01", no_read=True)[0][3],
                         "reader call in a no-read arm")

    def test_ledger_fingerprint_deterministic(self):
        self.assertEqual(ob.ledger_fingerprint(), ob.ledger_fingerprint())

    def test_authorization_stage_freezes_fingerprint(self):
        self.assertIn("LEDGER-FINGERPRINT.json", dict(ob.STAGES)["authorization"])


class M7BlindnessDetection(unittest.TestCase):
    def test_auditor_reading_key_or_arm_dir_flagged(self):
        for path in ("pilot-s5-decomp/ob0018/AUDIT-KEY.json", "pilot-s5-decomp/PX0018-S02/objects.jsonl",
                     "pilot-s5-decomp/ob0018/CHECK-ALL.json", "pilot-s5-decomp/ob0018/VALIDATE.json"):
            c = [{"id": "1", "name": "Read", "input": {"file_path": path}}]
            self.assertEqual(ob.transcript_scan(c, set(), set(), run=ob.AUDITOR, blind_tokens=ob.AUDIT_BLIND_TOKENS)
                             [0][3], "auditor accessed an arm-identifying file", path)

    def test_package_and_compare_allowed(self):
        for path in ("pilot-s5-decomp/ob0018/AUDIT-PACKAGE.json", "pilot-s5-decomp/ob0018/COMPARE.json"):
            c = [{"id": "1", "name": "Read", "input": {"file_path": path}}]
            self.assertEqual(ob.transcript_scan(c, set(), set(), run=ob.AUDITOR, blind_tokens=ob.AUDIT_BLIND_TOKENS), [])

    def test_tokens_not_applied_to_other_runs(self):
        c = [{"id": "1", "name": "Read", "input": {"file_path": "pilot-s5-decomp/ob0018/INVARIANTS.json"}}]
        self.assertEqual(ob.transcript_scan(c, set(), set(), run="PX0018-S02"), [])


class M8WriteToolOnly(unittest.TestCase):
    def test_contract_requires_write_tool(self):
        txt = open(os.path.join(SCRIPTS, os.pardir, ob.CONTRACT), encoding="utf-8").read()
        self.assertIn("only with the Write (or Edit) tool", txt)
        self.assertIn("Never put quote text or file content inside a shell command", txt)


if __name__ == "__main__":
    unittest.main()
