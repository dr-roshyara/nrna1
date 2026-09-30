"""Tests (EG-6 RETIRE slice, G-LOG-0106; addendum v2.8 §2.3, §2.8; EG6-SPEC-A option (b), -v2 §1, -v3 §1):
`retire` and `may_start_attempt` in scripts/p3b_s5_r7_orchestrate.py, mirroring the reference model
audit-p3b/eg6/probes/retire_model.py.

  T130  attempt 2 after a retirement: the PRODUCTION verifier (r7_full_fixture) passes, no R7-U namespace failure
  T131  the retired directory unlisted / not re-frozen / edited / partly deleted → R7-U namespace (BATCH-FAIL)
  T133a a crash at every step S1…S6 (and the re-freeze): blocked while pending, resume byte-identical, idempotent
  T136  tamper after the intent; a source re-created after its move; evidence lost → refused (class S)
  T142/T143  EXDEV at S2 / S4 → refused class X, PENDING, resumable to the identical final state
  T144  st_dev mismatch → refused class X before any move (S0: nothing written; S4: the archive is not moved)
  S0    VERIFIED / post-VERIFIED (H) / class D, S, U / class mismatch / missing archive … → refused, nothing changed
  G     the may_start_attempt truth table; E2E: FAILED(X) → retire → new_attempt → attempt 2 PREPARED, prompts identical

Synthetic only: temp trees via tempfile (honours TMPDIR), a synthetic manifest / plan / slices / state / archive, and the
synthetic r7_full_fixture. No production state, manifest, ledger, archive, corpus or hold-out artifact is read or
written. Nothing prints an S-id or a label name.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_r7_retire
"""
import errno
import functools
import hashlib
import io
import json
import os
import shutil
import sys
import tempfile
import unittest
from contextlib import redirect_stdout, redirect_stderr
from unittest import mock

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
sys.path.insert(0, os.path.dirname(HERE))
import r7_full_fixture as FX                         # noqa: E402
import p3b_s5_ops_testlib as T                       # noqa: E402
import p3b_s5_r7_orchestrate as O                    # noqa: E402

U = O.U
stm = O.ops.load_script("p3b_s5_state")
B, OTHER = "OB9004", "OB9005"
ASM = f"{B}-R7"
RET1 = f"{B}-R7.A1"
SIG = "a" * 64
REASON = "G-LOG-TEST retire (synthetic)"
FIXED_UTC = "2026-09-30T00:00:00Z"
SLICE_ROOT = "_batch_input_r2/s5/rev7"
LABELS = {"lab-a": ("SINGLE", {f"{B}-R7-L01": "SINGLE"}),
          "lab-b": ("DECOMPOSED", {f"{B}-R7-L02U01": "UNIT", f"{B}-R7-L02U02": "UNIT", f"{B}-R7-L02S": "SYNTHESIS"}),
          "lab-c": ("EMPTY", {})}
RUNS = sorted(r for _, runs in LABELS.values() for r in runs)
MOVED = sorted(RUNS + [ASM])
EMPTY_DIGEST = hashlib.sha256().hexdigest()


class Crash(Exception):
    pass


def put(path, data):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "wb") as f:
        f.write(data if isinstance(data, bytes) else data.encode("utf-8"))
    return path


def rd(path):
    with open(path, "rb") as f:
        return f.read()


def snapshot(*bases):
    """{(base index, relpath): bytes} of every file under the bases (a missing base contributes nothing)."""
    out = {}
    for i, base in enumerate(bases):
        for b, _, fs in os.walk(base):
            for f in fs:
                p = os.path.join(b, f)
                out[(i, os.path.relpath(p, base))] = rd(p)
    return out


def plan_obj():
    return {"batch_id": B, "labels": {lab: {"index": i, "path": path,
                                            "runs": {r: {"role": role, "files": []} for r, role in runs.items()}}
                                      for i, (lab, (path, runs)) in enumerate(LABELS.items(), 1)}}


def rewrite_entry(man_path, batch, **fields):
    """Test helper: replace fields of one manifest entry line (the header is kept; used for the negative variants)."""
    lines = rd(man_path).decode("utf-8").split("\n")
    for i, l in enumerate(lines[1:], 1):
        if l.strip() and json.loads(l).get("batch_id") == batch:
            lines[i] = U.canon(dict(json.loads(l), **fields)).decode("utf-8")
    put(man_path, "\n".join(lines))


def manifest_entry(man_path, batch):
    for l in rd(man_path).decode("utf-8").split("\n")[1:]:
        if l.strip() and json.loads(l).get("batch_id") == batch:
            return json.loads(l)
    raise KeyError(batch)


class World:
    """A synthetic repository root (manifest, plan, slices, ledger, state at revision 7) plus an external archive.
    The batch's attempt 1 has every planned run directory (4 runs; one EMPTY label has none), the assembly, a historical
    legacy directory, and an archived session; another batch's directory stays untouched."""

    def __init__(self, tc, fail_class="X", archive=True, report=True):
        self.base = tempfile.mkdtemp(prefix="eg6ret-")
        tc.addCleanup(shutil.rmtree, self.base, True)
        self.root, self.arch, self.aux = (os.path.join(self.base, n) for n in ("cr", "archive", "aux"))
        os.makedirs(self.aux)
        self.ledger = os.path.join(self.root, U.LEDGER)
        self.man = os.path.join(self.root, "_batch_manifest_p3b_r2.jsonl")
        self.state_path = os.path.join(self.root, "P3B-STATE.json")
        self.nstage = 0
        ssha = {}
        for lab in LABELS:
            body = json.dumps({"hub": False, "in_checklist": False, "label": lab}, sort_keys=True)
            put(os.path.join(self.root, SLICE_ROOT, B, f"{lab}.json"), body + "\n")
            ssha[lab] = U.sha(body.encode("utf-8"))
        ppath = put(os.path.join(self.root, SLICE_ROOT, f"{B}.R7-PLAN.json"), U.canon(plan_obj()))
        put(os.path.join(self.ledger, f"{B}-R2", "objects.jsonl"), b'{"historical":"R2"}\n')
        for d in RUNS:
            put(os.path.join(self.ledger, d, "objects.jsonl"), json.dumps({"run": d}) + "\n")
            put(os.path.join(self.ledger, d, "READ-LOG.jsonl"), b"")
        put(os.path.join(self.ledger, RUNS[0], "nested", "deep.json"), b'{"n":1}')
        put(os.path.join(self.ledger, ASM, "INPUT-MANIFESTS.json"), b"{}")
        put(os.path.join(self.ledger, ASM, "WITNESS.jsonl"), b'{"w":1}\n')
        put(os.path.join(self.ledger, f"{OTHER}-R7-L01", "objects.jsonl"), b'{"other":1}\n')
        if archive:
            put(os.path.join(self.arch, B, "main.jsonl"), b'{"main":1}\n')
            put(os.path.join(self.arch, B, "subagents", "agent-1.jsonl"), b'{"a":1}\n')
            put(os.path.join(self.arch, B, "subagents", "agent-2.jsonl"), b'{"a":2}\n')
            put(os.path.join(self.arch, B, "tool-results", "t1.txt"), b"tool\n")
        planned = set(RUNS)
        rows = []
        for b in (B, OTHER):
            r = {"batch_id": b, "run_id": f"{b}-R7", "tier": "S", "hub_batch": False,
                 "labels": list(LABELS) if b == B else [f"lab-{b}-x"], "checklist": 1, "in_checklist": 1,
                 "weights": {"w": 1}, "predicted": {"p": 1}}
            if b == B:
                r.update(slice_sha256=ssha, r7_plan_sha256=U.fsha(ppath), legacy_dirs=[f"{B}-R2"],
                         legacy_ledger_sha256=U.legacy_digest(self.ledger, B, planned, ASM))
            rows.append(r)
        body = "\n".join(U.canon(r).decode("utf-8") for r in rows) + "\n"
        hdr = {"artifact": "_batch_manifest_p3b_r2.jsonl", "fixture": True, "slice_root": SLICE_ROOT,
               "contract": {"revision": 7, "sha256": U.ADDENDUM_SHA256, "path": U.ADDENDUM_PATH},
               "output_sha256": U.sha(body.encode("utf-8"))}
        put(self.man, U.canon({"header": hdr}).decode("utf-8") + "\n" + body)
        prev = os.path.join(self.aux, "prev.jsonl")
        T.write_jsonl(prev, [{"header": {"fixture": True}}] +
                      [{k: (f"{r['batch_id']}-R2" if k == "run_id" else r.get(k)) for k in stm.COMPOSITION_KEYS}
                       for r in rows])
        stm.init(prev, self.state_path)
        st = stm.load(self.state_path)
        stm.revision_transition(st, self.man, prev, "G-LOG-0102")
        for s in ("DISPATCHED", "PROPOSED"):
            stm.transition(st, B, s)
        stm.transition(st, B, "FAILED", reason="BATCH-FAIL", failure_class=fail_class,
                       failure_signature=SIG if fail_class in ("X", "A1", "A2") else None)
        stm.save(st, self.state_path)
        if report:
            T.write_report(os.path.join(self.root, "audit-p3b"), f"S5-VERIFY-{ASM}.json", B, ASM, result="BATCH-FAIL")

    def stage(self):
        self.nstage += 1
        return os.path.join(self.aux, f"stage-{self.nstage}")

    def retire(self, cls="X", reason=REASON, **kw):
        return O.retire(B, cls, reason, root=self.root, archive_root=self.arch, state_path=self.state_path,
                        staging=kw.pop("staging", None) or self.stage(), **kw)

    def may(self):
        return O.may_start_attempt(B, root=self.root, archive_root=self.arch)

    def snap(self):
        return snapshot(self.root, self.arch)


class Base(unittest.TestCase):
    def setUp(self):
        p = mock.patch.object(O.ops, "utc_now", return_value=FIXED_UTC)      # deterministic state bytes
        p.start()
        self.addCleanup(p.stop)
        h = T.fake_holdout()                                                  # the quarantine scan: synthetic sets
        h.__enter__()
        self.addCleanup(h.__exit__, None, None, None)

    def world(self, **kw):
        return World(self, **kw)

    def reference(self):
        w = self.world()
        w.retire()
        return w.snap()

    def assertRefusedUnchanged(self, w, fn, regex=None, cls=None):
        before = w.snap()
        with self.assertRaises(O.Refused) as cm:
            fn()
        if regex:
            self.assertRegex(str(cm.exception), regex)
        if cls:
            self.assertEqual(getattr(cm.exception, "cls", None), cls)
        self.assertEqual(w.snap(), before)
        return cm.exception


# ------------------------------------------------------------------------------------------------ clean retirement
class CleanRetirement(Base):

    def test_clean_run_moves_everything_verifies_and_refreezes(self):
        w = self.world()
        before = w.snap()
        r = w.retire()
        self.assertEqual((r["batch"], r["attempt"], r["ledger_dirs"], r["already_complete"]), (B, 1, len(MOVED), 0))
        tgt = os.path.join(w.ledger, RET1)
        self.assertEqual(sorted(x for x in os.listdir(tgt) if not x.startswith("RETIREMENT-")), MOVED)
        for d in MOVED:
            self.assertFalse(os.path.exists(os.path.join(w.ledger, d)))
        self.assertFalse(os.path.exists(os.path.join(w.arch, B)))
        self.assertTrue(os.path.isdir(os.path.join(w.arch, f"{B}.A1")))
        # every byte moved unaltered (old path → new path), nothing lost
        after = w.snap()
        for (i, rel), data in before.items():
            parts = rel.split(os.sep)
            if i == 0 and parts[0] == U.LEDGER and parts[1] in MOVED:
                new = (0, os.path.join(U.LEDGER, RET1, *parts[1:]))
            elif i == 1 and parts[0] == B:
                new = (1, os.path.join(f"{B}.A1", *parts[1:]))
            elif rel in ("_batch_manifest_p3b_r2.jsonl", "P3B-STATE.json"):
                continue
            else:
                new = (i, rel)
            self.assertEqual(after.get(new), data, rel)
        self.assertEqual(r["ledger_files"] + r["archive_files"], r["verified_files"])
        self.assertEqual(r["archive_files"], 4)
        # the records
        intent = json.loads(rd(os.path.join(tgt, "RETIREMENT-INTENT.json")))
        done = json.loads(rd(os.path.join(tgt, "RETIREMENT-COMPLETE.json")))
        self.assertEqual((intent["batch"], intent["attempt"], intent["cause_class"], intent["reason"]), (B, 1, "X", REASON))
        self.assertEqual(intent["verify_report"]["sha256"], U.fsha(os.path.join(w.root, "audit-p3b", f"S5-VERIFY-{ASM}.json")))
        self.assertEqual(len(intent["ledger_files"]), r["ledger_files"])
        for f in intent["ledger_files"] + intent["archive_files"]:
            self.assertEqual(set(f), {"old", "new", "sha256"})
        self.assertEqual(len(intent["transcript_sha256"]), 3)                   # main + 2 subagents
        self.assertEqual((done["state"], done["batch"], done["attempt"]), ("COMPLETE", B, 1))
        self.assertEqual(done["intent_sha256"], U.fsha(os.path.join(tgt, "RETIREMENT-INTENT.json")))
        self.assertEqual(r["complete_sha256"], U.fsha(os.path.join(tgt, "RETIREMENT-COMPLETE.json")))
        # the state's own check accepts the record
        ref = {"path": os.path.join(tgt, "RETIREMENT-COMPLETE.json"), "sha256": r["complete_sha256"]}
        self.assertEqual(stm.check_retirement(B, 1, ref)["sha256"], r["complete_sha256"])

    def test_manifest_refrozen_through_rebind_manifest(self):
        w = self.world()
        old_lines = rd(w.man).decode("utf-8").split("\n")
        st0 = stm.load(w.state_path)
        r = w.retire()
        new_lines = rd(w.man).decode("utf-8").split("\n")
        e = manifest_entry(w.man, B)
        self.assertEqual(e["legacy_dirs"], sorted([f"{B}-R2", RET1]))
        self.assertEqual(e["legacy_ledger_sha256"], U.legacy_digest(w.ledger, B, set(RUNS), ASM))
        self.assertEqual(e["legacy_ledger_sha256"], r["legacy_ledger_sha256"])
        old_e = manifest_entry(os.path.join(w.aux, "prev.jsonl"), B)                # composition unchanged
        self.assertEqual({k: e.get(k) for k in stm.COMPOSITION_KEYS if k != "run_id"},
                         {k: old_e.get(k) for k in stm.COMPOSITION_KEYS if k != "run_id"})
        changed = [i for i, (a, b) in enumerate(zip(old_lines, new_lines)) if a != b]
        self.assertEqual(len(old_lines), len(new_lines))
        self.assertEqual(len(changed), 2)                                           # the header + this batch's entry only
        hdr = json.loads(new_lines[0])["header"]
        self.assertEqual(hdr["output_sha256"], U.sha("\n".join(new_lines[1:]).encode("utf-8")))
        self.assertEqual(hdr["r7_legacy_refreezes"][-1]["authority"], REASON)
        self.assertEqual(U.revision_violations(hdr), [])
        st = stm.load(w.state_path)
        self.assertEqual(st["manifest_sha256"], U.fsha(w.man))
        self.assertEqual(st["batches"], st0["batches"])                              # no batch changed by retire
        self.assertEqual(st["history"][:len(st0["history"])], st0["history"])
        self.assertEqual([x["run_kind"] for x in st["history"][len(st0["history"]):]], ["MANIFEST-REVISION"])
        self.assertEqual(st["manifest_revisions"][-1]["reason"], REASON)
        self.assertEqual(U.namespace_violations(w.ledger, B, set(RUNS), e["legacy_ledger_sha256"], e["legacy_dirs"]), [])

    def test_rerun_after_completion_is_idempotent(self):
        w = self.world()
        r1 = w.retire()
        s1 = w.snap()
        r2 = w.retire()
        self.assertEqual(w.snap(), s1)
        self.assertEqual((r2["already_complete"], r2["manifest_written"], r2["state_saved"]), (1, 0, 0))
        self.assertEqual((r1["manifest_written"], r1["state_saved"]), (1, 1))
        steps = ("already_complete", "resumed", "manifest_written", "state_saved")
        self.assertEqual({k: v for k, v in r2.items() if k not in steps}, {k: v for k, v in r1.items() if k not in steps})

    def test_the_tool_never_deletes(self):
        w = self.world()

        def forbid(*a, **k):
            raise AssertionError("retire must never delete")
        with mock.patch.object(os, "remove", forbid), mock.patch.object(os, "unlink", forbid), \
                mock.patch.object(os, "rmdir", forbid), mock.patch.object(os, "removedirs", forbid), \
                mock.patch.object(shutil, "rmtree", forbid):
            w.retire()
        self.assertTrue(os.path.isfile(os.path.join(w.ledger, f"{OTHER}-R7-L01", "objects.jsonl")))

    def test_counts_only_on_the_cli(self):
        w = self.world()
        out, err = io.StringIO(), io.StringIO()
        with redirect_stdout(out), redirect_stderr(err):
            rc = O.main(["retire", B, "--cause-class", "X", "--reason", REASON, "--root", w.root, "--archive", w.arch,
                         "--state", w.state_path, "--staging", w.stage()])
        self.assertEqual(rc, 0, err.getvalue())
        text = out.getvalue()
        self.assertIn(f"S5-ORCH retire {B}:", text)
        for lab in LABELS:
            self.assertNotIn(lab, text)
        self.assertNotRegex(text, r"S\d{4}")
        self.assertTrue(os.path.isfile(os.path.join(w.ledger, RET1, "RETIREMENT-COMPLETE.json")))
        with redirect_stdout(io.StringIO()), redirect_stderr(err):              # a missing --reason is refused
            self.assertNotEqual(O.main(["retire", B, "--cause-class", "X", "--root", w.root, "--archive", w.arch,
                                        "--state", w.state_path]), 0)


# ------------------------------------------------------------------------------- T133-analogue: crash at every step
class CrashResume(Base):

    def crash_points(self):
        w = self.world()
        seen = []
        with mock.patch.object(O, "_checkpoint", side_effect=seen.append):
            w.retire()
        return seen

    def test_checkpoints_cover_every_step(self):
        pts = self.crash_points()
        for step in ("S1", "S3", "S4", "S5", "S6", "REFREEZE-STATE"):
            self.assertIn(step, pts)
        self.assertEqual(sorted(p for p in pts if p.startswith("S2:")), [f"S2:{d}" for d in MOVED])

    def test_crash_at_every_step_blocks_then_resumes_byte_identical(self):
        ref = self.reference()
        pts = self.crash_points()
        self.assertGreaterEqual(len(pts), 11)
        for p in pts:
            with self.subTest(crash=p):
                w = self.world()

                def boom(name, _p=p):
                    if name == _p:
                        raise Crash(name)
                with mock.patch.object(O, "_checkpoint", side_effect=boom):
                    with self.assertRaises(Crash):
                        w.retire()
                ok, why = w.may()
                self.assertFalse(ok, f"{p}: attempt 2 must be blocked ({why})")
                r = w.retire()                                                     # resume
                self.assertEqual(r["already_complete"], 1 if p in ("S6", "REFREEZE-STATE") else 0)
                self.assertEqual(w.snap(), ref, p)
                self.assertTrue(w.may()[0])
                w.retire()                                                         # idempotent
                self.assertEqual(w.snap(), ref, p)

    def test_stale_tmp_records_are_overwritten_not_trusted(self):
        ref = self.reference()
        w = self.world()                                     # a crash inside S1's put_once: only the tmp exists
        put(os.path.join(w.ledger, RET1, "RETIREMENT-INTENT.json.tmp"), b"partial")
        self.assertTrue(w.may()[0] is False)
        w.retire()
        self.assertEqual(w.snap(), ref)
        w = self.world()                                     # a crash inside S6's put_once
        with mock.patch.object(O, "_checkpoint", side_effect=lambda n: (_ for _ in ()).throw(Crash(n)) if n == "S5" else None):
            with self.assertRaises(Crash):
                w.retire()
        put(os.path.join(w.ledger, RET1, "RETIREMENT-COMPLETE.json.tmp"), b"partial")
        w.retire()
        self.assertEqual(w.snap(), ref)

    def test_resume_must_name_the_intent_class_and_reason(self):
        w = self.world()
        with mock.patch.object(O, "_checkpoint", side_effect=lambda n: (_ for _ in ()).throw(Crash(n)) if n == "S1" else None):
            with self.assertRaises(Crash):
                w.retire()
        self.assertRefusedUnchanged(w, lambda: w.retire(reason="another reason"), "reason")
        w.retire()
        self.assertEqual(w.snap(), self.reference())


# ---------------------------------------------------------------------------------------- T136: integrity refusals
class IntegrityRefusals(Base):

    def crashed(self, at):
        w = self.world()
        with mock.patch.object(O, "_checkpoint", side_effect=lambda n: (_ for _ in ()).throw(Crash(n)) if n == at else None):
            with self.assertRaises(Crash):
                w.retire()
        return w

    def test_tamper_between_intent_and_move_is_refused(self):
        w = self.crashed("S1")
        with open(os.path.join(w.ledger, RUNS[0], "objects.jsonl"), "ab") as f:
            f.write(b"tampered\n")
        e = self.assertRefusedUnchanged(w, w.retire, "S2: .*intent record; nothing moved", cls="S")   # before any rename
        self.assertFalse(w.may()[0])
        self.assertIn("class S", str(e))

    def test_tamper_after_the_move_is_refused(self):
        w = self.crashed(f"S2:{MOVED[-1]}")
        with open(os.path.join(w.ledger, RET1, RUNS[0], "objects.jsonl"), "ab") as f:
            f.write(b"tampered\n")
        self.assertRefusedUnchanged(w, w.retire, "S3", cls="S")

    def test_tamper_after_completion_is_never_refrozen(self):
        w = self.crashed("S6")
        with open(os.path.join(w.ledger, RET1, ASM, "WITNESS.jsonl"), "ab") as f:
            f.write(b"x")
        self.assertRefusedUnchanged(w, w.retire, "retired bytes", cls="S")

    def test_archive_tamper_is_refused(self):
        w = self.crashed("S3")
        with open(os.path.join(w.arch, B, "main.jsonl"), "ab") as f:
            f.write(b"x")
        self.assertRefusedUnchanged(w, w.retire, "S4: .*intent record", cls="S")
        w = self.crashed("S4")
        with open(os.path.join(w.arch, f"{B}.A1", "main.jsonl"), "ab") as f:
            f.write(b"x")
        self.assertRefusedUnchanged(w, w.retire, "S5", cls="S")

    def test_a_source_recreated_after_its_move_is_refused(self):
        w = self.crashed(f"S2:{MOVED[0]}")
        os.makedirs(os.path.join(w.ledger, MOVED[0]))                          # e.g. a late reader call
        self.assertRefusedUnchanged(w, w.retire, "both", cls="S")

    def test_evidence_lost_is_refused(self):
        w = self.crashed("S1")
        shutil.move(os.path.join(w.ledger, MOVED[1]), os.path.join(w.aux, "moved-away"))
        self.assertRefusedUnchanged(w, w.retire, "neither", cls="S")

    def test_archive_both_exist_is_refused(self):
        w = self.crashed("S3")
        os.makedirs(os.path.join(w.arch, f"{B}.A1"))
        self.assertRefusedUnchanged(w, w.retire, "both", cls="S")

    def test_a_changed_intent_record_is_refused(self):
        w = self.crashed("S5")
        ip = os.path.join(w.ledger, RET1, "RETIREMENT-INTENT.json")
        put(ip, rd(ip).replace(b'"attempt":1', b'"attempt":1,"x":1'))
        self.assertRefusedUnchanged(w, w.retire, None, cls="S")

    def test_complete_not_binding_its_intent_is_refused(self):
        w = self.crashed("S6")
        cp = os.path.join(w.ledger, RET1, "RETIREMENT-COMPLETE.json")
        doc = json.loads(rd(cp))
        doc["intent_sha256"] = "0" * 64
        put(cp, json.dumps(doc))
        self.assertRefusedUnchanged(w, w.retire, None, cls="S")


# ----------------------------------------------------------------------------- T142–T144: EXDEV and the device rule
class DeviceRule(Base):

    def exdev_for(self, victim):
        real = os.rename

        def boom(s, t, *a, **k):
            if os.path.basename(s) == victim:
                raise OSError(errno.EXDEV, "Invalid cross-device link")
            return real(s, t, *a, **k)
        return mock.patch.object(os, "rename", boom)

    def test_exdev_at_s2_refused_class_x_and_resumable(self):
        ref = self.reference()
        w = self.world()
        with self.exdev_for(MOVED[2]):
            with self.assertRaises(O.Refused) as cm:
                w.retire()
        self.assertEqual(cm.exception.cls, "X")
        self.assertIn("EXDEV", str(cm.exception))
        self.assertTrue(os.path.isdir(os.path.join(w.ledger, RET1, MOVED[0])))     # earlier moves stand
        self.assertTrue(os.path.isdir(os.path.join(w.ledger, MOVED[2])))           # the victim did not move
        self.assertFalse(w.may()[0])                                               # PENDING
        w.retire()
        self.assertEqual(w.snap(), ref)

    def test_exdev_at_s4_refused_class_x_and_resumable(self):
        ref = self.reference()
        w = self.world()
        with self.exdev_for(B):
            with self.assertRaises(O.Refused) as cm:
                w.retire()
        self.assertEqual(cm.exception.cls, "X")
        self.assertIn("S4", str(cm.exception))
        self.assertTrue(os.path.isdir(os.path.join(w.arch, B)))
        self.assertFalse(w.may()[0])
        w.retire()
        self.assertEqual(w.snap(), ref)

    def test_another_oserror_propagates_and_is_resumable(self):
        ref = self.reference()
        w = self.world()
        real = os.rename

        def boom(s, t, *a, **k):
            if os.path.basename(s) == MOVED[1]:
                raise OSError(errno.EIO, "I/O error")
            return real(s, t, *a, **k)
        with mock.patch.object(os, "rename", boom):
            with self.assertRaises(OSError) as cm:
                w.retire()
        self.assertNotIsInstance(cm.exception, O.Refused)
        w.retire()
        self.assertEqual(w.snap(), ref)

    def test_st_dev_mismatch_refused_before_any_move(self):
        ref = self.reference()
        w = self.world()
        real = O._dev
        arch_root = os.path.abspath(w.arch)
        with mock.patch.object(O, "_dev", lambda p: 999 if os.path.abspath(p) == arch_root else real(p)):
            e = self.assertRefusedUnchanged(w, w.retire, "device", cls="X")
        self.assertIn("S0", str(e))
        self.assertFalse(os.path.exists(os.path.join(w.ledger, RET1)))              # not even the intent
        w.retire()
        self.assertEqual(w.snap(), ref)

    def test_st_dev_mismatch_at_s4_refused_the_archive_not_moved(self):
        ref = self.reference()
        w = self.world()
        with mock.patch.object(O, "_checkpoint", side_effect=lambda n: (_ for _ in ()).throw(Crash(n)) if n == "S3" else None):
            with self.assertRaises(Crash):
                w.retire()
        real = O._dev
        arch_root = os.path.abspath(w.arch)
        with mock.patch.object(O, "_dev", lambda p: 999 if os.path.abspath(p) == arch_root else real(p)):
            e = self.assertRefusedUnchanged(w, w.retire, "device", cls="X")
        self.assertIn("S4", str(e))
        self.assertTrue(os.path.isdir(os.path.join(w.arch, B)))
        self.assertFalse(w.may()[0])
        w.retire()
        self.assertEqual(w.snap(), ref)

    def test_st_dev_mismatch_inside_the_ledger_refused(self):
        w = self.world()
        real = O._dev
        victim = os.path.abspath(os.path.join(w.ledger, MOVED[3]))
        with mock.patch.object(O, "_dev", lambda p: 999 if os.path.abspath(p) == victim else real(p)):
            self.assertRefusedUnchanged(w, w.retire, "device", cls="X")


# ---------------------------------------------------------------------------------------------- S0 preconditions
class Preconditions(Base):

    def test_class_must_be_rerunnable_and_equal_the_recorded_class(self):
        w = self.world(fail_class="A2")
        for cls in ("X", "A1", "D", "S", "H", "U", "", None):
            self.assertRefusedUnchanged(w, lambda c=cls: w.retire(cls=c))
        w.retire(cls="A2")

    def test_non_rerunnable_recorded_classes_are_refused(self):
        for rec in ("D", "S", "U"):
            w = self.world(fail_class=rec)
            for cls in (rec, "X"):
                self.assertRefusedUnchanged(w, lambda c=cls: w.retire(cls=c))

    def verified_world(self):
        w = self.world(report=False)
        d = os.path.join(w.aux, "fresh")
        prev = os.path.join(d, "prev.jsonl")
        os.makedirs(d)
        shutil.copy(os.path.join(w.aux, "prev.jsonl"), prev)
        os.remove(w.state_path)
        stm.init(prev, w.state_path)
        st = stm.load(w.state_path)
        stm.revision_transition(st, w.man, prev, "G-LOG-0102")
        stm.transition(st, B, "DISPATCHED")
        stm.transition(st, B, "PROPOSED")
        rep = T.write_report(os.path.join(w.root, "audit-p3b"), f"S5-VERIFY-{ASM}.json", B, ASM, result="BATCH-PASS")
        stm.transition(st, B, "VERIFIED", evidence=rep)
        stm.save(st, w.state_path)
        return w, st

    def test_a_verified_batch_is_never_retired(self):
        w, st = self.verified_world()
        self.assertRefusedUnchanged(w, w.retire)
        stm.transition(st, B, "FAILED", reason="§21 audit FAIL")                    # post-VERIFIED → class H
        self.assertEqual(st["history"][-1]["failure_class"], "H")
        stm.save(st, w.state_path)
        for cls in ("H", "X"):
            self.assertRefusedUnchanged(w, lambda c=cls: w.retire(cls=c))

    def test_an_accepted_batch_is_never_retired(self):
        w, st = self.verified_world()
        rep = T.write_report(os.path.join(w.root, "audit-p3b"), "S5-AUDITED-report.json", B, ASM, result="PASS")
        stm.transition(st, B, "AUDITED", evidence=rep)
        stm.transition(st, B, "ACCEPTED", _allow_accept=True)
        stm.save(st, w.state_path)
        self.assertRefusedUnchanged(w, w.retire)

    def test_an_empty_reason_is_refused(self):
        w = self.world()
        self.assertRefusedUnchanged(w, lambda: w.retire(reason=""))

    def test_a_reason_naming_a_holdout_label_is_refused(self):
        w = self.world()
        fake = sorted(T.FAKE_H)[0]                                                 # the synthetic hold-out set
        self.assertRefusedUnchanged(w, lambda: w.retire(reason=f"G-LOG-TEST {fake}"), "quarantine")

    def test_a_missing_or_empty_archive_is_refused(self):
        w = self.world(archive=False)
        self.assertRefusedUnchanged(w, w.retire, "archive")
        os.makedirs(os.path.join(w.arch, B))
        self.assertRefusedUnchanged(w, w.retire, "archive")

    def test_an_archive_inside_the_repository_is_refused(self):
        w = self.world()
        before = w.snap()
        with self.assertRaises(O.Refused):
            O.retire(B, "X", REASON, root=w.root, archive_root=os.path.join(O.CR, "not-an-archive"),
                     state_path=w.state_path, staging=w.stage())
        with self.assertRaises(O.Refused):
            O.retire(B, "X", REASON, root=w.root, archive_root=os.path.join(w.root, "arch"),
                     state_path=w.state_path, staging=w.stage())
        self.assertEqual(w.snap(), before)

    def test_a_target_with_foreign_content_is_refused(self):
        w = self.world()
        put(os.path.join(w.ledger, RET1, "foreign.txt"), b"x")
        self.assertRefusedUnchanged(w, w.retire, None, cls="S")

    def test_state_not_bound_to_the_manifest_is_refused(self):
        w = self.world()
        with open(w.man, "a", encoding="utf-8") as f:
            f.write("\n")
        self.assertRefusedUnchanged(w, w.retire, "manifest")

    def test_tampered_legacy_before_the_retirement_is_refused(self):
        w = self.world()
        with open(os.path.join(w.ledger, f"{B}-R2", "objects.jsonl"), "ab") as f:
            f.write(b"x")
        self.assertRefusedUnchanged(w, w.retire, "Legacy", cls="S")

    def test_another_pending_retirement_is_refused(self):
        w = self.world()
        put(os.path.join(w.ledger, f"{B}-R7.A7", "RETIREMENT-INTENT.json"), b"{}")
        # (the digest now differs as well; the PENDING record is named first)
        self.assertRefusedUnchanged(w, w.retire, "PENDING")

    def test_rr7_repeat_is_not_retired(self):
        """Follow-up item 1: attempt 2 fails with the attempt-1 signature (RR-7 → class D): not retired, evidence stays."""
        w = self.world()
        r = w.retire()
        st = stm.load(w.state_path)
        stm.new_attempt(st, B, "X", {"path": os.path.join(w.ledger, RET1, "RETIREMENT-COMPLETE.json"),
                                     "sha256": r["complete_sha256"]}, may_start=lambda b: (True, "ok"))
        for s in ("DISPATCHED", "PROPOSED"):
            stm.transition(st, B, s)
        stm.transition(st, B, "FAILED", reason="BATCH-FAIL", failure_class="X", failure_signature=SIG)
        stm.save(st, w.state_path)
        self.assertRefusedUnchanged(w, w.retire, "RR-7")

    def test_wsys_stop_is_not_retired(self):
        w = self.world()
        st = stm.load(w.state_path)
        stm._append(st, "*", None, None, "WSYS-STOP", "W-SYS stop (synthetic)", "test", kind="WSYS-STOP",
                    extra={"failure_class": "D"})
        stm.save(st, w.state_path)
        self.assertRefusedUnchanged(w, w.retire, "W-SYS")

    def test_attempt_cap_is_not_retired(self):
        w = self.world()
        with mock.patch.object(O._state_module(), "M_MAX_ATTEMPTS", 1):
            self.assertRefusedUnchanged(w, w.retire, "M = 1")
        w.retire()

    def test_resume_after_refreeze_crash_with_the_same_staging(self):
        """Follow-up item 3: staging is validated only when used; the REFREEZE-STATE resume does not use it."""
        ref = self.reference()
        w = self.world()
        stage = w.stage()
        with mock.patch.object(O, "_checkpoint",
                               side_effect=lambda n: (_ for _ in ()).throw(Crash(n)) if n == "REFREEZE-STATE" else None):
            with self.assertRaises(Crash):
                w.retire(staging=stage)
        self.assertTrue(os.path.isdir(stage))
        w.retire(staging=stage)
        self.assertEqual(w.snap(), ref)

    def test_staging_inside_the_repository_or_existing_is_refused(self):
        w = self.world()
        self.assertRefusedUnchanged(w, lambda: w.retire(staging=os.path.join(O.CR, "eg6-staging")), "staging")
        self.assertRefusedUnchanged(w, lambda: w.retire(staging=w.aux), "staging")

    def test_frozen_context_broken_is_refused(self):
        w = self.world()
        put(os.path.join(w.root, SLICE_ROOT, f"{B}.R7-PLAN.json"), b"{}")
        self.assertRefusedUnchanged(w, w.retire)


# ------------------------------------------------------------------------------------- the may_start_attempt guard
class Guard(Base):

    def done(self):
        w = self.world()
        w.retire()
        self.assertEqual(w.may(), (True, "ok"))
        return w

    def test_before_any_retirement_attempt_2_is_blocked(self):
        w = self.world()
        ok, why = w.may()
        self.assertFalse(ok)
        self.assertIn("archive", why)

    def test_pending_retirement_blocks(self):
        w = self.world()
        with mock.patch.object(O, "_checkpoint", side_effect=lambda n: (_ for _ in ()).throw(Crash(n)) if n == "S5" else None):
            with self.assertRaises(Crash):
                w.retire()
        ok, why = w.may()
        self.assertEqual((ok, "PENDING" in why), (False, True))

    def test_non_empty_archive_blocks_empty_does_not(self):
        w = self.done()
        os.makedirs(os.path.join(w.arch, B))
        self.assertTrue(w.may()[0])
        put(os.path.join(w.arch, B, "main.jsonl"), b"{}\n")
        ok, why = w.may()
        self.assertEqual((ok, "archive" in why), (False, True))

    def test_a_live_run_or_assembly_directory_blocks(self):
        for d in (RUNS[0], ASM):
            w = self.done()
            os.makedirs(os.path.join(w.ledger, d))
            ok, why = w.may()
            self.assertFalse(ok, d)
            self.assertIn("still exist", why)

    def test_legacy_digest_not_refrozen_blocks(self):
        w = self.done()
        rewrite_entry(w.man, B, legacy_ledger_sha256=EMPTY_DIGEST)
        self.assertFalse(w.may()[0])
        w = self.done()
        with open(os.path.join(w.ledger, RET1, RUNS[0], "objects.jsonl"), "ab") as f:
            f.write(b"edited")
        self.assertFalse(w.may()[0])
        w = self.done()
        rewrite_entry(w.man, B, legacy_dirs=[f"{B}-R2"])                            # unlisted retired directory
        self.assertFalse(w.may()[0])

    def test_unusable_context_fails_closed(self):
        w = self.done()
        put(os.path.join(w.root, SLICE_ROOT, f"{B}.R7-PLAN.json"), b"{}")
        self.assertFalse(w.may()[0])

    def test_the_state_cli_guard_loader_finds_it(self):
        self.assertTrue(callable(stm.load_guard()))


# ----------------------------------------------------------------------------------------------------- end to end
class EndToEnd(Base):

    def prompts(self, w):
        ctx = O._context(B, w.root)
        return {run: O.render_prompt(run, B, ctx["plan"], {"entries": []}, "/abs/p3b_read_source.py", "0" * 40, w.root,
                                     slice_text=ctx["slices"][lab])
                for run, (lab, _, _) in sorted(ctx["owners"].items())}

    def test_failed_x_retire_new_attempt_prepared_prompts_identical(self):
        w = self.world()
        p1 = self.prompts(w)
        guard = functools.partial(O.may_start_attempt, root=w.root, archive_root=w.arch)
        st = stm.load(w.state_path)
        with self.assertRaisesRegex(stm.StateError, "retirement record does not exist"):   # no retirement yet
            stm.new_attempt(st, B, "X", {"path": os.path.join(w.ledger, RET1, "RETIREMENT-COMPLETE.json"),
                                         "sha256": "0" * 64}, may_start=guard)
        self.assertFalse(guard(B)[0])
        r = w.retire()
        st = stm.load(w.state_path)
        ref = {"path": os.path.join(w.ledger, RET1, "RETIREMENT-COMPLETE.json"), "sha256": r["complete_sha256"]}
        stm.new_attempt(st, B, "X", ref, may_start=guard)
        stm.save(st, w.state_path)
        st = stm.load(w.state_path)
        self.assertEqual((st["batches"][B]["state"], st["batches"][B]["run_id"], stm.current_attempt(st, B)),
                         ("PREPARED", ASM, 2))
        self.assertEqual(st["history"][-1]["retirement"]["sha256"], r["complete_sha256"])
        self.assertEqual(self.prompts(w), p1)                                       # T-08: byte-identical prompts
        with self.assertRaises(O.Refused):                                          # attempt 2 is not retirable
            w.retire()

    def test_new_attempt_refused_while_the_manifest_is_not_refrozen(self):
        w = self.world()
        with mock.patch.object(O, "_checkpoint",
                               side_effect=lambda n: (_ for _ in ()).throw(Crash(n)) if n == "REFREEZE-STATE" else None):
            with self.assertRaises(Crash):
                w.retire()
        st = stm.load(w.state_path)
        cp = os.path.join(w.ledger, RET1, "RETIREMENT-COMPLETE.json")
        guard = functools.partial(O.may_start_attempt, root=w.root, archive_root=w.arch)
        with self.assertRaisesRegex(stm.StateError, "may_start_attempt refused"):
            stm.new_attempt(st, B, "X", {"path": cp, "sha256": U.fsha(cp)}, may_start=guard)


# ---------------------------------------------------------------- T130 / T131 through the PRODUCTION verifier
FX_STATE = FX.base()


def fixture_retire(variant=None):
    """Inside the fixture's materialized batch: retire the batch's attempt (synthetic state FAILED X), then put an
    identical attempt 2 in place (the planned run directories, the assembly and the archive), apply `variant`, and
    run the production verifier. -> (verifier body, {retire counts, guard after retire})."""
    out = {}
    OB, R7 = FX.OB, FX.R7

    def mut(st):
        def post(root, adir, info):
            arch = os.path.dirname(adir)
            aux = tempfile.mkdtemp(prefix="eg6fx-")
            try:
                man = os.path.join(root, FX.prep.MANIFEST)
                rows = [json.loads(l) for l in rd(man).decode("utf-8").split("\n")[1:] if l.strip()]
                prev, sp = os.path.join(aux, "prev.jsonl"), os.path.join(aux, "P3B-STATE.json")
                T.write_jsonl(prev, [{"header": {"fixture": True}}] +
                              [{k: (f"{r['batch_id']}-R2" if k == "run_id" else r.get(k)) for k in stm.COMPOSITION_KEYS}
                               for r in rows])
                stm.init(prev, sp)
                s = stm.load(sp)
                stm.revision_transition(s, man, prev, "G-LOG-0102")
                for x in ("DISPATCHED", "PROPOSED"):
                    stm.transition(s, OB, x)
                stm.transition(s, OB, "FAILED", reason="BATCH-FAIL", failure_class="X", failure_signature=SIG)
                stm.save(s, sp)
                ledger = os.path.join(root, U.LEDGER)
                live = sorted(d for d in os.listdir(ledger) if d == R7 or d.startswith(R7 + "-"))
                side = os.path.join(aux, "side")
                for d in live:
                    shutil.copytree(os.path.join(ledger, d), os.path.join(side, "ledger", d))
                shutil.copytree(adir, os.path.join(side, "arch"))
                with T.fake_holdout():
                    out["retire"] = O.retire(OB, "X", REASON, root=root, archive_root=arch, state_path=sp,
                                             staging=os.path.join(aux, "stage"))
                out["may"] = O.may_start_attempt(OB, root=root, archive_root=arch)
                out["live"] = len(live)
                for d in live:                                                      # attempt 2 (identical bytes)
                    shutil.copytree(os.path.join(side, "ledger", d), os.path.join(ledger, d))
                shutil.copytree(os.path.join(side, "arch"), adir)
                if variant:
                    variant(root, arch)
            finally:
                shutil.rmtree(aux, ignore_errors=True)
        st["post"] = post
    body = FX.verify(FX_STATE, mut)
    return body, out


def freeze_again(root, arch):
    """freeze_final from scratch (the fixture's own freezes removed first, as in T120). -> 'OK' or the refusal text."""
    for n in ("WITNESS.jsonl", "WITNESS-DIGESTS.json"):
        p = os.path.join(root, U.LEDGER, FX.R7, n)
        if os.path.isfile(p):
            os.remove(p)
    try:
        with T.fake_holdout():
            O.freeze_final(FX.OB, root=root, archive_root=arch, resolver_factory=FX.Fake)
        return "OK"
    except O.Refused as e:
        return str(e)


def ns_failures(body):
    return [f for f in body.get("failures") or [] if "R7-U namespace" in f]


class T130T131ProductionVerifier(unittest.TestCase):

    def test_t130_attempt_2_after_retirement_passes(self):
        body, out = fixture_retire()
        self.assertEqual(out["may"], (True, "ok"))
        self.assertGreater(out["retire"]["ledger_dirs"], 1)
        self.assertEqual(out["retire"]["ledger_dirs"], out["live"])
        self.assertEqual(ns_failures(body), [])
        self.assertEqual(body["result"], "BATCH-PASS", body.get("failures"))

    def test_t131_unlisted_not_refrozen_edited_deleted_fail_namespace(self):
        R1 = f"{FX.OB}-R7.A1"

        def unlisted(root, arch):
            rewrite_entry(os.path.join(root, FX.prep.MANIFEST), FX.OB, legacy_dirs=[])

        def not_refrozen(root, arch):
            rewrite_entry(os.path.join(root, FX.prep.MANIFEST), FX.OB, legacy_ledger_sha256=EMPTY_DIGEST)

        def edited(root, arch):
            p = os.path.join(root, U.LEDGER, R1, "RETIREMENT-INTENT.json")
            with open(p, "ab") as f:
                f.write(b" ")

        def deleted(root, arch):
            d = os.path.join(root, U.LEDGER, R1, FX.R7)
            os.remove(os.path.join(d, sorted(os.listdir(d))[0]))
        for name, fn, tag in (("unlisted", unlisted, "is neither a planned run"), ("not re-frozen", not_refrozen, "differ"),
                              ("edited", edited, "differ"), ("partly deleted", deleted, "differ")):
            with self.subTest(variant=name):
                body, _ = fixture_retire(fn)
                self.assertEqual(body["result"], "BATCH-FAIL")
                self.assertTrue(any(tag in f for f in ns_failures(body)), name)

    def test_t134_stale_session_archived_for_the_next_attempt_is_refused(self):
        seen = {}
        fixture_retire(lambda root, arch: seen.update(res=freeze_again(root, arch)))
        self.assertIn("retired transcript digest", seen["res"])

    def test_t134_fresh_session_freezes(self):
        seen = {}

        def mut(st):
            def post(root, adir, info):
                put(os.path.join(root, U.LEDGER, f"{FX.OB}-R7.A1", "RETIREMENT-INTENT.json"),
                    json.dumps({"batch": FX.OB, "attempt": 1, "transcript_sha256": ["0" * 64, "1" * 64]}))
                seen["res"] = freeze_again(root, os.path.dirname(adir))
            st["post"] = post
        FX.verify(FX_STATE, mut)
        self.assertEqual(seen["res"], "OK")

    def test_t134_unreadable_retired_intent_fails_closed(self):
        seen = {}

        def mut(st):
            def post(root, adir, info):
                put(os.path.join(root, U.LEDGER, f"{FX.OB}-R7.A1", "RETIREMENT-INTENT.json"), b"not json")
                seen["res"] = freeze_again(root, os.path.dirname(adir))
            st["post"] = post
        FX.verify(FX_STATE, mut)
        self.assertIn("retired", seen["res"])


if __name__ == "__main__":
    unittest.main()
