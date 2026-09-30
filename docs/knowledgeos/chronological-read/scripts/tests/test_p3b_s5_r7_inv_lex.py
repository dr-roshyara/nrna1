#!/usr/bin/env python3
"""R7 v2.4 INV-LEX suite: lexical source-id occurrence ≠ evidentiary support (addendum v2.4 §3.0, §3.3; G-LOG-0089;
T98–T104). Formerly the v2.4 proposal's non-interference tests (`9654a6d91`).

Two registries:
- V24: the frozen v2.4 addendum, loaded by the production loader (sha-checked).
- V23: the frozen v2.3 addendum (sha256 cdbf53cb…f211), recovered read-only from git commit 05569322d. It is used
  only as the differential baseline, and those tests are skipped if git is unavailable.

The full-path v2.3 arm substitutes V23 for the verifier's contract loader in memory only (registry()).

Property under test: for every object O and every META-only mention X at a v2.4 path,
- the claims, evidence obligations, reconstruction relations, precedence, lifecycle, statistics frame and canonical
  claim ids of O+X equal those of O; and
- failures_v2.4(O+X) = failures_v2.3(O+X) − {R7-U untyped failures at the v2.4 paths}.

The whole-record lexical constraints (G-01, AUDIT, QUARANTINE, S3) keep applying to META text (fail-closed).
No corpus, no reader process, no repository write (temp dirs only). Synthetic hold-outs only (testlib).
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_r7_inv_lex
"""
import contextlib
import copy
import hashlib
import importlib.util
import re
import subprocess
import sys
import os
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
sys.path.insert(0, os.path.dirname(HERE))
import r7_full_fixture as FX                        # noqa: E402
import p3b_s5_ops_testlib as testlib                # noqa: E402
import p3b_s5_r7_universe as U                      # noqa: E402
import p3b_s5_r7_reconstruction as C                # noqa: E402
import p3b_s5_r7_evidence as E                      # noqa: E402
import p3b_s5_r7_stats as S                         # noqa: E402

CR = FX.CR
V23_SHA, V23_COMMIT = "cdbf53cbe531cc92dc5decc9b29496188b9b6e7ffc0e2ec875bc5d4dc4a6f211", "05569322d"
V24_ROWS = [("absences.<dim>.reason", "—", "META", "—"), ("stage2_dispositions[*].reason", "—", "META", "—"),
            ("timeline[*].historical_position", "—", "META", "—"), ("escalations[*].field", "—", "META", U.SELF_REF_RULE)]
NEW_PATHS = ("absences.", "stage2_dispositions[*].reason", "timeline[*].historical_position", "escalations[*].field")
SGL = "relation-8-tuple-vs-triple-reduction"

V24_REG, CONS, V24_F = U.load_frozen_contract(CR)


def _v23():
    try:
        raw = subprocess.run(["git", "show", f"{V23_COMMIT}:docs/knowledgeos/chronological-read/{U.ADDENDUM_PATH}"],
                             cwd=CR, capture_output=True, check=True).stdout
    except (OSError, subprocess.CalledProcessError):
        return None
    if hashlib.sha256(raw).hexdigest() != V23_SHA:
        return None
    return U.load_contract(raw)[0]


V23_REG = _v23()
need_v23 = unittest.skipIf(V23_REG is None, "v2.3 addendum not recoverable from git")


@contextlib.contextmanager
def registry(reg):
    """In memory only: every fresh load of the R7 composer gets `reg` from its contract loader."""
    orig = importlib.util.spec_from_file_location

    def spec(name, path, *a, **k):
        s = orig(name, path, *a, **k)
        if name == "p3b_s5_r7_verify":
            ex = s.loader.exec_module

            def exec_module(m):
                ex(m)
                m.U.load_frozen_contract = lambda cr: (reg, CONS, [])
            s.loader.exec_module = exec_module
        return s
    importlib.util.spec_from_file_location = spec
    try:
        yield
    finally:
        importlib.util.spec_from_file_location = orig


_BASE = None


def base():
    global _BASE
    if _BASE is None:
        _BASE = FX.base()
    return _BASE


def sgl(st):
    return FX.objs_of(st, SGL)


def known(st, n=1):
    return [p["source_id"] for p in sgl(st)["timeline"]][:n]


def meta_everywhere(text_fn):
    """A mutation writing text_fn(state) into every v2.4 NDB-1 location of the SGL object (META-only mentions)."""
    def m(st):
        o = sgl(st)
        t = text_fn(st)
        for a in (o.get("absences") or {}).values():
            if isinstance(a, dict):
                a["reason"] = f"census note; {t}"
        for d in o.get("stage2_dispositions") or []:
            if isinstance(d, dict):
                d["reason"] = f"disposition note; {t}"
        for p in o["timeline"]:
            p["historical_position"] = f"{p.get('historical_position')} ({t})"
    return m


def projection(reg, o, meta):
    """Every registry-independent semantic output of one object (what must NOT change)."""
    cl = U.claims(reg, o)
    pts = {p.get("source_id"): p for p in o.get("timeline") or []}
    return {
        "claims": sorted(U.canon(c) for c in cl),                                   # claims + canonical claim ids
        "claim_count": len(cl),
        "evidence_sources": sorted({c["source"] for c in cl if c["source"]}),
        "whole": sorted((c["id"], c["whole"]) for c in cl),
        "validation": U.validation_violations(o),
        "meta_rules": U.meta_violations(reg, o),
        "summary": C.summary_violations(o, meta),
        "lifecycle": C.lifecycle_violations(o, meta),
        "contradiction_precedence": sorted(C.contradiction_precedence(o, meta).items()),
        "precedence": sorted((t, s, C.precedence(t, s, pts, meta)) for t in pts for s in pts if t != s),
        "reading_rule": E.reading_rule_violations(o, {c["source"]: "WHOLE-FILE" for c in cl}, cl),
        "empty": C.empty_violations(o, cl),
    }


def norm_fail(xs):
    return sorted(re.sub(r"/tmp/[^\s'\"]*", "<tmp>", x) for x in xs)


def is_new_path_typing(x):
    return "untyped S-id-bearing location" in x and any(p in x for p in NEW_PATHS)


def unknown_sid():
    files = FX.c.files_meta()
    return next(f"S{n:04d}" for n in range(9989, 9000, -1) if f"S{n:04d}" not in files and f"S{n:04d}" not in testlib.FAKE_HF)


# ---------------------------------------------------------------- the v2.4 contract itself
class Contract(unittest.TestCase):
    def test_v24_loads_and_carries_the_four_rows(self):
        self.assertEqual(V24_F, [])
        tail = [(r["path"], r["discriminator"], r["type"], r["kind_or_rule"]) for r in V24_REG[-4:]]
        self.assertEqual(tail, V24_ROWS)
        self.assertTrue(all(r["_disc"] for r in V24_REG))

    @need_v23
    def test_v24_only_appends_to_v23(self):
        strip = lambda rs: [{k: v for k, v in r.items() if not k.startswith("_")} for r in rs]
        self.assertEqual(len(V24_REG), len(V23_REG) + 4)
        self.assertEqual(strip(V24_REG[:len(V23_REG)]), strip(V23_REG))

    def test_no_evidentiary_or_derived_type_is_added(self):
        self.assertEqual({r["type"] for r in V24_REG[-4:]}, {"META"})
        self.assertEqual(U.consumer_violations(V24_REG, CONS), [])      # F2: no consumer may read a META path

    def test_new_rows_are_unambiguous_on_the_positive_control(self):
        st = copy.deepcopy(base())
        meta_everywhere(lambda s: " ".join(known(s, 2)))(st)
        for lab in st["plan"]["labels"]:
            self.assertFalse(any("more than one registry row" in x
                                 for x in U.typing_violations(V24_REG, FX.objs_of(st, lab))))


# ---------------------------------------------------------------- component-level non-interference
class ComponentNonInterference(unittest.TestCase):
    META = None

    @classmethod
    def setUpClass(cls):
        cls.META = FX.c.files_meta()

    def pair(self, mut):
        o = copy.deepcopy(sgl(base()))
        st = copy.deepcopy(base())
        mut(st)
        return o, sgl(st)

    def assert_same_semantics(self, o, ox):
        for reg in [V24_REG] + ([V23_REG] if V23_REG else []):
            self.assertEqual(projection(reg, o, self.META), projection(reg, ox, self.META))

    def test_T98_meta_mention_changes_nothing_but_typing(self):
        o, ox = self.pair(meta_everywhere(lambda st: " and ".join(known(st, 2))))
        self.assert_same_semantics(o, ox)
        self.assertEqual(U.typing_violations(V24_REG, ox), [])
        if V23_REG:
            self.assertTrue(any(is_new_path_typing(x) for x in U.typing_violations(V23_REG, ox)))

    def test_claims_ignore_the_registry(self):
        """Claim enumeration is structurally closed: registry rows cannot create a claim (claims() reads fixed paths)."""
        o = sgl(base())
        self.assertEqual(U.claims(V24_REG, o), U.claims([], o))

    def test_mutation_removed(self):
        o, ox = self.pair(meta_everywhere(lambda st: "no identifier here"))
        self.assert_same_semantics(o, ox)

    def test_mutation_malformed_is_not_discovered(self):
        for t in ("S12", "s0001", "S00012", "S-0001", "SS0001x"):
            o, ox = self.pair(meta_everywhere(lambda st, t=t: t))
            self.assert_same_semantics(o, ox)
            self.assertEqual(U.typing_violations(V24_REG, ox), U.typing_violations(V24_REG, o), t)

    def test_mutation_multiple_and_duplicated(self):
        for fn in (lambda st: ", ".join(known(st, 3)), lambda st: f"{known(st)[0]} … again {known(st)[0]}"):
            o, ox = self.pair(meta_everywhere(fn))
            self.assert_same_semantics(o, ox)
            self.assertEqual(U.typing_violations(V24_REG, ox), [])

    def test_mutation_unknown_sid_creates_no_claim(self):
        o, ox = self.pair(meta_everywhere(lambda st: unknown_sid()))
        self.assert_same_semantics(o, ox)                                  # rejection is the historical G-01's job

    def test_T102_moved_into_an_evidence_field_does_create_a_claim(self):
        """Control: the SAME S-id in a registry-EVIDENTIARY location is a claim; the META location never is."""
        def mv(st):
            sgl(st)["superseded_by_sources"] = [{"source_id": known(st)[0], "position": "UNDATED"}]
        o, ox = self.pair(mv)
        self.assertEqual(len(U.claims(V24_REG, ox)), len(U.claims(V24_REG, o)) + 1)
        self.assertNotEqual(projection(V24_REG, o, self.META), projection(V24_REG, ox, self.META))

    def test_statistics_frame_is_object_independent(self):
        st = copy.deepcopy(base())
        before = S.frame_attributes(st["plan"], st["ctx"]["slices"])
        meta_everywhere(lambda s: " ".join(known(s, 2)))(st)
        self.assertEqual(S.frame_attributes(st["plan"], st["ctx"]["slices"]), before)


# ---------------------------------------------------------------- full path (production verifier), v2.3 vs v2.4
@need_v23
class FullPathNonInterference(unittest.TestCase):
    """failures_v2.4(M) = failures_v2.3(M) − {R7-U untyped at the v2.4 paths}, for every mutation M."""

    def both(self, mut):
        with registry(V23_REG):
            old = FX.verify(base(), mut)
        new = FX.verify(base(), mut)                                        # the frozen v2.4 file
        return old, new

    def assert_only_typing_removed(self, old, new):
        self.assertEqual(norm_fail(new["failures"]), norm_fail([x for x in old["failures"] if not is_new_path_typing(x)]))

    def test_positive_control(self):
        old, new = self.both(None)
        self.assertEqual(new["result"], "BATCH-PASS", new["failures"][:6])
        self.assertTrue(any(is_new_path_typing(x) for x in old["failures"]))   # de-sanitized: v2.3 rejects real text
        self.assert_only_typing_removed(old, new)

    def test_T98_meta_only_mention(self):
        old, new = self.both(meta_everywhere(lambda st: " and ".join(known(st, 2))))
        self.assertEqual(old["result"], "BATCH-FAIL")
        self.assertEqual(new["result"], "BATCH-PASS", new["failures"][:6])
        self.assertEqual(new["predicates"], {"U": "T", "W": "T", "E": "T", "R": "T"})
        self.assert_only_typing_removed(old, new)

    def test_T99_unknown_sid_still_fails_closed(self):
        old, new = self.both(meta_everywhere(lambda st: unknown_sid()))
        self.assertEqual(new["result"], "BATCH-FAIL")
        self.assertTrue(any(x.startswith(("G-01", "AUDIT")) for x in new["failures"]), new["failures"][:6])
        self.assert_only_typing_removed(old, new)

    def test_T100_holdout_mention_still_breaches_the_seal(self):
        old, new = self.both(meta_everywhere(lambda st: sorted(testlib.FAKE_HF)[0]))
        self.assertEqual(new["result"], "BATCH-FAIL")
        self.assertTrue(any(x.startswith("QUARANTINE") for x in new["failures"]), new["failures"][:6])
        self.assert_only_typing_removed(old, new)

    def test_T101_range_in_meta_text_still_fails_S3(self):
        old, new = self.both(meta_everywhere(lambda st: f"{known(st)[0]}–{known(st, 2)[1]}"))
        self.assertEqual(new["result"], "BATCH-FAIL")
        self.assertTrue(any("S3" in x for x in new["failures"]), new["failures"][:6])
        self.assert_only_typing_removed(old, new)

    def test_removed_malformed_multiple_duplicated(self):
        for fn in (lambda st: "no identifier", lambda st: "S00012", lambda st: ", ".join(known(st, 3)),
                   lambda st: f"{known(st)[0]} and {known(st)[0]}"):
            old, new = self.both(meta_everywhere(fn))
            self.assert_only_typing_removed(old, new)
            self.assertEqual(new["result"], "BATCH-PASS", new["failures"][:6])

    def test_T102_moved_into_evidence_field_is_judged_as_evidence(self):
        def mv(st):
            sgl(st)["superseded_by_sources"] = [{"source_id": known(st)[0], "position": "UNDATED"}]
        old, new = self.both(mv)
        self.assertEqual(new["result"], "BATCH-FAIL")
        self.assert_only_typing_removed(old, new)

    def test_T103_dc1_escalation_field(self):
        def m(st):
            s = sgl(st)["timeline"][0]["source_id"]
            sgl(st)["escalations"].append({"field": f"timeline[{s}].order", "reason": "SCHEMA-LIMITATION", "detail": "x"})
        old, new = self.both(m)
        self.assertTrue(any("escalations[*].field" in x for x in old["failures"]))
        self.assertFalse(any("escalations[*]" in x for x in new["failures"]))
        self.assert_only_typing_removed(old, new)


if __name__ == "__main__":
    unittest.main()
