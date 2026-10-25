"""Tests for allowed_order.py and close_source.py's allowed-list rules (the Principal, 2026-10-25).
Run: python3 -I tmp/tools/test_allowed_order.py   (the --start test runs the real tool; a refused start writes nothing)"""
import sys, json, pathlib, subprocess, unittest
HERE = pathlib.Path(__file__).resolve().parent
W = HERE.parents[1]
sys.path.insert(0, str(HERE))
import allowed_order as ao

INDEX = [json.loads(l) for l in open(W / '02-FILES.jsonl', encoding='utf-8')]
POS = {r['source_id']: i for i, r in enumerate(INDEX)}
ALLOWED = ao.load_allowed(W.parent / 'knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv')
STARTS = W / 'tmp/corpus-reading/START-TIMES.tsv'


class AllowedOrder(unittest.TestCase):
    def test_s0155_to_s0157_not_allowed(self):
        for s in ('S0155', 'S0156', 'S0157'):
            self.assertNotIn(INDEX[POS[s]]['path'], ALLOWED, s)

    def test_next_after_s0154_skips_three(self):
        nxt, skipped = ao.next_allowed(INDEX, POS['S0154'], ALLOWED)
        self.assertEqual(nxt, 'S0158')
        self.assertEqual(skipped, ['S0155', 'S0156', 'S0157'])

    def test_next_after_allowed_is_plain(self):
        self.assertEqual(ao.next_allowed(INDEX, POS['S0151'], ALLOWED), ('S0152', []))

    def test_synthetic(self):
        idx = [{'source_id': f'S{i}', 'path': f'p{i}'} for i in range(5)]
        self.assertEqual(ao.next_allowed(idx, 0, {'p3'}), ('S3', ['S1', 'S2']))
        self.assertEqual(ao.next_allowed(idx, 3, {'p3'}), (None, ['S4']))

    def test_start_refuses_and_writes_nothing(self):
        before = STARTS.read_bytes() if STARTS.exists() else b''
        for s in ('S0155', 'S0156', 'S0157'):
            r = subprocess.run([sys.executable, '-I', str(HERE / 'close_source.py'), '--start', s],
                               capture_output=True, text=True)
            self.assertNotEqual(r.returncode, 0, s)
            self.assertIn('NOT on the allowed list', r.stderr + r.stdout)
        after = STARTS.read_bytes() if STARTS.exists() else b''
        self.assertEqual(before, after)


if __name__ == '__main__':
    unittest.main(verbosity=1)
