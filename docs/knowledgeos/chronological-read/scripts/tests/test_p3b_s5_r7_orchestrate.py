#!/usr/bin/env python3
"""EG-1 (G-LOG-0104): the production R7 orchestrator tool `p3b_s5_r7_orchestrate` — prepare / archive / freeze-units /
freeze-final / verify. It reuses the production functions (U.input_manifest, U.slice_view, W.witness, W.witness_bytes,
W.digests, p3b_s5_verify). Validation: on the complete synthetic batch of r7_full_fixture, the tool's artifacts are
BYTE-IDENTICAL to the fixture's independently produced ones, and the production verifier returns BATCH-PASS on the
tool's artifacts. The tool dispatches nothing. Temp dirs only; synthetic hold-out sets.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_r7_orchestrate
"""
import json
import os
import re
import subprocess
import sys
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
sys.path.insert(0, os.path.dirname(HERE))
import r7_full_fixture as FX                         # noqa: E402
import r7_execution_fixture as xf                    # noqa: E402
import p3b_s5_ops_testlib as testlib                 # noqa: E402
import p3b_s5_r7_orchestrate as O                    # noqa: E402

U, OB, R7 = FX.U, FX.OB, FX.R7
ADR = f"{U.LEDGER}/{R7}"
FREEZES = ("INPUT-MANIFESTS.json", "WITNESS.jsonl", "WITNESS-DIGESTS.json", "WITNESS-UNIT.jsonl", "WITNESS-UNIT-DIGESTS.json")
STATE = FX.base()


def _rd(root, rel):
    p = os.path.join(root, rel)
    return open(p, "rb").read() if os.path.isfile(p) else None


def run_in_fixture(fn):
    """Run fn(root, arch_root, info) inside the fixture's materialized batch, after the fixture wrote its own freezes.
    Returns (verifier body, fn's result)."""
    out = {}

    def m(st):
        def post(root, adir, info):
            with testlib.fake_holdout():
                out["r"] = fn(root, os.path.dirname(adir), info)
        st["post"] = post
    body = FX.verify(STATE, m)
    return body, out.get("r")


def dispatch_cwd(root):
    """F-1/F-5: a synthetic git repository whose docs/knowledgeos/chronological-read is a symlink to the fixture root.
    An agent started there resolves (by the reader's own git top-level rule) exactly the fixture root."""
    repo = tempfile.mkdtemp(prefix="r7orch-repo-")
    subprocess.run(["git", "init", "-q", repo], check=True)
    os.makedirs(os.path.join(repo, "docs", "knowledgeos"))
    os.symlink(root, os.path.join(repo, "docs", "knowledgeos", "chronological-read"))
    return repo


def other_repo():
    """A git repository that is NOT the fixture's (canary attempt 1: the session's cwd was another repository)."""
    repo = tempfile.mkdtemp(prefix="r7orch-other-")
    subprocess.run(["git", "init", "-q", repo], check=True)
    return repo


def decomposed(plan):
    return sorted(l for l, L in plan["labels"].items() if L["path"] == "DECOMPOSED")


def replay(root, arch, emit):
    """The runbook in tool form: drop the fixture's freezes and views, then prepare → freeze-units → freeze-final."""
    ref = {n: _rd(root, f"{ADR}/{n}") for n in FREEZES}
    views = {l: _rd(root, f"{FX.prep.SLICE_ROOT}/{OB}/{l}.view.txt") for l in STATE["plan"]["labels"]}
    for n in FREEZES:
        os.remove(os.path.join(root, ADR, n))
    for l in views:
        os.remove(os.path.join(root, FX.prep.SLICE_ROOT, OB, f"{l}.view.txt"))
    prep = O.prepare(OB, root=root, emit=emit, cwd=dispatch_cwd(root))
    after_prepare = json.loads(_rd(root, f"{ADR}/INPUT-MANIFESTS.json"))
    for lab in decomposed(STATE["plan"]):
        O.freeze_units(OB, lab, root=root, archive_root=arch, resolver_factory=FX.Fake)
    O.freeze_final(OB, root=root, archive_root=arch, resolver_factory=FX.Fake)
    got = {n: _rd(root, f"{ADR}/{n}") for n in FREEZES}
    gviews = {l: _rd(root, f"{FX.prep.SLICE_ROOT}/{OB}/{l}.view.txt") for l in views}
    return {"ref": ref, "got": got, "views": views, "gviews": gviews, "prep": prep, "after_prepare": after_prepare}


class Replay(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.emit = tempfile.mkdtemp(prefix="r7orch-emit-")
        cls.body, cls.r = run_in_fixture(lambda root, arch, info: replay(root, arch, cls.emit))

    def test_tool_freezes_are_byte_identical_to_the_fixture(self):
        self.assertEqual(json.loads(self.r["got"]["INPUT-MANIFESTS.json"]), json.loads(self.r["ref"]["INPUT-MANIFESTS.json"]))
        for n in FREEZES[1:]:
            self.assertEqual(self.r["got"][n], self.r["ref"][n], n)

    def test_views_rewritten_identically(self):
        self.assertEqual(self.r["gviews"], self.r["views"])

    def test_production_verifier_passes_on_the_tool_artifacts(self):
        self.assertEqual(self.body["result"], "BATCH-PASS", self.body.get("failures"))

    def test_prepare_freezes_reading_runs_only(self):
        owners = U.run_owner(STATE["plan"])
        want = {r for r, (_, role, _) in owners.items() if role != "SYNTHESIS"}
        self.assertEqual(set(self.r["after_prepare"]), want)
        self.assertTrue(any(role == "SYNTHESIS" for _, role, _ in owners.values()))

    def test_one_prompt_per_planned_run(self):
        owners = U.run_owner(STATE["plan"])
        self.assertEqual(set(self.r["prep"]["prompts"]), set(owners))
        for run in owners:
            self.assertTrue(os.path.isfile(os.path.join(self.emit, OB, f"{run}.prompt.txt")))

    def test_prompt_binding_and_grammar(self):
        owners = U.run_owner(STATE["plan"])
        rx = U.canonical_reader(xf.READER_ABS)
        for run, (lab, role, files) in owners.items():
            p = self.r["prep"]["prompts"][run]
            self.assertEqual(p.split("\n", 1)[0], xf.binding(run, OB, lab))
            self.assertEqual(p.count("S5-RUN-BINDING"), 1)
            self.assertIn(f"{FX.prep.SLICE_ROOT}/{OB}/{lab}.view.txt", p)
            for w in U.permitted_writes(run, role):
                self.assertIn(w, p)
            cmds = [l.strip() for l in p.split("\n") if l.strip().startswith("python3 -B ")]
            if role == "SYNTHESIS":
                self.assertEqual(cmds, [])
                self.assertIn("file-reading-records.jsonl", p)
            else:
                self.assertEqual(len(cmds), 1)
                probe = cmds[0].replace("<1|7|10>", "7").replace("<K>", "1").replace("<S####>", sorted(files)[0])
                self.assertTrue(rx.match(probe), probe)
                for s in files:
                    self.assertIn(s, p)

    def test_prompt_names_no_stale_addendum_version(self):
        """Canary pre-dispatch finding (2026-09-30): the prompt said "the R7 addendum v2.7" while v2.8 was bound. The
        prompt must not hard-code an addendum version; it refers to the ADDENDUM input listed in the prompt."""
        import re
        for run, p in self.r["prep"]["prompts"].items():
            self.assertIsNone(re.search(r"addendum v2\.\d", p), run)
            self.assertIn("the R7 addendum listed below as ADDENDUM", p)


class Decisions(unittest.TestCase):
    def test_decided_files_are_withheld_from_the_reader_list(self):
        owners = U.run_owner(STATE["plan"])
        run, (lab, role, files) = next((r, o) for r, o in sorted(owners.items()) if o[1] == "SINGLE")
        s = sorted(files)[0]
        plan = json.loads(json.dumps(STATE["plan"]))
        plan["labels"][lab]["binary_decisions"] = {s: {"decision": "FALSE-HIT"}}
        m = {"entries": [{"category": "SLICE-VIEW", "path": f"x/{lab}.view.txt"}]}
        p = O.render_prompt(run, OB, plan, m, xf.READER_ABS, xf.COMMIT, "/root")
        self.assertIn("Do NOT call the reader on", p)
        self.assertIn(f"{s}: FALSE-HIT", p)
        listed = p.split("Your files are exactly:", 1)[1].split("\n", 1)[0]
        self.assertNotIn(s, listed)


class Refusals(unittest.TestCase):
    def _refused(self, fn):
        def wrap(root, arch, info):
            try:
                fn(root, arch, info)
            except O.Refused as e:
                return str(e)
            return None
        _, r = run_in_fixture(wrap)
        self.assertIsNotNone(r, "expected Refused")
        return r

    def test_emit_inside_the_repository_is_refused(self):
        self._refused(lambda root, arch, info: O.prepare(OB, root=root, emit=os.path.join(O.CR, "x")))

    def test_plan_hash_mismatch_is_refused(self):
        def fn(root, arch, info):
            p = os.path.join(root, FX.prep.SLICE_ROOT, f"{OB}.R7-PLAN.json")
            open(p, "ab").write(b" ")
            O.prepare(OB, root=root, emit=tempfile.mkdtemp(), cwd=dispatch_cwd(root))
        self.assertIn("frozen plan", self._refused(fn))

    def test_a_differing_view_is_refused_never_overwritten(self):
        lab = sorted(STATE["plan"]["labels"])[0]
        seen = {}

        def fn(root, arch, info):
            p = os.path.join(root, FX.prep.SLICE_ROOT, OB, f"{lab}.view.txt")
            open(p, "a", encoding="utf-8").write("x")
            seen["before"] = open(p, "rb").read()
            try:
                O.prepare(OB, root=root, emit=tempfile.mkdtemp(), cwd=dispatch_cwd(root))
            finally:
                seen["after"] = open(p, "rb").read()
        self.assertIn("SLICE-VIEW", self._refused(fn))
        self.assertEqual(seen["before"], seen["after"])

    def test_a_differing_frozen_manifest_is_refused(self):
        def fn(root, arch, info):
            p = os.path.join(root, ADR, "INPUT-MANIFESTS.json")
            m = json.load(open(p))
            m.pop(next(r for r in sorted(m) if m[r]["role"] != "SYNTHESIS"))     # a reading run's frozen I(run) lost
            open(p, "w").write(json.dumps(m, sort_keys=True))
            O.prepare(OB, root=root, emit=tempfile.mkdtemp(), cwd=dispatch_cwd(root))
        self.assertIn("INPUT-MANIFESTS", self._refused(fn))

    def test_state_not_prepared_is_refused(self):
        def fn(root, arch, info):
            sp = os.path.join(tempfile.mkdtemp(), "P3B-STATE.json")
            json.dump({"batches": {OB: {"state": "DISPATCHED", "run_id": R7}}}, open(sp, "w"))
            O.prepare(OB, root=root, emit=tempfile.mkdtemp(), state_path=sp, cwd=dispatch_cwd(root))
        self.assertIn("PREPARED", self._refused(fn))

    def test_freeze_units_refuses_a_non_decomposed_label(self):
        lab = next(l for l, L in STATE["plan"]["labels"].items() if L["path"] != "DECOMPOSED")
        self._refused(lambda root, arch, info: O.freeze_units(OB, lab, root=root, archive_root=arch,
                                                               resolver_factory=FX.Fake))

    def test_freeze_units_refuses_missing_unit_records(self):
        lab = decomposed(STATE["plan"])[0]
        unit = next(r for r, d in STATE["plan"]["labels"][lab]["runs"].items() if d["role"] == "UNIT")

        def fn(root, arch, info):
            os.remove(os.path.join(root, U.LEDGER, unit, "file-reading-records.jsonl"))
            O.freeze_units(OB, lab, root=root, archive_root=arch, resolver_factory=FX.Fake)
        self._refused(fn)

    def test_freeze_units_refuses_without_the_units_validated_marker(self):
        lab = decomposed(STATE["plan"])[0]

        def fn(root, arch, info):
            p = os.path.join(arch, OB, "main.jsonl")
            t = open(p, encoding="utf-8").read().replace("S5-ORCH UNITS-VALIDATED", "S5-ORCH UNITS-PENDING")
            open(p, "w", encoding="utf-8").write(t)
            O.freeze_units(OB, lab, root=root, archive_root=arch, resolver_factory=FX.Fake)
        self._refused(fn)

    def test_a_prompt_naming_hold_out_material_is_refused(self):
        with testlib.fake_holdout() as sets:
            s = sorted(sets[1])[0]
            with self.assertRaises(O.Refused):
                O.check_prompt(f"S5-RUN-BINDING run=x\nread {s}")


class DispatchRoot(unittest.TestCase):
    """Canary attempt 1, F-1/F-5: the reader resolves CR from the agent's working directory (git top level), which the
    agents inherit from the dispatching session. `prepare` must refuse unless the dispatch working directory resolves,
    through the READER's own resolution, to the prepare root, with the batch activated there."""
    RUN = f"{OB}-R7-L99"                                      # synthetic, unplanned: the reader always refuses it

    def _prepare(self, cwd_of, drop_frozen=True, mutate=None):
        """prepare from cwd_of(root) after removing the freezes/views prepare would write -> (refusal or None, written)."""
        def fn(root, arch, info):
            if drop_frozen:
                os.remove(os.path.join(root, ADR, "INPUT-MANIFESTS.json"))
                for lab in STATE["plan"]["labels"]:
                    os.remove(os.path.join(root, FX.prep.SLICE_ROOT, OB, f"{lab}.view.txt"))
            if mutate:
                mutate(root)
            emit = tempfile.mkdtemp(prefix="r7orch-emit-")
            try:
                O.prepare(OB, root=root, emit=emit, cwd=cwd_of(root))
                why = None
            except O.Refused as e:
                why = str(e)
            written = sorted(os.listdir(emit))
            written += [n for n in ("INPUT-MANIFESTS.json",) if os.path.isfile(os.path.join(root, ADR, n))]
            written += [l for l in STATE["plan"]["labels"]
                        if os.path.isfile(os.path.join(root, FX.prep.SLICE_ROOT, OB, f"{l}.view.txt"))]
            return why, written
        return run_in_fixture(fn)[1]

    def _clean(self, msg):
        self.assertIsNone(re.search(r"S\d{4}", msg))
        for lab in STATE["plan"]["labels"]:
            self.assertNotIn(lab, msg)

    def test_prepare_refused_from_another_git_repository_nothing_written(self):
        why, written = self._prepare(lambda root: other_repo())
        self.assertIsNotNone(why)
        self.assertIn("dispatch working directory", why)
        self.assertEqual(written, [])
        self._clean(why)

    def test_prepare_refused_from_a_directory_outside_any_repository(self):
        why, written = self._prepare(lambda root: tempfile.mkdtemp(prefix="r7orch-nogit-"))
        self.assertIsNotNone(why)
        self.assertIn("dispatch working directory", why)
        self.assertEqual(written, [])
        self._clean(why)

    def test_prepare_accepted_from_inside_the_repository(self):
        for sub in ((), ("docs",), ("docs", "knowledgeos")):
            why, written = self._prepare(lambda root: os.path.join(dispatch_cwd(root), *sub))
            self.assertIsNone(why, sub)
            self.assertIn(OB, written)
            self.assertIn("INPUT-MANIFESTS.json", written)

    def test_prepare_refused_when_the_batch_is_not_activated_at_the_resolved_root(self):
        def deactivate(root):
            p = os.path.join(root, "_batch_manifest_p3b_r2.jsonl")
            out = []
            for l in open(p, encoding="utf-8").read().split("\n"):
                if l.strip():
                    e = json.loads(l)
                    if e.get("batch_id") == OB:
                        e.pop("r7_plan_sha256")
                    l = json.dumps(e, sort_keys=True)
                out.append(l)
            open(p, "w", encoding="utf-8").write("\n".join(out))
        why, written = self._prepare(dispatch_cwd, mutate=deactivate)
        self.assertIsNotNone(why)
        self.assertIn("activated", why)
        self.assertEqual(written, [])
        self._clean(why)

    def test_the_check_uses_the_readers_own_resolution(self):
        """Run the REAL reader CLI from each cwd: its refusal log lands under the root reader_resolution reports, and
        it reports "no activated R7 plan" exactly when reader_resolution says the batch is not activated there. A
        divergence between the check and the reader fails this test."""
        def fn(root, arch, info):
            out = []
            for cwd in (dispatch_cwd(root), other_repo()):
                cr, activated, why = O.reader_resolution(OB, cwd)
                p = subprocess.run([sys.executable, "-B", O.READER_PATH, "--run", self.RUN, "--batch", OB, "--label",
                                    "synthetic-nonowner", "--step", "7", "--mode", "bytes", "--page", "1", "S9999"],
                                   cwd=cwd, capture_output=True, text=True)
                logp = os.path.join(cr, "ledger-p3b-r2", self.RUN, "READ-LOG.jsonl")
                recs = [json.loads(l) for l in open(logp, encoding="utf-8")] if os.path.isfile(logp) else []
                out.append({"same_as_root": os.path.realpath(cr) == os.path.realpath(root), "activated": activated,
                            "why": why, "rc": p.returncode, "recs": recs,
                            "no_plan": "no activated R7 plan" in p.stderr})
            return out
        mine, other = run_in_fixture(fn)[1]
        for r in (mine, other):
            self.assertEqual(r["rc"], 1)
            self.assertEqual(len(r["recs"]), 1)                  # the reader logged under the reported root
            self.assertTrue(r["recs"][0]["refused"])
            self.assertEqual(r["no_plan"], not r["activated"])
        self.assertTrue(mine["same_as_root"] and mine["activated"] and mine["why"] is None)
        self.assertFalse(other["same_as_root"] or other["activated"])

    def test_operator_git_env_and_pythonpath_do_not_change_the_resolution(self):
        """R-C1 review 1(b): GIT_DIR / GIT_WORK_TREE / PYTHONPATH in the operator's env pointing elsewhere must not
        change what the probe resolves (they are scrubbed from the child env)."""
        keys = ("GIT_DIR", "GIT_WORK_TREE", "PYTHONPATH")

        def fn(root, arch, info):
            cwd = os.path.join(dispatch_cwd(root), "docs")
            base = O.reader_resolution(OB, cwd)
            elsewhere, junk = other_repo(), tempfile.mkdtemp(prefix="r7orch-pp-")
            open(os.path.join(junk, "json.py"), "w").write("raise ImportError('shadowed')\n")
            saved = {k: os.environ.get(k) for k in keys}
            os.environ.update(GIT_DIR=os.path.join(elsewhere, ".git"), GIT_WORK_TREE=elsewhere, PYTHONPATH=junk)
            try:
                polluted = O.reader_resolution(OB, cwd)
            finally:
                for k, v in saved.items():
                    if v is None:
                        os.environ.pop(k, None)
                    else:
                        os.environ[k] = v
            return base, polluted, os.path.realpath(root)
        base, polluted, root = run_in_fixture(fn)[1]
        self.assertEqual(os.path.realpath(base[0]), root)
        self.assertTrue(base[1])
        self.assertEqual(polluted, base)

    def test_the_probe_runs_the_reader_script_equivalent_not_from_cwd(self):
        """R-C1 review 1(a): agents run the reader as a script (sys.path[0] = the reader's directory), so a module in
        the dispatch cwd that shadows a stdlib name must not affect the probe either."""
        def fn(root, arch, info):
            cwd = os.path.join(dispatch_cwd(root), "docs")
            open(os.path.join(cwd, "json.py"), "w").write("raise ImportError('shadowed')\n")
            return O.reader_resolution(OB, cwd), os.path.realpath(root)
        (cr, activated, why), root = run_in_fixture(fn)[1]
        self.assertIsNone(why)
        self.assertEqual(os.path.realpath(cr), root)
        self.assertTrue(activated)


class DispatchCwdCLI(unittest.TestCase):
    """F-5: the CLI's `prepare` REQUIRES --dispatch-cwd (the session's primary working directory, which the dispatched
    agents inherit; the tool process's own cwd is not it), forwards it to prepare(cwd=…), and refuses (exit 2, nothing
    written) without it. The counts line prints the resolved dispatch root (path only)."""

    def _cli(self, extra_of):
        import contextlib
        import io

        def fn(root, arch, info):
            os.remove(os.path.join(root, ADR, "INPUT-MANIFESTS.json"))
            for lab in STATE["plan"]["labels"]:
                os.remove(os.path.join(root, FX.prep.SLICE_ROOT, OB, f"{lab}.view.txt"))
            emit = tempfile.mkdtemp(prefix="r7orch-emit-")
            out, err = io.StringIO(), io.StringIO()
            orig = (O.c.verify_frozen, O.c.assert_sealed)
            O.c.verify_frozen = O.c.assert_sealed = lambda: True
            try:
                with contextlib.redirect_stdout(out), contextlib.redirect_stderr(err):
                    rc = O.main(["prepare", OB, "--root", root, "--emit", emit] + extra_of(root))
            finally:
                O.c.verify_frozen, O.c.assert_sealed = orig
            written = sorted(os.listdir(emit))
            written += [n for n in ("INPUT-MANIFESTS.json",) if os.path.isfile(os.path.join(root, ADR, n))]
            written += [l for l in STATE["plan"]["labels"]
                        if os.path.isfile(os.path.join(root, FX.prep.SLICE_ROOT, OB, f"{l}.view.txt"))]
            return {"rc": rc, "out": out.getvalue(), "err": err.getvalue(), "written": written,
                    "root": os.path.realpath(root)}
        return run_in_fixture(fn)[1]

    def test_prepare_without_dispatch_cwd_is_refused(self):
        r = self._cli(lambda root: [])
        self.assertEqual(r["rc"], 2)
        self.assertIn("--dispatch-cwd", r["err"])
        self.assertEqual(r["written"], [])
        self.assertEqual(r["out"], "")

    def test_prepare_with_another_repository_is_refused(self):
        r = self._cli(lambda root: ["--dispatch-cwd", other_repo()])
        self.assertEqual(r["rc"], 2)
        self.assertIn("dispatch working directory", r["err"])
        self.assertEqual(r["written"], [])

    def test_prepare_from_inside_the_repository_is_accepted_and_prints_the_dispatch_root(self):
        r = self._cli(lambda root: ["--dispatch-cwd", os.path.join(dispatch_cwd(root), "docs")])
        self.assertEqual(r["rc"], 0, "prepare refused")
        self.assertIn(OB, r["written"])
        line = [l for l in r["out"].split("\n") if l.startswith(f"S5-ORCH prepare {OB}: ")]
        self.assertEqual(len(line), 1)
        self.assertIn(f"dispatch_root={r['root']}", line[0].split(" "))


class LineCounts(unittest.TestCase):
    """Canary attempt 1, F-3: the prompt states each input's line count as the harness Read reports it, so the agent
    stops paging at it (a Read past EOF displays nothing and the witness scores it as W8)."""
    STOP = ("Stop paging at the stated line count; a Read past it displays no content; if a Read reports that the file "
            "is shorter than the offset, stop paging that file.")
    BODIES = {"CONTRACT": b"c1\nc2\n", "ADDENDUM": b"a1\na2\na3", "PLAN": b"{}\n", "SLICE": b"",
              "SLICE-VIEW": b"".join(b"line %d\n" % i for i in range(1, 60))}

    def setUp(self):
        owners = U.run_owner(STATE["plan"])
        self.run, (self.lab, _, _) = next((r, o) for r, o in sorted(owners.items()) if o[1] == "SINGLE")
        self.srun = next(r for r, o in sorted(owners.items()) if o[1] == "SYNTHESIS")
        self.root = tempfile.mkdtemp(prefix="r7orch-lines-")
        self.m = {"entries": []}
        for cat, body in self.BODIES.items():
            rel = f"x/{cat.lower()}.txt"
            xf.put(self.root, rel, body)
            self.m["entries"].append({"category": cat, "path": rel, "sha256": U.sha(body)})

    def render(self, run=None, m=None):
        return O.render_prompt(run or self.run, OB, STATE["plan"], m or self.m, xf.READER_ABS, xf.COMMIT, self.root)

    def test_harness_line_count_is_the_harness_rule(self):
        # verified against the harness Read on synthetic files (past-EOF notice "The file has N lines"; the full read
        # displays N lines, the last one empty after a trailing newline)
        for data, n in ((b"a\nb\nc\n", 4), (b"a\nb\nc", 3), (b"a\n\n\n", 4), (b"", 1)):
            self.assertEqual(O.harness_line_count(data), n)

    def test_each_input_line_states_the_actual_line_count(self):
        p = self.render().split("\n")
        self.assertEqual(p[0], xf.binding(self.run, OB, self.lab))
        for e in self.m["entries"]:
            n = len(self.BODIES[e["category"]].decode("utf-8").split("\n"))
            want = f"  {e['category']}  {os.path.join(self.root, e['path'])}  ({n} lines)"
            self.assertTrue(want in p, want)                     # no prompt dump on failure

    def test_the_stop_sentence_follows_the_paging_lines(self):
        p = self.render().split("\n")
        i = p.index("SLICE-VIEW (read the view, not the raw SLICE), at most 300 lines per Read for every other input.")
        self.assertEqual(p[i + 1], self.STOP)
        self.assertEqual(p[i + 2], "")

    def test_a_past_eof_offset_is_never_implied(self):
        p = self.render()
        for e in self.m["entries"]:
            body = self.BODIES[e["category"]]
            n = int(re.search(re.escape(os.path.join(self.root, e["path"])) + r"  \((\d+) lines\)", p).group(1))
            shown = len(body.decode("utf-8").split("\n"))          # lines the harness displays for a full read
            self.assertEqual(n, shown)
            cap = 25 if e["category"] == "SLICE-VIEW" else 300
            offsets = list(range(1, n + 1, cap))                  # paging to the stated count
            self.assertTrue(all(o <= shown for o in offsets))     # never past EOF
            self.assertGreaterEqual(offsets[-1] + cap - 1, shown)  # and the whole file is displayed

    def test_the_prompt_is_deterministic(self):
        self.assertEqual(self.render(), self.render())

    def test_an_existing_input_without_a_frozen_sha_is_stated_at_dispatch(self):
        """R-C1 review 3: an existing file whose I(run) sha256 is None is not frozen; no count from its bytes."""
        m = json.loads(json.dumps(self.m))
        m["entries"][0]["sha256"] = None
        p = self.render(m=m).split("\n")
        want = f"  {m['entries'][0]['category']}  {os.path.join(self.root, m['entries'][0]['path'])}  (line count at dispatch)"
        self.assertTrue(want in p, want)

    def test_an_input_not_yet_existing_is_stated_at_dispatch(self):
        m = {"entries": self.m["entries"] + [{"category": "UNIT-RECORDS", "path": "ledger/u/file-reading-records.jsonl",
                                              "sha256": None}]}
        p = self.render(self.srun, m)
        want = f"  UNIT-RECORDS  {os.path.join(self.root, 'ledger/u/file-reading-records.jsonl')}  (line count at dispatch)"
        self.assertTrue(want in p.split("\n"), want)

    def test_bytes_not_equal_to_the_frozen_sha_are_refused(self):
        m = json.loads(json.dumps(self.m))
        m["entries"][0]["sha256"] = "0" * 64
        with self.assertRaises(O.Refused):
            self.render(m=m)


class Archive(unittest.TestCase):
    def session(self):
        d = tempfile.mkdtemp(prefix="r7orch-sess-")
        main = os.path.join(d, "sess.jsonl")
        xf.put(d, "sess.jsonl", b'{"a":1}\n')
        xf.put(d, "sess/subagents/agent-a1.jsonl", b'{"b":1}\n')
        xf.put(d, "sess/subagents/agent-a1.meta.json", b'{"toolUseId":"t"}')
        xf.put(d, "sess/tool-results/r1.txt", b"out")
        return d, main

    def test_copies_the_harness_layout(self):
        d, main = self.session()
        arch = tempfile.mkdtemp(prefix="r7orch-arch-")
        O.archive(OB, main, arch)
        for src, dst in (("sess.jsonl", "main.jsonl"), ("sess/subagents/agent-a1.jsonl", "subagents/agent-a1.jsonl"),
                         ("sess/subagents/agent-a1.meta.json", "subagents/agent-a1.meta.json"),
                         ("sess/tool-results/r1.txt", "tool-results/r1.txt")):
            self.assertEqual(_rd(arch, f"{OB}/{dst}"), _rd(d, src))

    def test_refresh_is_append_only(self):
        d, main = self.session()
        arch = tempfile.mkdtemp(prefix="r7orch-arch-")
        O.archive(OB, main, arch)
        open(main, "ab").write(b'{"a":2}\n')
        O.archive(OB, main, arch)                                   # an append (a live transcript grows): allowed
        self.assertEqual(_rd(arch, f"{OB}/main.jsonl"), _rd(d, "sess.jsonl"))
        open(main, "wb").write(b'{"a":9}\n')
        with self.assertRaises(O.Refused):                          # a rewrite: refused, the archive is unchanged
            O.archive(OB, main, arch)
        self.assertEqual(_rd(arch, f"{OB}/main.jsonl"), b'{"a":1}\n{"a":2}\n')

    def test_archive_inside_the_repository_is_refused(self):
        _, main = self.session()
        with self.assertRaises(O.Refused):
            O.archive(OB, main, os.path.join(O.CR, "arch"))


if __name__ == "__main__":
    unittest.main()
