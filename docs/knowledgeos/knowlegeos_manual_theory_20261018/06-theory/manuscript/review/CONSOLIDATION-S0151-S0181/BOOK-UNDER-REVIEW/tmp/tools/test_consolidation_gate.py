"""Tests for consolidation_gate.py (Rulebook v1.4 §12). Run: python3 -I tmp/tools/test_consolidation_gate.py"""
import sys, pathlib, tempfile, unittest
sys.path.insert(0, str(pathlib.Path(__file__).resolve().parent))
import consolidation_gate as g

ORDER = {f'S{n:04d}': n - 1 for n in range(1, 400)}


def row(sid, depth='full'):
    return ['2026-10-24T00:00:00Z', sid, 'p', 'h', '1', depth, 'purpose', 'note']


def reads(a, b, depth='full'):
    return [row(f'S{n:04d}', depth) for n in range(a, b + 1)]


class Gate(unittest.TestCase):
    def setUp(self):
        self.tmp = tempfile.TemporaryDirectory()
        self.review = pathlib.Path(self.tmp.name)

    def tearDown(self):
        self.tmp.cleanup()

    def folder(self, name, settled=True, stage1=False):
        d = self.review / name
        d.mkdir()
        if settled:
            (d / 'SETTLEMENT.md').write_text('x')
        if stage1:
            (d / 'SETTLEMENT-STAGE1.md').write_text('x')

    def test_24_allowed_25_refused(self):
        self.folder('CONSOLIDATION-S0131-S0150')
        self.assertFalse(g.check(reads(1, 174), -1, self.review, ORDER)[0])
        due, msg = g.check(reads(1, 175), -1, self.review, ORDER)
        self.assertTrue(due)
        self.assertIn('25 of 25', msg)
        self.assertIn('S0150', msg)

    def test_stage1_only_does_not_count(self):
        self.folder('CONSOLIDATION-S0101-S0130')
        self.folder('CONSOLIDATION-S0131-S0150', settled=False, stage1=True)
        due, msg = g.check(reads(1, 155), -1, self.review, ORDER)
        self.assertTrue(due)                       # 25 since S0130: S0131-S0155
        self.assertIn('S0130', msg)

    def test_milestone_review_counts(self):
        self.folder('CONSOLIDATION-S0036-S0065')
        self.folder('WHOLE-BOOK-S0100')
        self.assertEqual(g.last_settled(self.review, ORDER), ('S0100', 99))

    def test_folders_without_range_ignored(self):
        self.folder('CONSOLIDATION-20261019')
        self.folder('C28-S0146-S0150')            # a window, not a consolidation
        self.assertEqual(g.last_settled(self.review, ORDER), (None, -1))

    def test_topic_full_and_branch_reads_not_counted(self):
        self.folder('CONSOLIDATION-S0131-S0150')
        rows = reads(151, 174) + reads(300, 340, 'topic-full') + reads(341, 345, 'branch-consult') + reads(346, 350, 'scan')
        self.assertFalse(g.check(rows, -1, self.review, ORDER)[0])

    def test_rows_before_restart_ignored(self):
        self.folder('CONSOLIDATION-S0131-S0150')
        rows = reads(151, 200) + [['t', 'RESTART']] + reads(151, 160)
        due, msg = g.check(rows, 50, self.review, ORDER)
        self.assertFalse(due)
        self.assertIn('10 of 25', msg)

    def test_repeat_rows_count_once(self):
        self.folder('CONSOLIDATION-S0131-S0150')
        self.assertFalse(g.check(reads(151, 174) + reads(151, 174), -1, self.review, ORDER)[0])


if __name__ == '__main__':
    unittest.main(verbosity=1)
