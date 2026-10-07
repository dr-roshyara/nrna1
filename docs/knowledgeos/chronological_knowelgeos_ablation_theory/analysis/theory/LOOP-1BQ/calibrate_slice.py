#!/usr/bin/env python3
"""Within-family calibration (PREREG-SLICE §2): the fresh Claude worker's U-frame values against the sealed
V1b U values. The mapping is fixed in PREREG-SLICE §2. Run on the worker's output BEFORE its U_P2 values are read.

usage: calibrate_slice.py WORKER_SLICE_RESULTS.json OUT.json
"""
import json, os, sys

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
import compare_slice as S  # widens the value grammar; harmless for U
X = S.X


def main(argv):
    worker = json.load(open(argv[0]))['U']
    v1b = json.load(open(X.CLAUDE_V1B))
    rows = []

    def cmp(uid, a, b, kind):
        try:
            ca = X.canon(kind, a, side='claude') if kind in ('hist', 'cex') else X.canon(kind, a)
            cb = X.canon(kind, b, side='claude') if kind in ('hist', 'cex') else X.canon(kind, b)
            ok = ca == cb
        except Exception as e:  # noqa: BLE001
            ok, ca, cb = None, repr(e), None
        rows.append({'unit': uid, 'match': ok, 'v1b': str(ca), 'worker': str(cb)})

    cmp('U/n_frame_histories', v1b['_meta']['U_size'], worker['n_frame_histories'], 'int')
    for R in X.READINGS:
        vb, wk = v1b[R]['U'], worker['readings'][R]
        for s in S.SIZE_KEYS:
            cmp(f'U/{R}/sizes/{s}', vb['class_sizes'][s], wk['sizes'][s], 'int')
        for form in S.SFORMS + S.OFORMS:
            cmp(f'U/{R}/{form}/holds', vb['checks'][form]['holds'], wk[form]['holds'], 'bool')
        for form in S.SFORMS:
            a, b = vb['checks'][form]['least_counterexample'], wk[form]['least_counterexample']
            cmp(f'U/{R}/{form}/least_counterexample', a, b, 'hist')
        cmp(f'U/{R}/(iii)-L/scope_size', vb['checks']['(iii)-L']['scope_size'], wk['(iii)-L']['scope_size'], 'int')
        cmp(f'U/{R}/(iii)-L/n_counterexamples', vb['checks']['(iii)-L']['n_counterexamples'],
            wk['(iii)-L']['n_counterexamples'], 'int')
    mism = [r for r in rows if r['match'] is not True]
    out = {'n_compared': len(rows), 'n_mismatch': len(mism), 'mismatches': mism,
           'not_calibrated': 'U (i)-S and (ii)-S scope_size and n_counterexamples (V1b did not report them)',
           'rows': rows}
    json.dump(out, open(argv[1], 'w'), indent=1, ensure_ascii=False)
    print(json.dumps({k: out[k] for k in ('n_compared', 'n_mismatch')}), [m['unit'] for m in mism][:20])


if __name__ == '__main__':
    main(sys.argv[1:])
