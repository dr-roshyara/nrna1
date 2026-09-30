#!/usr/bin/env python3
"""EG-3 (R7 v2.8 part (f), addendum §5.9a, G-LOG-0106): required view lines R(run) for hub runs, the hub dispatch
prompt's REQUIRED LINES block, and the RI-1b coverage report (report-only). Design: audit-p3b/eg3/EG3-SPEC-A.md §4,
§5.1, §5.2 (E1–E13) and EG3-SPEC-A-v2.md D1 (rule (iii): NO leaf under search_records[0].ledger_hits / raw_hits).

SYNTHETIC ONLY: the hub slices are built as the EG-3 probe builds them (fake S-ids S7xxx/S8xxx/S9xxx, fake label
names) and rendered with the production U.slice_view; the hold-out sets are the synthetic testlib sets. No production
slice, view, ledger, corpus, hold-out or archive is read.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_r7_hub_required
"""
import hashlib
import json
import os
import random
import re
import sys
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
sys.path.insert(0, os.path.dirname(HERE))
import p3b_s5_ops_testlib as testlib                 # noqa: E402
import p3b_s5_r7_orchestrate as O                    # noqa: E402

U = O.U
OB = "OB9999"
READER_ABS = "/repo/docs/knowledgeos/chronological-read/scripts/p3b_read_source.py"
COMMIT = "c0ffee0000000000000000000000000000000000"
SLICE_ROOT = "slices-synthetic"


def canon(x):
    return json.dumps(x, sort_keys=True, ensure_ascii=False, separators=(",", ":"))


def synth(n_hit_files, hits_per_file, n_dims, n_rows, n_dates=3, seed=1, hub=True, lab="synthetic-hub-label",
          extra=None):
    """The EG-3 probe's synthetic hub slice (probes/eg3_probe_required_coverage.py `synth`, same seed and shape)."""
    rnd = random.Random(seed)
    own = [f"S9{i:03d}" for i in range(n_rows)]
    hitf = [f"S8{i:03d}" for i in range(n_hit_files)]
    terms = [f"term{i}" for i in range(6)]
    raw = {s: sorted([[rnd.randrange(10 ** 6), rnd.randrange(6)] for _ in range(hits_per_file)]) for s in hitf}
    led = {s: [[f"a{k}", 0] for k in range(2)] for s in own[:2]}
    lh = {"record": "LABEL-HITS", "label": lab, "terms": terms, "ledger_hits": led, "raw_hits": raw, "population_basis": "X"}
    dims = [{"record": "DIMENSION", "label": lab, "dimension": f"dim{d}", "hits_ref": lab, "scope": "DISCOVERY-POPULATION",
             "files_searched": 5000, "firewall_skipped": [f"S70{k:02d}" for k in range(12)], "negative_label": None,
             "population_basis": "X", "identity_summary": {"stasis_unobservable": 1, "p0_row_exception": 0}}
            for d in range(n_dims)]
    rows = [canon({"source_id": s, "anchor": "x", "text": "r" * 200}) for s in own]
    hub_record = {"working_label": lab, "tier": "U", "in_checklist": False, "predicted_dispositions": 9999,
                  "distinct_hit_keys": 99, "absence_dimensions": n_dims, "predicted_stage2_bytes": 1,
                  "predicted_stage2_file_reads": n_hit_files, "rule": "T4", "threshold": 4081, "reason": "LOAD",
                  "treatment": "t", "evidence_unavailable": "e"}
    sl = {"working_label": lab, "batch_id": OB, "run_id": f"{OB}-R2", "contract_sha256": "0" * 64,
          "input_manifest_sha256": "0" * 64, "tier": "U", "in_checklist": False, "population_basis": "X",
          "bundle": {"rows_verbatim": rows, "sources_verbatim": [], "reconciliation_object": None},
          "family_md": "f" * 3000, "family_md_status": "OK", "family_md_sha256": "0" * 64,
          "source_tracks": {s: {"track_tag": "TRACK-A", "d23_track": "A"} for s in own},
          "search_records": [lh] + dims, "stage2_files": sorted(set(raw) | set(led)),
          "p3a_pairs": [], "pairs_touching_missing": [], "semantic_status_mechanical": None,
          "hub": hub, "hub_record": hub_record if hub else None, "quarantine": {"p3a_pairs_withheld": 0}}
    if extra:
        extra(sl)
    # the probe's set (own ∪ hit files ∪ firewall; its 5-digit S8xxxx ids included) ∪ any S-id an `extra` placed
    sids = sorted(set(own) | set(hitf) | {f"S70{k:02d}" for k in range(12)} | set(re.findall(r"\bS\d{4}\b", canon(sl))))
    sl["source_meta"] = {s: {"status": "CONTENT", "provenance": "PRIMARY", "best_historical_date": "2026-01-01",
                             "best_historical_date_basis": "EXPLICIT",
                             "explicit_dates": ["2026-01-0%d" % (k + 1) for k in range(n_dates)],
                             "mtime_block": None, "file_mtime": "2026-01-01T00:00:00Z"} for s in sids}
    return canon(sl)


SMALL = dict(n_hit_files=40, hits_per_file=30, n_dims=4, n_rows=3)
MEDIAN = dict(n_hit_files=150, hits_per_file=16, n_dims=5, n_rows=4)
LARGE = dict(n_hit_files=1500, hits_per_file=40, n_dims=6, n_rows=40)


def view_of(**kw):
    s = synth(**kw)
    v = U.slice_view(s)
    assert U.slice_view_violations(s, v, "synthetic") == []
    return s, v


def paths(view):
    """{1-based view line: path} for every leaf line (line 1 is the header)."""
    return {i + 2: json.loads(l.split("\t", 1)[0]) for i, l in enumerate(view.split("\n")[1:-1])}


def citable(slice_text):
    d = json.loads(slice_text)
    for k in ("search_records", "stage2_files", "source_meta"):
        d.pop(k, None)
    return set(re.findall(r"\bS\d{4}\b", canon(d)))


def expected(view, T):
    """Independent expectation of R(run) (§5.9a (i)–(iv)), written from the rule text, not from the implementation."""
    exp = {1}
    for n, p in paths(view).items():
        if p[0] not in ("search_records", "source_meta"):
            exp.add(n)
        elif p[0] == "search_records" and len(p) >= 2 and isinstance(p[1], int) and p[1] >= 1:
            exp.add(n)
        elif p[0] == "search_records" and len(p) >= 2 and p[1] == 0 and not (len(p) >= 3 and p[2] in ("ledger_hits", "raw_hits")):
            exp.add(n)
        elif p[0] == "source_meta" and len(p) >= 2 and p[1] in T:
            exp.add(n)
    return exp


def reads(ranges, page=25):
    return sum(-(-(b - a + 1) // page) for a, b in ranges)


def pages(ranges, page=25):
    """Harness Reads with offset/limit ≤ page over the ranges → displayed [min, max] per Read."""
    out = []
    for a, b in ranges:
        x = a
        while x <= b:
            y = min(b, x + page - 1)
            out.append([x, y])
            x = y + 1
    return out


def events(run, path, ranges, ok=True):
    return [{"kind": "read-input", "run": run, "path": path, "manifest_entry": "SLICE-VIEW", "displayed_range": r,
             "content_match": ok} for r in pages(ranges)]


def nlines(view):
    return len(view.split("\n")) - 1


VIEW_PATH = f"{SLICE_ROOT}/{OB}/synthetic-hub-label.view.txt"
RUN = f"{OB}-R7-L01"


class RequiredLines(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.s, cls.v = view_of(**SMALL)
        cls.R = O.hub_required_lines(cls.v)

    def test_E1_deterministic_and_from_the_view_alone(self):
        self.assertEqual(O.hub_required_lines(self.v), self.R)
        self.assertEqual(O.hub_required_lines(str(self.v)), O.hub_required_lines(U.slice_view(self.s)))
        self.assertEqual(self.R, sorted(self.R))
        self.assertEqual(len(set(self.R)), len(self.R))
        self.assertEqual(self.R[0], 1)                            # the header line

    def test_E2_exact_set_equality_with_the_independent_expectation(self):
        for kw in (SMALL, MEDIAN, LARGE):
            s, v = view_of(**kw)
            T = citable(s)
            self.assertEqual(set(O.hub_required_lines(v)), expected(v, T), kw)
            ps = paths(v)
            R = set(O.hub_required_lines(v))
            for key in ("label", "population_basis", "record", "terms"):
                self.assertTrue(any(ps[n][:3] == ["search_records", 0, key] for n in R if n > 1), key)
            self.assertTrue(any(ps[n][0] == "search_records" and ps[n][1] >= 1 for n in R if n > 1))

    def test_rule_iii_no_hit_leaf_is_required(self):
        """EG3-SPEC-A-v2 D1: every leaf under search_records[0].ledger_hits / raw_hits is excluded (incl. an E line)."""
        def empty_ledger(sl):
            sl["search_records"][0]["ledger_hits"] = {}
        for extra in (None, empty_ledger):
            s = synth(**SMALL, extra=extra)
            v = U.slice_view(s)
            ps = paths(v)
            hit = {n for n, p in ps.items() if p[:2] == ["search_records", 0] and len(p) >= 3
                   and p[2] in ("ledger_hits", "raw_hits")}
            self.assertTrue(hit)
            self.assertEqual(hit & set(O.hub_required_lines(v)), set())
            if extra:
                self.assertTrue(any(ps[n] == ["search_records", 0, "ledger_hits"] for n in hit))

    def test_rule_iii_the_superseded_first_leaf_variant_differs(self):
        ps = paths(self.v)
        first = min(n for n, p in ps.items() if p[:3] == ["search_records", 0, "raw_hits"])
        self.assertNotIn(first, self.R)
        self.assertNotEqual(set(self.R) | {first}, expected(self.v, citable(self.s)))

    def test_E2_all_chunks_of_a_chunked_string_are_required(self):
        def long_terms(sl):
            sl["search_records"][0]["terms"] = ["t" * 2500]
        s = synth(**SMALL, extra=long_terms)
        v = U.slice_view(s)
        ps = paths(v)
        chunks = [n for n, p in ps.items() if p == ["search_records", 0, "terms", 0]]
        self.assertEqual(len(chunks), 3)
        self.assertTrue(set(chunks) <= set(O.hub_required_lines(v)))

    def test_E3_citable_sids(self):
        def place(sl):
            sl["family_md"] += " see S7501"
            sl["p3a_pairs"] = [{"a": "S7502", "relationship": "X", "basis": "Y"}]
            sl["stage2_files"] = sl["stage2_files"] + ["S7503"]
        s = synth(**SMALL, extra=place)
        v = U.slice_view(s)
        T = set(O.hub_citable_sids(v))
        self.assertEqual(T, citable(s))
        self.assertIn("S7501", T)                                 # family_md only
        self.assertIn("S7502", T)                                 # p3a_pairs only
        self.assertIn("S9000", T)                                 # bundle rows
        self.assertNotIn("S7503", T)                              # stage2_files only
        self.assertNotIn("S8000", T)                              # raw_hits (and stage2_files) only
        self.assertNotIn("S7000", T)                              # DIMENSION firewall_skipped only
        ps, R = paths(v), set(O.hub_required_lines(v))
        for sid, want in (("S7501", True), ("S7502", True), ("S7503", False), ("S8000", False), ("S7000", False)):
            lines = {n for n, p in ps.items() if p[:2] == ["source_meta", sid]}
            self.assertTrue(lines, sid)
            self.assertEqual(lines <= R, want, sid)
            self.assertEqual(bool(lines & R), want, sid)

    def test_T_L_is_deterministic(self):
        self.assertEqual(O.hub_citable_sids(self.v), O.hub_citable_sids(self.v))
        self.assertEqual(O.hub_citable_sids(self.v), sorted(citable(self.s)))
        self.assertEqual(O.hub_citable_sids(self.v), ["S9000", "S9001", "S9002"])

    def test_E4_non_hub_view_requires_every_line(self):
        s = synth(**SMALL, hub=False)
        v = U.slice_view(s)
        self.assertEqual(O.required_lines(v), list(range(1, nlines(v) + 1)))
        self.assertEqual(O.required_lines(self.v), self.R)

    def _edge(self, mutate):
        s = synth(**SMALL, extra=mutate)
        v = U.slice_view(s)
        return paths(v), set(O.hub_required_lines(v))

    def test_edge_empty_search_records_is_excluded(self):
        def f(sl):
            sl["search_records"] = []
        ps, R = self._edge(f)
        n = [n for n, p in ps.items() if p == ["search_records"]]
        self.assertEqual(len(n), 1)
        self.assertNotIn(n[0], R)

    def test_edge_empty_record_0_is_included(self):
        def f(sl):
            sl["search_records"][0] = {}
        ps, R = self._edge(f)
        n = [n for n, p in ps.items() if p == ["search_records", 0]]
        self.assertEqual(len(n), 1)
        self.assertIn(n[0], R)

    def test_edge_empty_source_meta_is_excluded(self):
        s = json.loads(synth(**SMALL))
        s["source_meta"] = {}
        v = U.slice_view(canon(s))
        ps, R = paths(v), set(O.hub_required_lines(v))
        n = [n for n, p in ps.items() if p == ["source_meta"]]
        self.assertEqual(len(n), 1)
        self.assertNotIn(n[0], R)

    def test_line_ranges(self):
        self.assertEqual(O.line_ranges([]), [])
        self.assertEqual(O.line_ranges([1]), [(1, 1)])
        self.assertEqual(O.line_ranges([8, 1, 2, 3, 5, 7, 2]), [(1, 3), (5, 5), (7, 8)])

    def test_probe_counts_209_353_2157_lines_and_11_16_88_reads(self):
        for kw, want_lines, want_reads, want_view in ((SMALL, 209, 11, 3085), (MEDIAN, 353, 16, 6619),
                                                      (LARGE, 2157, 88, 135773)):
            _, v = view_of(**kw)
            R = O.hub_required_lines(v)
            rr = O.line_ranges(R)
            self.assertEqual(nlines(v), want_view)
            self.assertEqual(len(R), want_lines)
            self.assertEqual(len(rr), 4)
            self.assertEqual(reads(rr), want_reads)


def plan_of(hub_lab="synthetic-hub-label", other="synthetic-plain-label", empty="synthetic-empty-label"):
    return {"batch_id": OB, "labels": {
        hub_lab: {"index": 1, "path": "DECOMPOSED", "runs": {
            f"{OB}-R7-L01U01": {"role": "UNIT", "files": ["S9000"]},
            f"{OB}-R7-L01U02": {"role": "UNIT", "files": ["S9001"]},
            f"{OB}-R7-L01S": {"role": "SYNTHESIS", "files": []}}},
        other: {"index": 2, "path": "SINGLE", "runs": {f"{OB}-R7-L02": {"role": "SINGLE", "files": ["S9000"]}}},
        empty: {"index": 3, "path": "EMPTY", "runs": {}}}}


def manifest_of(lab):
    return {"entries": [{"category": "SLICE", "path": f"{SLICE_ROOT}/{OB}/{lab}.json"},
                        {"category": "SLICE-VIEW", "path": f"{SLICE_ROOT}/{OB}/{lab}.view.txt"}]}


# Updated 2026-09-30 (canary pre-dispatch finding): the only change is "(the R7 addendum v2.7)" → "(the R7 addendum
# listed below as ADDENDUM)"; reversing that phrase reproduces the previous golden
# c1c566f9b2b9e1e5b8f81548c4326ef91fdacf217f2fb067b6d005bccdc87d7a exactly (verified).
# Updated 2026-09-30 (S5 canary attempt 1, F-3): the only changes are the suffix "  (line count at dispatch)" on each
# YOUR INPUTS line (2 here: the synthetic manifest's files do not exist under "/root") and the one added line "Stop paging
# at the stated line count; a Read past it displays no content." after the paging lines; removing exactly those
# reproduces the previous golden e75cf840e8496b1b49a27cbf6fdecb27dd96c65dd925caa638408daa813cc0eb (verified, with and
# without a non-hub slice).
# Updated 2026-09-30 (R-C1 review 3): the only change is the STOP line, which gains "; if a Read reports that the file is
# shorter than the offset, stop paging that file." (its final "." replaced); restoring the previous STOP line reproduces
# d8a8ddb4995e9ca2e394eba4ccf23819348af71cb727e9e962c6fed0e93c1730, and removing the STOP line and the 2 suffixes
# reproduces e75cf840e8496b1b49a27cbf6fdecb27dd96c65dd925caa638408daa813cc0eb (both verified, with and without a slice).
HEAD_UNIT_PROMPT_SHA256 = "c83bbac1b88a01e71008a8dc9d66f776605ea0658d9678db58ddddc8cfcf04c7"
OLD_PAGING = ["Page every input with offset/limit until the whole file has been displayed: at most 25 lines per Read for the",
              "SLICE-VIEW (read the view, not the raw SLICE), at most 300 lines per Read for every other input."]


class Prompt(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.plan = plan_of()
        cls.hub_s, cls.hub_v = view_of(**SMALL)
        cls.plain_s = synth(**SMALL, hub=False, lab="synthetic-plain-label")

    def render(self, run, slice_text=None):
        """slice_text None → today's call signature (no slice passed); else the slice is passed."""
        lab = U.run_owner(self.plan)[run][0]
        with testlib.fake_holdout():
            if slice_text is None:
                return O.render_prompt(run, OB, self.plan, manifest_of(lab), READER_ABS, COMMIT, "/root")
            return O.render_prompt(run, OB, self.plan, manifest_of(lab), READER_ABS, COMMIT, "/root",
                                   slice_text=slice_text)

    def test_E8_non_hub_prompt_is_byte_identical(self):
        run = f"{OB}-R7-L02"
        base = self.render(run)
        self.assertEqual(self.render(run, self.plain_s), base)
        lines = base.split("\n")
        i = lines.index(OLD_PAGING[0])
        self.assertEqual(lines[i + 1], OLD_PAGING[1])
        self.assertNotIn("REQUIRED LINES", base)

    def test_E8_non_hub_unit_prompt_equals_the_HEAD_golden(self):
        """Golden: sha256 of the UNIT prompt rendered by HEAD's render_prompt (captured from `git show HEAD:…` into a
        scratch module, same synthetic plan / manifest / READER_ABS / COMMIT, fake hold-out). A UNIT prompt carries no
        RECORD IDS block (S1), so the pre-EG-3 text must be reproduced exactly, with and without a non-hub slice."""
        run = f"{OB}-R7-L01U01"
        for p in (self.render(run), self.render(run, self.plain_s)):
            self.assertEqual(hashlib.sha256(p.encode("utf-8")).hexdigest(), HEAD_UNIT_PROMPT_SHA256)

    def test_E8_hub_prompt_states_the_merged_ranges_and_changes_only_the_paging_lines(self):
        run = f"{OB}-R7-L01U01"
        base, hub = self.render(run).split("\n"), self.render(run, self.hub_s).split("\n")
        rr = O.line_ranges(O.hub_required_lines(self.hub_v))
        block = "SLICE-VIEW REQUIRED LINES (§5.9a): " + ", ".join(f"{a}–{b}" for a, b in rr) + \
                "; other view lines are optional."
        self.assertIn(block, hub)
        i = base.index(OLD_PAGING[0])
        self.assertEqual(base[i + 1], OLD_PAGING[1])
        j = hub.index(block)
        self.assertEqual(hub[:i], base[:i])                       # everything before the paging lines unchanged
        self.assertEqual(hub[j + 1:], base[i + 2:])               # everything after unchanged
        new = hub[i:j + 1]
        self.assertNotIn("until the whole file has been displayed: at most 25", "\n".join(new))
        self.assertIn("at most 25 lines per Read", "\n".join(new))   # the cap stays
        self.assertIn("at most 300 lines per Read", "\n".join(new))
        self.assertIn("until the whole file has been displayed", "\n".join(new))  # still for the other inputs
        self.assertNotIn("term0", "\n".join(hub))                 # no view payload in the prompt
        self.assertNotIn("S8000", "\n".join(hub))

    def test_E9_every_run_of_a_hub_label_gets_the_same_ranges(self):
        blocks = set()
        for run in (f"{OB}-R7-L01U01", f"{OB}-R7-L01U02", f"{OB}-R7-L01S"):
            blocks |= {l for l in self.render(run, self.hub_s).split("\n") if l.startswith("SLICE-VIEW REQUIRED LINES")}
        self.assertEqual(len(blocks), 1)

    def test_E10_hub_empty_label_has_no_run_and_no_prompt(self):
        owners = U.run_owner(self.plan)
        self.assertNotIn("synthetic-empty-label", {lab for lab, _, _ in owners.values()})


class Coverage(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.s, cls.v = view_of(**SMALL)
        cls.R = O.hub_required_lines(cls.v)
        cls.ps = paths(cls.v)

    def cov(self, recs):
        return O.run_view_coverage(self.v, VIEW_PATH, RUN, recs)

    def test_E5_full_display_is_covered(self):
        r = self.cov(events(RUN, VIEW_PATH, [(1, nlines(self.v))]))
        self.assertEqual(r["status"], "COVERED")
        self.assertEqual(r["uncovered_lines"], 0)
        self.assertEqual(r["uncovered_paths"], [])
        self.assertTrue(r["hub"])

    def test_E5_exactly_R_displayed_is_covered(self):
        r = self.cov(events(RUN, VIEW_PATH, O.line_ranges(self.R)))
        self.assertEqual(r["status"], "COVERED")
        self.assertEqual(r["required_lines"], len(self.R))

    def _omit(self, pred):
        keep = [n for n in self.R if n == 1 or not pred(self.ps[n])]
        miss = {json.dumps(self.ps[n], ensure_ascii=False, separators=(",", ":")) for n in self.R if n > 1 and pred(self.ps[n])}
        return self.cov(events(RUN, VIEW_PATH, O.line_ranges(keep))), miss

    def test_E6_omitted_dimension_records_are_named(self):
        r, miss = self._omit(lambda p: p[0] == "search_records" and p[1] >= 1)
        self.assertEqual(r["status"], "NOT-COVERED")
        self.assertEqual(set(r["uncovered_paths"]), miss)
        self.assertTrue(all(json.loads(p)[:2] != ["search_records", 0] for p in r["uncovered_paths"]))

    def test_E6_omitted_terms_and_one_source_meta_entry_are_named(self):
        for pred in (lambda p: p[:3] == ["search_records", 0, "terms"], lambda p: p[:2] == ["source_meta", "S9001"]):
            r, miss = self._omit(pred)
            self.assertEqual(r["status"], "NOT-COVERED")
            self.assertEqual(set(r["uncovered_paths"]), miss)
            self.assertGreaterEqual(r["uncovered_lines"], len(miss))
            self.assertTrue(miss)

    def test_E6_hits_only_is_not_covered(self):
        hits = [n for n, p in self.ps.items() if p[:3] == ["search_records", 0, "raw_hits"]]
        r = self.cov(events(RUN, VIEW_PATH, O.line_ranges(hits)))
        self.assertEqual(r["status"], "NOT-COVERED")
        self.assertEqual(r["uncovered_lines"], len(self.R))
        self.assertIn("<header>", r["uncovered_paths"])

    def test_E7_content_mismatch_raw_slice_other_run_and_no_range_contribute_nothing(self):
        full = [(1, nlines(self.v))]
        recs = (events(RUN, VIEW_PATH, full, ok=False) + events(RUN, f"{SLICE_ROOT}/{OB}/synthetic-hub-label.json", full)
                + events(f"{OB}-R7-L02", VIEW_PATH, full)
                + [{"kind": "read-input", "run": RUN, "path": VIEW_PATH, "displayed_range": None, "content_match": True},
                   {"kind": "read", "run": RUN, "path": VIEW_PATH, "displayed_range": [1, nlines(self.v)],
                    "content_match": True}])
        r = self.cov(recs)
        self.assertEqual(r["status"], "NOT-COVERED")
        self.assertEqual(r["uncovered_lines"], len(self.R))
        self.assertEqual(r["reads_counted"], 0)

    def test_witness_shaped_read_input_records(self):
        """Records shaped exactly as W.witness `_classify` emits a read-input event (same keys), with displayed_range /
        content_match computed by the production W._content_match over a harness-style `<n>\\t<line>` display."""
        W = O.W
        vb = self.v.encode("utf-8")
        lines = self.v.split("\n")
        recs = []
        for a, b in pages(O.line_ranges(self.R)):
            shown = "\n".join(f"{n:>6}\t{lines[n - 1]}" for n in range(a, b + 1))
            ok, rng = W._content_match(shown, vb)
            self.assertTrue(ok)
            recs.append({"kind": "read-input", "agent_id": "a0", "run": RUN, "path": VIEW_PATH,
                         "manifest_entry": "SLICE-VIEW", "sha256_frozen": U.sha(vb), "sha256_observed": U.sha(vb),
                         "displayed_range": rng, "content_match": ok, "t_call": None, "t_result": None})
        self.assertEqual(self.cov(recs)["status"], "COVERED")
        tampered = "\n".join(f"{n:>6}\t{lines[n - 1]}x" for n in range(1, 3))
        ok, rng = W._content_match(tampered, vb)
        self.assertFalse(ok)
        bad = dict(recs[0], displayed_range=rng, content_match=ok)
        r = self.cov([bad] + recs[1:])
        self.assertEqual(r["status"], "NOT-COVERED")

    def test_view_path_is_taken_from_the_manifest_entry(self):
        lab = "synthetic-hub-label"
        other = "elsewhere/OB9999/synthetic-hub-label.view.txt"
        ms = {RUN: {"entries": [{"category": "SLICE", "path": "x.json"}, {"category": "SLICE-VIEW", "path": other}]}}
        self.assertEqual(O.view_path_of(RUN, lab, OB, SLICE_ROOT, ms), other)
        self.assertEqual(O.view_path_of(RUN, lab, OB, SLICE_ROOT, None), VIEW_PATH)
        self.assertEqual(O.view_path_of(f"{OB}-R7-L01S", lab, OB, SLICE_ROOT, ms), f"{SLICE_ROOT}/{OB}/{lab}.view.txt")
        plan = {"batch_id": OB, "labels": {lab: {"index": 1, "path": "SINGLE",
                                                 "runs": {RUN: {"role": "SINGLE", "files": ["S9000"]}}}}}
        full = [(1, nlines(self.v))]
        self.assertEqual(O.coverage_report(OB, plan, {lab: self.s}, SLICE_ROOT, events(RUN, other, full), ms)[RUN]["status"],
                         "COVERED")
        self.assertEqual(O.coverage_report(OB, plan, {lab: self.s}, SLICE_ROOT, events(RUN, VIEW_PATH, full), ms)[RUN]
                         ["status"], "NOT-COVERED")

    def test_E4_non_hub_run_needs_the_whole_view(self):
        s = synth(**SMALL, hub=False)
        v = U.slice_view(s)
        R = O.hub_required_lines(v)
        r = O.run_view_coverage(v, VIEW_PATH, RUN, events(RUN, VIEW_PATH, O.line_ranges(R)))
        self.assertFalse(r["hub"])
        self.assertEqual(r["status"], "NOT-COVERED")
        r = O.run_view_coverage(v, VIEW_PATH, RUN, events(RUN, VIEW_PATH, [(1, nlines(v))]))
        self.assertEqual(r["status"], "COVERED")

    def test_E9_E10_batch_report_one_entry_per_planned_run(self):
        plan = plan_of()
        slices = {"synthetic-hub-label": self.s, "synthetic-plain-label": synth(**SMALL, hub=False,
                                                                                    lab="synthetic-plain-label"),
                  "synthetic-empty-label": synth(**SMALL, lab="synthetic-empty-label")}
        recs = events(f"{OB}-R7-L01U01", VIEW_PATH, O.line_ranges(self.R))
        rep = O.coverage_report(OB, plan, slices, SLICE_ROOT, recs)
        self.assertEqual(set(rep), set(U.run_owner(plan)))
        self.assertEqual(rep[f"{OB}-R7-L01U01"]["status"], "COVERED")
        self.assertEqual(rep[f"{OB}-R7-L01U02"]["status"], "NOT-COVERED")
        self.assertEqual(rep[f"{OB}-R7-L01U01"]["required_lines"], rep[f"{OB}-R7-L01S"]["required_lines"])

    def test_E11_report_only_writes_nothing_and_never_raises(self):
        plan = plan_of()
        slices = {"synthetic-hub-label": self.s, "synthetic-plain-label": synth(**SMALL, hub=False,
                                                                                    lab="synthetic-plain-label"),
                  "synthetic-empty-label": synth(**SMALL, lab="synthetic-empty-label")}
        root = tempfile.mkdtemp(prefix="eg3-cov-")
        ctx = {"plan": plan, "slices": slices, "slice_root": SLICE_ROOT, "mhdr": {}, "entry": {},
               "owners": U.run_owner(plan), "adir": os.path.join(root, "adir")}
        recs = events(f"{OB}-R7-L01U01", VIEW_PATH, [(1, 3)])
        oc, ow = O._context, O._witness
        O._context = lambda b, r: ctx
        O._witness = lambda b, r, cx, a, f: ({"records": recs, "failures": []}, b"", {})
        try:
            r = O.view_coverage(OB, root=root)
        finally:
            O._context, O._witness = oc, ow
        self.assertEqual([x for x in os.walk(root)], [(root, [], [])])
        self.assertEqual(r["runs"], len(U.run_owner(plan)))
        self.assertEqual(r["covered"] + r["not_covered"], r["runs"])
        self.assertEqual(r["not_covered"], r["runs"])
        out = "\n".join(O.coverage_lines(r))
        self.assertNotIn("synthetic-", out)                      # counts only: no label, no path, no S-id
        self.assertNotIn("search_records", out)
        self.assertIsNone(re.search(r"\bS\d{4}\b", out))


if __name__ == "__main__":
    unittest.main()
