"""Tests: P3B-STATE.json management (scripts/p3b_s5_state.py) and the H-06 acceptance recorder
(scripts/p3b_s5_accept.py). All on /tmp copies."""
import io
import json
import os
import unittest
from contextlib import redirect_stdout, redirect_stderr

import p3b_s5_ops_testlib as T

stm = T.ops.load_script("p3b_s5_state")
acc = T.ops.load_script("p3b_s5_accept")
c = T.c
BIDS = ["OB0004", "OB0005", "OB0006"]


def fresh():
    d = T.tmpdir("state")
    man, st = os.path.join(d, "_batch_manifest_p3b_r2.jsonl"), os.path.join(d, "P3B-STATE.json")
    T.write_manifest(man, BIDS)
    stm.init(man, st)
    return d, man, st


def evidence(st_path, batch, to, **kw):
    run = stm.load(st_path)["batches"][batch]["run_id"]
    return T.write_report(os.path.join(os.path.dirname(st_path), "reports"), f"{run}-{to}.json", batch, run, **kw)


def walk(st_path, batch, states):
    st = stm.load(st_path)
    for s in states:
        ev = evidence(st_path, batch, s) if s in stm.EVIDENCE_REQUIRED else None
        stm.transition(st, batch, s, reason="cause" if s in ("FAILED", "INCOMPLETE") else "", evidence=ev)
    stm.save(st, st_path)
    return st


def rev_manifest(path, bids, suffix="-R2", mut=None):
    rows = [{"batch_id": b, "run_id": f"{b}{suffix}", "tier": "S", "hub_batch": False, "labels": [f"lab-{b}-{i}" for i in range(3)],
             "checklist": 1, "in_checklist": 1, "weights": {"w": 1}, "predicted": {"p": 1}} for b in bids]
    if mut:
        mut(rows)
    T.write_jsonl(path, [{"header": {"artifact": "_batch_manifest_p3b_r2.jsonl", "fixture": True}}] + rows)


class RevisionTransition(unittest.TestCase):
    """AG-2 (G-LOG-0102): the governed R2→R7 execution-identity transition. Only run_id changes (B-R2 → B-R7); the
    composition otherwise identical; only PREPARED/FAILED batches; R2 history preserved; not a general remap."""

    def setUp(self):
        self.d = T.tmpdir("revtrans")
        self.prev, self.st_path = os.path.join(self.d, "prev.jsonl"), os.path.join(self.d, "P3B-STATE.json")
        rev_manifest(self.prev, BIDS)
        stm.init(self.prev, self.st_path)
        walk(self.st_path, "OB0004", ["DISPATCHED", "PROPOSED", "FAILED"])      # a historical R2 failure
        self.new = os.path.join(self.d, "new.jsonl")
        rev_manifest(self.new, BIDS, "-R7")

    def test_valid_complete_transition_preserves_r2_history(self):
        st = stm.load(self.st_path)
        n = len(st["history"])
        stm.revision_transition(st, self.new, self.prev, "G-LOG-0102")
        stm.save(st, self.st_path)
        st = stm.load(self.st_path)
        self.assertEqual(st["manifest_sha256"], stm.ops.sha256_path(self.new))
        for b in BIDS:
            B = st["batches"][b]
            self.assertEqual((B["run_id"], B["state"], B["revision"]), (f"{b}-R7", "PREPARED", 7))
            self.assertEqual(B["runs"][-1], {"run_id": f"{b}-R7", "state": "PREPARED", "revision": 7})
        self.assertEqual([r["state"] for r in st["batches"]["OB0004"]["runs"][:-1]], ["FAILED"])   # R2 failure kept
        self.assertEqual(st["batches"]["OB0005"]["runs"][0]["run_id"], "OB0005-R2")
        kinds = [e["run_kind"] for e in st["history"][n:]]
        self.assertEqual(kinds, ["REVISION-TRANSITION"] * len(BIDS) + ["MANIFEST-REVISION"])
        self.assertEqual(st["manifest_revisions"][-1]["reason"], "G-LOG-0102")
        self.assertEqual(st["manifest_revisions"][-1]["kind"], "REVISION-TRANSITION R2→R7")
        stm.verify_chain(st)                                                     # the hash chain stays valid

    def refused(self, new_mut=None, prev_mut=None, state_mut=None, reason="G-LOG-0102"):
        if prev_mut:
            rev_manifest(self.prev, BIDS, mut=prev_mut)
        rev_manifest(self.new, BIDS, "-R7", mut=new_mut)
        st = stm.load(self.st_path)
        if state_mut:
            state_mut(st)
        before = json.dumps(st, sort_keys=True)
        with self.assertRaises(stm.StateError):
            stm.revision_transition(st, self.new, self.prev, reason)
        self.assertEqual(json.dumps(st, sort_keys=True), before, "a refused transition changes nothing")

    def test_refuses_running_or_accepted_batches(self):
        for s in ("DISPATCHED", "PROPOSED", "VERIFIED", "AUDITED", "ACCEPTED", "INCOMPLETE"):
            self.refused(state_mut=lambda st, s=s: st["batches"]["OB0005"].update(state=s))

    def test_refuses_any_change_other_than_run_id(self):
        for k, v in (("tier", "T"), ("hub_batch", True), ("labels", ["x"]), ("checklist", 9), ("weights", {}), ("predicted", {})):
            self.refused(new_mut=lambda rows, k=k, v=v: rows[1].update({k: v}))

    def test_refuses_wrong_old_or_new_run_id(self):
        self.refused(prev_mut=lambda rows: rows[0].update(run_id="OB0004-R2.2"))
        self.refused(new_mut=lambda rows: rows[0].update(run_id="OB0004-R8"))
        self.refused(new_mut=lambda rows: rows[0].update(run_id="OB0005-R7"))

    def test_refuses_missing_added_or_reordered_batches(self):
        self.refused(new_mut=lambda rows: rows.pop())
        self.refused(new_mut=lambda rows: rows.append(dict(rows[0], batch_id="OB0099", run_id="OB0099-R7")))
        self.refused(new_mut=lambda rows: rows.reverse())

    def test_refuses_without_reason_or_wrong_previous(self):
        self.refused(reason="")
        st = stm.load(self.st_path)
        with self.assertRaises(stm.StateError):
            stm.revision_transition(st, self.new, self.new, "G-LOG-0102")

    def test_refuses_open_comparison_runs(self):
        self.refused(state_mut=lambda st: st["comparison_runs"].update({"OB0005": {"run_id": "OB0005-R2S", "state": "PREPARED"}}))

    def test_not_repeatable(self):
        st = stm.load(self.st_path)
        stm.revision_transition(st, self.new, self.prev, "G-LOG-0102")
        with self.assertRaises(stm.StateError):
            stm.revision_transition(st, self.new, self.new, "G-LOG-0102")          # already R7 / previous not bound

    def test_no_r2_style_rerun_of_an_r7_batch(self):
        st = stm.load(self.st_path)
        stm.revision_transition(st, self.new, self.prev, "G-LOG-0102")
        walk_st = st
        stm.transition(walk_st, "OB0005", "DISPATCHED")
        stm.transition(walk_st, "OB0005", "INCOMPLETE", reason="interrupted")
        with self.assertRaises(stm.StateError):
            stm.new_run(walk_st, "OB0005", "retry")                              # R7 re-run naming needs a human act

    def test_resume_after_transition(self):
        st = stm.load(self.st_path)
        stm.revision_transition(st, self.new, self.prev, "G-LOG-0102")
        self.assertEqual(stm.resume(st, self.new)["counts"]["PREPARED"], 3)


class State(unittest.TestCase):
    def test_manifest_rebind_requires_identical_composition(self):
        """§26 contract revision (G-LOG-0048): rebind only to a manifest with the identical batch composition, from the
        manifest the state is bound to; recorded in the append-only history; the run history is kept."""
        d, man, st_path = fresh()
        walk(st_path, "OB0004", ["DISPATCHED", "PROPOSED", "FAILED"])
        prev = os.path.join(d, "previous.jsonl")
        with open(man) as f, open(prev, "w") as g:
            g.write(f.read())
        new = os.path.join(d, "new.jsonl")
        T.write_jsonl(new, [{"header": {"artifact": "_batch_manifest_p3b_r2.jsonl", "fixture": True, "revision": 2}}] +
                      [{"batch_id": b, "labels": [f"lab-{b}-{i}" for i in range(3)], "contract_sha256": "x" * 64} for b in BIDS])
        st = stm.load(st_path)
        n_hist = len(st["history"])
        with self.assertRaises(stm.StateError):
            stm.rebind_manifest(st, new, new, "G-LOG-0048")                       # wrong previous manifest
        changed = os.path.join(d, "changed.jsonl")
        T.write_jsonl(changed, [{"header": {}}] + [{"batch_id": b, "labels": [f"lab-{b}-{i}" for i in range(2)]} for b in BIDS])
        with self.assertRaises(stm.StateError):
            stm.rebind_manifest(st, changed, prev, "G-LOG-0048")                  # composition differs
        with self.assertRaises(stm.StateError):
            stm.rebind_manifest(st, new, prev, "")                                # no recorded reason
        stm.rebind_manifest(st, new, prev, "G-LOG-0048")
        stm.save(st, st_path)
        st = stm.load(st_path)
        self.assertEqual(st["manifest_sha256"], stm.ops.sha256_path(new))
        self.assertEqual(st["history"][-1]["run_kind"], "MANIFEST-REVISION")
        self.assertEqual(len(st["history"]), n_hist + 1)
        self.assertEqual(st["batches"]["OB0004"]["state"], "FAILED")               # the failed run stays recorded
        stm.new_run(st, "OB0004", "contract revision 2 (G-LOG-0048)")
        self.assertEqual(st["batches"]["OB0004"]["run_id"], "OB0004-R2.2")
        self.assertEqual(stm.resume(st, new)["counts"]["PREPARED"], 3)

    def test_init_prepared_and_refuse_reinit(self):
        _, man, st = fresh()
        s = stm.load(st)
        self.assertEqual({b: e["state"] for b, e in s["batches"].items()}, {b: "PREPARED" for b in BIDS})
        self.assertEqual(s["batches"]["OB0004"]["run_id"], "OB0004-R2")
        with self.assertRaises(c.S5Error):
            stm.init(man, st)

    def test_valid_path_and_invalid_transitions(self):
        _, _, st = fresh()
        walk(st, "OB0004", ["DISPATCHED", "PROPOSED", "VERIFIED", "AUDITED"])
        s = stm.load(st)
        self.assertEqual(s["batches"]["OB0004"]["state"], "AUDITED")
        for frm, to in (("OB0005", "VERIFIED"), ("OB0005", "PROPOSED"), ("OB0005", "AUDITED")):
            with self.assertRaises(c.S5Error):
                stm.transition(s, frm, to)
        with self.assertRaises(c.S5Error):
            stm.transition(s, "OB0004", "ACCEPTED")                # only the acceptance recorder
        with self.assertRaises(c.S5Error):
            stm.transition(s, "OB0004", "FAILED")                  # cause required
        with self.assertRaises(c.S5Error):
            stm.transition(s, "OB9999", "DISPATCHED")

    def test_failed_rerun_ids(self):
        _, _, st = fresh()
        walk(st, "OB0004", ["DISPATCHED", "PROPOSED", "FAILED"])
        s = stm.load(st)
        stm.new_run(s, "OB0004", "G-04 failed")
        self.assertEqual(s["batches"]["OB0004"]["run_id"], "OB0004-R2.2")
        stm.save(s, st)
        walk(st, "OB0004", ["DISPATCHED", "INCOMPLETE"])
        s = stm.load(st)
        stm.new_run(s, "OB0004", "session interrupted")
        self.assertEqual(s["batches"]["OB0004"]["run_id"], "OB0004-R2.3")
        self.assertEqual([r["state"] for r in s["batches"]["OB0004"]["runs"]], ["FAILED", "INCOMPLETE", "PREPARED"])
        with self.assertRaises(c.S5Error):
            stm.new_run(s, "OB0005", "not failed")

    def test_interrupted_goes_incomplete_then_new_run(self):
        _, _, st = fresh()
        walk(st, "OB0005", ["DISPATCHED", "INCOMPLETE"])
        s = stm.load(st)
        stm.new_run(s, "OB0005", "interrupted")
        self.assertEqual(s["batches"]["OB0005"]["run_id"], "OB0005-R2.2")

    def test_resume_from_state_and_manifest_only(self):
        _, man, st = fresh()
        walk(st, "OB0004", ["DISPATCHED", "PROPOSED"])
        r = stm.resume(stm.load(st), man)
        self.assertEqual(r["counts"], {"PROPOSED": 1, "PREPARED": 2})
        self.assertEqual(r["batches"][0]["next"], "run verifier")
        with open(man, "a") as f:
            f.write(json.dumps({"batch_id": "OB0007"}) + "\n")
        with self.assertRaises(c.S5Error):
            stm.resume(stm.load(st), man)

    def test_history_append_only_and_tamper_evident(self):
        _, _, st = fresh()
        walk(st, "OB0004", ["DISPATCHED"])
        s = stm.load(st)
        s["history"] = s["history"][:-1]                          # rewrite history
        with self.assertRaises(c.S5Error):
            stm.save(s, st)
        raw = json.loads(T.read(st))
        raw["history"][1]["to"] = "ACCEPTED"
        with open(st, "w") as f:
            json.dump(raw, f)
        with self.assertRaises(c.S5Error):
            stm.load(st)

    def test_comparison_runs_never_accepted(self):
        d, _, st = fresh()
        s = stm.load(st)
        with self.assertRaises(c.S5Error):
            stm.add_comparison(s, "OB0004")                       # primary not accepted yet
        s["batches"]["OB0006"]["state"] = "ACCEPTED"              # direct set, only to reach the precondition
        stm.add_comparison(s, "OB0006")
        self.assertEqual(s["comparison_runs"]["OB0006"]["run_id"], "OB0006-R2S")
        stm.comparison_transition(s, "OB0006", "DISPATCHED")
        stm.comparison_transition(s, "OB0006", "PROPOSED")
        primary_ev = T.write_report(d, "p.json", "OB0006", "OB0006-R2S", comparison=False)
        with self.assertRaises(c.S5Error):
            stm.comparison_transition(s, "OB0006", "VERIFIED", evidence=primary_ev)   # comparison flag must match
        for to in ("VERIFIED", "AUDITED"):
            stm.comparison_transition(s, "OB0006", to,
                                      evidence=T.write_report(d, f"c-{to}.json", "OB0006", "OB0006-R2S", comparison=True))
        with self.assertRaises(c.S5Error):
            stm.comparison_transition(s, "OB0006", "ACCEPTED")

    def test_verified_and_audited_require_evidence(self):
        d, _, st = fresh()
        walk(st, "OB0004", ["DISPATCHED", "PROPOSED"])
        s = stm.load(st)
        bad = [None, T.write_report(d, "fail.json", "OB0004", "OB0004-R2", result="FAIL"),
               T.write_report(d, "other.json", "OB0005", "OB0005-R2"),
               T.write_report(d, "oldrun.json", "OB0004", "OB0004-R2.2"),
               T.write_report(d, "nohdr.json", "OB0004", "OB0004-R2", header=False),
               T.write_report(d, "handmade.json", "OB0004", "OB0004-R2", script="fixture"),        # not the verifier
               T.write_report(d, "badhash.json", "OB0004", "OB0004-R2", bad_hash=True),           # header ≠ body
               T.write_report(d, "audit-as-verify.json", "OB0004", "OB0004-R2", script="scripts/p3b_s5_audit_record.py"),
               os.path.join(c.CR, c.FORBIDDEN[4])]
        for ev in bad:
            with self.assertRaises(c.S5Error):
                stm.transition(s, "OB0004", "VERIFIED", evidence=ev)
        self.assertEqual(s["batches"]["OB0004"]["state"], "PROPOSED")
        ok = T.write_report(d, "ok.json", "OB0004", "OB0004-R2")
        stm.transition(s, "OB0004", "VERIFIED", evidence=ok)
        ev = s["history"][-1]["evidence"]
        self.assertEqual(ev["sha256"], T.ops.sha256_path(ok))
        self.assertTrue(ev["path"].startswith("<unlisted path #"))     # an outside-repository path is not stored
        with self.assertRaises(c.S5Error):
            stm.transition(s, "OB0004", "AUDITED")

    def test_deterministic_structure(self):
        a, b = fresh(), fresh()
        strip = lambda s: {k: v for k, v in stm.load(s).items() if k not in ("history", "history_head_sha256")}
        self.assertEqual(strip(a[2]), strip(b[2]))

    def test_cli_refuses_when_not_sealed(self):
        _, _, st = fresh()
        with T.seal_state("UNSEALED"), redirect_stderr(io.StringIO()), redirect_stdout(io.StringIO()):
            self.assertEqual(stm.main(["--state", st, "transition", "OB0004", "DISPATCHED"]), 1)
        self.assertEqual(stm.load(st)["batches"]["OB0004"]["state"], "PREPARED")


class Accept(unittest.TestCase):
    def setUp(self):
        self.d, self.man, self.st = fresh()
        self.out = os.path.join(self.d, "S5-ACCEPTANCE.jsonl")

    def rec(self, batch="OB0004", outcome="ACCEPTED", decided_by="Dr. N. R. Roshyara", decision="accepted as verified",
            ref="G-LOG-9999"):
        return acc.record(batch, outcome, decision, decided_by, ref, self.st, self.out)

    def test_refuses_without_evidence_entries(self):
        walk(self.st, "OB0004", ["DISPATCHED", "PROPOSED", "VERIFIED", "AUDITED"])
        raw = json.loads(T.read(self.st))
        for e in raw["history"]:
            e.pop("evidence", None)
        # re-chain so only the missing evidence is at fault
        prev = None
        for e in raw["history"]:
            e["prev_sha256"] = prev
            e["entry_sha256"] = T.ops.canon_hash({k: v for k, v in e.items() if k != "entry_sha256"})
            prev = e["entry_sha256"]
        with open(self.st, "w") as f:
            json.dump(raw, f)
        with self.assertRaises(c.S5Error):
            self.rec()

    def test_refuses_unless_verified_and_audited(self):
        for states in ([], ["DISPATCHED"], ["DISPATCHED", "PROPOSED"], ["DISPATCHED", "PROPOSED", "VERIFIED"]):
            d, man, st = fresh()
            walk(st, "OB0004", states)
            with self.assertRaises(c.S5Error):
                acc.record("OB0004", "ACCEPTED", "x", "Human Name", "G-LOG-1", st, os.path.join(d, "A.jsonl"))
            self.assertFalse(os.path.exists(os.path.join(d, "A.jsonl")))

    def test_requires_explicit_human_decision(self):
        walk(self.st, "OB0004", ["DISPATCHED", "PROPOSED", "VERIFIED", "AUDITED"])
        for kw in ({"decision": ""}, {"decided_by": ""}, {"ref": " "}, {"decided_by": "Claude"},
                   {"decided_by": "orchestrator"}, {"outcome": "AUTO"}):
            with self.assertRaises(c.S5Error):
                self.rec(**kw)
        with redirect_stderr(io.StringIO()), self.assertRaises(SystemExit):
            acc.main(["--batch", "OB0004", "--decided-by", "Human", "--reference", "R", "--outcome", "ACCEPTED",
                      "--state", self.st, "--out", self.out])     # no --decision
        self.assertFalse(os.path.exists(self.out))

    def test_accept_records_and_transitions_once(self):
        walk(self.st, "OB0004", ["DISPATCHED", "PROPOSED", "VERIFIED", "AUDITED"])
        r = self.rec()
        self.assertEqual(stm.load(self.st)["batches"]["OB0004"]["state"], "ACCEPTED")
        self.assertEqual(T.read_jsonl(self.out)[0]["run_id"], "OB0004-R2")
        self.assertEqual(r["state_entry_sha256"], stm.load(self.st)["history"][-1]["entry_sha256"])
        with self.assertRaises(c.S5Error):
            self.rec()

    def test_not_accepted_fails_batch(self):
        walk(self.st, "OB0005", ["DISPATCHED", "PROPOSED", "VERIFIED", "AUDITED"])
        self.rec(batch="OB0005", outcome="NOT-ACCEPTED", decision="protocol violation found in audit")
        self.assertEqual(stm.load(self.st)["batches"]["OB0005"]["state"], "FAILED")

    def test_accept_refused_when_not_sealed(self):
        walk(self.st, "OB0004", ["DISPATCHED", "PROPOSED", "VERIFIED", "AUDITED"])
        with T.seal_state("UNSEALED"), redirect_stderr(io.StringIO()), redirect_stdout(io.StringIO()):
            rc = acc.main(["--batch", "OB0004", "--outcome", "ACCEPTED", "--decision", "ok", "--decided-by", "Human",
                           "--reference", "G-LOG-1", "--state", self.st, "--out", self.out])
        self.assertEqual(rc, 1)
        self.assertFalse(os.path.exists(self.out))


if __name__ == "__main__":
    unittest.main()
