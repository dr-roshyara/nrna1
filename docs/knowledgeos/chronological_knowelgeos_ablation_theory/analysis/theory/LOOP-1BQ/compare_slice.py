#!/usr/bin/env python3
"""Blind-slice comparator (LOOP-1BQ/PREREG-SLICE.md). Written before the non-Claude return exists.

Reuses LOOP-1BP/compare_xcheck.py (classes, fail-closed parsing, collision handling) unchanged, except
that the deontic-value grammar is widened to V = {p, q, r} for histories.

usage:
  compare_slice.py CLAUDE_SLICE_RESULTS.json CHECKER_DIR OUT_DIR [--untestable F] [--ambiguous F]
"""
import argparse, json, os, re, sys
from collections import Counter

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, os.path.join(os.path.dirname(HERE), 'LOOP-1BP'))
import compare_xcheck as X  # noqa: E402

VALS = ('p', 'q', 'r')


def to_sigma3(x):
    if isinstance(x, (list, tuple)) and len(x) == 2 and all(v in VALS for v in x):
        return tuple(x)
    if isinstance(x, str):
        m = re.match(r'^\(?\s*([pqr])\s*,?\s*([pqr])\s*\)?$', x.strip())
        if m:
            return (m.group(1), m.group(2))
    raise X.ParseError(f'sigma {x!r}')


RE_ISS3 = re.compile(r'^(iss|issue)\(\s*d\s*=\s*([01])\s*,\s*v\s*=\s*([pqr])\s*,\s*prep\s*=\s*([01])\s*,\s*f\s*=\s*(0|1|inf|∞)\s*\)$')
_slot_orig = X.slot


def slot3(x, me):
    if isinstance(x, dict) and X.KIND.get(str(x.get('kind', '')).lower()) == 'iss':
        d = {k: v for k, v in x.items() if k != 'present' or v is not True}
        if set(d) != {'kind', 'd', 'v', 'prep', 'f'} or d['v'] not in VALS:
            raise X.ParseError(f'iss slot {x!r}')
        return ('iss', X._bit(d['d'], 'd'), d['v'], X._bit(d['prep'], 'prep'), X._f(d['f']))
    if isinstance(x, str):
        m = RE_ISS3.match(x.strip())
        if m:
            fv = m.group(5)
            return ('iss', int(m.group(2)), m.group(3), int(m.group(4)), X._f(int(fv) if fv.isdigit() else fv))
    return _slot_orig(x, me)


X.to_sigma = to_sigma3
X.slot = slot3

FRAMES = ['U', 'U_P2']
T_FRAME = {'u': 'U', 'up2': 'U_P2'}
READINGS = X.READINGS
SIZE_KEYS = ['R_B', 'H-D', 'H-B', 'H-D&H-B', 'H-D&R_B']
T_SIZE = {'rb': 'R_B', 'hd': 'H-D', 'hb': 'H-B', 'hdhb': 'H-D&H-B', 'hd&hb': 'H-D&H-B',
          'hdrb': 'H-D&R_B', 'hd&rb': 'H-D&R_B'}
SFORMS = ['(i)-S', '(ii)-S', '(iii)-L']
OFORMS = ['(i)-O', '(ii)-O']
T_FORM = {'is': '(i)-S', 'iis': '(ii)-S', 'iiil': '(iii)-L', 'io': '(i)-O', 'iio': '(ii)-O'}
SFIELDS = {'holds', 'scope_size', 'n_counterexamples', 'least_counterexample'}

# De-duplication (PREREG-SLICE §3)
RB_SAME = {'R0', 'R-b', 'R-d', 'R-e', 'R-g', 'R-g+', 'R-j'}
HD_SAME = {'R0', 'R-b', 'R-c', 'R-f', 'R-g', 'R-g+', 'R-h', 'R-i', "R-i'"}
HDRB_EQ_HD = {'R-c', 'R-f', 'R-i', "R-i'"}


# REVIEW-slice-r2 (structural equalities, from V1B-SPEC's reading definitions)
HB_SAME = {'R0', 'R-b', 'R-d', 'R-e', 'R-j'}          # B-clauses unchanged
HDRB_SAME = {'R0', 'R-b', 'R-g', 'R-g+'}              # H-D and R_B both unchanged
RB_CF = {'R-c', 'R-f'}                                # B-rec^c = B-rec^f = D-rec
IIIL_A = {'R0', 'R-b', 'R-g', 'R-g+'}
IIIL_B = {'R-c', 'R-f', 'R-i', "R-i'"}
# least counterexamples derivable as the R-1 minimum of the scope (no witness: D-rec, w1 or D-chg fails)
CEX_DERIVABLE = {('(i)-S', R) for R in ('R-c', 'R-g', 'R-g+', 'R-i', "R-i'")} | \
                {('(ii)-S', R) for R in ('R-g', 'R-g+', 'R-i', "R-i'")}


def quantity_id(R, what):
    """Distinct-quantity id (used to count distinct BLIND quantities). R-b's classes equal R0's."""
    if R == 'R-b':
        R = 'R0'
    if what in ('R_B', '(i)-S/scope_size'):
        if R == "R-i'":
            return 'n'  # R_B^{R-i'} = U_P2
        if R in RB_CF:
            return 'R_B@cf'
        return 'R_B@' + ('B-rec-unchanged' if R in RB_SAME else R)
    if what == 'H-D' or (what == 'H-D&R_B' and R in HDRB_EQ_HD):
        return 'H-D@' + ('D-unchanged' if R in HD_SAME else R)
    if what == 'H-B' and R in HB_SAME:
        return 'H-B@B-unchanged'
    if what == 'H-D&R_B' and R in HDRB_SAME:
        return 'H-D&R_B@unchanged'
    if what == '(ii)-S/scope_size':
        if R in RB_SAME:
            return 'iiS-scope@B-rec-unchanged'
        if R in RB_CF:
            return 'iiS-scope@cf'
    if what == '(iii)-L/scope_size':
        if R in IIIL_A:
            return 'iiiL-scope@A'
        if R in IIIL_B:
            return 'iiiL-scope@B'
    return f'{what}@{R}'


def norm_key(table, k, what, unrec, path):
    if table is T_SIZE and k in SIZE_KEYS:
        return k  # TASK.md's exact size keys contain '&', which the closed norm() rejects by design
    try:
        return X.lookup(table, k, what)
    except X.ParseError:
        unrec.append(f'{path}/{k}')
        return None


def index(d, table, what, unrec, path):
    out = {}
    if not isinstance(d, dict):
        return out
    for k, v in d.items():
        cid = norm_key(table, k, what, unrec, path)
        if cid is None:
            continue
        if cid in out:
            unrec.append(f'{path}/{k} (collides with an earlier key for {cid})')
            out[cid] = X.ParseError(f'colliding keys for {cid}')
        else:
            out[cid] = v
    return out


def build(claude, checker, unrec):
    units = []
    kf = index(checker, T_FRAME, 'frame', unrec, '') if isinstance(checker, dict) else {}
    for F in FRAMES:
        cF = claude[F]
        kF = kf.get(F, X.ABSENT)
        blindF = F == 'U_P2'
        if isinstance(kF, dict):
            for x in set(kF) - {'n_frame_histories', 'readings'}:
                unrec.append(f'{F}/{x}')
        kn = X._get(kF, 'n_frame_histories')
        units.append(X.Unit(f'{F}/n_frame_histories', 'BLIND' if blindF else 'EXPOSED', True,
                            cF['n_frame_histories'], kn, 'int', qid=f'n#{F}'))
        kR = index(X._get(kF, 'readings') if isinstance(kF, dict) else {}, X.T_READING, 'reading', unrec, f'{F}/readings')
        for R in READINGS:
            cR = cF['readings'][R]
            kRR = kR.get(R, X.ABSENT)
            ksz, kforms = X.ABSENT, {}
            if isinstance(kRR, dict):
                for k, v in kRR.items():
                    if k == 'sizes':
                        ksz = index(v, T_SIZE, 'size', unrec, f'{F}/{R}/sizes') if isinstance(v, dict) else X.ParseError('sizes')
                        continue
                    fid = norm_key(T_FORM, k, 'form', unrec, f'{F}/{R}')
                    if fid is None:
                        continue
                    if fid in kforms:
                        unrec.append(f'{F}/{R}/{k} (collides with an earlier key for {fid})')
                        kforms[fid] = X.ParseError('colliding form keys')
                        continue
                    kforms[fid] = v
                    if isinstance(v, dict):
                        allowed = SFIELDS if fid in SFORMS else {'holds'}
                        for x in set(v) - allowed:
                            unrec.append(f'{F}/{R}/{k}/{x}')
            elif isinstance(kRR, X.ParseError):
                ksz = kRR
            for s in SIZE_KEYS:
                tier = 'BLIND' if blindF and not (s == 'R_B' and R == "R-i'") else 'EXPOSED'
                units.append(X.Unit(f'{F}/{R}/sizes/{s}', tier, True, cR['sizes'][s], X._get(ksz, s) if not isinstance(ksz, X.ParseError) else ksz,
                                    'int', qid=quantity_id(R, s) + f'#{F}'))
            for form in SFORMS + OFORMS:
                cf = cR[form]
                kfm = kforms.get(form, X.ABSENT)
                kh = X._get(kfm, 'holds')
                units.append(X.Unit(f'{F}/{R}/{form}/holds', 'EXPOSED', True, cf['holds'], kh, 'bool', qid=None))
                if form in SFORMS:
                    cholds_false = cf['holds'] is False
                    sc_tier = 'BLIND' if blindF and not (form == '(i)-S' and R == "R-i'") else 'EXPOSED'
                    units.append(X.Unit(f'{F}/{R}/{form}/scope_size', sc_tier, True, cf['scope_size'],
                                        X._get(kfm, 'scope_size'), 'int', qid=quantity_id(R, f'{form}/scope_size') + f'#{F}'))
                    cnt_tier = 'BLIND' if (blindF and cholds_false) else 'EXPOSED'
                    Rq = 'R0' if R == 'R-b' else R
                    units.append(X.Unit(f'{F}/{R}/{form}/n_counterexamples', cnt_tier, True, cf['n_counterexamples'],
                                        X._get(kfm, 'n_counterexamples'), 'int', qid=f'ncex:{form}@{Rq}#{F}'))
                    lc_tier = 'EXPOSED' if (form, R) in CEX_DERIVABLE else cnt_tier
                    units.append(X.Unit(f'{F}/{R}/{form}/least_counterexample', lc_tier, None,
                                        cf['least_counterexample'], X._get(kfm, 'least_counterexample'), 'cex',
                                        claude_holds=cf['holds'], checker_holds=kh, qid=f'lcex:{form}@{Rq}#{F}'))
    return units


def main(argv=None):
    ap = argparse.ArgumentParser()
    ap.add_argument('claude_results'); ap.add_argument('checker_dir'); ap.add_argument('out_dir')
    ap.add_argument('--untestable'); ap.add_argument('--ambiguous')
    a = ap.parse_args(argv)
    claude = json.load(open(a.claude_results))
    try:
        checker = json.load(open(os.path.join(a.checker_dir, 'SLICE-RESULTS.json')), object_pairs_hook=X._no_dup)
    except FileNotFoundError:
        checker = 'MISSING'
    except (json.JSONDecodeError, UnicodeDecodeError, X.ParseError):
        checker = 'MALFORMED'
    unt = json.load(open(a.untestable)) if a.untestable else []
    amb = json.load(open(a.ambiguous)) if a.ambiguous else []
    unrec = []
    units = build(claude, checker if isinstance(checker, dict) else None, unrec)
    rows = []
    for u in units:
        if not isinstance(checker, dict):
            cls, flags, cv, kv = 'INCOMPLETE', ['FILE_' + str(checker)], None, None
        else:
            cls, flags, cv, kv = X.classify(u, unt, amb)
        rows.append({'unit': u.uid, 'tier': u.tier, 'qid': u.kw.get('qid'), 'class': cls, 'flags': flags,
                     'claude': cv, 'checker': kv})
    scored = [r for r in rows if r['class'] != 'NOT_A_UNIT']
    flagged = [r['unit'] for r in scored if 'AMBIGUOUS' in r['flags']]
    licence = bool(scored) and all(r['class'] in ('AGREEMENT', 'NOT_APPLICABLE') for r in scored) \
        and not flagged and not unrec
    blind_q = sorted({r['qid'] for r in scored if r['tier'] == 'BLIND' and r['class'] == 'AGREEMENT'})
    summary = {'n_units_scored': len(scored),
               'counts': {f'{t}/{c}': n for (t, c), n in sorted(Counter((r['tier'], r['class']) for r in rows).items())},
               'distinct_blind_quantities_agreeing': len(blind_q),
               'unrecognized_checker_keys': unrec, 'ambiguous_flagged': flagged,
               'disagreements_for_review': [r['unit'] for r in rows if r['class'] == 'DISAGREEMENT'
                                            or 'value-compared:DISAGREEMENT' in r['flags']],
               'licence_cross_family_reproduction_of_computational_core': licence}
    os.makedirs(a.out_dir, exist_ok=True)
    json.dump({'summary': summary, 'units': rows, 'distinct_blind_quantities': blind_q},
              open(os.path.join(a.out_dir, 'SLICE-COMPARISON.json'), 'w'), indent=1, ensure_ascii=False)
    print(json.dumps({k: v for k, v in summary.items()}, indent=1, ensure_ascii=False)[:2500])
    return summary


if __name__ == '__main__':
    main()
