#!/usr/bin/env python3
"""SYNTAX-ONLY adapter for the Haiku 4.5 slice return (PREREG-SLICE §4; LOOP-1BP addendum: syntax only,
committed separately, reviewed). Committed BEFORE the adapted comparison is run.

Maps two structural deviations, and nothing else:
  1. frame -> reading -> {...}           becomes  frame -> {"readings": {reading -> {...}}}
  2. n_frame_histories reported per reading becomes one value per frame, ONLY if it is identical across every
     reading of the frame; otherwise the frame value is set to the string "CONFLICT" (-> MALFORMED, loud).
No value is changed, added (other than moving n), or dropped; per-reading n is removed after the move.
usage: adapt_haiku_v1.py IN_SLICE_RESULTS.json OUT_DIR
"""
import json, os, sys

src, out = sys.argv[1], sys.argv[2]
d = json.load(open(src))
res = {}
for F, readings in d.items():
    ns = {R: body.get('n_frame_histories') for R, body in readings.items() if isinstance(body, dict)}
    vals = set(json.dumps(v) for v in ns.values())
    n = list(ns.values())[0] if len(vals) == 1 else 'CONFLICT'
    res[F] = {'n_frame_histories': n,
              'readings': {R: {k: v for k, v in body.items() if k != 'n_frame_histories'} for R, body in readings.items()}}
os.makedirs(out, exist_ok=True)
json.dump(res, open(os.path.join(out, 'SLICE-RESULTS.json'), 'w'), indent=1)
print('frames', {F: res[F]['n_frame_histories'] if res[F]['n_frame_histories'] == 'CONFLICT' else 'consistent' for F in res})
