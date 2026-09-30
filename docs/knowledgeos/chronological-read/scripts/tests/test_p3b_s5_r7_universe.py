#!/usr/bin/env python3
"""B1 UNIVERSE (R7 v2.3): registry sovereignty, discovery vs validation, claims, plan, namespace, binding, I(run), W8
grammar. Synthetic objects and temp roots only; the registry is loaded from the frozen addendum (sha-checked).
  cd scripts/tests && PYTHONPATH=.:.. python3 -m unittest test_p3b_s5_r7_universe
"""
import copy
import json
import os
import shutil
import sys
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(HERE)
CR = os.path.dirname(SCRIPTS)
sys.path.insert(0, SCRIPTS)
import p3b_s5_r7_universe as U                      # noqa: E402

REG, CONS, CF = U.load_frozen_contract(CR)


def obj(**kw):
    o = {"working_label": "lab-a",
         "timeline": [{"source_id": "S0001", "change_vs_previous": "FIRST", "states": {"epistemic_class": "SOURCE", "quote": "q"}},
                      {"source_id": "S0002", "change_vs_previous": "CONTRADICTS", "states": {"epistemic_class": "SOURCE", "quote": "q"}}],
         "timeline_summary": {"first_lexical": "ESTABLISHED-LEXICAL-BIRTH[S0001]", "later_support": [], "later_refinement": [],
                              "contradicted_by": [{"source_id": "S0002", "contradicts": "S0001"}], "rejected_by": [],
                              "current_lifecycle": "SOURCE-CLAIMED-ACTIVE"},
         "births": {"lexical": "ESTABLISHED-LEXICAL-BIRTH[S0001]", "conceptual": "NOT-EVIDENCED-IN-CAPTURE",
                    "formal": "NOT-EVIDENCED-IN-CAPTURE", "operational": "NOT-EVIDENCED-IN-CAPTURE", "governance": "NOT-EVIDENCED-IN-CAPTURE"},
         "absences": {"d1": {"resolution": "FOUND", "supplied_by": {"source_id": "S0001", "anchor": "L1", "quote": "q"}},
                      "d2": {"resolution": "NOT-EVIDENCED-IN-CAPTURE", "supplied_by": None}},
         "stage2_dispositions": [], "dependency_edges": [], "semantic_evidence": {"d4": [], "d5": []},
         "superseded_by_sources": [], "escalations": [], "status_basis": {}, "hindsight_dependency": []}
    o.update(kw)
    return o


class Contract(unittest.TestCase):
    def test_frozen_contract_loads_without_violation(self):
        self.assertEqual(CF, [])
        self.assertEqual(len(REG), 39)                     # v2.3: 35 + the four v2.4 META rows (G-LOG-0089)
        self.assertEqual(len(CONS), 8)

    def test_modified_contract_bytes_are_detected(self):
        raw = open(os.path.join(CR, U.ADDENDUM_PATH), "rb").read()
        _, _, F = U.load_contract(raw + b" ")
        self.assertTrue(any("differ from the frozen sha256" in x for x in F))

    def test_T43_consumer_conformance(self):
        self.assertEqual(U.consumer_violations(REG, CONS), [])
        bad = CONS + [{"consumer": "rogue", "read_set": "status_basis.<field>.<text>"}]
        self.assertTrue(U.consumer_violations(REG, bad))


class DiscoveryValidationTyping(unittest.TestCase):
    def test_valid_object_fully_typed_and_valid(self):
        o = obj()
        self.assertEqual(U.typing_violations(REG, o), [])
        self.assertEqual(U.validation_violations(o), [])

    def test_T01_null_source_is_discovered_and_invalid(self):
        o = obj()
        o["timeline"][0]["source_id"] = None
        self.assertTrue(any("null source" in x or "untyped" in x for x in U.typing_violations(REG, o)))
        self.assertTrue(any("not an S-id" in x for x in U.validation_violations(o)))

    def test_T02_missing_source_only_validation_detects(self):
        o = obj()
        del o["timeline"][0]["source_id"]
        self.assertEqual([x for x in U.typing_violations(REG, o) if "timeline[*]" in x and "source" in x], [])
        self.assertTrue(any("without source_id" in x for x in U.validation_violations(o)))

    def test_T03_unknown_sid_bearing_key(self):
        o = obj(x_notes="see S0003 for details")
        self.assertTrue(any("untyped S-id-bearing location 'x_notes'" in x for x in U.typing_violations(REG, o)))

    def test_T04_non_object_timeline_element(self):
        o = obj()
        o["timeline"].append("S0009 restates")
        self.assertTrue(any("is not an object" in x for x in U.validation_violations(o)))

    def test_T39_missing_change_class_T41_unknown_discriminator_T42_cardinality(self):
        o = obj()
        del o["timeline"][1]["change_vs_previous"]
        self.assertTrue(any("without change_vs_previous" in x for x in U.validation_violations(o)))
        o = obj()
        o["timeline"][1]["change_vs_previous"] = "REFUTES"
        self.assertTrue(any("closed enum" in x for x in U.validation_violations(o)))
        self.assertTrue(any("untyped" in x for x in U.typing_violations(REG, o)))       # no registry row matches
        o = obj()
        o["timeline"].append(dict(o["timeline"][0]))
        self.assertTrue(any("duplicate point" in x for x in U.validation_violations(o)))

    def test_T07_duplicate_relation(self):
        o = obj()
        o["timeline_summary"]["contradicted_by"].append({"source_id": "S0002", "contradicts": "S0001"})
        self.assertTrue(any("duplicate relation" in x for x in U.validation_violations(o)))

    def test_found_absence_discriminated_by_value(self):
        o = obj()
        o["absences"]["d2"]["supplied_by"] = {"source_id": "S0001", "anchor": "x", "quote": "q"}   # non-FOUND with a source
        self.assertTrue(any("absences.d2.supplied_by" in x for x in U.typing_violations(REG, o)))

    def test_T103_dc1_own_location_escalation_field_is_typed(self):
        """v2.4 (G-LOG-0089, DC-1): a rule-(b) escalation naming this object's own timeline location is typed META
        and satisfies the self-reference rule; it creates no claim."""
        o = obj(escalations=[{"field": "timeline[S0001].order", "reason": "SCHEMA-LIMITATION", "detail": "x"}])
        self.assertEqual(U.typing_violations(REG, o), [])
        self.assertEqual(U.meta_violations(REG, o), [])
        self.assertEqual(U.claims(REG, o), U.claims(REG, obj()))

    def test_T104_dc1_field_that_is_not_an_own_location_fails(self):
        for f in ("timeline[S0009].order", "timeline[S0001].order S0001", "S0001 supports this", "timeline[S0001].states",
                  "timeline[S0001].order, timeline[S0002].order"):
            o = obj(escalations=[{"field": f, "reason": "SCHEMA-LIMITATION", "detail": "x"}])
            self.assertTrue(any("not an own-object location" in x for x in U.meta_violations(REG, o)), f)
        o = obj(escalations=[{"field": "timeline[S12].order", "reason": "SCHEMA-LIMITATION", "detail": "x"}])
        self.assertEqual(U.meta_violations(REG, o), [])                  # malformed id: not discovered, inert

    def test_ndb1_reason_and_position_mentions_are_typed_meta(self):
        """v2.4 (G-LOG-0089, NDB-1): S-id mentions in the rev3 free-text locations are META: typed, no claim (INV-LEX)."""
        o = obj()
        o["absences"]["d1"]["reason"] = "S0001 defines it; S0002 only mentions it"
        o["absences"]["d2"]["reason"] = "census over S0001, S0002"
        o["stage2_dispositions"] = [{"source_id": "S0002", "by_dimension": {"d2": "UNSUPPLIED-DIMENSION"}, "reason": "hit in S0002"}]
        o["timeline"][1]["historical_position"] = "2026-09-07 (after S0001)"
        self.assertEqual(U.typing_violations(REG, o), [])
        self.assertEqual(U.meta_violations(REG, o), [])
        self.assertEqual(U.claims(REG, o), U.claims(REG, obj()))
        o["timeline"][1]["x_position_note"] = "after S0001"             # an unregistered path stays default-deny
        self.assertTrue(any("untyped" in x for x in U.typing_violations(REG, o)))

    def test_claims_enumerated_from_types(self):
        ids = {c["id"] for c in U.claims(REG, obj())}
        self.assertEqual(ids, {"timeline:S0001", "timeline:S0002", "birth:lexical:S0001", "contra:S0002:S0001", "absence:d1"})
        whole = {c["id"]: c["whole"] for c in U.claims(REG, obj())}
        self.assertFalse(whole["timeline:S0001"])
        self.assertTrue(whole["timeline:S0002"])


class PlanNamespaceBinding(unittest.TestCase):
    def setUp(self):
        self.d = tempfile.mkdtemp(prefix="u-")

    def tearDown(self):
        shutil.rmtree(self.d, ignore_errors=True)

    def test_namespace_T09_T11_and_legacy_digest(self):
        led = os.path.join(self.d, "ledger")
        for dname in ("OB9020-R2", "OB9020-R7", "OB9020-R7-L01"):
            os.makedirs(os.path.join(led, dname))
        open(os.path.join(led, "OB9020-R2", "READ-LOG.jsonl"), "w").write("{}\n")
        planned = {"OB9020-R7-L01"}
        dig = U.legacy_digest(led, "OB9020", planned, "OB9020-R7")
        self.assertEqual(U.namespace_violations(led, "OB9020", planned, dig, ["OB9020-R2"]), [])
        os.makedirs(os.path.join(led, "OB9020-R7-L03U09"))
        self.assertTrue(any("OB9020-R7-L03U09" in x for x in U.namespace_violations(led, "OB9020", planned, dig, ["OB9020-R2"])))
        os.rmdir(os.path.join(led, "OB9020-R7-L03U09"))
        open(os.path.join(led, "OB9020-R2", "READ-LOG.jsonl"), "a").write("{}\n")         # a read under the legacy run
        self.assertTrue(any("legacy directories differ" in x for x in U.namespace_violations(led, "OB9020", planned, dig, ["OB9020-R2"])))

    def test_T13_revision_binding(self):
        ok = {"contract": {"revision": 7, "sha256": U.ADDENDUM_SHA256, "path": U.ADDENDUM_PATH}}
        self.assertEqual(U.revision_violations(ok), [])
        for bad in ({"revision": "7"}, {"revision": 7, "sha256": "0" * 64}, {"revision": 8}):
            h = copy.deepcopy(ok)
            h["contract"].update(bad)
            self.assertTrue(U.revision_violations(h), bad)

    def test_canonical_reader_grammar(self):
        rx = U.canonical_reader("/abs/p3b_read_source.py")
        good = "python3 -B /abs/p3b_read_source.py --run OB9020-R7-L01 --batch OB9020 --label lab-a --step 7 --mode bytes --page 1 S0001"
        self.assertTrue(rx.match(good))
        for bad in (good + " | head", good.replace("OB9020-R7-L01", "OB9020-R2"), "cd x && " + good,
                    good.replace("--label lab-a", "--label $L"), good + "; " + good, good.replace("R7-L01", "R7-L01S")):
            self.assertIsNone(rx.match(bad), bad)


class InputManifest(unittest.TestCase):
    def plan(self):
        return {"batch_id": "OB9020", "revision": 7, "labels": {
            "lab-a": {"index": 1, "path": "DECOMPOSED", "runs": {"OB9020-R7-L01U01": {"role": "UNIT", "files": ["S1"]},
                                                                 "OB9020-R7-L01U02": {"role": "UNIT", "files": ["S2"]},
                                                                 "OB9020-R7-L01S": {"role": "SYNTHESIS", "files": []}}},
            "lab-b": {"index": 2, "path": "SINGLE", "runs": {"OB9020-R7-L02": {"role": "SINGLE", "files": ["S3"]}}}}}

    def test_synthesis_manifest_contains_same_label_unit_records_only(self):
        d = tempfile.mkdtemp(prefix="im-")
        try:
            m = U.input_manifest(d, "OB9020", "sr", self.plan(), "OB9020-R7-L01S")
            cats = [e["category"] for e in m["entries"]]
            self.assertEqual(cats.count("UNIT-RECORDS"), 2)
            self.assertTrue(all("L01U" in e["path"] for e in m["entries"] if e["category"] == "UNIT-RECORDS"))
            u = U.input_manifest(d, "OB9020", "sr", self.plan(), "OB9020-R7-L01U01")
            self.assertNotIn("UNIT-RECORDS", [e["category"] for e in u["entries"]])
            self.assertTrue(any("without sha256" in x for x in U.input_manifest_violations(m, self.plan(), "OB9020", "sr")))
        finally:
            shutil.rmtree(d, ignore_errors=True)

    def test_T95_prohibited_entries_reported(self):
        m = {"run": "OB9020-R7-L01S", "role": "SYNTHESIS", "persisted_outputs": "ALLOWED", "entries": [
            {"path": "P3B-HOLDOUT-SEAL.json", "owner_run": "OB9020-R7-L01S", "category": "PLAN", "sha256": "x", "declared_role": "SYNTHESIS"},
            {"path": "ledger-p3b-r2/OB9020-R7-L02/file-reading-records.jsonl", "owner_run": "OB9020-R7-L01S",
             "category": "UNIT-RECORDS", "sha256": "x", "declared_role": "SYNTHESIS"},
            {"path": "sr/OB9020/lab-b.json", "owner_run": "OB9020-R7-L01S", "category": "SLICE", "sha256": "x",
             "declared_role": "SYNTHESIS"},
            {"path": "app/Models/User.php", "owner_run": "OB9020-R7-L01S", "category": "DECLARED-SYNTHESIS-INPUT",
             "sha256": "x", "declared_role": "SYNTHESIS"}]}
        m["manifest_sha256"] = U.sha(U.canon(m))
        F = U.input_manifest_violations(m, self.plan(), "OB9020", "sr")
        self.assertTrue(any("prohibited path" in x and "HOLDOUT" in x for x in F))
        self.assertTrue(any("not of the same label" in x for x in F))
        self.assertTrue(any("not the run's own label slice" in x for x in F))
        self.assertTrue(any("app/Models" in x for x in F))
        self.assertEqual(len(m["entries"]), 4)                  # reported, never removed


class MetaSubset(unittest.TestCase):
    def test_status_basis_sources_must_be_cited(self):
        o = obj(status_basis={"births.lexical": {"sources": ["S0001"], "what_says_this": "birth [S0001]"}})
        self.assertEqual(U.meta_violations(REG, o), [])
        o = obj(status_basis={"births.lexical": {"sources": ["S0007"], "what_says_this": "x"}}, hindsight_dependency=["S0008"])
        F = U.meta_violations(REG, o)
        self.assertTrue(any("S0007" in x for x in F) and any("S0008" in x for x in F))


BIN = b"\x00\x01binary\x00" * 10
TXT = b"plain text file\n" * 10


def decisions_bytes(recs=None, authority="G-LOG-0096", artifact="BINARY-DECISIONS"):
    recs = recs if recs is not None else [
        {"source_id": "S0001", "decision": "FALSE-HIT", "content_sha256": U.sha(BIN), "authorized_by": authority},
        {"source_id": "S0002", "decision": "NOT-CONSUMED-ESCALATED", "content_sha256": U.sha(BIN), "authorized_by": authority}]
    return json.dumps({"artifact": artifact, "authority": authority, "decisions": recs}, sort_keys=True).encode()


class BinaryDecisionsArtifact(unittest.TestCase):
    """v2.6-DC3 hardening (AG-1 checklist): an invalid decision artifact never lets execution continue silently; a valid
    one applies exactly the governed decisions, with provenance kept."""

    def load(self, raw, authority="G-LOG-0096", sha=None):
        return U.binary_decisions(raw, sha or (U.sha(raw) if raw is not None else "0" * 64), authority)

    def test_valid_artifact_keeps_both_classes_and_provenance(self):
        dec, recs, F = self.load(decisions_bytes())
        self.assertEqual(F, [])
        self.assertEqual(dec, {"S0001": "FALSE-HIT", "S0002": "NOT-CONSUMED-ESCALATED"})
        self.assertEqual(recs["S0001"]["content_sha256"], U.sha(BIN))
        self.assertEqual(recs["S0002"]["authorized_by"], "G-LOG-0096")

    def test_no_header_binding_means_no_decisions(self):
        self.assertEqual(U.binary_decisions(decisions_bytes(), None, None), ({}, {}, []))

    def test_rejections(self):
        good = {"source_id": "S0003", "decision": "FALSE-HIT", "content_sha256": U.sha(BIN), "authorized_by": "G-LOG-0096"}
        cases = {
            "unavailable": (None, None),
            "hash mismatch (tampering / replacement)": (decisions_bytes(), "1" * 64),
            "malformed": (b"{not json", None),
            "empty": (decisions_bytes(recs=[]), None),
            "wrong artifact": (decisions_bytes(artifact="SOMETHING-ELSE"), None),
            "wrong authority": (decisions_bytes(authority="G-LOG-0001"), None),
            "duplicate": (decisions_bytes(recs=[good, dict(good)]), None),
            "EXTRACT": (decisions_bytes(recs=[dict(good, decision="EXTRACT")]), None),
            "unsupported value": (decisions_bytes(recs=[dict(good, decision="MAYBE")]), None),
            "missing content sha": (decisions_bytes(recs=[{k: v for k, v in good.items() if k != "content_sha256"}]), None),
            "record authority ≠ artifact authority": (decisions_bytes(recs=[dict(good, authorized_by="G-LOG-0002")]), None),
        }
        for name, (raw, sha) in cases.items():
            want = sha or (U.sha(raw) if raw is not None else "0" * 64)
            dec, recs, F = U.binary_decisions(raw, want, "G-LOG-0096")
            self.assertTrue(F, name)
            self.assertEqual(dec, {}, f"{name}: an invalid artifact applies NO decision")

    def test_integrity_against_resolver_bytes(self):
        _, recs, _ = self.load(decisions_bytes())
        ok = U.binary_decision_integrity(recs, {"S0001": BIN, "S0002": BIN, "S0009": TXT}, {"S0001", "S0002", "S0009"})
        self.assertEqual(ok, [])
        F = U.binary_decision_integrity(recs, {"S0001": TXT, "S0002": BIN}, {"S0001", "S0002"})
        self.assertTrue(any("not binary" in x for x in F))                       # reader/verifier disagreement
        F = U.binary_decision_integrity(recs, {"S0001": BIN[::-1], "S0002": BIN}, {"S0001", "S0002"})
        self.assertTrue(any("content sha256" in x for x in F))                   # the decided bytes changed
        F = U.binary_decision_integrity(recs, {"S0001": BIN, "S0002": BIN, "S0007": BIN}, {"S0001", "S0002", "S0007"})
        self.assertTrue(any("undecided binary" in x and "S0007" in x for x in F))  # a missing decision
        self.assertEqual(U.binary_decision_integrity(recs, {"S0009": TXT}, {"S0009"}), [])   # decisions for other batches


if __name__ == "__main__":
    unittest.main()
