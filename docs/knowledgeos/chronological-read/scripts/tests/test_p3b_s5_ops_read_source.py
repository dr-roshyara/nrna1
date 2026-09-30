"""Regression tests: the O-16 run/batch-id extension of scripts/p3b_read_source.py.

No real read is ever made: the resolver is replaced by a fake and the log writer by a capture. The committed (HEAD)
version of the reader is executed from `git show` in memory, with the same patches, to prove that every S4-era argument
vector behaves identically and that CLOSED_RUNS is still refused."""
import contextlib
import importlib.util
import io
import os
import subprocess

PRE_S5_COMMIT = "334871f48~1"
import sys
import types
import unittest

import p3b_s5_ops_testlib as T

RS_PATH = os.path.join(T.SCRIPTS, "p3b_read_source.py")


def load_current():
    spec = importlib.util.spec_from_file_location("rs_current", RS_PATH)
    m = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(m)
    return m


def load_head():
    rel = os.path.relpath(RS_PATH, T.c.REPO_ROOT)
    # the last pre-S5 reader (the parent of the commit that added the S5 run ids), pinned so the comparison
    # stays meaningful after the extension is committed
    src = subprocess.run(["git", "-C", T.c.REPO_ROOT, "show", f"{PRE_S5_COMMIT}:{rel}"], capture_output=True, text=True, check=True).stdout
    m = types.ModuleType("rs_head")
    m.__file__ = RS_PATH                                   # so its sibling import of p3b_discovery_io resolves
    exec(compile(src, RS_PATH + "@HEAD", "exec"), m.__dict__)
    return m


class FakeResolver:
    def __init__(self, refuse=False):
        self.refuse, self.calls = refuse, []
        self.rows = {}

    def read_many(self, sids, rs=None):
        self.calls.append(list(sids))
        if self.refuse:
            raise self.error("sealed")
        self.rows = {s: {"content_sha256": "0" * 64} for s in sids}
        return {s: b"fake" for s in sids}


PRE_R7_COMMIT = "5469a014f~1"          # the last commit before R7 production activation (G-LOG-0102)
_PRE_R7_MANIFEST = []


def pre_r7_manifest():
    """These are tests of the R2 reader GRAMMAR: they run against the revision-3 manifest as it was before R7 activation
    (after activation the reader default-denies every legacy run of an activated batch — the intended M2 protection,
    asserted separately in ProductionActivation)."""
    if not _PRE_R7_MANIFEST:
        rel = os.path.relpath(os.path.join(T.c.CR, "_batch_manifest_p3b_r2.jsonl"), T.c.REPO_ROOT)
        txt = subprocess.run(["git", "-C", T.c.REPO_ROOT, "show", f"{PRE_R7_COMMIT}:{rel}"], capture_output=True, text=True,
                             check=True).stdout
        p = os.path.join(T.tmpdir("pre-r7"), "_batch_manifest_p3b_r2.jsonl")
        with open(p, "w", encoding="utf-8") as f:
            f.write(txt)
        _PRE_R7_MANIFEST.append(p)
    return _PRE_R7_MANIFEST[0]


def invoke(mod, argv, refuse=False, manifest=None):
    fake = FakeResolver(refuse)
    fake.error = mod.dio.s3.IdentityError
    logged = []
    orig_res, orig_log, orig_argv = mod.dio.discovery_resolver, mod.log, sys.argv
    orig_man = getattr(mod, "MANIFEST", None)
    mod.dio.discovery_resolver = lambda *a, **k: fake
    mod.log = lambda opts, sids, refused, data=None: logged.append((opts["run"], refused))
    if orig_man is not None:
        mod.MANIFEST = manifest or pre_r7_manifest()
    sys.argv = ["p3b_read_source.py"] + argv
    try:
        with contextlib.redirect_stdout(io.StringIO()), contextlib.redirect_stderr(io.StringIO()):
            rc = mod.main()
    finally:
        mod.dio.discovery_resolver, mod.log, sys.argv = orig_res, orig_log, orig_argv
        if orig_man is not None:
            mod.MANIFEST = orig_man
    return rc, len(fake.calls), logged


class ProductionActivation(unittest.TestCase):
    def test_legacy_run_of_an_activated_batch_is_refused(self):
        """After R7 activation (G-LOG-0102) the production manifest activates every OB batch: a legacy R2 run id is
        refused before any read (reader default-deny, M2)."""
        mod = load_current()
        rc, reads, _ = invoke(mod, ["--run", "OB0004-R2", "--batch", "OB0004", "--label", "x", "--step", "1", "S1234"],
                              manifest=mod.MANIFEST)
        self.assertNotEqual(rc, 0)
        self.assertEqual(reads, 0)


def args(run, batch, label="disc-a", step="7", sids=("S1234",)):
    return ["--run", run, "--batch", batch, "--label", label, "--step", step, *sids]


S4_MATRIX = [
    args("S4-PILOT-R2-002", "PB02"), args("S4-PILOT-R2-003", "PB05", step="1"), args("S4-PILOT-R2-001", "PB02"),
    args("OB0001-R2", "B0001"), args("OB0003-R2.2", "B0003", step="10"), args("S4-PILOT-R2-002", "PB2"),
    args("S4-PILOT-R2-02", "PB02"), args("S4-PILOT-R2-002", "PB02", step="2"), args("S4-PILOT-R2-002", "PB02", sids=("X1",)),
    args("S4-PILOT-R2-002", "PB02", label="-bad"), ["--run", "S4-PILOT-R2-002", "--batch", "PB02", "S1234"],
]


class ReadSourceExtension(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.cur, cls.head = load_current(), load_head()

    def test_s4_behaviour_identical_to_head(self):
        for a in S4_MATRIX:
            self.assertEqual(invoke(self.cur, a), invoke(self.head, a), a)

    def test_closed_run_still_refused(self):
        self.assertIn("S4-PILOT-R2-001", self.cur.CLOSED_RUNS)
        rc, calls, logged = invoke(self.cur, args("S4-PILOT-R2-001", "PB02"))
        self.assertEqual((rc, calls, logged), (2, 0, []))

    def test_new_s5_ids_accepted(self):
        for a in (args("OB0004-R2", "OB0004"), args("OB0004-R2.2", "OB0004"), args("OB0123-R2S", "OB0123"),
                  args("OT0001-R2", "OT0001"), args("OT0042-R2", "OB0004", step="10")):
            rc, calls, logged = invoke(self.cur, a)
            self.assertEqual((rc, calls, len(logged)), (0, 1, 1), a)

    def test_new_ids_rejected_at_head(self):
        self.assertEqual(invoke(self.head, args("OT0001-R2", "OT0001"))[0], 2)
        self.assertEqual(invoke(self.head, args("OB0004-R2S", "OB0004"))[0], 2)

    def test_malformed_ids_still_rejected(self):
        for a in (args("OT0001-R2.2", "OT0001"), args("OT0001-R2S", "OT0001"), args("OB0004-R3", "OB0004"),
                  args("OB0004-R2SX", "OB0004"), args("OB004-R2", "OB0004"), args("OB0004-R2", "OB004"),
                  args("OB0004-R2", "XB0004"), args("OB0004-R2", "OB0004", step="9")):
            rc, calls, logged = invoke(self.cur, a)
            self.assertEqual((rc, calls, logged), (2, 0, []), a)

    def test_refusal_is_logged(self):
        rc, calls, logged = invoke(self.cur, args("OB0004-R2", "OB0004"), refuse=True)
        self.assertEqual((rc, logged), (1, [("OB0004-R2", True)]))



class PagedReader(unittest.TestCase):
    """Contract revision 3 (G-LOG-0050): deterministic pages; the log carries page identity and hashes; the pages of a
    file reconstruct it exactly; paged output redirected to a regular file is refused. Fake resolver, captured log."""

    @classmethod
    def setUpClass(cls):
        cls.m = load_current()

    def run_paged(self, text, page, stdout_kind="pipe"):
        m = self.m
        fake = FakeResolver()
        fake.error = m.dio.s3.IdentityError
        fake.read_many = lambda sids, rs=None: (fake.rows.update({s: {"content_sha256": "c" * 64} for s in sids})
                                               or {s: text.encode("utf-8") for s in sids})
        logged, out = [], io.StringIO()
        orig = (m.dio.discovery_resolver, m.log, sys.argv, m.stdout_kind, m.MANIFEST)
        m.dio.discovery_resolver = lambda *a, **k: fake
        m.log = lambda opts, sids, refused, data=None, extra=None: logged.append((refused, data, extra))
        m.stdout_kind = lambda: stdout_kind
        m.MANIFEST = pre_r7_manifest()                 # the R2 paged grammar, against the pre-activation manifest
        sys.argv = ["p3b_read_source.py", "--run", "OB0004-R2.3", "--batch", "OB0004", "--label", "disc-a", "--step", "7",
                    "--page", str(page), "S1234"]
        try:
            with contextlib.redirect_stdout(out), contextlib.redirect_stderr(io.StringIO()):
                rc = m.main()
        finally:
            m.dio.discovery_resolver, m.log, sys.argv, m.stdout_kind, m.MANIFEST = orig
        return rc, logged, out.getvalue()

    def test_pages_reconstruct_the_file_and_are_logged(self):
        text = "".join(chr(0x41 + (i % 26)) for i in range(45123)) + "ü€"          # multi-byte tail
        n = len(self.m.pages_of(text))
        self.assertEqual(n, 3)
        parts = []
        for k in range(1, n + 1):
            rc, logged, out = self.run_paged(text, k)
            self.assertEqual(rc, 0)
            refused, data, extra = logged[-1]
            rec = extra["page"]
            self.assertFalse(refused)
            self.assertEqual((rec["page"], rec["n_pages"]), (k, n))
            chunk = text[rec["char_start"]:rec["char_end"]]
            self.assertEqual(rec["page_sha256"], self.m.dio.s3.sha256_bytes(chunk.encode("utf-8")))
            self.assertEqual(data["S1234"], chunk.encode("utf-8"))
            parts.append(chunk)
            self.assertIn(f"page {k}/{n}", out)
        self.assertEqual("".join(parts), text)

    def test_file_stdout_now_allowed_and_logged_and_page_out_of_range(self):
        # the redirect refusal is withdrawn (G-LOG-0052): the harness captures stdout into a file
        rc, logged, _ = self.run_paged("abc", 1, stdout_kind="file")
        self.assertEqual(rc, 0)
        self.assertFalse(logged[-1][0])
        self.assertEqual(logged[-1][2]["stdout"], "file")
        rc, _, _ = self.run_paged("abc", 2)
        self.assertEqual(rc, 2)

    def test_pilot_namespace_session_and_isolation(self):
        m = self.m
        fake = FakeResolver()
        fake.error = m.dio.s3.IdentityError
        fake.read_many = lambda sids, rs=None: (fake.rows.update({s: {"content_sha256": "c" * 64} for s in sids}) or {s: b"abc" for s in sids})
        logged = []
        orig = (m.dio.discovery_resolver, m.log, sys.argv)
        m.dio.discovery_resolver = lambda *a, **k: fake
        m.log = lambda opts, sids, refused, data=None, extra=None: logged.append((opts, refused, extra))
        try:
            for argv, rc_want in ((["--run", "PX0004-U02", "--batch", "PX0004", "--session", "2"], 0),
                                  (["--run", "PX0004-U02", "--batch", "OB0004"], 2),          # pilot run needs a pilot batch
                                  (["--run", "OB0004-R2.3", "--batch", "PX0004"], 2),         # and vice versa
                                  (["--run", "PX0004-X02", "--batch", "PX0004"], 2)):
                sys.argv = ["p3b_read_source.py", *argv, "--label", "disc-a", "--step", "7", "--page", "1", "S1234"]
                with contextlib.redirect_stdout(io.StringIO()), contextlib.redirect_stderr(io.StringIO()):
                    self.assertEqual(m.main(), rc_want, argv)
        finally:
            m.dio.discovery_resolver, m.log, sys.argv = orig
        self.assertEqual(logged[0][2]["session"], 2)
        self.assertTrue(m.re.fullmatch(m.PILOT_RUN, "PX0004-S01") and m.PILOT_ROOT == "pilot-s5-decomp")

    def test_paged_mode_only_for_s5_runs(self):
        m = self.m
        sys_argv = sys.argv
        sys.argv = ["p3b_read_source.py", "--run", "S4-PILOT-R2-002", "--batch", "PB02", "--label", "disc-a", "--step", "7",
                    "--page", "1", "S1234"]
        try:
            with contextlib.redirect_stderr(io.StringIO()):
                self.assertEqual(m.main(), 2)
        finally:
            sys.argv = sys_argv


if __name__ == "__main__":
    unittest.main()
