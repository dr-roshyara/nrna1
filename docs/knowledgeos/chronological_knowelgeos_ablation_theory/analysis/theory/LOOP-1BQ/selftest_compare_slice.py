#!/usr/bin/env python3
"""Planted controls for compare_slice.py, run on the sealed Claude-side results before the non-Claude return."""
import copy, contextlib, io, json, os, sys, tempfile
HERE = os.path.dirname(os.path.abspath(__file__)); sys.path.insert(0, HERE)
import compare_slice as S
CL = os.path.join(HERE, 'CLAUDE-SIDE', 'SLICE-RESULTS.json')


def run(chk, **kw):
    d = tempfile.mkdtemp()
    if chk is not None:
        open(os.path.join(d, 'SLICE-RESULTS.json'), 'w').write(chk if isinstance(chk, str) else json.dumps(chk))
    args = [CL, d, os.path.join(d, 'o')]
    for k, v in kw.items():
        p = os.path.join(d, k); json.dump(v, open(p, 'w')); args += ['--' + k, p]
    with contextlib.redirect_stdout(io.StringIO()):
        S.main(args)
    return json.load(open(os.path.join(d, 'o', 'SLICE-COMPARISON.json')))


def cls(x, uid):
    return [u['class'] for u in x['units'] if u['unit'] == uid]


ok = True
base = json.load(open(CL))
x = run(copy.deepcopy(base)); s = x['summary']
print('P0', s['counts'], 'distinct BLIND', s['distinct_blind_quantities_agreeing'], 'licence', s['licence_cross_family_reproduction_of_computational_core'])
ok &= s['licence_cross_family_reproduction_of_computational_core'] and not s['disagreements_for_review'] and not s['unrecognized_checker_keys']
c = copy.deepcopy(base); c['U_P2']['readings']['R-g']['sizes']['H-B'] += 1
x = run(c); t = cls(x, 'U_P2/R-g/sizes/H-B'); ok &= t == ['DISAGREEMENT']; print('N1 size off by one', t)
c = copy.deepcopy(base); del c['U_P2']['readings']['R-j']['(ii)-S']['scope_size']
x = run(c); t = cls(x, 'U_P2/R-j/(ii)-S/scope_size'); ok &= t == ['INCOMPLETE']; print('N2 missing', t)
c = copy.deepcopy(base); rr = c['U_P2']['readings']; rr["R-i’"] = copy.deepcopy(rr["R-i'"]); rr["R-i’"]['sizes']['H-B'] += 7
x = run(json.dumps({'U': c['U'], 'U_P2': {'n_frame_histories': c['U_P2']['n_frame_histories'], 'readings': dict([("R-i’", rr.pop("R-i’"))] + list(rr.items()))}}))
ok &= not x['summary']['licence_cross_family_reproduction_of_computational_core'] and any('collides' in k for k in x['summary']['unrecognized_checker_keys']); print('N3 alias collision blocks licence')
fl = next((R, f) for R in S.READINGS for f in S.SFORMS if base['U_P2']['readings'][R][f]['holds'] is False)
c = copy.deepcopy(base); h = c['U_P2']['readings'][fl[0]][fl[1]]['least_counterexample']; h['c'] = 'c2' if h['c'] == 'c1' else 'c1'
x = run(c); t = cls(x, f'U_P2/{fl[0]}/{fl[1]}/least_counterexample'); ok &= t == ['DISAGREEMENT']; print('N4 cex', fl, t)
c = copy.deepcopy(base); h = c['U_P2']['readings'][fl[0]][fl[1]]['least_counterexample']; h['sigma'] = ['r', 's']
x = run(c); t = cls(x, f'U_P2/{fl[0]}/{fl[1]}/least_counterexample'); ok &= t == ['INCOMPLETE']; print('N5 bad value', t)
x = run('{"U": {}, "U": {}}'); ok &= all(u['class'] == 'INCOMPLETE' for u in x['units']); print('N6 duplicate key -> INCOMPLETE')
print('SELFTEST', 'PASS' if ok else 'FAIL')
sys.exit(0 if ok else 1)
