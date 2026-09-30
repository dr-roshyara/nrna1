#!/usr/bin/env python3
"""F-Series v1.2 infrastructure and adversarial tests. Every test runs on a synthetic fixture in a throw-away root
outside the repository (F_SERIES_TEST_ROOT); no F-file is read and no repository file is written. Test names cite the
audit finding they answer (F-nn, RN-nn, C-nn). A passing test proves only what it checks: that the code conforms to
the v1.2 specification — not that the specification is methodologically valid.

  python3 tests/test_f_pipeline.py -v
"""
import hashlib
import importlib
import json
import os
import shutil
import subprocess
import sys
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.join(os.path.dirname(HERE), "scripts")
FDIR_REL = "docs/knowledgeos/chronological_knowelgeos_ablation_theory"
PAGE = 20000
SHORT = ("short second file with one quotable sentence in it for tests.\n\nA **widget** is a map from A to B.\n\n"
         "$$\nW : A \\to B\n$$\n\n---\n\nThat is all.\n")
NOW = "2026-09-25T12:00:00Z"


def fixture_text():
    parts = []
    for k in (1, 2, 3):
        s = f"Page-{k} marker sentence: the fixture states that object X{k} is defined as a map from A{k} to B{k}. "
        parts.append((s * (PAGE // len(s) + 1))[:PAGE] if k < 3 else s * 20)
    return "".join(parts)


def sha(b):
    return hashlib.sha256(b).hexdigest()


class Root:
    """A throw-away F-Series root with manifest, approval lines, human refs and undecided HDR switches."""

    def __init__(self, approve=True, n_checkpoint="50"):
        self.root = tempfile.mkdtemp(prefix="f-series-test-")
        self.fdir = os.path.join(self.root, FDIR_REL)
        os.makedirs(os.path.join(self.fdir, "ledger"))
        self.env = dict(os.environ, F_SERIES_TEST_ROOT=self.root, PYTHONDONTWRITEBYTECODE="1", F_SERIES_CP_N=n_checkpoint)
        os.environ.update(F_SERIES_TEST_ROOT=self.root, F_SERIES_CP_N=n_checkpoint)
        if SCRIPTS not in sys.path:
            sys.path.insert(0, SCRIPTS)
        import f_common
        importlib.reload(f_common)
        self.C = f_common
        for m in ("f_integrity", "f_checks", "f_compare_inventory", "f_checkpoint", "f_audit"):
            importlib.reload(importlib.import_module(m))
        import f_checks
        import f_integrity
        self.K, self.I = f_checks, f_integrity
        for rel in f_common.GOVERNING_DOCS:                       # stand-in governing documents
            p = os.path.join(self.root, rel)
            os.makedirs(os.path.dirname(p), exist_ok=True)
            open(p, "w").write(f"stand-in for {rel}\n")
        rows = []
        for i, fid in enumerate(("F9001", "F9002", "F9003"), 1):   # F9003 = byte copy of F9002 (RL-03)
            rel = f"fixture/{fid}.md"
            p = os.path.join(self.root, rel)
            os.makedirs(os.path.dirname(p), exist_ok=True)
            with open(p, "w", encoding="utf-8") as fh:
                fh.write(fixture_text() if fid == "F9001" else SHORT)
            b = open(p, "rb").read()
            t = b.decode()
            rows.append({"f_id": fid, "list_order": i, "listed_path": rel, "resolved_path": rel, "resolve": "RESOLVED",
                         "content_sha256": sha(b), "size_bytes": len(b), "kind": "TEXT", "n_chars": len(t),
                         "n_pages": len(f_common.pages_of(t)), "dup_within_f": "F9002" if fid == "F9003" else None,
                         "content_equals_s_sources": ["S0999"] if fid == "F9002" else [], "repair_evidence": None,
                         "list_mtime": f"Sep {i} 10:00", "file_mtime": 1790000000 + i})
        with open(os.path.join(self.fdir, "F-MANIFEST.jsonl"), "w") as f:
            for r in rows:
                f.write(json.dumps(r) + "\n")
        json.dump({"temporal_semantics": None, "multiplicity_rules": None},
                  open(os.path.join(self.fdir, "F-DECISIONS.json"), "w"))
        self.write_govlog(approve)
        for fid in ("F9001", "F9002", "F9003"):
            for st in ("REGISTERED", "RESOLVED", "IDENTIFIED"):
                f_common.record_event(fid, st, {"test": True}, run_id="F-P0")

    def write_govlog(self, approve=True, extra=""):
        lines = ["# F-SERIES GOVERNANCE LOG (fixture)", ""]
        if approve:
            for rel in self.C.GOVERNING_DOCS:
                lines.append(f"APPROVED-FOR-EXECUTION: {rel} {sha(open(os.path.join(self.root, rel), 'rb').read())}")
        lines += ["", "## F-LOG-0901 — fixture human decisions", "Accepts L1 of F9001 and F9002; exceptions for F9001.",
                  "", "## F-LOG-0902 — fixture decision about another file", "Names F9003 only.", "", extra]
        open(os.path.join(self.fdir, "F-GOVERNANCE-LOG.md"), "w").write("\n".join(lines) + "\n")

    # ---- helpers --------------------------------------------------------------------------------------------------
    def run(self, script, *args, stdout=subprocess.PIPE):
        return subprocess.run([sys.executable, os.path.join(SCRIPTS, script), *args], env=self.env,
                              stdout=stdout, stderr=subprocess.PIPE, text=True)

    def tr(self, *a):
        return self.run("f_transition.py", *a)

    def L(self, fid, name):
        return os.path.join(self.fdir, "ledger", fid, name)

    def wj(self, fid, name, rows):
        os.makedirs(os.path.join(self.fdir, "ledger", fid), exist_ok=True)
        with open(self.L(fid, name), "w") as f:
            for r in rows:
                f.write(json.dumps(r) + "\n")

    def wjson(self, fid, name, obj):
        os.makedirs(os.path.join(self.fdir, "ledger", fid), exist_ok=True)
        json.dump(obj, open(self.L(fid, name), "w"))

    def text(self, fid):
        return open(os.path.join(self.root, f"fixture/{fid}.md"), encoding="utf-8").read()

    def units(self, fid):
        return self.K.units_of(self.text(fid))

    def read(self, fid="F9001", run=None, pages=None, digests_name="PAGE-DIGESTS.jsonl"):
        run = run or f"FR-{fid}-001"
        t = self.text(fid)
        n = len(self.C.pages_of(t))
        pages = list(pages or range(1, n + 1))
        assert self.run("f_read_source.py", "--run", run, "--info", fid).returncode == 0
        for k in pages:
            r = self.run("f_read_source.py", "--run", run, "--page", str(k), fid)
            assert r.returncode == 0, r.stderr
        rows = [{"run_id": run, "page": k, "digest": f"page {k} of the fixture", "verbatim_quote": t[a:b][:60]}
                for k, (a, b) in enumerate(self.C.pages_of(t), 1) if k in pages]
        self.wj(fid, digests_name, rows)
        return run

    def to_read_complete(self, fid="F9001"):
        run = self.read(fid)
        assert self.tr(fid, "READING", "--run", run).returncode == 0
        r = self.tr(fid, "READ-COMPLETE", "--run", run)
        assert r.returncode == 0, r.stderr
        return run

    def attest(self, fid="F9001", run=None, role="EXTRACTOR", name="ISOLATION-ATTESTATION.json", extra_reads=()):
        run = run or f"FR-{fid}-001"
        self.wjson(fid, name, {"run_id": run, "role": role, "model_id": "test-model",
                               "received": [f"{FDIR_REL}/prompts/protocol.md", f"reader:{fid}"],
                               "read_paths": [f"{FDIR_REL}/prompts/protocol.md", f"reader:{fid}", *extra_reads],
                               "denied_material_consulted": False})

    def inventory(self, fid="F9001", skip=(), dispose=(), override=None, name="CONTENT-INVENTORY.jsonl",
                  write_units=True):
        run = f"FR-{fid}-001"
        if write_units:
            r = self.run("f_units.py", "--run", run, fid)
            assert r.returncode == 0, r.stderr
        t, items, disp, n = self.text(fid), [], [], 0
        for u in self.units(fid):
            if u["kind"] == "MARKUP" or u["unit_id"] in skip:
                continue
            ut = t[u["char_start"]:u["char_end"]].strip()
            if u["unit_id"] in dispose:
                disp.append({"unit_ids": [u["unit_id"]], "disposition": "NO-SUBSTANTIVE-CONTENT", "reason": "closing rhetoric"})
                continue
            n += 1
            prefix = "FCI" if name == "CONTENT-INVENTORY.jsonl" else "FAI"
            it = {"item_id": f"{prefix}-{fid}-{n:04d}", "covers_units": [u["unit_id"]], "page": u["page_start"],
                  "verbatim_quote": ut[:200], "content": "fixture extraction of this unit's claim"}
            if u["math_bearing"]:
                it.update(category="FORMULA", latex=ut.strip("$").strip())
            elif u["definition_cue"]:
                it.update(category="DEFINITION", term="widget" if fid != "F9001" else "object X1",
                          source_definition=ut[:200], context="fixture", related_terms=[], examples=[],
                          qualifications=[], definition_form="INFORMAL")
            else:
                it["category"] = "CONCEPT"
            if override and u["unit_id"] in override:
                it.update(override[u["unit_id"]])
            items.append(it)
        self.wj(fid, name, items)
        if name == "CONTENT-INVENTORY.jsonl":
            self.wj(fid, "UNIT-DISPOSITIONS.jsonl", disp)
            cnt = {}
            for i in items:
                cnt[i["category"]] = cnt.get(i["category"], 0) + 1
            self.wjson(fid, "CATEGORY-CHECK.json", {c: {"count": cnt.get(c, 0), "checked": f"walked every unit for {c}"}
                                                    for c in self.K.INVENTORY_CATEGORIES})
            self.attest(fid)
        return items

    def to_content_extracted(self, fid="F9001"):
        run = self.to_read_complete(fid)
        self.inventory(fid)
        r = self.tr(fid, "CONTENT-EXTRACTED", "--run", run)
        assert r.returncode == 0, r.stderr
        return run

    def independent_audit(self, fid="F9001", verdict="CONFIRMED", href="F-LOG-0901", aud_override=None):
        arun = f"FR-{fid}-901"
        self.read(fid, run=arun, digests_name="AUDITOR-PAGE-DIGESTS.jsonl")
        self.inventory(fid, name="AUDITOR-INVENTORY.jsonl", override=aud_override, write_units=False)
        self.attest(fid, run=arun, role="AUDITOR", name="AUDITOR-ATTESTATION.json")
        args = ["--auditor-run", arun, "--run", f"FR-{fid}-001", "--verdict", verdict]
        if href:
            args += ["--human-ref", href]
        return self.run("f_compare_inventory.py", *args, fid)

    def phase1(self, fid="F9001", refs=None):
        row = [r for r in self.C.read_jsonl(os.path.join(self.fdir, "F-MANIFEST.jsonl")) if r["f_id"] == fid][0]
        inv = self.C.read_jsonl(self.L(fid, "CONTENT-INVENTORY.jsonl"))
        self.wj(fid, "files.jsonl", [{"f_id": fid, "content_sha256": row["content_sha256"], "status": "CONTENT",
                                      "summary": "fixture file", "contribution_assessment": "defines fixture objects",
                                      "provenance": "PRIMARY", "order_evidence": "LIST-POSITION",
                                      "content_identical_to_s": bool(row["content_equals_s_sources"]),
                                      "objects_touched": ["x"], "explicit_dates": []}])
        contribs = []
        for it in inv:
            if refs is not None and it["item_id"] not in refs:
                continue
            contribs.append({"f_id": fid, "anchor": it["verbatim_quote"][:120], "scope": "OBJECT", "labels": ["x"],
                             "types": ["DEFINITION" if it["category"] == "DEFINITION" else "CONCEPT"],
                             "statement": "fixture contribution", "completeness": "PARTIAL", "review_flag": None,
                             "version_ref": "unknown", "assumptions": [], "lineage_claims": [],
                             "inventory_refs": [it["item_id"]]})
        self.wj(fid, "contributions.jsonl", contribs)
        self.wj(fid, "index-proposals.jsonl", [])

    def to_reconstructed(self, fid="F9001"):
        run = self.to_content_extracted(fid)
        if fid == "F9001":
            r = self.independent_audit(fid)
            assert r.returncode == 0, r.stderr + r.stdout
        self.phase1(fid)
        r = self.tr(fid, "RECONSTRUCTED", "--run", run)
        assert r.returncode == 0, r.stderr
        return run

    def analyze(self, fid="F9001", rec=None, lens=None, dispose=True):
        inv = [i["item_id"] for i in self.C.read_jsonl(self.L(fid, "CONTENT-INVENTORY.jsonl"))]
        r = {"an_id": f"FAN-{fid}-001", "level": 2, "kind": "STRUCTURE", "lens": "MATHEMATICAL",
             "epistemic_class": "RESEARCH-OBSERVATION", "statement": "the file states a map signature",
             "reasoning": "each object is introduced with a domain and a codomain", "inventory_refs": inv[:1],
             "research_time": NOW, "run_id": f"FR-{fid}-001", "model_id": "test"}
        r.update(rec or {})
        self.wj(fid, "ANALYSIS.jsonl", [r])
        ck = {"STRUCTURAL": {"status": "NOT-APPLICABLE", "reason": "fixture has no structure"},
              "MATHEMATICAL": {"status": "APPLIED", "reason": "fixture defines maps"},
              "LOGICAL": {"status": "NOT-APPLICABLE", "reason": "fixture has no argument"},
              "DDD": {"status": "NOT-APPLICABLE", "reason": "fixture has no domain model"},
              "STATISTICAL-ML": {"status": "DEFERRED-TO-CHECKPOINT", "reason": "C09 checkpoint only"}}
        ck.update(lens or {})
        self.wjson(fid, "ANALYSIS-CHECKLIST.json", ck)
        importlib.reload(self.K)
        cands = self.K.cross_file_candidates(fid)
        self.wj(fid, "CROSS-FILE.jsonl", [{"candidate_id": c["candidate_id"], "disposition": "UNDETERMINED",
                                           "basis": "compared the two quotes; not decidable"} for c in cands]
                if dispose else [])
        return cands

    def to_analyzed(self, fid="F9001"):
        run = self.to_reconstructed(fid)
        self.analyze(fid)
        r = self.tr(fid, "ANALYZED", "--run", run)
        assert r.returncode == 0, r.stderr
        return run

    def research(self, fid="F9001", rec=None, evidence=None):
        runbook = os.path.join(self.root, self.C.CONTRACT_REL)
        q = {"F9001": "object X1 is defined as a map from A1 to B1", "F9002": "short second file with one quotable"}[fid]
        r = {"rs_id": f"FRS-{fid}-001", "kind": "SUGGESTION-RESEARCH", "level": 3, "topics": ["t"],
             "lens": "MATHEMATICAL", "scale": "OBJECT", "statement": "investigate whether the maps compose",
             "epistemic_class": "RESEARCH-SUGGESTION", "output_layer": "C",
             "supporting_evidence": evidence or [{"f_id": fid, "quote": q, "evidence_kind": "CORPUS"}],
             "research_time": NOW, "run_id": f"FR-{fid}-001", "model_id": "test",
             "contract_sha256": sha(open(runbook, "rb").read()), "analysis_refs": [f"FAN-{fid}-001"]}
        r.update(rec or {})
        self.wj(fid, "research.jsonl", [r])

    def to_audited(self, fid="F9001"):
        run = self.to_analyzed(fid)
        self.research(fid)
        r = self.tr(fid, "RESEARCHED", "--run", run)
        assert r.returncode == 0, r.stderr
        r = self.tr(fid, "AUDITED", "--run", run)
        assert r.returncode == 0, r.stderr
        return run

    def close(self):
        shutil.rmtree(self.root)


class Base(unittest.TestCase):
    approve = True
    n_checkpoint = "50"

    def setUp(self):
        self.R = Root(approve=self.approve, n_checkpoint=self.n_checkpoint)

    def tearDown(self):
        self.R.close()

    def refused(self, r, text):
        self.assertNotEqual(r.returncode, 0, r.stdout + r.stderr)
        self.assertIn(text, r.stderr + r.stdout)


# ============================== reading (C01; F-13) ================================================================
class Reading(Base):
    def test_happy_path_to_audited(self):
        self.R.to_audited()
        st = self.R.run("f_status.py").stdout
        self.assertIn("chain: VERIFIED", st)
        self.assertIn("last contiguous AUDITED F-ID (list order): F9001", st)

    def test_missing_page_blocks_read_complete(self):
        run = self.R.read(pages=(1, 3))
        self.R.tr("F9001", "READING", "--run", run)
        self.refused(self.R.tr("F9001", "READ-COMPLETE", "--run", run), '"missing_pages": [2]')

    def test_forged_page_hash(self):
        run = self.R.read()
        self.R.tr("F9001", "READING", "--run", run)
        log = self.R.C.read_jsonl(self.R.L("F9001", "READ-LOG.jsonl"))
        for x in log:
            if x.get("page", {}).get("page") == 2:
                x["page"]["page_sha256"] = "0" * 64
        self.R.wj("F9001", "READ-LOG.jsonl", log)
        self.refused(self.R.tr("F9001", "READ-COMPLETE", "--run", run), '"forged_or_mismatched_pages": [2]')

    def test_digest_from_wrong_page(self):
        run = self.R.read()
        self.R.tr("F9001", "READING", "--run", run)
        d = self.R.C.read_jsonl(self.R.L("F9001", "PAGE-DIGESTS.jsonl"))
        d[2]["verbatim_quote"] = d[0]["verbatim_quote"]
        self.R.wj("F9001", "PAGE-DIGESTS.jsonl", d)
        self.refused(self.R.tr("F9001", "READ-COMPLETE", "--run", run), "digest_missing_or_invalid_pages")

    def test_redirect_refused(self):
        with open(os.path.join(self.R.root, "out.txt"), "w") as f:
            r = self.R.run("f_read_source.py", "--run", "FR-F9001-001", "--page", "1", "F9001", stdout=f)
        self.assertEqual(r.returncode, 3)

    def test_identity_drift(self):
        open(os.path.join(self.R.root, "fixture/F9001.md"), "a").write("x")
        self.refused(self.R.run("f_read_source.py", "--run", "FR-F9001-001", "--info", "F9001"), "IDENTITY-DRIFT")

    def test_F13_reader_refuses_look_ahead(self):
        self.refused(self.R.run("f_read_source.py", "--run", "FR-F9002-001", "--info", "F9002"), "no look-ahead")


class Unapproved(Base):
    approve = False

    def test_F20_unapproved_protocol_blocks_reading_and_transitions(self):
        self.refused(self.R.run("f_read_source.py", "--run", "FR-F9001-001", "--info", "F9001"), "NOT APPROVED")
        self.refused(self.R.tr("F9001", "READING", "--run", "FR-F9001-001"), "NOT APPROVED")

    def test_F20_contract_changed_after_approval_revokes_execution(self):
        rel = f"{FDIR_REL}/prompts/contracts-v1.2/C99-fixture.md"
        p = os.path.join(self.R.root, rel)
        open(p, "w").write("fixture contract\n")
        idx = os.path.join(self.R.root, self.R.C.CONTRACTS_INDEX_REL)
        open(idx, "w").write(f"| C99 | `{rel}` | `{sha(open(p, 'rb').read())}` |\n")
        self.R.write_govlog(approve=True)                        # approve the index as it now stands
        self.assertEqual(self.R.run("f_read_source.py", "--run", "FR-F9001-001", "--info", "F9001").returncode, 0)
        open(p, "a").write("an edit after approval\n")
        self.refused(self.R.run("f_read_source.py", "--run", "FR-F9001-001", "--info", "F9001"),
                     "differ from the approved index")

    def test_F20_approval_inside_code_block_is_not_approval(self):
        lines = [f"APPROVED-FOR-EXECUTION: {rel} {sha(open(os.path.join(self.R.root, rel), 'rb').read())}"
                 for rel in self.R.C.GOVERNING_DOCS]
        for wrap in (("```", "```"), ("~~~", "~~~")):
            self.R.write_govlog(approve=False, extra="\n".join([wrap[0], *lines, wrap[1]]))
            self.refused(self.R.run("f_read_source.py", "--run", "FR-F9001-001", "--info", "F9001"), "NOT APPROVED")
        self.R.write_govlog(approve=False, extra="\n".join("    " + l for l in lines))     # indented template
        self.refused(self.R.run("f_read_source.py", "--run", "FR-F9001-001", "--info", "F9001"), "NOT APPROVED")

    def test_F20_approval_with_wrong_hash_is_not_approval(self):
        self.R.write_govlog(approve=False, extra="\n".join(
            f"APPROVED-FOR-EXECUTION: {rel} {'0' * 64}" for rel in self.R.C.GOVERNING_DOCS))
        self.refused(self.R.run("f_read_source.py", "--run", "FR-F9001-001", "--info", "F9001"), "NOT APPROVED")


# ============================== state integrity (C13; F-07, F-17) ==================================================
class StateIntegrity(Base):
    def _append_raw(self, rec):
        with open(os.path.join(self.R.fdir, "F-SERIES-STATE.jsonl"), "a") as f:
            f.write(json.dumps(rec, sort_keys=True, separators=(",", ":")) + "\n")

    def test_illegal_transition_refused_before_any_evidence_work(self):
        self.R.read()
        self.refused(self.R.tr("F9001", "READ-COMPLETE", "--run", "FR-F9001-001"), "ILLEGAL-TRANSITION")

    def test_F07_forged_event_breaks_chain(self):
        self._append_raw({"f_id": "F9001", "seq": 4, "from": "IDENTIFIED", "state": "READING", "run_id": "FR-F9001-001"})
        self.refused(self.R.tr("F9001", "READ-COMPLETE", "--run", "FR-F9001-001"), "chain broken")
        self.refused(self.R.run("f_status.py"), "BROKEN")

    def test_F07_wrong_prev_hash(self):
        rec = self.R.I.chained_record({"f_id": "F9001", "seq": 4, "from": "IDENTIFIED", "state": "READING"})
        rec["prev_hash"] = "0" * 64
        rec["hash"] = sha(self.R.I.canonical({k: v for k, v in rec.items() if k != "hash"}).encode())
        self._append_raw(rec)
        ok, f = self.R.I.verify_chain()
        self.assertFalse(ok)
        self.assertTrue(any("prev_hash" in x for x in f))

    def test_F07_broken_seq_and_wrong_from(self):
        self._append_raw(self.R.I.chained_record({"f_id": "F9001", "seq": 9, "from": "RESEARCHED", "state": "AUDITED"}))
        ok, f = self.R.I.verify_chain()
        self.assertFalse(ok)
        self.assertTrue(any("seq 9" in x for x in f))
        self.assertTrue(any("from=RESEARCHED" in x for x in f))

    def test_F07_edited_legacy_line_detected_by_anchor(self):
        p = os.path.join(self.R.fdir, "F-SERIES-STATE.jsonl")
        lines = open(p).read().splitlines()
        legacy = [json.dumps({k: v for k, v in json.loads(l).items() if k not in ("hash", "prev_hash")},
                             sort_keys=True, separators=(",", ":")) for l in lines]
        open(p, "w").write("\n".join(legacy) + "\n")                # an unchained, pre-v1.2 ledger
        self.R.I.write_anchor()
        self.assertTrue(self.R.I.verify_chain()[0])
        self.R.C.record_event("F9001", "READING", {"test": True}, run_id="FR-F9001-001")   # chained after the anchor
        self.assertTrue(self.R.I.verify_chain()[0])
        body = open(p).read().splitlines()
        body[0] = body[0].replace('"test":true', '"test":false')
        open(p, "w").write("\n".join(body) + "\n")
        ok, f = self.R.I.verify_chain()
        self.assertFalse(ok)
        self.assertTrue(any("anchor" in x.lower() for x in f))

    def test_F07_hand_written_audit_json_is_ignored(self):
        run = self.R.to_analyzed()
        self.R.research()
        self.R.tr("F9001", "RESEARCHED", "--run", run)
        os.remove(self.R.L("F9001", "research.jsonl"))
        self.R.wjson("F9001", "AUDIT.json", {"run_id": run, "audited_state": "RESEARCHED", "verdict": "PASS"})
        self.refused(self.R.tr("F9001", "AUDITED", "--run", run), "audit FAIL")

    def test_F17_audit_failed_cannot_be_audited(self):
        self.assertNotIn("AUDIT-FAILED", self.R.C.ALLOWED_FROM["AUDITED"])

    def test_F17_run_mismatch_refused(self):
        self.R.to_content_extracted()
        self.refused(self.R.tr("F9001", "RECONSTRUCTED", "--run", "FR-F9001-002"), "not the run of the previous event")

    def test_F17_re_entry_after_audit_failed_keeps_run_and_refreezes(self):
        run = self.R.to_analyzed()
        self.R.research(rec={"analysis_refs": ["FAN-F9001-001"]})
        self.assertEqual(self.R.tr("F9001", "RESEARCHED", "--run", run).returncode, 0)
        os.remove(self.R.L("F9001", "ANALYSIS-CHECKLIST.json"))    # the audit must now fail
        r = self.R.tr("F9001", "AUDIT-FAILED", "--run", run)
        self.assertEqual(r.returncode, 0, r.stderr)
        self.refused(self.R.tr("F9001", "AUDITED", "--run", run), "ILLEGAL-TRANSITION")
        self.refused(self.R.tr("F9001", "ANALYZED", "--run", run), "ANALYSIS-CHECKLIST")
        self.R.analyze()                                           # restore; re-enter at ANALYZED
        self.refused(self.R.tr("F9001", "ANALYZED", "--run", "FR-F9001-002"), "not the run of the previous event")
        self.assertEqual(self.R.tr("F9001", "ANALYZED", "--run", run).returncode, 0)
        frz = self.R.I.active_freezes("F9001")
        self.assertIn("ANALYZED", frz)
        self.assertNotIn("RESEARCHED", frz)                        # the later stage's freeze is invalidated


# ============================== L1 freeze (F-01) ===================================================================
class Freeze(Base):
    def test_F01_edit_inventory_after_content_extracted(self):
        run = self.R.to_content_extracted()
        self.assertEqual(self.R.independent_audit().returncode, 0)
        inv = self.R.C.read_jsonl(self.R.L("F9001", "CONTENT-INVENTORY.jsonl"))
        inv[0]["content"] = "silently rewritten after the gate"
        self.R.wj("F9001", "CONTENT-INVENTORY.jsonl", inv)
        self.R.phase1()
        self.refused(self.R.tr("F9001", "RECONSTRUCTED", "--run", run), "changed after the gate")

    def test_F01_deleted_frozen_file(self):
        run = self.R.to_content_extracted()
        os.remove(self.R.L("F9001", "CATEGORY-CHECK.json"))
        self.refused(self.R.tr("F9001", "RECONSTRUCTED", "--run", run), "changed after the gate")

    def test_F01_edit_research_after_researched(self):
        run = self.R.to_analyzed()
        self.R.research()
        self.assertEqual(self.R.tr("F9001", "RESEARCHED", "--run", run).returncode, 0)
        self.R.research(rec={"statement": "a different, later suggestion"})
        self.refused(self.R.tr("F9001", "AUDITED", "--run", run), "changed after the gate")

    def test_F01_freeze_hashes_recorded_in_event(self):
        self.R.to_content_extracted()
        ev = self.R.C.events("F9001")[-1]
        self.assertEqual(set(ev["evidence"]["frozen"]), {"UNITS.jsonl", "CONTENT-INVENTORY.jsonl",
                         "UNIT-DISPOSITIONS.jsonl", "CATEGORY-CHECK.json", "ISOLATION-ATTESTATION.json"})
        self.assertTrue(all(len(h) == 64 for h in ev["evidence"]["frozen"].values()))
        self.assertIn("governing_sha256", ev)

    def test_F01_units_cannot_be_rewritten_after_freeze(self):
        run = self.R.to_reconstructed()
        self.refused(self.R.run("f_units.py", "--run", run, "F9001"), "frozen after CONTENT-EXTRACTED")


# ============================== content extraction (C02, C03, C07, C15; F-02–F-05, F-14, F-19) =====================
class Extraction(Base):
    def _ce(self, **kw):
        self.R.to_audited("F9001")
        run = self.R.to_read_complete("F9002")
        self.R.inventory("F9002", **kw)
        return run

    def test_F03_catch_all_item(self):
        run = self._ce()
        units = [u["unit_id"] for u in self.R.units("F9002") if u["kind"] != "MARKUP"]
        self.R.wj("F9002", "CONTENT-INVENTORY.jsonl", [{"item_id": "FCI-F9002-0001", "category": "CONCEPT",
                  "covers_units": units, "page": 1, "verbatim_quote": "is", "content": "x"}])
        r = self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run)
        self.refused(r, "shorter than 20")
        for s in ("content must be an extraction", "DEFINITION/TERM", "FORMULA/NOTATION/THEOREM"):
            self.assertIn(s, r.stderr)

    def test_F03_non_consecutive_units(self):
        run = self._ce(override={"U0001": {"covers_units": ["U0001", "U0003"]}})
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "consecutive units")

    def test_F03_spliced_quote(self):
        run = self._ce(override={"U0001": {"covers_units": ["U0001", "U0002"],
                                           "verbatim_quote": "short second file A **widget** is a map"}})
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "not a contiguous quote")

    def test_F03_content_equal_to_quote(self):
        run = self._ce(override={"U0001": {"content": "short second file with one quotable sentence in it for tests."}})
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "not the quote itself")

    def test_F05_definition_cue_needs_definition_item(self):
        run = self._ce(override={"U0002": {"category": "CONCEPT"}})
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "DEFINITION/TERM")

    def test_F05_not_a_definition_release_is_accepted(self):
        run = self._ce(override={"U0002": {"category": "CONCEPT"}})
        d = self.R.C.read_jsonl(self.R.L("F9002", "UNIT-DISPOSITIONS.jsonl"))
        d.append({"unit_ids": ["U0002"], "disposition": "NOT-A-DEFINITION", "reason": "fixture: judged an example"})
        self.R.wj("F9002", "UNIT-DISPOSITIONS.jsonl", d)
        r = self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run)
        self.assertEqual(r.returncode, 0, r.stderr)

    def test_F04_math_unit_needs_math_item(self):
        run = self._ce(override={"U0003": {"category": "CONCEPT"}})
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "FORMULA/NOTATION/THEOREM")

    def test_F04_math_unit_cannot_be_dispositioned(self):
        run = self._ce(dispose=("U0003",))
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "never dispositioned")

    def test_prose_disposition_accepted(self):
        run = self._ce(dispose=("U0005",))
        r = self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run)
        self.assertEqual(r.returncode, 0, r.stderr)

    def test_uncovered_unit(self):
        run = self._ce(skip=("U0005",))
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "neither covered nor dispositioned")

    def test_F14_s_lane_identifier_in_inventory_content(self):
        run = self._ce(override={"U0001": {"content": "as S0472 established, this is the kernel"}})
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "S-lane identifiers")

    def test_F19_identical_category_check_text(self):
        run = self._ce()
        cc = json.load(open(self.R.L("F9002", "CATEGORY-CHECK.json")))
        for v in cc.values():
            v["checked"] = "checked the whole file carefully"
        self.R.wjson("F9002", "CATEGORY-CHECK.json", cc)
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "same 'checked' text")

    def test_F02_missing_isolation_attestation(self):
        run = self._ce()
        os.remove(self.R.L("F9002", "ISOLATION-ATTESTATION.json"))
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "ISOLATION-ATTESTATION")

    def test_F02_attestation_citing_denied_material(self):
        run = self._ce()
        self.R.attest("F9002", extra_reads=(".claude/CONTEXT.md", f"{FDIR_REL}/quarantine/F3082-draft-v1.0/p1.py"))
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "outside the C15 allow-list")

    def test_C07_source_framed_contradiction_needs_framing_quote(self):
        run = self._ce(override={"U0001": {"category": "CONTRADICTION-OR-TENSION"}})
        self.refused(self.R.tr("F9002", "CONTENT-EXTRACTED", "--run", run), "source_framing_quote")


class Detectors(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.R = Root()
        cls.K = cls.R.K

    @classmethod
    def tearDownClass(cls):
        cls.R.close()

    def test_F04_math_positives(self):
        for s in ("the map $f : A \\to B$ is total", "\\(x \\in K\\) holds", "we have \\mathfrak{K} = Fix(\\Phi)",
                  "∀x ∈ K, x ⊆ K", "a line $$a=b$$ holds", "𝔎 is the kernel"):
            self.assertTrue(self.K.units_of(s)[0]["math_bearing"], s)

    def test_F04_math_negatives(self):
        for s in ("it costs $5 and $6 today", "see C:\\dir\\new file", "a line with a \\n escape in prose",
                  "Evidence → Authority is a collapse"):
            self.assertFalse(self.K.units_of(s)[0]["math_bearing"], s)

    def test_F04_blocks_and_fences(self):
        t = ("intro\n\n\\[\nx = y\n\\]\n\n\\begin{equation}\na=b\n\\end{equation}\n\n~~~\ncode\n~~~\n\n"
             "para\n\n    indented code\n    more\n\n- item\n\n    continuation\n")
        self.assertEqual([u["kind"] for u in self.K.units_of(t)],
                         ["LINE", "MATH", "MATH", "CODE", "LINE", "CODE", "LIST-ITEM", "LINE"])

    def test_F04_single_line_display_does_not_swallow(self):
        self.assertEqual(len(self.K.units_of("a $$a=b$$ holds\nnext line\n")), 2)
        self.assertEqual(len(self.K.units_of("$$a=b$$ holds\nnext line\n")), 2)

    def test_F05_definition_cue_positives(self):
        for s in ("The kernel is not a set of invariants. It is a self-negating process.",
                  "UNKNOWN is **indexed by the shape that generated it**. There is no general UNKNOWN.",
                  "A shape refers to a form with an immanent criterion.", "Let K be the knowledge category.",
                  "By sublation we mean the elevation of both.", "We say that a state is closed.", "K := Fix(Phi)",
                  "UNKNOWN stands for an indexed failure.", "**Definition 1.** A kernel is …",
                  "## Definition 3: Invariant", "**Kernel** — the fixed point of Dial"):
            self.assertTrue(self.K.definition_cue(s), s)

    def test_F05_definition_cue_negatives(self):
        for s in ("This is a **constraint**, not a **structure**.", "The weather is nice today.",
                  "We walked to the store."):
            self.assertFalse(self.K.definition_cue(s), s)


# ============================== independent L1 audit (C13 §3; F-06) ================================================
class IndependentAudit(Base):
    def test_F06_reconstructed_requires_l1_audit_when_due(self):
        run = self.R.to_content_extracted()
        self.R.phase1()
        self.refused(self.R.tr("F9001", "RECONSTRUCTED", "--run", run), "INDEPENDENT-AUDIT-L1.json is missing")

    def test_F06_two_field_file_is_not_an_audit(self):
        run = self.R.to_content_extracted()
        self.R.wjson("F9001", "INDEPENDENT-AUDIT-L1.json", {"run_id": run, "verdict": "CONFIRMED"})
        self.R.phase1()
        self.refused(self.R.tr("F9001", "RECONSTRUCTED", "--run", run), "does not match the recomputation")

    def test_F06_auditor_without_coverage(self):
        self.R.to_content_extracted()
        self.R.inventory("F9001", name="AUDITOR-INVENTORY.jsonl", write_units=False)
        self.R.attest("F9001", run="FR-F9001-901", role="AUDITOR", name="AUDITOR-ATTESTATION.json")
        r = self.R.run("f_compare_inventory.py", "--auditor-run", "FR-F9001-901", "--run", "FR-F9001-001",
                       "--verdict", "CONFIRMED", "--human-ref", "F-LOG-0901", "F9001")
        self.refused(r, "auditor read coverage incomplete")

    def test_F06_tampered_stored_comparison(self):
        run = self.R.to_content_extracted()
        self.assertEqual(self.R.independent_audit().returncode, 0)
        a = json.load(open(self.R.L("F9001", "INDEPENDENT-AUDIT-L1.json")))
        a["mechanical_discrepancies"] = [{"kind": "fabricated"}]
        self.R.wjson("F9001", "INDEPENDENT-AUDIT-L1.json", a)
        self.R.phase1()
        self.refused(self.R.tr("F9001", "RECONSTRUCTED", "--run", run), "does not match the recomputation")

    def test_F06_discrepancy_blocks_and_re_extraction_needs_human_ref(self):
        run = self.R.to_content_extracted()
        self.refused(self.R.independent_audit(href=None), "CONFIRMED needs --human-ref")
        r = self.R.independent_audit(verdict="DISCREPANCY", href=None, aud_override={"U0001": {"term": "object X2"}})
        self.assertEqual(r.returncode, 0, r.stderr)
        self.assertIn("DEFINITION-TERM-MISSING", r.stdout)
        self.R.phase1()
        self.refused(self.R.tr("F9001", "RECONSTRUCTED", "--run", run), "verdict is DISCREPANCY")
        self.refused(self.R.tr("F9001", "CONTENT-EXTRACTED", "--run", run), "re-extraction")
        self.assertEqual(self.R.tr("F9001", "CONTENT-EXTRACTED", "--run", run, "--human-ref", "F-LOG-0901").returncode, 0)

    def test_F06_auditor_attestation_cannot_read_extractor_l1(self):
        self.R.to_content_extracted()
        arun = "FR-F9001-901"
        self.R.read("F9001", run=arun, digests_name="AUDITOR-PAGE-DIGESTS.jsonl")
        self.R.inventory("F9001", name="AUDITOR-INVENTORY.jsonl", write_units=False)
        self.R.attest("F9001", run=arun, role="AUDITOR", name="AUDITOR-ATTESTATION.json",
                      extra_reads=(f"{FDIR_REL}/ledger/F9001/CONTENT-INVENTORY.jsonl",))
        r = self.R.run("f_compare_inventory.py", "--auditor-run", arun, "--run", "FR-F9001-001", "--verdict",
                       "CONFIRMED", "--human-ref", "F-LOG-0901", "F9001")
        self.refused(r, "outside the C15 allow-list")

    def test_F06_auditor_units_index_needs_auditor_read(self):
        self.R.to_content_extracted()
        self.refused(self.R.run("f_units.py", "--run", "FR-F9001-901", "--auditor", "F9001"), "no complete read coverage")


# ============================== reconstruction (C04) ===============================================================
class Reconstruction(Base):
    def test_every_item_carried(self):
        run = self.R.to_content_extracted()
        self.R.independent_audit()
        self.R.phase1(refs=[])
        self.refused(self.R.tr("F9001", "RECONSTRUCTED", "--run", run), "contributions.jsonl is empty")

    def test_F03_contribution_anchor_must_match_an_item(self):
        run = self.R.to_content_extracted()
        self.R.independent_audit()
        self.R.phase1()
        c = self.R.C.read_jsonl(self.R.L("F9001", "contributions.jsonl"))
        c[0]["anchor"] = "object X3 is defined as a map from A3 to B3"
        self.R.wj("F9001", "contributions.jsonl", c)
        self.refused(self.R.tr("F9001", "RECONSTRUCTED", "--run", run), "anchor must coincide")


# ============================== Level 2 (C04–C08; F-09) ============================================================
class Level2(Base):
    def _run(self, rec=None, lens=None):
        run = self.R.to_reconstructed()
        self.R.analyze(rec=rec, lens=lens)
        return self.R.tr("F9001", "ANALYZED", "--run", run)

    def test_F09_prescriptive_l2_refused(self):
        self.refused(self._run({"statement": "KnowledgeOS should implement X as the aggregate root of its Kernel"}),
                     "prescriptive")

    def test_F09_quoted_source_prescription_allowed(self):
        r = self._run({"statement": "the source writes \"the kernel should be computed\" as its integration rule"})
        self.assertEqual(r.returncode, 0, r.stderr)

    def test_F09_correctness_finding_needs_reasoning_and_basis(self):
        r = self._run({"kind": "CORRECTNESS-FINDING", "reasoning": ""})
        self.refused(r, "reasoning")
        self.assertIn("analytical_status", r.stderr)

    def test_F09_status_basis_mismatch(self):
        self.refused(self._run({"kind": "CORRECTNESS-FINDING", "analytical_status": "EXTERNALLY-CONTRADICTED",
                                "basis": "INTERNAL"}), "matching basis")

    def test_F09_external_needs_source(self):
        self.refused(self._run({"kind": "CORRECTNESS-FINDING", "analytical_status": "EXTERNALLY-CONTRADICTED",
                                "basis": "EXTERNAL"}), "external_source")

    def test_F09_unresolved_is_valid(self):
        r = self._run({"kind": "CORRECTNESS-FINDING", "analytical_status": "UNRESOLVED", "basis": "UNRESOLVED"})
        self.assertEqual(r.returncode, 0, r.stderr)

    def test_F09_domain_interpretation_needs_frame(self):
        self.refused(self._run({"epistemic_class": "DOMAIN-INTERPRETATION"}), "interpretation_frame")

    def test_C08_ddd_structure_is_source_described(self):
        r = self._run({"lens": "DDD"}, lens={"DDD": {"status": "APPLIED", "reason": "fixture DDD"},
                                            "MATHEMATICAL": {"status": "NOT-APPLICABLE", "reason": "moved to DDD"}})
        self.refused(r, "SOURCE-DESCRIBED")

    def test_lens_applied_without_record(self):
        self.refused(self._run(lens={"DDD": {"status": "APPLIED", "reason": "claimed but empty"}}), "APPLIED but no")

    def test_only_statistical_ml_deferred(self):
        self.refused(self._run(lens={"LOGICAL": {"status": "DEFERRED-TO-CHECKPOINT", "reason": "later, at a checkpoint"}}),
                     "only STATISTICAL-ML")


# ============================== Level 3 (C10; F-10, F-11, F-12, F-15, F-19) ========================================
HYP = {"kind": "HYPOTHESIS", "epistemic_class": "HYPOTHESIS", "falsification_condition": "a map fails to compose",
       "validation_question": "do all maps compose?", "competing_hypotheses": ["maps are unrelated"],
       "contradicting_evidence": [],
       "disconfirmation_plan": {"population": "AUDITED text F-IDs", "method": "LEXICAL", "completeness": "EXHAUSTIVE",
                                "termination_bound": "the manifest", "where": "inventory FORMULA items"},
       "preregistration": {"population_rule": "all AUDITED text F-IDs at CP-01", "temporal_scope": "processing order",
                           "selection_rule": "all", "comparison_rule": "competing hypothesis", "stopping_rule": "CP-01",
                           "prediction": "maps compose", "registered_after_f": "F9001",
                           "holdout_basis": "PROCESSING-ORDER", "confirmatory_checkpoint": "CP-01",
                           "family": "F9001-maps", "multiplicity_rule": "SINGLE-PRIMARY",
                           "outcome_vocabulary": ["SUPPORTED", "UNSUPPORTED", "UNDETERMINED"]}}


class Level3(Base):
    def _run(self, rec=None, evidence=None, decided=False):
        if decided:
            json.dump({"temporal_semantics": {"allowed_bases": ["PROCESSING-ORDER"]},
                       "multiplicity_rules": {"allowed": ["SINGLE-PRIMARY"]}},
                      open(os.path.join(self.R.fdir, "F-DECISIONS.json"), "w"))
        run = self.R.to_analyzed()
        self.R.research(rec=rec, evidence=evidence)
        return self.R.tr("F9001", "RESEARCHED", "--run", run)

    def test_level2_kind_refused(self):
        self.refused(self._run({"kind": "OBSERVATION"}), "is Level 2")

    def test_F10_no_refs_refused(self):
        self.refused(self._run({"analysis_refs": []}), "needs ≥ 1 analysis_refs")

    def test_F10_theory_candidate_and_corpus_scale_refused(self):
        r = self._run({"epistemic_class": "THEORY-CANDIDATE", "scale": "CORPUS"})
        self.refused(r, "THEORY-CANDIDATE exists only at checkpoints")
        self.assertIn("CORPUS scale exists only at checkpoints", r.stderr)

    def test_F10_outcome_via_other_key_refused(self):
        self.refused(self._run({"result": "SUPPORTED"}), "no test outcome")

    def test_F10_nested_outcome_refused(self):
        self.refused(self._run({"notes": {"test_outcome": "UNSUPPORTED"}}), "no test outcome")

    def test_F10_same_file_quote_must_be_in_inventory(self):
        self.refused(self._run(evidence=[{"f_id": "F9001", "quote": "object X3 is defined as a map from A3 to B3"}]),
                     "same-file quote")

    def test_F12_F15_hypothesis_blocked_until_human_decision(self):
        self.refused(self._run(dict(HYP)), "HUMAN DECISION REQUIRED")

    def test_F12_out_of_sample_language_refused_while_undecided(self):
        self.refused(self._run({"statement": "test this out-of-sample on later files"}), "HDR-2")

    def test_F11_disconfirmation_plan_with_result_refused(self):
        h = json.loads(json.dumps(HYP))
        h["disconfirmation_plan"]["result"] = "none found"
        self.refused(self._run(h, decided=True), "planned, never performed")

    def test_F11_valid_preregistered_hypothesis_accepted_once_decided(self):
        r = self._run(json.loads(json.dumps(HYP)), decided=True)
        self.assertEqual(r.returncode, 0, r.stderr)

    def test_F19_contract_sha_and_research_time(self):
        r = self._run({"contract_sha256": "0" * 64, "research_time": "yesterday"})
        self.refused(r, "contract_sha256")
        self.assertIn("ISO-8601", r.stderr)

    def test_s_evidence_refused(self):
        self.refused(self._run(evidence=[{"f_id": "S0472", "quote": "x"}]), "S contamination")


# ============================== exceptions (F-08) ==================================================================
class Exceptions(Base):
    def test_F08_placeholder_needs_valid_human_ref(self):
        run = self.R.to_read_complete()
        for ref in (None, "F-LOG-0902", "F-LOG-0999"):
            args = ["F9001", "PLACEHOLDER", "--run", run, "--reason", "stub"] + (["--human-ref", ref] if ref else [])
            self.refused(self.R.tr(*args), "--human-ref")
        self.assertEqual(self.R.tr("F9001", "PLACEHOLDER", "--run", run, "--reason", "stub", "--human-ref",
                                   "F-LOG-0901").returncode, 0)
        self.assertEqual(self.R.tr("F9001", "AUDITED", "--run", run).returncode, 0)

    def test_F08_unresolved_exception_needs_human_ref(self):
        run = self.R.to_read_complete()
        self.refused(self.R.tr("F9001", "CONTENT-EXTRACTION-UNRESOLVED", "--run", run, "--reason", "too hard"),
                     "--human-ref")


# ============================== cross-file (C05; F-14) and duplicates (RL-03) ======================================
class CrossFile(Base):
    def test_candidates_carry_s_flags_and_hashed_ids(self):
        self.R.to_audited("F9001")
        run = self.R.to_reconstructed("F9002")
        cands = self.R.analyze("F9002")
        self.assertTrue(cands)
        self.assertTrue(all(c["this_identical_to_s"] and not c["other_identical_to_s"] for c in cands))
        self.assertTrue(all(len(c["candidate_id"].split("-")[-1]) == 12 for c in cands))
        self.assertEqual(self.R.tr("F9002", "ANALYZED", "--run", run).returncode, 0)

    def test_undispositioned_candidate(self):
        self.R.to_audited("F9001")
        run = self.R.to_reconstructed("F9002")
        self.R.analyze("F9002", dispose=False)
        self.refused(self.R.tr("F9002", "ANALYZED", "--run", run), "not dispositioned")

    def test_exact_duplicate_pointer(self):
        self.R.to_audited("F9001")
        self.R.to_audited("F9002")
        self.assertEqual(self.R.tr("F9003", "EXACT-DUPLICATE", "--run", "FR-F9003-001", "--reason", "copy").returncode, 0)
        self.assertEqual(self.R.tr("F9003", "AUDITED", "--run", "FR-F9003-001").returncode, 0)
        self.assertEqual(self.R.C.events("F9003")[-2]["evidence"]["duplicate_of"], "F9002")


# ============================== checkpoints (F-15, F-16, RN-08, RN-09) =============================================
class Checkpoint(Base):
    n_checkpoint = "1"

    def test_F16_reading_refused_when_checkpoint_due(self):
        self.R.to_audited("F9001")
        self.R.read("F9002")
        self.refused(self.R.tr("F9002", "READING", "--run", "FR-F9002-001"), "checkpoint CP-01 is due")
        r = self.R.run("f_checkpoint.py", "open")
        self.assertEqual(r.returncode, 0, r.stderr)
        self.assertIn("MONITORING-ONLY", r.stdout)
        self.assertEqual(self.R.tr("F9002", "READING", "--run", "FR-F9002-001").returncode, 0)
        pops = json.load(open(os.path.join(self.R.fdir, "checkpoints/CP-01/POPULATIONS.json")))
        self.assertEqual(pops["unique-content"], ["F9001"])
        self.assertIn("SIGNAL", pops["near-duplicate-signal"]["status"])

    def test_F15_checkpoint_run_blocked_until_decided(self):
        self.refused(self.R.run("f_checkpoint.py", "run", "CP-01"), "HUMAN DECISION REQUIRED")


if __name__ == "__main__":
    unittest.main()
