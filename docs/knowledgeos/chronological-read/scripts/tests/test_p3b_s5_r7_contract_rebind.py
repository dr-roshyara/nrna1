"""EG-4 (G-LOG-0105): the narrow production rebind of the R7 contract binding v2.6+AF-1 → v2.7.

Only the manifest header's contract.sha256 changes, plus an appended provenance record (r7_contract_rebinds). The 396
entry lines stay byte-identical, so body output_sha256, slices, plans, labels, strata and the Freeze 2 frame are
unchanged. The state is rebound through the existing p3b_s5_state.rebind_manifest (composition identical); every
batch must be PREPARED at revision 7 (nothing dispatched). A refusal writes nothing. All on /tmp copies.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_r7_contract_rebind
"""
import json
import os
import unittest

import p3b_s5_ops_testlib as T
import test_p3b_s5_ops_state as TS

stm = TS.stm
act = T.ops.load_script("p3b_s5_r7_activate")
U = act.U
OLD = "4916932802ca9acf68cee404aea6c0a7a725f2874df275386c64a3f7ac155e6e"


def r7_setup(old_sha=OLD, revision=7):
    """A state at R7 (via the AG-2 transition) bound to an R7 manifest whose header binds old_sha."""
    d = T.tmpdir("eg4")
    prev, st_path, man = (os.path.join(d, n) for n in ("prev.jsonl", "P3B-STATE.json", "_batch_manifest_p3b_r2.jsonl"))
    TS.rev_manifest(prev, TS.BIDS)
    stm.init(prev, st_path)
    TS.rev_manifest(man, TS.BIDS, "-R7")
    lines = open(man, encoding="utf-8").read().split("\n")
    hdr = {"artifact": "_batch_manifest_p3b_r2.jsonl", "output_sha256": U.sha(("\n".join(lines[1:])).encode()),
           "contract": {"revision": revision, "sha256": old_sha, "path": U.ADDENDUM_PATH}}
    with open(man, "w", encoding="utf-8") as f:
        f.write(U.canon({"header": hdr}).decode() + "\n" + "\n".join(lines[1:]))
    st = stm.load(st_path)
    stm.revision_transition(st, man, prev, "G-LOG-0102")
    stm.save(st, st_path)
    return d, man, st_path


def snap(*paths):
    return [open(p, "rb").read() for p in paths]


class ContractRebind(unittest.TestCase):
    def rebind(self, man, st_path, **kw):
        return act.rebind_contract("G-LOG-0105", kw.pop("staging", os.path.join(T.tmpdir("eg4-stage"), "s")),
                                   man_path=man, state_path=st_path, **kw)

    def test_valid_rebind_changes_only_the_contract_sha(self):
        d, man, st_path = r7_setup()
        old_lines = open(man, encoding="utf-8").read().split("\n")
        st0 = stm.load(st_path)
        out = self.rebind(man, st_path)
        new_lines = open(man, encoding="utf-8").read().split("\n")
        self.assertEqual(new_lines[1:], old_lines[1:])                                   # body byte-identical
        h0, h1 = json.loads(old_lines[0])["header"], json.loads(new_lines[0])["header"]
        self.assertEqual(h1["contract"], dict(h0["contract"], sha256=U.ADDENDUM_SHA256))
        self.assertEqual(h1["r7_contract_rebinds"], [{"from_sha256": OLD, "to_sha256": U.ADDENDUM_SHA256,
                                                      "authority": "G-LOG-0105"}])
        self.assertEqual({k: v for k, v in h1.items() if k not in ("contract", "r7_contract_rebinds")},
                         {k: v for k, v in h0.items() if k != "contract"})                # no other header field
        self.assertEqual(U.revision_violations(h1), [])
        st = stm.load(st_path)
        self.assertEqual(st["manifest_sha256"], U.fsha(man))
        self.assertEqual(st["batches"], st0["batches"])                                   # no batch changes
        self.assertEqual(st["history"][:len(st0["history"])], st0["history"])            # append-only
        self.assertEqual([e["run_kind"] for e in st["history"][len(st0["history"]):]], ["MANIFEST-REVISION"])
        self.assertEqual(st["manifest_revisions"][-1]["reason"], "G-LOG-0105")
        stm.verify_chain(st)
        self.assertEqual(out["body_output_sha256_unchanged"], True)

    def assertRefusedUnchanged(self, man, st_path, **kw):
        before = snap(man, st_path)
        with self.assertRaises(SystemExit):
            self.rebind(man, st_path, **kw)
        self.assertEqual(snap(man, st_path), before)

    def test_a_dispatched_batch_refuses(self):
        d, man, st_path = r7_setup()
        st = stm.load(st_path)
        stm.transition(st, TS.BIDS[1], "DISPATCHED")
        stm.save(st, st_path)
        self.assertRefusedUnchanged(man, st_path)

    def test_already_bound_is_refused(self):
        d, man, st_path = r7_setup(old_sha=U.ADDENDUM_SHA256)
        self.assertRefusedUnchanged(man, st_path)

    def test_a_non_r7_manifest_is_refused(self):
        d, man, st_path = r7_setup(revision=6)
        self.assertRefusedUnchanged(man, st_path)

    def test_a_target_other_than_the_frozen_addendum_is_refused(self):
        d, man, st_path = r7_setup()
        self.assertRefusedUnchanged(man, st_path, new_sha="0" * 64)

    def test_state_not_bound_to_the_manifest_is_refused(self):
        d, man, st_path = r7_setup()
        with open(man, "a", encoding="utf-8") as f:
            f.write("\n")
        self.assertRefusedUnchanged(man, st_path)

    def test_staging_inside_the_repository_or_existing_is_refused(self):
        d, man, st_path = r7_setup()
        self.assertRefusedUnchanged(man, st_path, staging=os.path.join(act.CR, "eg4-staging"))
        existing = T.tmpdir("eg4-exists")
        self.assertRefusedUnchanged(man, st_path, staging=existing)


class FreshTargetRebind(unittest.TestCase):
    """EG-5 package review D-02: the mechanism must rebind a manifest bound to the CURRENT addendum (v2.7) to a
    FRESHLY computed target sha (a v2.8-like addendum), not only the one historical v2.6+AF-1 transition."""

    def test_v27_to_fresh_target(self):
        cur = U.ADDENDUM_SHA256
        d, man, st_path = r7_setup(old_sha=cur)
        croot = T.tmpdir("eg4-fresh-cr")
        text = open(os.path.join(act.CR, U.ADDENDUM_PATH), "rb").read() + b"\n<!-- synthetic v2.8-like amendment -->\n"
        os.makedirs(os.path.dirname(os.path.join(croot, U.ADDENDUM_PATH)), exist_ok=True)
        with open(os.path.join(croot, U.ADDENDUM_PATH), "wb") as f:
            f.write(text)
        fresh = U.sha(text)
        self.assertNotEqual(fresh, cur)
        saved = (act.CR, U.ADDENDUM_SHA256)
        try:
            act.CR, U.ADDENDUM_SHA256 = croot, fresh                  # the frozen addendum is now the fresh target
            old_lines = open(man, encoding="utf-8").read().split("\n")
            out = act.rebind_contract("G-LOG-TEST", os.path.join(T.tmpdir("eg4-fresh-stage"), "s"),
                                      man_path=man, state_path=st_path)
            new_lines = open(man, encoding="utf-8").read().split("\n")
            h1 = json.loads(new_lines[0])["header"]
            self.assertEqual((out["from_contract_sha256"], out["to_contract_sha256"]), (cur, fresh))
            self.assertEqual(new_lines[1:], old_lines[1:])
            self.assertEqual(h1["contract"]["sha256"], fresh)
            self.assertEqual(h1["r7_contract_rebinds"][-1], {"from_sha256": cur, "to_sha256": fresh, "authority": "G-LOG-TEST"})
            self.assertEqual(stm.load(st_path)["manifest_sha256"], U.fsha(man))
            with self.assertRaises(SystemExit):                        # a second rebind to the same target is refused
                act.rebind_contract("G-LOG-TEST", os.path.join(T.tmpdir("eg4-fresh-stage2"), "s"),
                                    man_path=man, state_path=st_path)
        finally:
            act.CR, U.ADDENDUM_SHA256 = saved


if __name__ == "__main__":
    unittest.main()
